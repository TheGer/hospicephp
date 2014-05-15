<html>
<head><title></title>
<body>

<form method="post" id="frmMembershipPayment" action="https://www.paypal.com/cgi-bin/webscr" target="paypal">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="business" value="hospice@email.com">
    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($membershipType); ?>" />
    <input type="hidden" name="item_number" value="">
    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($membershipCost); ?>" > 
    <input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="shipping" value="">
    <input type="hidden" name="shipping2" value="">
    <input type="hidden" name="handling_cart" value="">
    <input type="hidden" name="bn"  value="ButtonFactory.PayPal.001">
    <input type="image" name="add" src="http://www.powersellersunite.com/buttonfactory/x-click-but23.gif">
</form>
</body>
