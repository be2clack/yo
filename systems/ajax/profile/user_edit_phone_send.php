<?php
$phone = formatPhone($_POST["phone"]);
$validatePhone = validatePhone($phone);

if($validatePhone['status']){

 $_SESSION["verify_sms"][$phone]["code"] = smsVerificationCode($phone);

 echo json_encode(["status"=>true]);

}else{
 echo json_encode(["status"=>false, "answer"=>$validatePhone['error']]);
}
?>