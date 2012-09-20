<div class="content">
	<div id="items-amount">
		<% if Items %>
			<span class="cartinfo">$Count - ${$Order.i18nTotal}</span>
		<% else %>
			<% _t('Store.CARTEMPTY','Cart is Empty') %>
		<% end_if %>
	</div>
	<% if Items %>
	<div class="cart-items">
		<table width="350">
			<tr>
				<th>&nbsp;</th>
				<th style="text-align:center;"><% _t('CartItems.QUANTITY','Qty') %></th>
				<th><% _t('CartItems.ITEMNAME','Item Name') %></th>
				<th style="text-align:right;"><% _t('CartItems.SUBTOTAL','Subtotal') %></th>
				<th>&nbsp;</th>
			</tr>
			<% loop Items %>
				<tr>
					<td width="5%" style="text-align:center;">$Item.Image.PaddedImage(30,30)</td>
					<td width="10%" style="text-align:center;">$Quantity</td>
					<td width="70%">$Item.Title</td>
					<td width="10%" style="text-align:right;">${$I18nSubTotal}</td>
					<td width="5%" style="text-align:center;"><img class="delete-item" rel="$Item.ID" src="DOStore/images/delete-item.png" alt="<% _t('CartItems.DELETEITEM','Delete') %> $Item.Title"></td>
				</tr>
			<% end_loop %>
			<tr>
				<td colspan="3" style="text-align:right;">
					<strong><% _t('CartItems.TOTAL','Total') %></strong>
				</td>
				<td style="text-align:right;">
					${$Order.i18nTotal}
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><img id="waiting-indicator" src="DOStore/images/ajax-loader.gif" width="16" height="16" alt="Ajax Loader"></td>
				<td colspan="3" style="text-align:right;">
					<a id="cart-clear"><% _t('MicroCart.CLEAR','Clear') %> </a> | 
					<a id="cart-checkout"><% _t('MicroCart.CHECKOUT','Checkout') %></a>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<div id="checkoutForm">
			$CartForm
		</div>
	</div>
	<% end_if %>
</div>