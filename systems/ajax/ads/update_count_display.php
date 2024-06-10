<?php
if($_SESSION['count_display_ads']){

	 foreach ($_SESSION['count_display_ads'] as $id => $id_user) {
	     insert("INSERT INTO uni_ads_views_display_temp(ad_id,user_id)VALUES(?,?)", [$id,$id_user]);
	 }

	 unset($_SESSION['count_display_ads']);

}
?>