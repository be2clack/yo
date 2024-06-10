<?php

$idUser = (int)$_GET["id_user"];
$page = (int)$_GET["page"];

$results = [];

$output = 30;

$totalCountReviews = (int)getOne("SELECT count(*) as total FROM uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=?", [$idUser,1])["total"];

$getReviews = getAll('select * from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=? order by clients_reviews_id desc'.navigation_offset(["count"=>$totalCountReviews, "output"=>$output, "page"=>$page]), [$idUser,1]);

if(count($getReviews)){
	foreach ($getReviews as $value) {

    	$results[] = apiArrayDataReviews($value);
    	
	}
}

echo json_encode(['data'=>$results ?: null, 'total_rating'=>$Profile->ratingBalls($idUser), 'total_count_reviews_string'=>$totalCountReviews.' '.ending($totalCountReviews, apiLangContent('отзыв'), apiLangContent('отзыва'), apiLangContent('отзывов')), 'count'=>$totalCountReviews, 'pages'=>getCountPage($totalCountReviews,$output)]);

?>