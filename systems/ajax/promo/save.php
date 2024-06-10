<?php

if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){

	update("update uni_promo_pages set promo_pages_html_edit=?,promo_pages_logotip=?,promo_pages_color=? where promo_pages_id=?", [ trim($_POST["html"]),intval($_POST["logo"]),clear($_POST["color"]), intval($_POST["id"]) ]);

	$_POST["html"] = preg_replace('#<div class="promo-add-element">.*?</div>#s', "", trim($_POST["html"]));
	$_POST["html"] = preg_replace('#<div class="promo-controls">.*?</div>#s', "", trim($_POST["html"]));

	update("update uni_promo_pages set promo_pages_html_public=? where promo_pages_id=?", [ $_POST["html"], intval($_POST["id"]) ]);

}

?>