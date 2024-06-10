<?php

if(!$_SESSION["profile"]["id"]){ echo $Main->response(401); }

unset($_SESSION['cart']);

update("DELETE FROM uni_cart WHERE cart_user_id=?", [$_SESSION["profile"]["id"]]);


?>