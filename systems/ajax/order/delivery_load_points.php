<?php

$idCity = clear($_POST["id_city"]);

$data = [];

if($idCity){
$get = getAll("SELECT * FROM uni_boxberry_points WHERE boxberry_points_city_code=?", [$idCity]);
}else{
$get = getAll("SELECT * FROM uni_boxberry_points");
}

if(count($get)){

 foreach($get AS $value){

      $value['boxberry_points_gps'] = explode(',', $value['boxberry_points_gps']);

      $data['gps'][$value['boxberry_points_id']] = [$value['boxberry_points_gps'][0],$value['boxberry_points_gps'][1]];

      $data['last_gps'] = $value['boxberry_points_gps'];

      if($settings['map_vendor'] == 'yandex'){

        $data['data'][$value['boxberry_points_id']]['balloonContentHeader'] = $value['boxberry_points_address'];
        $data['data'][$value['boxberry_points_id']]['hintContent'] = $value['boxberry_points_address'];
        $data['data'][$value['boxberry_points_id']]['balloonContentBody'] = '<div class="ballon-point"><div>'.$value['boxberry_points_address'].'<br>'.$value['boxberry_points_workshedule'].'<br>'.$value['boxberry_points_phone'].'</div><div class="btn-custom-mini btn-color-blue mt15 delivery-change-point" data-id-point="'.$value['boxberry_points_code'].'" >'.$ULang->t("Выбрать").'</div></div>';

      }elseif($settings['map_vendor'] == 'google'){
        
        $data["data"][$value['boxberry_points_id']]['gps'] = $value['boxberry_points_gps'];
        $data["data"][$value['boxberry_points_id']]['title'] = $value['boxberry_points_address'];
        $data["data"][$value['boxberry_points_id']]['content'] = '<div class="ballon-point"><div>'.$value['boxberry_points_address'].'<br>'.$value['boxberry_points_workshedule'].'<br>'.$value['boxberry_points_phone'].'</div><div class="btn-custom-mini btn-color-blue mt15 delivery-change-point" data-id-point="'.$value['boxberry_points_code'].'" >'.$ULang->t("Выбрать").'</div></div>';

      }elseif($settings['map_vendor'] == 'openstreetmap'){

        $data["data"][$value['boxberry_points_id']]['gps'] = $value['boxberry_points_gps'];
        $data["data"][$value['boxberry_points_id']]['id'] = $value['boxberry_points_id'];
        $data["data"][$value['boxberry_points_id']]['content'] = '<div class="ballon-point"><div>'.$value['boxberry_points_address'].'<br>'.$value['boxberry_points_workshedule'].'<br>'.$value['boxberry_points_phone'].'</div><div class="btn-custom-mini btn-color-blue mt15 delivery-change-point" data-id-point="'.$value['boxberry_points_code'].'" >'.$ULang->t("Выбрать").'</div></div>';

      }

 }   

}

echo json_encode($data);

?>