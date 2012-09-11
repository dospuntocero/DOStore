<?php
/**
 * Cart
 * Handles Cart and Checkout through diferent methods
 * Google Checkout
 * Paypal
 * DineroMail
 * Deposit
 * Authorize
 *
 * @author Francisco Arenas
 */
class DOCart extends Page_Controller{

	static $url_segment = 'store';

	public static $allowed_actions = array (
		'cart',
		'checkout',
		'cartclear',
		'deleteitem',
		'addtocart',
		'showcart',
		'SendCartByEmail'
		);

	// ===========================================================
	// = session related functions. getting and setting the cart =
	// ===========================================================

	public function getCartSession(){
		return Session::get('StoreCart');
	}

	public function setCartSession($message){
		Session::set('StoreCart', $message);
	}
	


	// ===============================================================
	// = Handles the adition of new Objects to our Cart through Ajax =
	// ===============================================================

	public function addtocart(){
		$c = $this->CartSession;
		$cart = Order::get()->filter(array(
			"ClientIdentifier" => $c
		))->first();

		if($cart){
			//If this user has a cart, we add a new item
			$this->CreateCartItem($cart, $_REQUEST);
		}else{
			//If there is no cart, we create a new one
			$cart = new Order();
			$cart->Status = 'InCart';
			$stamp = sha1($_SERVER['REMOTE_ADDR'].time());
			Session::set('StoreCart', $stamp);
			$cart->ClientIdentifier = $stamp;
			$cart->write();
			$this->CreateCartItem($cart, $_REQUEST);
		}
		return $this->showcart();
	}

	// ===============================================
	// = creates the item dataobject in the database =
	// ===============================================

	public function CreateCartItem($cart, $_REQUEST){
		$item = OrderItem::get()->filter(array(
			"OrderID" => $cart->ID,
			"ShoppableID" => $_REQUEST['ID']
		))->first();

		if($item){
			$item->Quantity = $item->Quantity+1;
			$item->write();
		}else{
			$cartItem = DOArticle::get()->filter(array(
				"ID" => $_REQUEST['ID']
			))->first();
			if ($cartItem) {
				$item = new OrderItem();
				$item->Quantity = 1;
				$item->OrderID = $cart->ID;
				$item->ShoppableID = $cartItem->ID;
				$item->write();
			}
		}
	}

	// ===============
	// = delete item =
	// ===============

	public function deleteitem(){
		$c = $this->CartSession;
		$cart = Order::get()->filter(array(
			"ClientIdentifier" => $c
		))->first();
		$item = OrderItem::get()->filter(array(
			"OrderID" => $cart->ID,
			"ShoppableID" => $_REQUEST['ID']
		))->first();
		if($item){
			$item->delete();
		}else{
			return true;
		}
	}

	// =====================
	// = clearing the cart =
	// =====================

	public function cartclear(){
		$c = $this->CartSession;
		if ($c) {
			$cart = Order::get()->filter(array(
				"ClientIdentifier" => $c
			))->first();
			foreach($cart->Items() as $item){
				$item->delete();
			}
			$items = $cart->Items();
			Session::clear('StoreCart');
		}
		return $this->showcart();
	}

	// ===============================================================
	// = function that returns the total amount of items in the cart =
	// ===============================================================

	function CountItems(){
		$c = $this->CartSession;
		$itemsAmount = 0;
		if ($c) {
			$cart = Order::get()->filter(array(
				"ClientIdentifier" => $c
			))->first();
			foreach($cart->Items() as $item){
				$itemsAmount += $item->Quantity;
			}
		}
		return intval($itemsAmount);

	}

	// ==================
	// = shows the cart =
	// ==================

	public function showcart(){
		$c = $this->CartSession;
		$cart = Order::get()->filter(array(
			"ClientIdentifier" => $c
		))->first();
	
		if ($cart) {
			$items = $cart->Items();
			return $this->customise(array(
				'Items' => $items, 
				'Cart'  => $cart,
				"Order" => $cart,
				"Count" => $this->CountItems()
			))->renderWith('CartItems');
		}else{
			// ========================================================================
			// = if the cart is empty, just return the partial without any data array =
			// ========================================================================
			return $this->renderWith('CartItems');
		}

	}

	// =================
	// = checkout part =
	// =================
	function CartForm() {
	// Create fields
		$fields = new FieldList(
			new LiteralField("title","<div>"._t("DOCart.GETDEPOSITINFORMATION","Get Deposit information")."</div>"),
			TextField::create('Name')->setTitle(_t('DOCart.NAMEINPUT',"Name <em>*</em>")),
			EmailField::create("Email")->setTitle(_t('DOCart.EMAIL',"Email address"))->setAttribute('type', 'email')
		);

		// Create action
		$send = new FormAction('SendCartByEmail', _t('DOCart.SEND',"Send"));
		$send->addExtraClass("success btn");
		$actions = new FieldList($send);

		$validator = new RequiredFields('Name', 'Email');
		return new Form($this, 'SendCartByEmail', $fields, $actions, $validator);
	}

	function SendCartByEmail($data) {

		// ===============================================
		// = get the cart and items for sending by email =
		// ===============================================
		$c = $this->CartSession;
		$cart = Order::get()->filter(array(
			"ClientIdentifier" => $c
		))->first();
		$items = $cart->Items();

		// ==================
		// = set email data =
		// ==================
		
		$From = $data['Name'] ."<". $data['Email'].">";
		$To = "im@god.cl";
		$Subject = _t('DOCart.NEWBUY',"New purchase from our store");
		$email = new Email($From, $To, $Subject);
		
		// = set the template =
		$email->setTemplate('CartEmail');

		// = populate template =
		$email->populateTemplate(
			array(
				"Name" => $data['Name'],
				"Email" => $data['Email'],
				'Items' => $items,
				"Cart" => $cart,
				"Order" => $cart,
			)
		);

		// = send mail =
		if ($email->send()) {
			Controller::curr()->redirect(Director::baseURL(). $this->URLSegment . "/success");
		}else{
			Controller::curr()->redirect(Director::baseURL(). $this->URLSegment . "/error");
		}
	}


	public function error(){
		return $this->httpError(500);
	}

	public function success(){
		$renderedContent = $this->renderWith('Page', array('Content' => _t('DOCart.THANKS',"Thanks for your purchase")));
		return $renderedContent;
	}
	
}