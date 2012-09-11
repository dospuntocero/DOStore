<% if Order.Items %>
	<% with Order %>

	<% if Items %>
	<div class="cart-items">
		<table>
			<tr>
				<th><% _t('CartItems.QUANTITY','Quantity') %></th>
				<th><% _t('CartItems.ITEMNAME','Item Name') %></th>
				<th><% _t('CartItems.SUBTOTAL','Subtotal') %></th>
			</tr>
			<% loop Items %>
				<tr>
					<td width="20%">$Quantity</td>
					<td width="70%">$Item.Title</td>
					<td width="10%">${$I18nSubTotal}</td>
				</tr>
			<% end_loop %>
			<tr>
				<td colspan="2">
					<% _t('CartItems.TOTAL','Total') %>
				</td>
				<td>
					${$I18nTotal}
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<a id="cart-clear"><% _t('MicroCart.CLEAR','Clear') %> </a><br />
					<a href="store/checkout" id="cart-checkout"><% _t('MicroCart.CHECKOUT','Checkout') %></a>
				</td>
			</tr>
		</table>
	</div>
	
	<% else %>
		<p><% _t('Store.CARTEMPTY','Cart is Empty') %></p>
	<% end_if %>



	<% end_with %>	
<% else %>
	<p><% _t('Store.CARTEMPTY','The cart is empty') %></p>
<% end_if %>