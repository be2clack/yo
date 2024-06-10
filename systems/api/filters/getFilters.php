<?php

$filters = $_GET["filters"] ? apiStructureFilters(json_decode($_GET["filters"], true)) : [];
$idCat = (int)$_GET["id_cat"];

$results = [];

$filters_ids = $Filters->getCategory(["id_cat"=>$idCat]);

if($filters_ids){
  $query = "ads_filters_visible='1' and ads_filters_id IN(".implode(",", $filters_ids).")";
}else{
  $query = "ads_filters_visible='1' and ads_filters_id IN(0)";
}

$getCatFilters = $Filters->getFilters("where $query");
$getAllFilters = $Filters->getFilters("where ads_filters_visible='1'");

if($getCatFilters["id_parent"][0]){

  foreach ($getCatFilters["id_parent"][0] as $id_filter => $value) {
     
     $items = [];
     $ids = [];
     
     $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_sort asc", [$id_filter]);

     if($getItems){

       	foreach ($getItems as $item) {
       		$items[] = ['name'=>html_entity_decode($item['ads_filters_items_value']), 'id'=>$item['ads_filters_items_id'], 'podfilter'=>findOne('uni_ads_filters_items', 'ads_filters_items_id_item_parent=?', [$item['ads_filters_items_id']]) ? true : false];
       	}

     }

     $ids_podfilter = $Filters->idsBuild($value["ads_filters_id"],$getAllFilters);
     if($ids_podfilter){
        foreach (explode(',', $ids_podfilter) as $id) {
          $ids[] = $id;
        }
     }

  	$results[] = [
  		'id' => $id_filter,
  		'view' => $value["ads_filters_type"],
  		'name' => $value["ads_filters_name"],
      'ids_podfilter'=> $ids ?: null,
  		'items' => $items,
      'required' => $value['ads_filters_required'] ? true : false,
  		'podfilter' => $getAllFilters['id_parent'][$value['ads_filters_id']] ? true : false,
  	];

  	if(isset($filters[$id_filter])){ 
  		$parentFilter = apiPodfilters($id_filter, $filters, $getAllFilters);
  		if($parentFilter){ 
  			$results = array_merge($results, $parentFilter);
  		}
  	}

     
  }

}

echo json_encode($results);

?>