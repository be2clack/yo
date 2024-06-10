<?php

$cat_id = (int)$_GET["cat_id"];
$page = (int)$_GET["page"];

$results = [];

$output = 15;

if(!$cat_id){
	$totalCount = (int)getOne("SELECT count(*) as total FROM uni_blog_articles where blog_articles_visible=?", [1])["total"];
	$getAll = getAll('select * from uni_blog_articles where blog_articles_visible=? order by blog_articles_id desc'.navigation_offset(["count"=>$totalCount, "output"=>$output, "page"=>$page]), [1]);
}else{
	$totalCount = (int)getOne("SELECT count(*) as total FROM uni_blog_articles where blog_articles_visible=? and blog_articles_id_cat=?", [1,$cat_id])["total"];
	$getAll = getAll('select * from uni_blog_articles where blog_articles_visible=? and blog_articles_id_cat=? order by blog_articles_id desc'.navigation_offset(["count"=>$totalCount, "output"=>$output, "page"=>$page]), [1,$cat_id]);
}

if(count($getAll)){
	foreach ($getAll as $value) {

		$getCategory = findOne('uni_blog_category','blog_category_id=?', [$value["blog_articles_id_cat"]]);
		if($getCategory){
    		$results['articles'][] = ['id'=>$value['blog_articles_id'],'title'=>$ULang->tApp( $value['blog_articles_title'], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ),'text'=>$ULang->tApp( $value['blog_articles_text'], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_text" ] ),'date_add'=>date('d.m.Y', strtotime($value['blog_articles_date_add'])),'desc'=>$value['blog_articles_desc'],'count_view'=>$value['blog_articles_count_view'],'image'=>Exists($config["media"]["big_image_blog"],$value['blog_articles_image'],$config["media"]["no_image"]),'cat_name'=>$ULang->tApp( $getCategory['blog_category_name'], [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] )];
    	}
    	
	}
}

$getCategories = getAll('select * from uni_blog_category where blog_category_visible=?', [1]);
 
if($getCategories){
	foreach ($getCategories as $key => $value) {
		$count = (int)getOne("SELECT count(*) as total FROM uni_blog_articles where blog_articles_visible=? and blog_articles_id_cat=?", [1,$value['blog_category_id']])["total"];
		$results['categories'][] = ['id'=>$value['blog_category_id'], 'name'=>$ULang->tApp( $value["blog_category_name"], [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ), 'count'=>$count];
	}
}

echo json_encode(['data'=>$results['articles'], 'count'=>$totalCount, 'pages'=>getCountPage($totalCount,$output), 'categories'=>$results['categories'] ?: null]);

?>