<div id="SalesSummary" class="field readonly">
	<label class="left"><% _t('SalesSummary.SALESSUMMARY','Sales Summary') %></label>
	<div class="middleColumn">
		<table width="600" style="padding:20px 60px 40px 20px;border:1px solid #eee;">
			<tr>
				<th>&nbsp;</th>
				<th style="text-align:center;"><% _t('CartItems.QUANTITY','Qty') %></th>
				<th><% _t('CartItems.ITEMNAME','Item Name') %></th>
				<th><% _t('CartItems.SUBTOTAL','Subtotal') %></th>
			</tr>
			<% loop Items %>
				<tr>
					<td width="5%" style="text-align:center;">$Item.Image.PaddedImage(50,50)</td>
					<td width="15%" style="text-align:center;">$Quantity</td>
					<td width="70%">$Item.Title</td>
					<td width="10%" style="text-align:right;">${$I18nSubTotal}</td>
				</tr>
			<% end_loop %>
			<tr>
				<td colspan="3" style="text-align:right;">
					<strong><% _t('CartItems.TOTAL','Total') %></strong>
				</td>
				<td style="text-align:right;">
					${$Order.i18nTotal}
				</td>
			</tr>
		</table>
	</div>
</div>