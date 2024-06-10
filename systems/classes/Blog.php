<?php
/**
 * UniSite CMS
 *
 * @copyright 	2018 Artur Zhur
 * @link 		https://unisitecms.ru
 * @author 		Artur Zhur
 *
 */
 
class Blog{
   
   function aliasArticle($param = array()){
      return _link("blog/".$param["blog_category_alias"]."/".$param["blog_articles_alias"]."-".$param["blog_articles_id"]); 
   }   

   function aliasCategory($alias){
      return _link("blog/".$alias); 
   }   

   function delete($ids = []){
    global $config;
      
      if($ids){ 

       $get = getAll("select * from uni_blog_articles where blog_articles_id IN(".implode(",", $ids).")");

         foreach ($get as $key => $value) {

            @unlink($config["basePath"] . "/" . $config["media"]["big_image_blog"] . "/" . $value["blog_articles_image"]);

            update("DELETE FROM uni_blog_articles WHERE blog_articles_id=?", array($value["blog_articles_id"]));

         }
         
      }

   }

   function getAll( $array = array(), $param = array()){
      global $settings,$Cashed;

        if(isset($array["query"])){
          if(isset($array["sort"])){
            $array["query"] = " where " . $array["query"] . " " . $array["sort"];
          }else{
            $array["query"] = " where " . $array["query"];
          }         
        }else{
          $array["query"] = $array["sort"];
        }

        $inner = "INNER JOIN `uni_blog_category` ON `uni_blog_category`.blog_category_id = `uni_blog_articles`.blog_articles_id_cat";

        if(!isset($array["output"])) $array["output"] = $settings["catalog_out_content"];

         $getOne = getOne("select count(*) as total from uni_blog_articles $inner {$array["query"]}",$param);

         if(isset($array["navigation"])){ 
            $getAll = getAll("select * from uni_blog_articles $inner {$array["query"]} ".navigation_offset( array( "count"=>$getOne["total"], "output"=>$array["output"], "page"=>$array["page"] ) ),$param);
         }else{
            $getAll = getAll("select * from uni_blog_articles $inner {$array["query"]} ",$param);
         }

         return array("count"=>$getOne["total"], "all"=>$getAll);

   }

   function get( $query = "", $param = array()){
      global $settings,$Cashed;

        $inner = "
        INNER JOIN `uni_blog_category` ON `uni_blog_category`.blog_category_id = `uni_blog_articles`.blog_articles_id_cat
        ";

        return getOne("select * from uni_blog_articles $inner {$query} ",$param);

   }

