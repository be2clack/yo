<?php

$id_filter = (int)$_POST["id_filter"];
$id_item = (int)$_POST["id_item"];


if($_POST["view"] == "catalog"){
 echo $Filters->load_podfilters_catalog($id_filter,$id_item);
}elseif($_POST["view"] == "modal"){
 echo $Filters->load_podfilters_catalog($id_filter,$id_item,[],"podfilters_modal");
}elseif($_POST["view"] == "ad"){
 echo $Filters->load_podfilters_ad($id_filter,$id_item);
}

?>