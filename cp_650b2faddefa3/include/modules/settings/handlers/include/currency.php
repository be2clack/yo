<?php

$main = clear($_POST["main_currency"]);

if(isset($_POST["currency"])){

$currency_array = array();

 foreach($_POST["currency"] AS $array){
     foreach($array AS $id => $data){
      
      if(!empty($data["name"])){
        $currency_array[$id]["name"] = clear($data["name"]);  
      }
      if(!empty($data["sign"])){
        $currency_array[$id]["sign"] = clear($data["sign"]);  
      }
      if(!empty($data["code"])){
        $currency_array[$id]["code"] = clear($data["code"]);  
      } 

    }    
 }

 foreach($currency_array AS $id => $data){
   if(!empty($id)){
      
     if(!empty($main)){           
         if($data["code"] == $main){
            $main_update = ",main='1'";
         }else{
            $main_update = ",main='0'";
         }  
     }

     update("UPDATE uni_currency SET name='{$data["name"]}',sign='{$data["sign"]}',code='{$data["code"]}',visible='1' $main_update WHERE id = '$id'"); 
                                          
   }
 }
 
}
?>