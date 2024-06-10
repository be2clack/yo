<?php
if($_POST["user_score_booking"]) $user_score = encrypt($_POST["user_score_booking"]);

update("update uni_clients set clients_score_booking=? where clients_id=?", [$user_score,$_SESSION["profile"]["id"]]);
echo json_encode( ["status"=>true] );
?>