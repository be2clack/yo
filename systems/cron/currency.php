<?php
defined('unisitecms') or exit();


	$file = simplexml_load_file("https://www.cbr.ru/scripts/XML_daily.asp?date_req=".date("d/m/Y"));
	foreach ($file AS $el){
	    $data["RUB"][strval($el->CharCode)] = [ "val" => strval($el->Value), "nominal" => strval($el->Nominal), "code" => strval($el->CharCode) ];
	}

	$file = [];

	
	$file = simplexml_load_file("https://www.nationalbank.kz/rss/rates_all.xml");
    foreach ($file->channel->item as $item){
        $data["KZT"][strval($item->title)] = [ "val" => strval($item->description), "nominal" => strval($item->quant), "code" => strval($item->title) ];
    }

    $file = [];

	
	$file = simplexml_load_file("https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange");
	foreach ($file->currency as $item){
	    $data["UAH"][strval($item->cc)] = [ "val" => strval($item->rate), "code" => strval($item->cc) ];
	}

	$file = [];

	
	$file = simplexml_load_file("http://www.nbrb.by/services/xmlexrates.aspx");
	foreach ($file->Currency as $key => $item){
	    $data["BYN"][strval($item->CharCode)] = [ "val" => strval($item->Rate), "nominal" => strval($item->Scale), "code" => strval($item->CharCode) ];
	}

	$file = [];

	
	$file=file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");

	foreach($file as $line){
	  if(preg_match("/currency='([[:alpha:]]+)'/",$line,$currency)){
	    if(preg_match("/rate='([[:graph:]]+)'/", $line,$rate)){
	    	$data["EUR"][strval($currency[1])] = [ "val" => strval($rate[1]), "code" => strval($currency[1]) ];
	    }
	  }
	}



if($data){
	update("update uni_settings set value=? where name=?", [json_encode($data), "currency_json"]);
}else{
	update("update uni_settings set value=? where name=?", ["", "currency_json"]);
}

?>