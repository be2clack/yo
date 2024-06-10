<?php

class ULang{

    public function __construct( $execute = true ) {
        global $config, $settings;

        $dir = $config["basePath"]."/lang";
        if(!is_dir($dir)){ @mkdir($dir, $config["create_mode"] ); }

        if($execute == true){
          $_SESSION["ULang"]["lang_data"] = $this->loadFile();
        }

    }

    public function loadFile( $table = "", $lang = "" ){
       global $config;

       if(!$lang) $lang = getLang();

       $file = $table ? $table : "main";

       if( file_exists( $config["basePath"] . "/lang/{$lang}/{$file}.php" ) ){
          return require $config["basePath"] . "/lang/{$lang}/{$file}.php";
       }else{
          return [];
       }

    }

    public function t( $string = "", $param = [] ){
       global $settings;
       
       if( $param ){
           $data = $this->loadFile( $param["table"] );
           $key = md5( $param["field"] . "_" . $string );
       }else{
           $data = $_SESSION["ULang"]["lang_data"];
           $key = md5( $string );
       }

       if( isset($data[$key]) ){
          return stripcslashes($data[$key]);
       }else{
          if(!$param) $_SESSION["ULang"]["in_data"][md5($string)] = $string;
       }

       return stripcslashes($string);
    }

    public function tApp( $string = "", $param = [], $iso = "" ){
       global $settings,$config;

       if(!$iso) $iso = $_GET["lang_iso"] ? $_GET["lang_iso"] : $_POST["lang_iso"];

       if(!file_exists($config["basePath"] . "/lang/{$iso}/{$param["table"]}.php")){
          return $string;
       }
       
       $data = require $config["basePath"] . "/lang/{$iso}/{$param["table"]}.php";
       $key = md5( $param["field"] . "_" . $string );

       if( isset($data[$key]) ){
          return stripcslashes($data[$key]);
       }

       return stripcslashes($string);
    }

    public function edit( $in_data = [], $lang = "", $table = "" ){
       global $config;

       $line = [];

       if($lang){
          
           foreach ($in_data as $key => $value) {
             if($value) $line[] = '"'.$key.'" => "'.addslashes($value).'"';
           }
       
           $forming_s = '<?php return ['.preg_replace('~\\\+~', '\\1\\', implode(",", $line)).']; ?>';

           if( file_put_contents( $config["basePath"] . "/lang/{$lang}/main.php" , $forming_s) ){
              return true;
           }else{
              return false;
           }

       }

    }

    public function editApp( $in_data = [], $lang = "", $content_type = ""  ){
       global $config;

       $line = [];
       $data = [];

       if($lang){

           foreach ($in_data as $key => $value) {
             if($value) $line[] = '"'.$key.'" => "'.addslashes($value).'"';
           }
       
           $forming_s = '<?php return ['.preg_replace('~\\\+~', '\\1\\', implode(",", $line)).']; ?>';

           if( file_put_contents( $config["basePath"] . "/lang/{$lang}/app.php" , $forming_s) ){
              return true;
           }else{
              return false;
           }

       }

    }

    public function search($query=""){
        global $settings;

        if(!$settings["visible_lang_site"]) return [];
        if($settings["lang_site_default"] == $_SESSION["langSite"]["iso"]) return [];

        $foundCities = [];
        $matchedCities = [];

        $query = mb_strtolower($query, "UTF-8");

        $geo = $this->loadFile('geo');

        if(empty($geo)) return [];

        foreach($geo as $md5 => $city){ 
            $city = mb_strtolower($city, "UTF-8");
            if (str_contains($city,$query)){
                $foundCities[] = $md5;
            }
        }

        if(empty($foundCities)){
            return [];
        }

        $allCities = getAll("SELECT * FROM uni_city 
                         INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id 
                         INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id 
                         WHERE `uni_country`.country_status = '1'  
                         ORDER BY city_name ASC");

        foreach($allCities as $city){
            if(in_array(md5('geo_name_'.$city["city_name"]), $foundCities) || in_array(md5('geo_name_'.$city["region_name"]), $foundCities) || in_array(md5('geo_name_'.$city["country_name"]), $foundCities)){
                $matchedCities[] = $city;
            }
        }

        return $matchedCities;
    }

    public function __destruct() {
       global $config, $settings;
       
       $line = [];
       $lang = getLang();

       if(isset($_SESSION["ULang"]["in_data"])){

          $dir = $config["basePath"]."/lang/{$lang}";
          if(!is_dir($dir)){
             @mkdir($dir, $config["create_mode"] );
          }

          $data = $this->loadFile();
          
          if($data){
            $result = array_merge($data,$_SESSION["ULang"]["in_data"]);
          }else{
            $result = $_SESSION["ULang"]["in_data"];
          }
          
          if($result){
            foreach ($result as $key => $value) {
               if($value) $line[] = '"'.$key.'" => "'.addslashes($value).'"';
            }
          }
       
          $forming_s = '<?php return ['.implode(",", $line).']; ?>';

          file_put_contents( $config["basePath"] . "/lang/{$lang}/main.php" , $forming_s);

       }

       unset($_SESSION["ULang"]["in_data"]);

    }

}

?>