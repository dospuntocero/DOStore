(function($){
	$(document).ready(function(){

// =========================
// = Add Items to the Cart =
// =========================

	if (!$.cookie('cartDisplay')) {
		$.cookie('cartDisplay',"small", { expires: 7, path: '/' });
	};

	$('.add_to_cart').click(function(){
		var productinfo = $(this).attr('rel')
		$('#waiting-indicator').fadeIn('slow');
		$.post(
			"store/addtocart", {
				ID : productinfo
			}, function(data){
				// ==============================================================================================
				// = we added the product to the database and now we return a partial with the cart information =
				// ==============================================================================================
				$('#waiting-indicator').fadeOut('slow');
				showcart();
			}
		);
		return false;
	});

// =======================================
// = Updates Items for the shopping Cart =
// =======================================

	$('.item-quantity').live('change',function(){
		var $parent = $(this).parent();
		var title = $('.item-name', $parent).html();
		var quantity = $('.item-quantity', $parent).val();
		$('#waiting-indicator').fadeIn('slow');
		$.post(
			"store/updateitem", {
				name : title,
				quantity: quantity
			}, function(data){
				$('#waiting-indicator').fadeOut('slow');
				$('#Cart').html(data);
				if ($('#CartTotal').length) {
					showcart();
				};
			}
		);
	});
	
// ===================
// = Clears the cart =
// ===================
	
	$('#cart-clear').live('click',function(){
		$('#waiting-indicator').fadeIn('slow');
		$.post(
			"store/cartclear", {
			}, function(data){
				$('#waiting-indicator').fadeOut('slow');
				showcart();
			}
		);
	});
	
// =================================
// = deletes an item from the cart =
// =================================

	$('.delete-item').live('click', function(event) {
		$('#waiting-indicator').fadeIn('slow');
		var productid = $(this).attr('rel')
		$.post(
			"store/deleteitem", {
				ID : productid
			}, function(data){
				// ================================================================================================
				// = we deleted the product to the database and now we return a partial with the cart information =
				// ================================================================================================
				$('#waiting-indicator').fadeOut('slow');
				showcart();
			}
		);
		return false;
	});


// ===========================
// = shows the checkout form =
// ===========================

	$('#cart-checkout').live('click', function(event) {
			$('#checkoutForm').slideToggle('fast');
	});
	
// ===========================
// = Shows the complete cart =
// ===========================
	
	$('#items-amount').live('click', function(event) {
		$('.cart-items').slideToggle("fast");

		if ($.cookie('cartDisplay') == "big"){

			$.cookie('cartDisplay',"small");

		}else if ($.cookie('cartDisplay') == "small"){

			$.cookie('cartDisplay',"big");

		}

	});

// ==============================================
// = shows the cart if the client has a session =
// ==============================================	

	function showcart(){
		$("<div id='Cart'></div>").appendTo('#cartHolder');
		$.get('store/showcart', function(data) {
			$('#Cart').html(data);
			if ($.cookie('cartDisplay') == "big"){
				$('.cart-items').show();
			}else if ($.cookie('cartDisplay') == "small"){
				$('.cart-items').hide();
			}
		});
	}
	
	showcart();

});
})(jQuery)
