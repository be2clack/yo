<?php
 Class Seo{

   function replace($text=""){
   global $settings,$config;

   $Geo = new Geo();
   $ULang = new ULang();
   $geolocation = [];
   $geo_name = "";
   $geo_declination = "";

    if(isset($_SESSION["geo"])){

      if(isset($_SESSION["geo"]["data"]["city_name"])) $geolocation["city"] = $_SESSION["geo"]["data"]["city_name"];
      if(isset($_SESSION["geo"]["data"]["region_name"])) $geolocation["region"] = $_SESSION["geo"]["data"]["region_name"];
      if(isset($_SESSION["geo"]["data"]["country_name"])) $geolocation["country"] = $_SESSION["geo"]["data"]["country_name"];
      if(isset($_SESSION["geo"]["data"]["city_declination"])) $geolocation["city_declination"] = $_SESSION["geo"]["data"]["city_declination"];
      if(isset($_SESSION["geo"]["data"]["region_declination"])) $geolocation["region_declination"] = $_SESSION["geo"]["data"]["region_declination"];
      if(isset($_SESSION["geo"]["data"]["country_declination"])) $geolocation["country_declination"] = $_SESSION["geo"]["data"]["country_declination"];

    }

    if(isset($geolocation["city"])){
       $geo_name = $ULang->t($geolocation["city"] , [ "table" => "geo", "field" => "geo_name" ]);
       if($geolocation["city_declination"]) $geo_declination = $ULang->t($geolocation["city_declination"] , [ "table" => "geo", "field" => "geo_declination" ] );
    }elseif(isset($geolocation["region"])){
       $geo_name = $ULang->t($geolocation["region"] , [ "table" => "geo", "field" => "geo_name" ]);
       if($geolocation["region_declination"]) $geo_declination = $ULang->t($geolocation["region_declination"] , [ "table" => "geo", "field" => "geo_declination" ] );
    }elseif(isset($geolocation["country"])){
       $geo_name = $ULang->t($geolocation["country"] , [ "table" => "geo", "field" => "geo_name" ]);
       if($geolocation["country_declination"]) $geo_declination = $ULang->t($geolocation["country_declination"] , [ "table" => "geo", "field" => "geo_declination" ] );
    }

      $param_name = array(
        "{domen}",
        "{url}",
        "{country}",
        "{city}",
        "{region}",
        "{site_name}",
        "{geo}",
        "{geo_declination}",
      );

      $param_val = array(
        $_SERVER["SERVER_NAME"],
        $config["urlPath"],
        isset($geolocation["country"]) ? $ULang->t($geolocation["country"] , [ "table" => "geo", "field" => "geo_name" ] ) : "",
        isset($geolocation["city"]) ? $ULang->t($geolocation["city"] , [ "table" => "geo", "field" => "geo_name" ] ) : "",
        isset($geolocation["region"]) ? $ULang->t($geolocation["region"] , [ "table" => "geo", "field" => "geo_name" ] ) : "",
        $settings["site_name"],
        $geo_name,
        $geo_declination
      ); 

      return str_replace($param_name, $param_val, $text);

   }
    
   function out( $param = [], $data = [] ){
   global $settings,$config;

   $lang_iso = getLang();

   $CategoryBoard = new CategoryBoard(); 
   $Geo = new Geo();
   $Main = new Main();
   $Blog = new Blog();
   $ULang = new ULang();
     
   $geolocation = [];
   $geo_name = "";
   $geo_declination = "";

   if($param){
    
    if(isset($data["ad"]["city_name"])){

      $geo_name = $ULang->t($data["ad"]["city_name"], [ "table" => "geo", "field" => "geo_name" ]);
      if($data["ad"]["city_declination"]) $geo_declination = $ULang->t($data["ad"]["city_declination"] , [ "table" => "geo", "field" => "geo_name" ] );

    }else{
     
       if(isset($_SESSION["geo"])){

         if(isset($_SESSION["geo"]["data"]["city_name"])) $geolocation["city"] = $_SESSION["geo"]["data"]["city_name"];
         if(isset($_SESSION["geo"]["data"]["region_name"])) $geolocation["region"] = $_SESSION["geo"]["data"]["region_name"];
         if(isset($_SESSION["geo"]["data"]["country_name"])) $geolocation["country"] = $_SESSION["geo"]["data"]["country_name"];
         if(isset($_SESSION["geo"]["data"]["city_declination"])) $geolocation["city_declination"] = $_SESSION["geo"]["data"]["city_declination"];
         if(isset($_SESSION["geo"]["data"]["region_declination"])) $geolocation["region_declination"] = $_SESSION["geo"]["data"]["region_declination"];
         if(isset($_SESSION["geo"]["data"]["country_declination"])) $geolocation["country_declination"] = $_SESSION["geo"]["data"]["country_declination"];

       }

       if(isset($geolocation["city"])){
          $geo_name = $ULang->t($geolocation["city"] , [ "table" => "geo", "field" => "geo_name" ]);
          if($geolocation["city_declination"]) $geo_declination = $ULang->t($geolocation["city_declination"] , [ "table" => "geo", "field" => "geo_declination" ] );
       }elseif(isset($geolocation["region"])){
          $geo_name = $ULang->t($geolocation["region"] , [ "table" => "geo", "field" => "geo_name" ]);
          if($geolocation["region_declination"]) $geo_declination = $ULang->t($geolocation["region_declination"] , [ "table" => "geo", "field" => "geo_declination" ] );
       }elseif(isset($geolocation["country"])){
          $geo_name = $ULang->t($geolocation["country"] , [ "table" => "geo", "field" => "geo_name" ]);
          if($geolocation["country_declination"]) $geo_declination = $ULang->t($geolocation["country_declination"] , [ "table" => "geo", "field" => "geo_declination" ] );
       }

    }

    if(isset($data["category"]["category_board_description"])){
       $data["category"]["category_board_description"] = $this->replace( $ULang->t(urldecode($data["category"]["category_board_description"]) , [ "table" => "uni_category_board", "field" => "category_board_description" ] ) );
    }

    if(isset($data["category"]["category_board_text"])){
       $data["category"]["category_board_text"] = $this->replace( $ULang->t(urldecode($data["category"]["category_board_text"]) , [ "table" => "uni_category_board", "field" => "category_board_text" ] ) );
    }

    if(isset($data["category"]["category_board_title"])){
       $data["category"]["category_board_title"] = $this->replace( $ULang->t($data["category"]["category_board_title"] , [ "table" => "uni_category_board", "field" => "category_board_title" ] ) );
    }

    if(isset($data["category"]["category_board_h1"])){
       $data["category"]["category_board_h1"] = $this->replace( $ULang->t($data["category"]["category_board_h1"] , [ "table" => "uni_category_board", "field" => "category_board_h1" ] ) );
    }

    if(isset($data["category"]["blog_category_desc"])){
       $data["category"]["blog_category_desc"] = $this->replace( $ULang->t(urldecode($data["category"]["blog_category_desc"]) , [ "table" => "uni_blog_category", "field" => "blog_category_desc" ] ) );
    }

    if(isset($data["category"]["blog_category_text"])){
       $data["category"]["blog_category_text"] = $this->replace( $ULang->t(urldecode($data["category"]["blog_category_text"]) , [ "table" => "uni_blog_category", "field" => "blog_category_text" ] ) );
    }

    if(isset($data["ad"]["ads_price"])){

       $data["ad"]["ads_price"] = $Main->adPrefixPrice($Main->price($data["ad"]["ads_price"], $data["ad"]["ads_currency"], true),$data["ad"],false);

    }else{

       if( isset($data["ad"]["ads_price_free"]) ){
           $data["ad"]["ads_price"] = $ULang->t("Даром");
       }else{ 
           $data["ad"]["ads_price"] = $ULang->t("Цена не указана");
       }

    }

    $param_name = array(
      "{domen}",
      "{url}",
      "{country}",
      "{city}",
      "{region}",
      "{site_name}",
      "{board_main_categories}",
      "{board_category_name}",
      "{board_category_title}",
      "{board_category_h1}",
      "{geo}",
      "{geo_declination}",
      "{geo_meta_desc}",
      "{board_category_meta_desc}",
      "{board_category_text}",
      "{ad_title}",
      "{ad_text}",
      "{ad_city}",
      "{ad_region}",
      "{ad_country}",
      "{ad_price}",
      "{ad_publication}",
      "{blog_main_categories}",
      "{blog_category_name}",
      "{blog_category_title}",
      "{blog_category_h1}",
      "{blog_category_meta_desc}",
      "{blog_category_text}",      
      "{article_title}",
      "{article_meta_desc}", 
      "{shop_category_name}",
    );

    $param_val = array(
      $_SERVER["SERVER_NAME"],
      $config["urlPath"],
      isset($geolocation["country"]) ? $ULang->t($geolocation["country"] , [ "table" => "geo", "field" => "geo_name" ] ) : "",
      isset($geolocation["city"]) ? $ULang->t($geolocation["city"] , [ "table" => "geo", "field" => "geo_name" ] ) : "",
      isset($geolocation["region"]) ? $ULang->t($geolocation["region"] , [ "table" => "geo", "field" => "geo_name" ] ) : "",
      $ULang->t($settings["site_name"]),
      $CategoryBoard->allMain(),
      isset($data["category"]["category_board_name"]) ? $ULang->t($data["category"]["category_board_name"] , [ "table" => "uni_category_board", "field" => "category_board_name" ] ) : "",
      isset($data["category"]["category_board_title"]) ? $data["category"]["category_board_title"] : "",
      isset($data["category"]["category_board_h1"]) ? $data["category"]["category_board_h1"] : "",
      $geo_name,
      $geo_declination,
      isset($_SESSION["geo"]["desc"]) ? $ULang->t($_SESSION["geo"]["desc"] , [ "table" => "geo", "field" => "geo_desc" ] ) : "",
      isset($data["category"]["category_board_description"]) ? $data["category"]["category_board_description"] : "",
      isset($data["category"]["category_board_text"]) ? $data["category"]["category_board_text"] : "",
      isset($data["ad"]["ads_title"]) ? $data["ad"]["ads_title"] : "",
      isset($data["ad"]["ads_text"]) ? $data["ad"]["ads_text"] : "",
      isset($data["ad"]["city_name"]) ? $data["ad"]["city_name"] : "",
      isset($data["ad"]["region_name"]) ? $data["ad"]["region_name"] : "",
      isset($data["ad"]["country_name"]) ? $data["ad"]["country_name"] : "",
      isset($data["ad"]["ads_price"]) ? $data["ad"]["ads_price"] : "",
      isset($data["ad"]["ads_datetime_add"]) ? date("d.m.Y", strtotime($data["ad"]["ads_datetime_add"]) ) : "",
      $Blog->allMainCategory(),
      isset($data["category"]["blog_category_name"]) ? $ULang->t($data["category"]["blog_category_name"] , [ "table" => "uni_blog_category", "field" => "blog_category_name" ] ) : "",
      isset($data["category"]["blog_category_title"]) ? $ULang->t($data["category"]["blog_category_title"] , [ "table" => "uni_blog_category", "field" => "blog_category_title" ] ) : "",
      isset($data["category"]["blog_category_h1"]) ? $ULang->t($data["category"]["blog_category_h1"] , [ "table" => "uni_blog_category", "field" => "blog_category_h1" ] ) : "",
      isset($data["category"]["blog_category_desc"]) ? $data["category"]["blog_category_desc"] : "",
      isset($data["category"]["blog_category_text"]) ? $data["category"]["blog_category_text"] : "",
      isset($data["article"]["blog_articles_title"]) ? $ULang->t($data["article"]["blog_articles_title"] , [ "table" => "uni_blog_articles", "field" => "blog_articles_title" ] ) : "",
      isset($data["article"]["blog_articles_desc"]) ? $ULang->t($data["article"]["blog_articles_desc"] , [ "table" => "uni_blog_articles", "field" => "blog_articles_desc" ] ) : "",
      isset($data["current_category"]["category_board_name"]) ? $data["current_category"]["category_board_name"] : "",  
    );    

      if(isset($param["page"])){    
         
          $get = getOne("SELECT * FROM uni_seo WHERE page=? and lang_iso=?", array($param["page"], $lang_iso));

          if(count($get)){

            if(isset($get[$param["field"]])){
                if(!isset($param["text"])){
                    return str_replace($param_name, $param_val, $get[$param["field"]]);
                }else{
                    $replace = str_replace($param_name, $param_val, $get[$param["field"]]);
                    return str_replace($param_name, $param_val, $replace);
                }
            }

          }
      }else{
         if(isset($param["text"])) return str_replace($param_name, $param_val, $param["text"]);
      }


    }
   
   } 

    
 }
 
?>