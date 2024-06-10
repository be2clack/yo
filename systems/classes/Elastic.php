<?php 
use Elasticsearch\ClientBuilder;

class Elastic{

	public $client;

	function __construct(){
    global $config;

    if(!$config["elasticsearch"]["status"]){ return false; }

		$this->$client = ClientBuilder::create()->build();

	}

  function prepareFields( $fields = [] ){

    $prepare = [];

        if( isset($fields) ){
          foreach ($fields as $key => $divide) {
            if( is_array($divide) ){

              foreach ($divide as $name => $value) {
                
                if( $name == "ads_price" ){
                  $prepare[$name] = round($value,2);
                }elseif( $name == "ads_period_publication" ){
                  $prepare[$name] = date("Y-m-d", strtotime($value) ) . "T" . date("H:i:s", strtotime($value) );
                }elseif( $name == "ads_datetime_add" ){
                  $prepare[$name] = date("Y-m-d", strtotime($value) ) . "T" . date("H:i:s", strtotime($value) );
                }elseif( $name == "ads_datetime_view" ){
                  $prepare[$name] = date("Y-m-d", strtotime($value) ) . "T" . date("H:i:s", strtotime($value) );
                }else{
                  $prepare[$name] = $value;
                }

              }

            }else{

                if( $key == "ads_price" ){
                  $prepare[$key] = round($divide,2);
                }elseif( $key == "ads_period_publication" ){
                  $prepare[$key] = date("Y-m-d", strtotime($divide) ) . "T" . date("H:i:s", strtotime($divide) );
                }elseif( $key == "ads_datetime_add" ){
                  $prepare[$key] = date("Y-m-d", strtotime($divide) ) . "T" . date("H:i:s", strtotime($divide) );
                }elseif( $key == "ads_datetime_view" ){
                  $prepare[$key] = date("Y-m-d", strtotime($divide) ) . "T" . date("H:i:s", strtotime($divide) );
                }else{
                  $prepare[$key] = $divide;
                }

            }
          }
        }

        return $prepare;

  }

	function array_map( $results = [] ){
       
       if(!isset($results)) return [];

       $results = array_map(function($item) {
           return $item['_source'];
       }, $results);

       return $results;

	}

	function paramAdSearch( $query = "", $id_user = 0, $id_cat = 0 ){

    $CategoryBoard = new CategoryBoard();

    if( $id_user ){

        $term = '{ "term":  { "ads_id_user": "'.$id_user.'" }},';
        
    }else{

         if($_SESSION["geo"]["data"]){

            if($_SESSION["geo"]["data"]["city_id"]){

              $term = '{ "term":  { "ads_city_id": "'.$_SESSION["geo"]["data"]["city_id"].'" }},';

            }elseif($_SESSION["geo"]["data"]["region_id"]){

              $term = '{ "term":  { "ads_region_id": "'.$_SESSION["geo"]["data"]["region_id"].'" }},';
              
            }elseif($_SESSION["geo"]["data"]["country_id"]){

              $term = '{ "term":  { "ads_country_id": "'.$_SESSION["geo"]["data"]["country_id"].'" }},';
              
            }

         }

         if( $id_cat ){
            $ids_cat = idsBuildJoin( $CategoryBoard->idsBuild($id_cat, $CategoryBoard->getCategories("where category_board_visible=1")), $id_cat );
            $term .= '{ "terms":  { "ads_id_cat": ['.$ids_cat.'] }},';
         }         

    }

		$json = '
        {
          "query": {
            "bool": {  
              "must" : {
                "query_string" : {
                  "query":      "*'.$query.'*",
                  "fields":     [ "ads_title", "city_name", "category_board_name", "ads_address", "ads_filter_tags" ],
                  "analyze_wildcard": "true",
                  "allow_leading_wildcard": "true"
                }
              },
              "filter": [ 
                { "term":  { "ads_status": "1" }},
                '.$term.'
                { "terms":  { "clients_status": [0,1] }},
                { "range": { "ads_period_publication": { "gte": "now" }}}
              ]
            }
          }
        }
		';

		return json_decode($json, true);

	}

