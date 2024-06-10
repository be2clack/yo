<?php

$country_alias = clear($_POST["alias"]);

echo $Geo->cityDefault($country_alias,30,false);

?>