<?php
$config = require "./config.php";
$static_msg = require "./static/msg.php";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Banners = new Banners();
$Blog = new Blog();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");
$getCategoryBlog = $Blog->getCategories("where blog_category_visible=1");

$uri = parseUriBlog($riddle,$getCategoryBlog);

if( $uri["id"] ){

	$route_name = "blog_view";

	$data["article"] = $Blog->get("where blog_articles_id=? and blog_articles_alias=? and blog_category_alias=? and blog_articles_visible=?", [$uri["id"],$uri["alias_article"],$uri["alias_cat"],1]);

	if(!$data["article"]){
		$Main->response(404);
	}

	$Blog->viewArticle($uri["id"]);

	$data["article_rand"] = $Blog->getAll( ["query"=>"blog_articles_visible=1", "sort"=>"order by rand() limit 4"] );

	$data["meta_title"] = $Seo->out(["page" => "article", "field" => "meta_title"], $data);
	$data["meta_desc"] = $Seo->out(["page" => "article", "field" => "meta_desc"], $data);

	$data["breadcrumb"] = breadcrumb_count($Blog->breadcrumb($getCategoryBlog,$data["article"]["blog_category_id"],'
	                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
	                '),3);

    echo $Main->tpl("blog_view.tpl", compact( 'Seo','Geo','Main','visible_footer','data','route_name','Profile','settings','CategoryBoard','getCategoryBoard','Banners','getCategoryBlog','Blog','ULang' ) );

}else{

	$route_name = "blog";

	if($uri["alias_cat"]){

		$data["category"] = $getCategoryBlog["blog_category_chain"][ $uri["alias_cat"] ];
		if(!$data["category"]){
			$Main->response(404);
		}

		$data["meta_title"] = $Seo->out(["page" => "blog_category", "field" => "meta_title"], $data);
		$data["meta_desc"] = $Seo->out(["page" => "blog_category", "field" => "meta_desc"], $data);

		$data["h1"] = $Seo->out(["page" => "blog_category", "field" => "h1"], $data);
		$data["seo_text"] = $Seo->out(["page" => "blog_category", "field" => "text"], $data);
		$data["breadcrumb"] = breadcrumb_count($Blog->breadcrumb($getCategoryBlog,$data["category"]["blog_category_id"],'
	                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
	                '),3);

		$ids_cat = idsBuildJoin( $Blog->idsBuild($data["category"]["blog_category_id"], $Blog->getCategories("where blog_category_visible=1")), $data["category"]["blog_category_id"] );
		$query = "blog_articles_id_cat IN(".$ids_cat.") and blog_articles_visible='1'";

	}else{

		$data["meta_title"] = $Seo->out(["page" => "blog", "field" => "meta_title"], $data);
		$data["meta_desc"] = $Seo->out(["page" => "blog", "field" => "meta_desc"], $data);

		$data["h1"] = $Seo->out(["page" => "blog", "field" => "h1"], $data);
		$data["seo_text"] = $Seo->out(["page" => "blog", "field" => "text"], $data);
		$data["breadcrumb"] = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name">'.$data["h1"].'</span></li>';

		$query = "blog_articles_visible='1'";

	}

	$data["sidebar_category"] = $Blog->outCategories( $getCategoryBlog, 0, $data["category"]["blog_category_id"]);

	$data["articles"] = $Blog->getAll( array("navigation"=>true,"page"=>$_GET["page"],"output"=>$settings["blog_out_content"],"query"=>$query, "sort"=>"ORDER By blog_articles_id DESC") );

	echo $Main->tpl("blog.tpl", compact( 'Seo','Geo','Main','visible_footer','data','route_name','Profile','settings','CategoryBoard','getCategoryBoard','Banners','getCategoryBlog','Blog','ULang' ) );

}

?>