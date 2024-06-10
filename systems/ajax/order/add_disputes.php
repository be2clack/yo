<?php

if(!intval($_POST["id"])){ exit; }

$text = clear($_POST["text"]);

$attach = [];

if($text){

 $getSecure = findOne("uni_secure", "secure_id=? and (secure_status=? or secure_status=? or secure_status=?)", [intval($_POST["id"]),1,2,3]);

 if($getSecure){

 $files = normalize_files_array( $_FILES );
 if($files["files"]){
    foreach ( array_slice($files["files"], 0, 5) as $key => $value) {

        $path = $config["basePath"] . "/" . $config["media"]["user_attach"];
        $max_file_size = 2;
        $extensions = array('jpeg', 'jpg', 'png');
        $ext = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
        
        if($value["size"] <= $max_file_size*1024*1024){

          if (in_array($ext, $extensions))
          {
            
                $uid = md5($_SESSION['profile']['id'] . uniqid());

                $name = $uid . "." . $ext;
                
                if( move_uploaded_file($value["tmp_name"], $path . "/" . $name) ){
                    $attach[] = $name;
                }
                
          }

        }
         
    }
 }

 insert("INSERT INTO uni_secure_disputes(secure_disputes_id_secure,secure_disputes_text,secure_disputes_date,secure_disputes_id_user,secure_disputes_attach)VALUES(?,?,?,?,?)", [intval($_POST["id"]), $text, date("Y-m-d H:i:s"), $_SESSION['profile']['id'], json_encode($attach)]);

 update("update uni_secure set secure_status=? where secure_id=? and secure_id_user_buyer=?", [4,intval($_POST["id"]),$_SESSION['profile']['id']]);

 echo json_encode( ["status"=>true] );

 }

}else{
 echo json_encode( ["status"=>false, "answer"=>$ULang->t("Пожалуйста, опишите причину спора")] );
}

?>