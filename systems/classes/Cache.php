<?php
/**
 * UniSite CMS
 *
 * @copyright   2018 Artur Zhur
 * @link    https://unisitecms.ru
 * @author    Artur Zhur
 *
 */
 
class Cache{

     function __construct() {
        global $config;

        if(!file_exists($config["basePath"]."/temp/cache")){
            @mkdir($config["basePath"]."/temp/cache", $config["create_mode"]);
        }
        
     }

    function set( $params = [] ){
        global $settings,$config;

        $dir = $config["basePath"]."/temp/cache";
        $name_folder = md5($params["table"]);

        if( !is_dir( $dir . "/" . $name_folder ) ){
             @mkdir( $dir . "/" . $name_folder , $config["create_mode"] );
        }
          
        $key = md5( md5( $params["table"] . $params["key"] ) . $config["private_hash"] ) . ".temp";

        $content["time"] = time() + 3600;
        $content["data"] = $params["data"];

        if( file_put_contents( $dir . "/" . $name_folder . "/" . $key, "<?php return ".var_export($content, true)." ?>" ) ){
            return true;
        }else{
            return false;
        }
  
    }  

    function get( $params = [] ){
        global $settings,$config;

        $dir = $config["basePath"]."/temp/cache";
        $name_folder = md5($params["table"]);

        $key = md5( md5( $params["table"] . $params["key"] ) . $config["private_hash"] ) . ".temp";
        
          if(file_exists( $dir . "/" . $name_folder . "/" . $key )){

              $content = require $dir . "/" . $name_folder . "/" . $key;

              if(time() < $content["time"]){
                  return $content["data"];
              }

              unlink( $dir . "/" . $name_folder . "/" . $key );
              return false;

          }else{
              return false;
          }

    }
 
    function reset(){
      global $config;

      deleteFolder( $config["basePath"]."/temp/cache" );
      deleteFolder( $config["template_path"]."/temp" );

    }

    function update( $table = "", $key = "" ){
      global $config;

      if($table){
        if($key){
            unlink( $config["basePath"]."/temp/cache/" . md5($table) . "/" . md5( md5( $table . $key ) . $config["private_hash"] ) . ".temp" );
        }else{
            deleteFolder( $config["basePath"]."/temp/cache/" . md5($table) );
        }
      }

    }

    function setTpl( $params = [] ){
        global $settings,$config;

        $dir = $config["basePath"]."/temp/cache/tpl";

        $key = md5(md5($params["tpl"]).$config["private_hash"]).".temp";

        if(file_put_contents($dir."/".$key, $params['data'])){
            return true;
        }else{
            return false;
        }
    }  

    function getTpl( $params = [] ){
        global $settings,$config;

        $dir = $config["basePath"]."/temp/cache/tpl";

        $key = md5(md5($params["tpl"]).$config["private_hash"]).".temp";
        
        if(file_exists($dir."/".$key)){
           return file_get_contents($dir."/".$key);
        }else{
           return false;
        }
    }

}



?>