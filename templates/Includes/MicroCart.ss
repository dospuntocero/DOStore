<div id="microcart">
	<div class="cart-header rel">
		<h2><% _t('Store.MYCART','My Cart') %></h2>
	</div>
	<% control ShowCart %>
	<div id="cart">
		<% include CartItems %>
	</div>
	<% end_control %>
</div>