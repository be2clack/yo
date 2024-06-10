<div class="article-item" >
   
   <div class="row no-gutters" >

      <div class="col-lg-4 col-12 col-md-4 col-sm-4" >
         <div class="article-item-image" > <a href="<?php echo $Blog->aliasArticle($value); ?>"> <img alt="<?php echo $ULang->t( $value["blog_articles_title"], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ); ?>" class="image-autofocus" src="<?php echo Exists($config["media"]["big_image_blog"],$value["blog_articles_image"],$config["media"]["no_image"]); ?>" > </a> </div>  
      </div>
      <div class="col-lg-8 col-12 col-md-8 col-sm-8" >
         
         <div class="article-item-content" >

            <div>
               <span><?php echo $ULang->t( $value["blog_category_name"] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ); ?> &bull; <?php echo datetime_format($value["blog_articles_date_add"], false); ?></span>
            </div>

            <a class="article-item-content-title" href="<?php echo $Blog->aliasArticle($value); ?>"><?php echo custom_substr( $ULang->t( $value["blog_articles_title"], [ "table"=>"uni_blog_articles", "field"=>"blog_articles_title" ] ) , 50, "..."); ?></a>

            <?php if($value["blog_articles_desc"]){ ?>
            <p><?php echo custom_substr(urldecode($value["blog_articles_desc"]), 300, "..."); ?></p>
            <?php }else{ ?>
            <p><?php echo custom_substr(strip_tags(urldecode($value["blog_articles_text"])), 300, "..."); ?></p>
            <?php } ?>

            <a href="<?php echo $Blog->aliasArticle($value); ?>" class="article-item-content-link btn-custom-mini btn-color-light width100" ><?php echo $ULang->t('Читать'); ?></a>
            
         </div>

      </div>
      
   </div>

</div>
