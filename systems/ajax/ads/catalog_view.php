<?php
 if( $_POST["view"] == "grid" ){
    $_POST["view"] = "grid";
 }elseif( $_POST["view"] == "list" ){
    $_POST["view"] = "list";
 }else{
    $_POST["view"] = "grid";
 }

 $_SESSION["catalog_ad_view"] = $_POST["view"];
?>