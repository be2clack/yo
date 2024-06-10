<?php

$errors = [];
$requisites = $_POST['requisites_company'] ? encrypt(json_encode($_POST['requisites_company'])) : '';

if(!$_POST['requisites_company']['inn']){
$errors[] = $ULang->t("Пожалуйста, укажите ИНН"); 
}

if(!$_POST['requisites_company']['legal_form']){
$errors[] = $ULang->t("Пожалуйста, укажите правовую форму"); 
}else{
if($_POST['requisites_company']['legal_form'] == 1){
    if(!$_POST['requisites_company']['kpp']){
    $errors[] = $ULang->t("Пожалуйста, укажите КПП");
    }
    if(!$_POST['requisites_company']['ogrn']){
    $errors[] = $ULang->t("Пожалуйста, укажите ОГРН");
    }           
}elseif($_POST['requisites_company']['legal_form'] == 2){
    if(!$_POST['requisites_company']['ogrnip']){
    $errors[] = $ULang->t("Пожалуйста, укажите ОГРНИП");
    }          
}
}

if(!$_POST['requisites_company']['name_company']){
$errors[] = $ULang->t("Пожалуйста, укажите название организации"); 
}

if(!$_POST['requisites_company']['name_bank']){
$errors[] = $ULang->t("Пожалуйста, укажите название банка"); 
}

if(!$_POST['requisites_company']['payment_account_bank']){
$errors[] = $ULang->t("Пожалуйста, укажите расчетный счет в банке"); 
}

if(!$_POST['requisites_company']['correspondent_account_bank']){
$errors[] = $ULang->t("Пожалуйста, укажите корреспондентский счёт"); 
}

if(!$_POST['requisites_company']['bik_bank']){
$errors[] = $ULang->t("Пожалуйста, укажите БИК"); 
}

if(!$_POST['requisites_company']['address_index']){
$errors[] = $ULang->t("Пожалуйста, укажите почтовый индекс"); 
}

if(!$_POST['requisites_company']['address_region']){
$errors[] = $ULang->t("Пожалуйста, укажите регион"); 
}

if(!$_POST['requisites_company']['address_city']){
$errors[] = $ULang->t("Пожалуйста, укажите город"); 
}

if(!$_POST['requisites_company']['address_street']){
$errors[] = $ULang->t("Пожалуйста, укажите адрес"); 
}

if(!$_POST['requisites_company']['address_house']){
$errors[] = $ULang->t("Пожалуйста, укажите дом"); 
}

if(!$_POST['requisites_company']['fio']){
$errors[] = $ULang->t("Пожалуйста, укажите ФИО"); 
}

if(!$_POST['requisites_company']['phone']){
$errors[] = $ULang->t("Пожалуйста, укажите телефон"); 
}

if(!$_POST['requisites_company']['email']){
$errors[] = $ULang->t("Пожалуйста, укажите email"); 
}

if(!$errors){
update('update uni_clients set clients_requisites_company=? where clients_id=?', [$requisites,$_SESSION["profile"]["id"]]);
echo json_encode(["status"=>true]);
}else{
echo json_encode(["status"=>false, "errors"=> implode("\n", $errors)]);
}

?>