<?php

if(!$_POST["region_id"]){
  $_POST["city_id"] = 0;
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["city_auto_detect"]),'city_auto_detect'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["country_default"]),'country_default'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["region_id"]),'region_id'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["city_id"]),'city_id'));

if($_POST["country_default"]){

	 $country = findOne("uni_country","country_alias=?", array(clear($_POST["country_default"])));

	 update("UPDATE uni_settings SET value=? WHERE name=?", array($country->country_lat,'country_lat'));
	 update("UPDATE uni_settings SET value=? WHERE name=?", array($country->country_lng,'country_lng'));  
	 update("UPDATE uni_settings SET value=? WHERE name=?", array($country->country_id,'country_id')); 

}

?>