<div class="blog-view-sidebar" >
  
  <div class="blog-view-article-rand" >	
	  <div class="row" >
	  <?php
	  if($data["article_rand"]["count"]){
	  	 foreach ($data["article_rand"]["all"] as $key => $value) {
	  	 	?>
	      <div class="col-lg-12 col-6" >
	        <a href="<?php echo $Blog->aliasArticle($value); ?>" title="<?php echo $ULang->t( $value["blog_articles_title"], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ); ?>"  >
	         <div class="view-article-rand-image" ><img src="<?php echo Exists($config["media"]["big_image_blog"],$value["blog_articles_image"],$config["media"]["no_image"]); ?>" alt="<?php echo $ULang->t( $value["blog_articles_title"], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ); ?>" ></div>
	         <p><?php echo $ULang->t( $value["blog_articles_title"], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ); ?></p>
	        </a>
	      </div>
	  	 	<?php
	  	 }
	  }
	  ?>
	  </div>
  </div>

</div>

<?php echo $Banners->out( ["position_name"=>"blog_view_sidebar", "current_id_cat"=>$data["article"]["blog_articles_id_cat"], "categories"=>$getCategoryBlog] ); ?>