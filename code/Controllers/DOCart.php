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

	static $item_class = 'ArticleToSell';


	public static $allowed_actions = array (
		'cart',
		'checkout',
		'success',
		'cartclear',
		'deleteitem',
		'addtocart',
		'showcart',
		'SendCartByEmail',
		'getSalesSummary'
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


	public function getCurrentCart(){
		$c = $this->CartSession;
		$cart = Order::get()->filter(array(
			"ClientIdentifier" => $c
		))->first();
		return $cart;
	}


	// ===============================================================
	// = Handles the adition of new Objects to our Cart through Ajax =
	// ===============================================================

	public function addtocart(){
		$cart = $this->getCurrentCart();
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
		  $myClass = $this->Stat('item_class');
			$cartItem = $myClass::get()->filter(array(
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
		$cart = $this->getCurrentCart();
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
		$cart = $this->getCurrentCart();
		if ($cart) {
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
			new HeaderField("title",_t("DOCart.GETDEPOSITINFORMATION","Get Deposit information"),4),
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
		
	// ================================================================
	// = get siteconfig for the store settings: email, messages, etc. =
	// ================================================================
		$sc = SiteConfig::current_site_config();
		
	// ===============================================
	// = get the cart and items for sending by email =
	// ===============================================
		$cart = $this->getCurrentCart();
		$items = $cart->Items();

	// ====================================================================================
	// = this is the information the email needs for both the customer and commerce owner =
	// ====================================================================================
		$infoOnEmail = array(
			"Name" => $data['Name'],
			"Email" => $data['Email'],
			'Items' => $items,
			"Order" => $cart,
			"DepositInformation" => $sc->DOStoreDepositInstructions
		);

	// =================================
	// = set email data for site owner =
	// =================================

		$FromOwner = $data['Email'];
		$ToOwner = $sc->DOStoreMailTo;
		$SubjectOwner = _t('DOCart.NEWBUY',"New purchase from our store");
		$emailOwner = new Email($FromOwner, $ToOwner, $SubjectOwner);
		
	// = set the template & populate the template =
		$emailOwner->setTemplate('CartEmail');
		$emailOwner->populateTemplate($infoOnEmail);

	// ===================================
	// = set email data for the customer =
	// ===================================
		
		$FromCustomer = $sc->DOStoreMailTo;
		$ToCustomer = $data['Email'];
		$SubjectCustomer = _t('DOCart.PURCHASETHANKYOU',"Thank you for your purchase");
		$emailCustomer = new Email($FromCustomer, $ToCustomer, $SubjectCustomer);
		
	// = set the template & populate the template =
		$emailCustomer->setTemplate('CartEmailCustomer');
		$emailCustomer->populateTemplate($infoOnEmail);

	// = send mail =
		if ($emailOwner->send() && $emailCustomer->send()) {			

	// ==================================================
	// = Creates the order that will store the purchase =
	// ==================================================

			$sale = new Sale();
			$sale->Name = $data["Name"];
			$sale->Email = $data["Email"];
			$sale->PaymentMethod = "Deposit";
			$sale->Status = "Unpaid";
			$sale->CartBackUp = $this->getSalesSummary($items,$cart);
			$sale->Total = $cart->Total;
			$sale->write();

			
			Controller::curr()->redirect(Director::baseURL(). $this->URLSegment . "/success");
		}else{
			Controller::curr()->redirect(Director::baseURL(). $this->URLSegment . "/error");
		}
	}

// ========================================================================
// = creates a plain html version of the purchase so you can get a backup =
// ========================================================================
	public function getSalesSummary($items,$cart){
		return $this->customise(array(
			'Items' => $items, 
			"Order" => $cart
		))->renderWith('SalesSummary');
	}
	
// =========================================
// = success and error pages for the email =
// =========================================
	
	public function error(){
		return $this->httpError(500);
	}

	public function success(){
		$this->cartclear();
		$renderedContent = $this->renderWith('DOCart_success');
		return $renderedContent;
	}
	
}