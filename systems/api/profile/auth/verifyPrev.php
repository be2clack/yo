<?php

$user_login = clear($_POST['login']);

update('delete from uni_verify_code where phone=? or email=?', [$user_login,$user_login]);

echo json_encode(['status'=>true]);

?>