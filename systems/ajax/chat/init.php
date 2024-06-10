<?php

if(!$_SESSION['profile']['id']){ exit; }

$id_ad = (int)$_POST['id_ad'];
$id_user = (int)$_POST['id_user'];

if($id_ad){

    $getAd = findOne("uni_ads", "ads_id=?", [$id_ad]);

    if($getAd){ 

        $interlocutor = $Profile->oneUser(" where clients_id=?" , array($getAd['ads_id_user']));
        if($interlocutor){ 

            if(findOne("uni_ads", "ads_id=? and (ads_id_user=? or ads_id_user=?)", [$id_ad,intval($_SESSION['profile']['id']),intval($interlocutor["clients_id"])])){

                $getUserChat = findOne("uni_chat_users", "chat_users_id_ad=? and chat_users_id_user=? and chat_users_id_interlocutor=?", [$id_ad, intval($_SESSION['profile']['id']), $interlocutor["clients_id"] ]);
                if(!$getUserChat){
                    $data["id_hash"] = md5($id_ad.intval($_SESSION['profile']['id']));
                    insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor)VALUES(?,?,?,?)", array($id_ad, intval($_SESSION['profile']['id']), $data["id_hash"], $interlocutor["clients_id"]));
                }else{
                    $data["id_hash"] =  $getUserChat["chat_users_id_hash"];
                }

            }
            
        }
    }

}else{

    $interlocutor = $Profile->oneUser(" where clients_id=?" , array($id_user));
    if($interlocutor){ 

        $getUserChat = findOne("uni_chat_users", "chat_users_id_ad=? and ((chat_users_id_user=? and chat_users_id_interlocutor=?) or (chat_users_id_interlocutor=? and chat_users_id_user=?))", [0,$_SESSION['profile']['id'],$id_user,$_SESSION['profile']['id'],$id_user]);

        if(!$getUserChat){
            $data["id_hash"] = md5($_SESSION['profile']['id'].$id_user);
            insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor)VALUES(?,?,?,?)", array(0,$_SESSION['profile']['id'],$data["id_hash"],$id_user));
        }else{
            $data["id_hash"] = $getUserChat["chat_users_id_hash"];
            if($getUserChat['chat_users_id_interlocutor'] == $_SESSION['profile']['id']){
                insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor)VALUES(?,?,?,?)", array(0,$_SESSION['profile']['id'],$data["id_hash"],$id_user));
            }
        }
        
    }			

}


ob_start();
$Profile->chatUsers($data["id_hash"],true);
$list_chat_users = ob_get_clean();

ob_start();
include $config["template_path"] . "/include/chat_body.php";
echo ob_get_clean();

?>