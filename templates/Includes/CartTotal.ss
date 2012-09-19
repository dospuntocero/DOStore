<% control ShowCart %>
<div id="CartTotal">
	<div class="cart">
		<a href="cart">
			<% _t('Store.SHOPPINGCART','Shopping Cart') %>
		</a>: 
		<span class="subtotal">
			<strong>
				<% if I18nTotal %>
					${$I18nTotal}
				<% else %>
					$0
				<% end_if %>
			</strong>
		</span>
	</div>
</div>
<% end_control %>