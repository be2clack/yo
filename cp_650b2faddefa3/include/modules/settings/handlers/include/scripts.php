<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array($Main->clearPHP($_POST["code_script"]),'code_script'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($Main->clearPHP($_POST["header_meta"]),'header_meta'));

?>