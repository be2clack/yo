<?php
$amount = $paramForm["amount"];
if($param["test"]){
    $action = "https://test.paycom.uz";
}else{
    $action = "https://checkout.paycom.uz";
}
$html = '
<form method="POST" class="form-pay" action="'.$action.'">

    <input type="hidden" name="merchant" value="'.$param["merchant_id"].'"/>

    <input type="hidden" name="amount" value="'.$amount.'"/>

    <input type="hidden" name="account[id_order]" value="'.$paramForm["id_order"].'"/>

    <input type="hidden" name="description" value="'.$paramForm["title"].'"/>

    <input type="submit" name="pay" class="pay-trans" >

</form>
';

return ["form"=>$html];

?>
