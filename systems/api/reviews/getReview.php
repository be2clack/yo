<?php

$idReview = (int)$_GET["id_review"];

$getReview = findOne('uni_clients_reviews', 'clients_reviews_id=?', [$idReview]);

echo json_encode(['data'=>apiArrayDataReviews($getReview) ?: null]);

?>