<?php

$id = (int)$_POST['id'];

unset($_SESSION['cart'][$id]);

if($_SESSION['profile']['id']){
     update("DELETE FROM uni_cart WHERE cart_ad_id=? and cart_user_id=?", [$id,$_SESSION["profile"]["id"]]);
}

?>