    function allMainCategory(){
       
       $ULang = new ULang();
       $Blog = new Blog();
       $getCategories = $Blog->getCategories("where blog_category_visible=1");

       if($getCategories["blog_category_id_parent"][0]){
           foreach ($getCategories["blog_category_id_parent"][0] as $key => $value) {
              $out[] = $ULang->t( $value["blog_category_name"] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] );
           }
         return implode(",", $out);
       }

    }

    function getCategories($query = ""){
        $array = array();

        $Cache = new Cache();

        if( $Cache->get( [ "table" => "uni_blog_category", "key" => $query ] ) ){
           
           return $Cache->get( [ "table" => "uni_blog_category", "key" => $query ] );

        }else{

           $get = getAll("SELECT * FROM uni_blog_category $query ORDER By blog_category_id_position ASC");
           if (count($get)) { 
                            
                foreach($get AS $result){

                    if($result['blog_category_id_parent']){
                      $result['blog_category_chain'] = $this->aliasBuild($result['blog_category_id']);
                    }else{
                      $result['blog_category_chain'] = $result['blog_category_alias'];
                    }
                    
                    $array['blog_category_chain'][$result['blog_category_chain']] = $result;
                    $array['blog_category_id_parent'][$result['blog_category_id_parent']][$result['blog_category_id']] =  $result;
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_id_parent'] =  $result['blog_category_id_parent'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_name'] =  $result['blog_category_name'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_title'] =  $result['blog_category_title'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_h1'] =  $result['blog_category_h1'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_desc'] =  $result['blog_category_desc'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_image'] =  $result['blog_category_image'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_text'] =  $result['blog_category_text'];
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_alias'] =  $result['blog_category_alias'];        
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_visible'] =  $result['blog_category_visible'];        
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_id'] =  $result['blog_category_id'];        
                    $array['blog_category_id'][$result['blog_category_id']]['blog_category_chain'] =  $result['blog_category_chain'];        

                } 

                $Cache->set( [ "table" => "uni_blog_category", "key" => $query, "data" => $array ] ); 

           }            

           return $array;

        }        
      
           
    }

    function aliasBuild($id = 0){

      $get = getOne("SELECT * FROM uni_blog_category where blog_category_id=?", array($id));
      
        if($get['blog_category_id_parent']!=0){ 
            $out .= $this->aliasBuild($get['blog_category_id_parent'])."/";            
        }
        $out .= $get['blog_category_alias'];

        return $out; 
               
    }   

    function idsBuild($parent_id=0, $categories = []){
        
        if(isset($categories['blog_category_id_parent'][$parent_id])){

              foreach($categories['blog_category_id_parent'][$parent_id] as $cat){
                       
                $ids[] = $cat['blog_category_id'];
                
                if( $categories['blog_category_id_parent'][$cat['blog_category_id']] ){
                  $ids[] = $this->idsBuild($cat['blog_category_id'],$categories);
                }
                                            
              }

        }
        
        return isset($ids) ? implode(",", $ids) : '';

    }

   function viewArticle($id=0){
    if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
      if($id){    
          if(!isset($_SESSION["view-article"][$id])){
            update("UPDATE uni_blog_articles SET blog_articles_count_view=blog_articles_count_view+1 WHERE blog_articles_id=?", array($id)); 
            $_SESSION["view-article"][$id] = 1;
          }  
      } 
    }   
   }

    function breadcrumb($getCategories=array(),$id=0,$tpl="",$sep=""){

      $ULang = new ULang();

      if($getCategories){

        if($getCategories["blog_category_id"][$id]['blog_category_id_parent']!=0){
            $return[] = $this->breadcrumb($getCategories,$getCategories["blog_category_id"][$id]['blog_category_id_parent'],$tpl,$sep);  
        }

        $return[] = replace(array("{LINK}", "{NAME}"),array($this->aliasCategory($getCategories["blog_category_id"][$id]["blog_category_chain"]),$ULang->t( $getCategories["blog_category_id"][$id]['blog_category_name'] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] )),$tpl);

        return implode($sep,$return);

      } 
               
    }

    function outCategoryParent( $getCategories = [], $param = [] ){
      global $config;

      $ULang = new Ulang();

      if($param["category"]["blog_category_id"]){

        if (isset($getCategories["blog_category_id_parent"][$param["category"]["blog_category_id"]])) {

            foreach ($getCategories["blog_category_id_parent"][$param["category"]["blog_category_id"]] as $parent_value) {

               $countArticle = (int)getOne("select count(*) as total from uni_blog_articles where blog_articles_id_cat=? and blog_articles_visible=?", [$parent_value["blog_category_id"],1])["total"];
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_ITEM}"),array($this->aliasCategory($parent_value["blog_category_chain"]),Exists($config["media"]["big_image_blog"],$parent_value["blog_category_image"],$config["media"]["no_image"]),$ULang->t( $parent_value["blog_category_name"] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ),$countArticle),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }

        }else{

          $id_parent = $getCategories["blog_category_id"][$param["category"]["blog_category_id_parent"]];

          if(isset($getCategories["blog_category_id_parent"][$id_parent["blog_category_id"]])){
            foreach ($getCategories["blog_category_id_parent"][$id_parent["blog_category_id"]] as $parent_value) {

              if($parent_value["blog_category_id"] == $param["category"]["blog_category_id"]){
                $active = 'class="active"';
              }else{
                $active = '';
              }

              $countArticle = (int)getOne("select count(*) as total from uni_blog_articles where blog_articles_id_cat=? and blog_articles_visible=?", [$parent_value["blog_category_id"],1])["total"];
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}", "{ACTIVE}","{COUNT_ITEM}"),array($this->aliasCategory($parent_value["blog_category_chain"]),Exists($config["media"]["big_image_blog"],$parent_value["blog_category_image"],$config["media"]["no_image"]),$ULang->t( $parent_value["blog_category_name"] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ),$active,$countArticle),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
          }else{

              if (isset($getCategories["blog_category_id_parent"][0])) {
                  foreach ($getCategories["blog_category_id_parent"][0] as $parent_value) {

                    if($parent_value["blog_category_id"] == $param["category"]["blog_category_id"]){
                      $active = 'class="active"';
                    }else{
                      $active = '';
                    }

                     $countArticle = (int)getOne("select count(*) as total from uni_blog_articles where blog_articles_id_cat=? and blog_articles_visible=?", [$parent_value["blog_category_id"],1])["total"];
                    
                     $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_ITEM}","{ACTIVE}"),array($this->aliasCategory($parent_value["blog_category_chain"]),Exists($config["media"]["big_image_blog"],$parent_value["blog_category_image"],$config["media"]["no_image"]),$ULang->t( $parent_value["blog_category_name"] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ),$countArticle,$active),$param["tpl_parent"]);

                     $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
                     $parent = array();

                  }
              }           

          }

        }                 

      }else{

        if (isset($getCategories["blog_category_id_parent"][0])) {
            foreach ($getCategories["blog_category_id_parent"][0] as $parent_value) {

              if($parent_value["blog_category_id"] == $param["category"]["blog_category_id"]){
                $active = 'class="active"';
              }else{
                $active = '';
              }

               $countArticle = (int)getOne("select count(*) as total from uni_blog_articles where blog_articles_id_cat=? and blog_articles_visible=?", [$parent_value["blog_category_id"],1])["total"];
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_ITEM}","{ACTIVE}"),array($this->aliasCategory($parent_value["blog_category_chain"]),Exists($config["media"]["big_image_blog"],$parent_value["blog_category_image"],$config["media"]["no_image"]),$ULang->t( $parent_value["blog_category_name"] , [ "table"=>"uni_blog_category", "field"=>"blog_category_name" ] ),$countArticle,$active),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
        }

      }

      return $return;

    }

    function getComments( $id = 0 ){
        $array = array();
      
        $get = getAll("SELECT * FROM uni_blog_comments INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_blog_comments`.blog_comments_id_user WHERE blog_comments_id_article=? ORDER By blog_comments_id desc", [$id]);

        if (count($get)) { 
                          
              foreach($get AS $result){

                  $array['blog_comments_id_parent'][$result['blog_comments_id_parent']][$result['blog_comments_id']] =  $result;

              }  

        }            

        return $array;
           
    }

    function outComments($id_parent = 0, $getComments=[]) {
      global $config;

      $Profile = new Profile();
      $ULang = new ULang();
        
        if (isset($getComments["blog_comments_id_parent"][$id_parent])) {
            foreach ($getComments["blog_comments_id_parent"][$id_parent] as $value) {

                ?>

                    <div <?php if($id_parent != 0){ echo 'style="margin-left: 60px;"'; } ?> >
                       <div class="module-comments-avatar" >
                         <img src="<?php echo $Profile->userAvatar($value["clients_avatar"]); ?>">
                       </div>
                       <div class="module-comments-content" >
                         
                         <?php if( $_SESSION['cp_auth'][ $config["private_hash"] ] || intval($_SESSION["profile"]["id"]) == $value["blog_comments_id_user"] ){ ?>
                         <span class="module-comments-delete" data-id="<?php echo $value["blog_comments_id"]; ?>" > <i class="las la-trash"></i> <?php echo $ULang->t("Удалить"); ?> </span>
                         <?php } ?>

                         <p> <strong> <?php echo $Profile->name($value); ?> </strong> <span><?php echo datetime_format($value["blog_comments_date"]); ?></span> </p>

                         <?php
                         if($value["blog_comments_id_parent"]!=0){
                            $getMsg = getOne("SELECT * FROM uni_blog_comments INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_blog_comments`.blog_comments_id_user WHERE blog_comments_id=?", [$value["blog_comments_id_parent"]]);
                            ?>
                            <strong><i class="las la-share"></i> <?php echo $Profile->name($getMsg); ?></strong>,
                            <?php
                         }
                         ?>
                         
                         <?php echo $value["blog_comments_text"]; ?>

                         <?php if( intval($_SESSION["profile"]["id"]) != $value["blog_comments_id_user"] && $_SESSION['profile']['id'] ){ ?>

                         <div><span class="module-comments-otvet" data-id="<?php echo $value["blog_comments_id"]; ?>" ><?php echo $ULang->t("Ответить"); ?></span></div>

                         <?php } ?>

                         <div class="module-comments-form-otvet" >
                           <form class="module-comments-form" >
                           <textarea name="text" ></textarea>
                           <button class="module-comments-form-send" ><i class="las la-arrow-right"></i></button>
                           <input type="hidden" name="id_article" value="<?php echo $value["blog_comments_id_article"]; ?>" >
                           <input type="hidden" name="id_msg" value="<?php echo $value["blog_comments_id"]; ?>" >
                           <input type="hidden" name="token" value="<?php echo md5($config["private_hash"].$value["blog_comments_id"].$value["blog_comments_id_article"]); ?>" >
                           </form>
                         </div>

                       </div>
                       <div class="clr" ></div>
                    </div>

                <?php

                $this->outComments($value["blog_comments_id"], $getComments);

            }
        }
    }

    function idsComments($parent_id=0, $getComments=[]){
        
        if(isset($getComments['blog_comments_id_parent'][$parent_id])){

              foreach($getComments['blog_comments_id_parent'][$parent_id] as $value){
                
                $ids[] = $value['blog_comments_id'];
                
                if( $getComments['blog_comments_id_parent'][$value['blog_comments_id']] ){
                  $ids[] = $this->idsComments($value['blog_comments_id'],$getComments);
                }
                                                                    
              }

        }

        return implode(",", $ids);

    }

    function outCategories($getCategories = [], $id_parent = 0, $current_id_category = 0){

        $ULang = new ULang();
        
        if (isset($getCategories["blog_category_id_parent"][$id_parent])) {
        $tree = '<ul>';

            foreach($getCategories["blog_category_id_parent"][$id_parent] as $value){
                
                $activeLink = '';

                if( $current_id_category == $value['blog_category_id'] ){
                    $activeLink = 'class="active"';
                }

                $tree .= '<li> <a '.$activeLink.' href="'.$this->aliasCategory( $value['blog_category_chain'] ).'" >'.$ULang->t( $value["blog_category_name"], [ "table" => "uni_blog_category", "field" => "blog_category_name" ] ).' <i class="las la-check"></i></a> ';
                $tree .=  $this->outCategories($getCategories,$value['blog_category_id'],$current_id_category);
                $tree .= '</li>';
            }

        $tree .= '</ul>';
        }

        return $tree;
    }


      
}


?>