<?php

$_POST["payment_variant"] = $_POST["payment_variant"] ?: [];

update("UPDATE uni_settings SET value=? WHERE name=?", array(implode(",",$_POST["payment_variant"]),'payment_variant'));

if(isset($_POST["payment_param"])){

   $param = json_encode($_POST["payment_param"]);
   $param = encrypt($param);
   update("UPDATE uni_payments SET param=? WHERE code = ?", array($param,$_POST["payment"]));

}

?>