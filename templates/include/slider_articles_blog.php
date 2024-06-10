<a href="<?php echo $Blog->aliasArticle($value); ?>" class="article-grid-slider" style="background-image: linear-gradient(rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.24) 75%, rgba(0, 0, 0, 0.64)), url(<?php echo Exists($config["media"]["big_image_blog"],$value["blog_articles_image"],$config["media"]["no_image"]); ?>); background-position: center center; background-size: cover;" >

   <div class="article-grid-slider-item-title" >
      <?php echo custom_substr( $ULang->t( $value["blog_articles_title"], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ) , 50, "..."); ?>
   </div>
   
</a>
