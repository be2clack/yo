<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($_POST["bonus"]),'bonus_program'));

?>