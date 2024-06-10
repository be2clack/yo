<?php

if(intval($_POST["referral_program_award_percent"]) > 100){
  $_POST["referral_program_award_percent"] = 100;
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["referral_program_status"]),'referral_program_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["referral_program_award_percent"]),'referral_program_award_percent'));

?>