  function paramAdquery(){

    $json = '
        {
          "query": {
            "bool": {  
              "filter": [ 
                { "term":  { "ads_status": "1" }},
                { "terms":  { "clients_status": [0,1] }},
                { "range": { "ads_period_publication": { "gte": "now" }}}
              ]
            }
          }
        }
    ';

    return json_decode($json, true);

  }

	function navigationOffset( $page = 1, $out = 10 ){
        if($page > 1) return $out * $page; else return 0;
	}

  function index( $params = [] ){
    global $config;

    if(!$config["elasticsearch"]["status"]){ return false; }
        
        if( $params["body"]["ads_period_publication"] ){
            $params["body"]["ads_period_publication"] = date("Y-m-d", strtotime($params["body"]["ads_period_publication"]) ) . "T" . date("H:i:s", strtotime($params["body"]["ads_period_publication"]) );
        }

        if( $params["body"]["ads_datetime_add"] ){
            $params["body"]["ads_datetime_add"] = date("Y-m-d", strtotime($params["body"]["ads_datetime_add"]) ) . "T" . date("H:i:s", strtotime($params["body"]["ads_datetime_add"]) );
        }

        if( $params["body"]["ads_datetime_view"] ){
            $params["body"]["ads_datetime_view"] = date("Y-m-d", strtotime($params["body"]["ads_datetime_view"]) ) . "T" . date("H:i:s", strtotime($params["body"]["ads_datetime_view"]) );
        }

	    	try{

	    		return $this->$client->index($params);

	    	} catch (Exception $e) {

	    		return [];

	    	}

    }

    function update( $params = [] ){
      global $config;

      if(!$config["elasticsearch"]["status"]){ return false; }
        
        if($params["body"]){

        if( $params["body"]["doc"]["ads_period_publication"] ){
            $params["body"]["doc"]["ads_period_publication"] = date("Y-m-d", strtotime($params["body"]["doc"]["ads_period_publication"]) ) . "T" . date("H:i:s", strtotime($params["body"]["doc"]["ads_period_publication"]) );
        }

        if( $params["body"]["doc"]["ads_datetime_add"] ){
            $params["body"]["doc"]["ads_datetime_add"] = date("Y-m-d", strtotime($params["body"]["doc"]["ads_datetime_add"]) ) . "T" . date("H:i:s", strtotime($params["body"]["doc"]["ads_datetime_add"]) );
        }

        if( $params["body"]["doc"]["ads_datetime_view"] ){
            $params["body"]["doc"]["ads_datetime_view"] = date("Y-m-d", strtotime($params["body"]["doc"]["ads_datetime_view"]) ) . "T" . date("H:i:s", strtotime($params["body"]["doc"]["ads_datetime_view"]) );
        }

	    	try{

	    		return $this->$client->update($params);

	    	} catch (Exception $e) {

	    		return [];

	    	}

        }

    }

    function delete( $params = [] ){
      global $config;

      if(!$config["elasticsearch"]["status"]){ return false; }
        
	    	try{

	    		return $this->$client->delete($params);

	    	} catch (Exception $e) {

	    		return [];

	    	}

    }

    function getById( $params = [] ){
      global $config;

      if(!$config["elasticsearch"]["status"]){ return false; }

    	if( $params["id"] ){

	    	try{

	    		return $this->$client->get($params);

	    	} catch (Exception $e) {

	    		return [];

	    	}            
			
    	}

    }

    function search( $params = [] ){
      global $config;

      if(!$config["elasticsearch"]["status"]){ return false; }
      
    	try{

    		return $this->$client->search($params);

    	} catch (Exception $e) {

    		return [];

    	}

    }


}


?>