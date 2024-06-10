<?php

$location = clear($_POST['location']);
if($location) $_SESSION['point-auth-location'] = $location;

?>