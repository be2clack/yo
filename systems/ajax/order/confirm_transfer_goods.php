<?php

$id = (int)$_POST["id"];

$getOrder = findOne("uni_secure", "secure_id=?", [ $id ]);

if($getOrder["secure_status"] == 1){

update("update uni_secure set secure_status=? where secure_id=? and secure_id_user_seller=?", [ 2 , $id, $_SESSION['profile']['id'] ]);

echo true;

}else{

echo false;

}

?>