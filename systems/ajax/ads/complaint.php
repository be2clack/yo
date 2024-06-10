<?php
if(!$_SESSION["profile"]["id"]){ exit(json_encode(array("status"=>false,"answer"=>"","auth" => false))); }

$id = (int)$_POST["id"];
$text = custom_substr(clear($_POST["text"]), 2000);

$error = array();

if($text){

if($_POST["action_complain"] == 'ad'){

$getAd = findOne("uni_ads", "ads_id=?", array($id));

if($getAd){

      $getComplain = findOne("uni_ads_complain", "ads_complain_id_ad=? and ads_complain_from_user_id=? and ads_complain_action=? and ads_complain_status=?", array($id,intval($_SESSION["profile"]["id"]),'ad',0));

      if(!$getComplain){

        insert("INSERT INTO uni_ads_complain(ads_complain_id_ad,ads_complain_from_user_id,ads_complain_text,ads_complain_date,ads_complain_to_user_id,ads_complain_action)VALUES(?,?,?,?,?,?)", array($id,intval($_SESSION["profile"]["id"]),$text,date("Y-m-d H:i:s"),$getAd['ads_id_user'],$_POST["action_complain"])); 

        echo json_encode(array("status"=>true,"answer"=>$ULang->t("Спасибо! Обращение успешно принято!"),"auth" => true));

        $Admin->notifications("complaint");

        unset($_SESSION['csrf_token'][$_POST['csrf_token']]);

      }else{

         echo json_encode(array("status"=>true,"answer"=>$ULang->t("Ваше обращение уже принято и находится на рассмотрении."),"auth" => true));

      }

}

}elseif($_POST["action_complain"] == 'user'){

	$getUser = findOne("uni_clients", "clients_id=?", array($id));

	if($getUser){

	      $getComplain = findOne("uni_ads_complain", "ads_complain_from_user_id=? and ads_complain_to_user_id=? and ads_complain_action=? and ads_complain_status=?", array(intval($_SESSION["profile"]["id"]),$id,'user',0));

	      if(!$getComplain){

	        insert("INSERT INTO uni_ads_complain(ads_complain_from_user_id,ads_complain_text,ads_complain_date,ads_complain_to_user_id,ads_complain_action)VALUES(?,?,?,?,?)", array(intval($_SESSION["profile"]["id"]),$text,date("Y-m-d H:i:s"),$getUser['clients_id'],$_POST["action_complain"])); 

	        echo json_encode(array("status"=>true,"answer"=>$ULang->t("Спасибо! Обращение успешно принято!"),"auth" => true));

	        $Admin->notifications("complaint");

	        unset($_SESSION['csrf_token'][$_POST['csrf_token']]);

	      }else{

	         echo json_encode(array("status"=>true,"answer"=>$ULang->t("Ваше обращение уже принято и находится на рассмотрении."),"auth" => true));

	      }

	}

}

}else{
  echo json_encode(array("status"=>false,"answer"=>$ULang->t("Пожалуйста, опишите подробности нарушения"),"auth" => true));
}
?>