<?php

  $fields = array(); 

  $fields["WMI_MERCHANT_ID"]    = $param["id_merchant"];
  $fields["WMI_PAYMENT_AMOUNT"] = number_format($paramForm["amount"], 2, ".", ""); 
  $fields["WMI_CURRENCY_ID"]    = $param["curr"];
  $fields["WMI_PAYMENT_NO"]     = $paramForm["id_order"];
  $fields["WMI_DESCRIPTION"]    = $paramForm["title"];
  $fields["WMI_SUCCESS_URL"]    = $param["link_success"];
  $fields["WMI_FAIL_URL"]       = $param["link_cancel"];

  foreach($fields as $name => $val) 
  {
    if (is_array($val))
    {
       usort($val, "strcasecmp");
       $fields[$name] = $val;
    }
  }

  uksort($fields, "strcasecmp");
  $fieldValues = "";

  foreach($fields as $value) 
  {
      if (is_array($value))
         foreach($value as $v)
         {
            $v = iconv("utf-8", "windows-1251", $v);
            $fieldValues .= $v;
         }
     else
    {
       $value = iconv("utf-8", "windows-1251", $value);
       $fieldValues .= $value;
    }
  }

  $signature = base64_encode(pack("H*", md5($fieldValues . $param["key"])));

  $fields["WMI_SIGNATURE"] = $signature;                            
  foreach($fields as $key => $val)
  {
      if (is_array($val))
         foreach($val as $value)
         {
            $input .= "<input type='hidden' name='".$key."' value='".$value."'/>";   
         }
      else     
         $input .= "<input type='hidden' name='".$key."' value='".$val."'/>";    
  }


  $html = '<form method="post" class="form-pay" action="https://www.walletone.com/checkout/default.aspx" accept-charset="UTF-8"> '.$input.' <input type="submit" value=" " class="pay-trans" /></form>';

  return ["form"=>$html];
?>
 
