<?php

$id = (int)$_GET["id"];

$get = findOne('uni_blog_articles', 'blog_articles_id=?', [$id]);

if(!$get){
	http_response_code(500); exit('Article not found');
}

update("UPDATE uni_blog_articles SET blog_articles_count_view=blog_articles_count_view+1 WHERE blog_articles_id=?", array($id));

$getCategory = findOne('uni_blog_category','blog_category_id=?', [$get["blog_articles_id_cat"]]);

$results = [
	'title'=>$ULang->tApp( $get['blog_articles_title'], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ),
	'text'=>html_entity_decode(strip_tags(urldecode($ULang->tApp( $get['blog_articles_text'], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_text" ] )))),
	'date_add'=>date('d.m.Y', strtotime($get['blog_articles_date_add'])),
	'count_view'=>$get['blog_articles_count_view'],
	'image'=> $get['blog_articles_image'] ? Exists($config["media"]["big_image_blog"],$get['blog_articles_image'],$config["media"]["no_image"]) : null,
	'cat_name'=>$ULang->tApp( $getCategory["blog_category_name"], [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ),
];

echo json_encode($results);

?>