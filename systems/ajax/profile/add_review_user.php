<?php

$error = [];

$id_ad = (int)$_POST["id_ad"];
$status_result = (int)$_POST["status_result"];
$id_user = (int)$_POST["id_user"];
$attach = $_POST["attach"] ? array_slice($_POST["attach"],0, 10) : [];

if( !intval($_POST["rating"]) ){ $error[] = $ULang->t("Пожалуйста, поставьте оценку"); }
if( !$_POST["text"] ){ $error[] = $ULang->t("Пожалуйста, напишите отзыв"); }
if( !$id_user ){ $error[] = $ULang->t("Пожалуйста, выберите ваш статус"); }

$getAd = findOne("uni_ads", "ads_id=?", [ $id_ad ]);

if( !$getAd ){ $error[] = $ULang->t("Товар не найден!"); }

if( count($error) == 0 ){

        $status_publication_review = findOne("uni_clients_reviews", "clients_reviews_from_id_user=? and clients_reviews_id_user=? and clients_reviews_id_ad=?", [intval($_SESSION['profile']['id']), $id_user, $id_ad]);

        if(!$status_publication_review){

            insert("INSERT INTO uni_clients_reviews(clients_reviews_id_user,clients_reviews_text,clients_reviews_from_id_user,clients_reviews_rating,clients_reviews_id_ad,clients_reviews_status_result,clients_reviews_files,clients_reviews_date)VALUES(?,?,?,?,?,?,?,?)", [ $id_user,clear($_POST["text"]),$_SESSION["profile"]["id"],intval($_POST["rating"]),$id_ad,$status_result,implode(",",$attach), date("Y-m-d H:i:s") ]);

            $Profile->sendChat( array("id_ad" => $id_ad, "action" => 4, "user_from" => intval($_SESSION["profile"]["id"]), "user_to" => $id_user ) );
            
            if( count($attach) ){

                foreach ($attach as $name) {
                    @copy( $config["basePath"] . "/" . $config["media"]["temp_images"] . "/" . $name , $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $name );
                }
                
            }

            $Admin->notifications("review");

            echo json_encode( ["status"=>true, "answer"=>$ULang->t("Отзыв успешно принят. После проверки модератором он появится на сайте.")] );

            unset($_SESSION['csrf_token'][$_POST['csrf_token']]);

        }else{
            echo json_encode(["status"=>false, "answer"=>$ULang->t("Вы уже оставляли отзыв для данного объявления!")]);
        }

}else{
    echo json_encode( ["status"=>false, "answer"=> implode("\n", $error) ] );
}

?>