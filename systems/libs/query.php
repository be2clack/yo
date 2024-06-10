<?php

  defined('unisitecms') or exit();

  require 'rb.php';

  R::setup( 'mysql:host='.$config["db"]["host"].';port='.$config["db"]["port"].';dbname='.$config["db"]["database"],$config["db"]["user"], $config["db"]["pass"] );

  if (!R::testConnection()) { exit('There is no connection to the database'); }

  R::ext('xdispense',function($table_name){
    return R::getRedBean()->dispense($table_name);
  });


  function getAll($query, $param = array()){

    $res = R::getAll( $query , $param );

    if ($res)
     {  
        return $res;
     }else{
        return array();
     }

  }

  function getOne($query, $param = array()){
    
    if(stripos($query, 'limit') === false){
       $query = $query . ' LIMIT 1';
    }

    $res = R::getRow($query, $param);

    if ($res)
     {  
        return $res;
     }else{
        return array();
     }

  }

  function getOneCache($query, $param = array()){
    
    $Cache = new Cache();

    $query = strtolower($query);

    if( preg_match('/\b(uni_ads)\b/i', $query) ){

        $explode1 = explode("from", $query);
        $explode2 = explode("where", $explode1[1]);

        if( $Cache->get( [ "table" => "uni_ads", "key" => trim($explode2[1]) ] ) ){
            return [ "total" => $Cache->get( [ "table" => "uni_ads", "key" => trim($explode2[1]) ] ) ];
        }

    }else{

       $res = R::getRow($query . ' LIMIT 1' , $param);

       if ($res)
        {  
           return $res;
        }else{
           return array();
        }
      
    }

  }

  function find($table, $query, $param = array()){

    $res = R::find( $table, $query , $param );

    if ($res)
     {  
        return $res;
     }else{
        return array();
     }

  }

  function findOne($table, $query, $param = array()){

    $res = R::findOne( $table, $query , $param );

    if ($res)
     {  
        return $res;
     }else{
        return array();
     }

  }

  function getCount($table, $query = "", $param = array()){

    $res = R::count( $table, $query , $param );

    if ($res)
     {  
        return (int)$res;
     }else{
        return 0;
     }

  }

  function insert($query, $param = array()){

    $res = R::exec( $query , $param );

    if ($res)
     {  
        return R::getInsertID();
     }else{
        return 0;
     }

  }

  function smart_insert($table, $params = []){

   foreach($params as $name => $value){
      $fields[] = $name;
      $values[] = $value;
      $interrogative[] = '?';
   }

   $query = 'INSERT INTO '.$table.'('.implode(',',$fields).')VALUES('.implode(',',$interrogative).')';

   $res = R::exec( $query , $values );

   if ($res)
    {  
       return R::getInsertID();
    }else{
       return 0;
    }

  }

  function smart_update($table, $params = [], $conditions){

   foreach($params as $name => $value){
      $fields[] = $name.'=?';
      $values[] = $value;
   }

   $query = 'UPDATE '.$table.' SET '.implode(',',$fields).' WHERE '.$conditions;

   $res = R::exec( $query , $values );

   if ($res)
    {  
       return 1;
    }else{
       return 0;
    }

  }

  function exec_query($query, $param = array()){
     $res = R::exec( $query , $param );
     if($res){
        return 1;
     }else{
        return 0;
     }
  }

  function update($query, $param = array(), $addtonode = false){

    $res = R::exec( $query , $param );

    if ($res)
     {  

        $query = strtolower($query);

        if( preg_match('/\b(update)\b/i', $query) && preg_match('/\b(uni_ads)\b/i', $query) && $addtonode ){

           $Elastic = new Elastic();
           
           $explode1 = explode("set", $query);
           $explode2 = explode("where", $explode1[1]);
           $explode3 = explode("and", trim($explode2[1]) );

           $fields_array = explode(",", $explode2[0]);

           foreach ($explode3 as $key => $value) {
              $fields_array[] = trim($value);
           }

           foreach ($fields_array as $key => $value) {
              $fields[ explode("=", trim($value) )[0] ] = $param[$key];
           }

           if($fields && $fields["ads_id"]) $Elastic->update( [ "index" => "uni_ads", "type" => "ad", "id" => $fields["ads_id"], "body" => [ "doc" => $fields ] ] );

        }

        return 1;

     }else{

        return 0;

     }

  }



?>