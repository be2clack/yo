<?php

$id = (int)$_POST["id"];

$get_metro = getOne("SELECT count(*) as total FROM uni_metro WHERE city_id = '$id'")["total"];
$get_area = getAll("SELECT * FROM uni_city_area WHERE city_area_id_city = '$id' order by city_area_name asc");

if($get_area){

	  foreach ($get_area as $key => $value) {
	  	
     $items .= '<label> <input type="radio" name="area[]" value="'.$value["city_area_id"].'" > <span>'.$ULang->t( $value["city_area_name"], [ "table" => "uni_city_area", "field" => "city_area_name" ] ).'</span> <i class="la la-check"></i> </label>';

	  }

	  $data .= '
    <div class="ads-create-main-data-box-item" >      
    <p class="ads-create-subtitle" >'.$ULang->t("Район").'</p> 

   	     <div class="ads-create-main-data-city-options-area" >
            <div class="uni-select" data-status="0" >

                 <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                 <div class="uni-select-list" >
                     '.$items.'
                 </div>
            
            </div>
         </div>

    </div>
	  ';
}

if($get_metro){

	  $data .= '
    <div class="ads-create-main-data-box-item" >      
    <p class="ads-create-subtitle" >'.$ULang->t("Ближайшее метро").'</p>

   	     <div class="ads-create-main-data-city-options-metro" >
            <div class="container-custom-search" >
              <input type="text" class="ads-create-input action-input-search-metro" placeholder="'.$ULang->t("Начните вводить станции, а потом выберите ее из списка").'" >
              <div class="custom-results SearchMetroResults" ></div>
            </div>

            <div class="ads-container-metro-station" ></div>
         </div>

    </div>
	  ';

}

echo $data;

?>