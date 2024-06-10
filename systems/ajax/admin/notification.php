<?php

if (isset($_SESSION["CheckMessage"])){
    echo json_encode($_SESSION["CheckMessage"]);
    unset($_SESSION["CheckMessage"]);  
 }else{ echo false; }   

?>