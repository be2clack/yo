<?php

$sign = md5($param["id_shop"].':'.$paramForm["amount"].':'.$param["secret_word1"].':'.$param["currency"].':'.$paramForm["id_order"]);

$link = "https://pay.freekassa.ru/?m=".$param["id_shop"]."&oa=".$paramForm["amount"]."&o=".$paramForm["id_order"]."&s=".$sign."&currency=".$param["currency"];

return ["link"=>$link];

?>