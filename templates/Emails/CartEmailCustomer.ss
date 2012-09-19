<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<% base_tag %>
</head>
<body>
<table width="600" style="padding:60px;padding-top:50px;">
	<tr background="$BaseHREF/DOStore/images/header-background.png">
		<td height="16px">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" style="border:1px solid #eee;">
				<tr>
					<td>
						<a href="$BaseHREF"><img src="$BaseHREF/mysite/images/logo.png"/></a>
					</td>
					<td>
						<h1 style="font-size:16px;color:#666;margin-top:10px;"><% _t('CartEmail.FROMOURWEBSITE','New purchase') %></h1>
					</td>
				</tr>
			</table>
		</td>	
	</tr>
	<tr>
		<td>
			<ul style="width:590px;list-style:none;margin:0;padding:5px;background:#F4F4F4;">
				<li style="margin:5px;padding:0px;">
					$DepositInformation
				</li>
			</ul>

			<% with Order %>
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
						${$i18nTotal}
					</td>
				</tr>
			</table>
			<% end_with %>
		</td>
	</tr>
	<tr background="$BaseHREF/DOStore/images/header-background.png">
		<td height="16px">&nbsp;</td>
	</tr>
</table>
</body>
</html>