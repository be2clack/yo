<?php

 echo back_step_user();

 if(isset($_SESSION['user_step_route'])){
    unset($_SESSION['user_step_route'][ count($_SESSION['user_step_route']) - 1 ]);
 }

?>