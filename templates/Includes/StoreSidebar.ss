<div class="grid_1-4">
	<div id="store-sidebar">
		<% if Action = product %>
		    <% if inStock %>
			<div id="product_info">
			    <h3><% _t('Store.BUYTHISITEM','Buy this item') %></h3>
			    <p class="price">${$Product.Price}</p>
	        	<p><a href="#" class="button add_to_cart_productpage"> <% _t('StoreSidebar.ADDTOCART','Add to cart') %></a></p>
			</div>
		    <% end_if %>
		<% end_if %>
	</div><!-- #store-sidebar -->
</div>