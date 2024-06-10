<?php
if($param["test"] == 1){
   $param["pass1"] = $param["test_pass1"];
}

$mrh_login = $param["id_shop"];
$mrh_pass1 = $param["pass1"];

$inv_id    = $paramForm["id_order"]; 

$inv_desc  = $paramForm["title"]; 
$out_sum  = number_format($paramForm["amount"], 2, ".", "");


if($param["receipt"]){

   $items = [ 
       'sno' => $param["sno"] ?: 'osn',
       'items' => [
           [
              "name" => $inv_desc,
              "quantity" => 1,
              "sum" => $out_sum,
              "payment_method" => "full_payment",
              "payment_object" => "payment",
              "tax" => $param["tax"],
           ]
       ]
   ];

   $arr_encode = json_encode($items);
  
   $receipt = urlencode($arr_encode);
   $receipt_urlencode = urlencode($receipt);
  
   $crc = md5("$mrh_login:$out_sum:$inv_id:$receipt:$mrh_pass1");

   $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&OutSum=$out_sum&InvId=$inv_id&Receipt=$receipt_urlencode&Desc=$inv_desc&SignatureValue=$crc";

}else{

   $crc = md5("$mrh_login:$out_sum:$inv_id:$mrh_pass1");

   $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&OutSum=$out_sum&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc&IsTest={$param["test"]}";

}

return ["link"=>$url];

?>