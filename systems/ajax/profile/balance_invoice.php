<?php
$error = [];
$supplier_image_signature = '';
$supplier_image_print = '';

$getUser = findOne("uni_clients", "clients_id=?", [$_SESSION['profile']['id']]);

$supplier_requisites = $settings["requisites"] ? json_decode(decrypt($settings["requisites"]), true) : [];
$customer_requisites = $getUser["clients_requisites_company"] ? json_decode(decrypt($getUser["clients_requisites_company"]), true) : [];

$amount = 0;

if($_POST["amount"]){
   $amount = round($_POST["amount"],2);
}

if(!$customer_requisites){
  $error[] = $ULang->t("Пожалуйста, укажите реквизиты");
}

if(!$amount){
   $error[] = $ULang->t("Пожалуйста, укажите сумму пополнения");
}else{

    if( $amount < round($settings["min_deposit_balance"], 2) ){
       $error[] = $ULang->t("Минимальная сумма пополнения") . " " . $Main->price($settings["min_deposit_balance"]);
    }elseif( $amount > round($settings["max_deposit_balance"], 2) ){
       $error[] = $ULang->t("Максимальная сумма пополнения") . " " . $Main->price($settings["max_deposit_balance"]);
    }

}

$invoice_number = generateStringNumber(12);
$invoice_file = md5($_SESSION['profile']['id'].'_'.$invoice_number).'.pdf';
$create_time = date('Y-m-d H:i:s');

if(!$error){

  smart_insert('uni_invoices_requisites_balance',[
     'create_time' => $create_time,
     'amount' => $amount,
     'user_id' => $_SESSION['profile']['id'],
     'invoice' => $invoice_file,
     'invoice_number' => $invoice_number,
  ]);

  $template = file_get_contents($config['template_path'].'/html/invoice.html');

  if($supplier_requisites['legal_form'] == 1){
    $supplier_inline = $supplier_requisites['name_company'].', ИНН '.$supplier_requisites['inn'].', КПП '.$supplier_requisites['kpp'].', '.$supplier_requisites['address_index'].', '.$supplier_requisites['address_city'].', '.$supplier_requisites['address_street'].', дом № '.$supplier_requisites['address_house'].', офис '.($supplier_requisites['address_office'] ?: '-');
  }else{
    $supplier_inline = $supplier_requisites['name_company'].', ИНН '.$supplier_requisites['inn'].', ОГРНИП '.$supplier_requisites['ogrnip'].', '.$supplier_requisites['address_index'].', '.$supplier_requisites['address_city'].', '.$supplier_requisites['address_street'].', дом № '.$supplier_requisites['address_house'].', офис '.($supplier_requisites['address_office'] ?: '-');
  }
  
  if($customer_requisites['legal_form'] == 1){
    $customer_inline = $customer_requisites['name_company'].', ИНН '.$customer_requisites['inn'].', КПП '.$customer_requisites['kpp'].', '.$customer_requisites['address_index'].', '.$customer_requisites['address_city'].', '.$customer_requisites['address_street'].', дом № '.$customer_requisites['address_house'].', офис '.($customer_requisites['address_office'] ?: '-');
  }else{
    $customer_inline = $customer_requisites['name_company'].', ИНН '.$customer_requisites['inn'].', ОГРНИП '.$customer_requisites['ogrnip'].', '.$customer_requisites['address_index'].', '.$customer_requisites['address_city'].', '.$customer_requisites['address_street'].', дом № '.$customer_requisites['address_house'].', офис '.($customer_requisites['address_office'] ?: '-');
  }

  if($settings['requisites_image_signature']){
    $supplier_image_signature = '<img src="'.$config["urlPath"].'/'.$config["media"]["other"].'/'.$settings['requisites_image_signature'].'">';
  }
  
  if($settings['requisites_image_print']){
    $supplier_image_print = '<img src="'.$config["urlPath"].'/'.$config["media"]["other"].'/'.$settings['requisites_image_print'].'">';
  }

  if($supplier_requisites['nds']){
    $nds = (($amount / 100) * $supplier_requisites['nds']).' '.$settings["currency_main"]["code"];
  }else{
    $nds = 'Без НДС';
  }
  
  $template = str_replace(['{supplier_inline}','{customer_inline}','{invoice_number}','{create_time}','{amount}','{total}','{supplier_fio}','{currency}','{supplier_image_signature}','{supplier_image_print}','{supplier_company_name}','{supplier_company_inn}','{supplier_kpp}','{supplier_bank}','{supplier_bank_bik}','{supplier_bank_payment_account}','{nds}','{supplier_bank_correspondent_account}'], [$supplier_inline,$customer_inline,$invoice_number,date("d.m.Y", strtotime($create_time)),$amount,$amount,$supplier_requisites['fio'],$settings["currency_main"]["code"],$supplier_image_signature,$supplier_image_print,$supplier_requisites['name_company'],$supplier_requisites['inn'],$supplier_requisites['kpp'],$supplier_requisites['name_bank'],$supplier_requisites['bik_bank'],$supplier_requisites['payment_account_bank'],$nds,$supplier_requisites['correspondent_account_bank']], $template);


  $mpdf = new \Mpdf\Mpdf(['tempDir' => $config["basePath"].'/'.$config["media"]["temp_images"]]);
  $mpdf->WriteHTML($template);
  $mpdf->Output($config['basePath'].'/'.$config['media']['user_invoice'].'/'.$invoice_file);


  echo json_encode(array("status" => true, "link" => $config['urlPath'].'/'.$config['media']['user_invoice'].'/'.$invoice_file));

}else{

  echo json_encode(array("status" => false, "answer" => implode("\n", $error)));

}
?>