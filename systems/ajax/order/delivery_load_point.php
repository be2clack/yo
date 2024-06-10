<?php

$idPoint = clear($_POST["id_point"]);

$get = findOne("uni_boxberry_points", "boxberry_points_code=?", [$idPoint]);

if($get){
  
    //$info = json_decode(file_get_contents('http://api.boxberry.ru/json.php?token='.decrypt($settings['delivery_api_key']).'&method=PointsDescription&code='.$idPoint.'&photo=1'), true);
    if($info['photoLinks'][0]){
      echo '
      <div class="mb10 delivery-point-box-flex">
        <div class="delivery-point-box-flex1" >
        <a href="'.$info['photoLinks'][0].'" target="_blank" >
        <img src="'.$info['photoLinks'][0].'" height="64" />
        </a>
        </div>
        <div class="delivery-point-box-flex2" >'.$get['boxberry_points_address'].'<br>'.$get['boxberry_points_workshedule'].'<br>'.$get['boxberry_points_phone'].'</div>
      </div>';
    }else{
      echo '<div class="mb10"><div>'.$get['boxberry_points_address'].'<br>'.$get['boxberry_points_workshedule'].'<br>'.$get['boxberry_points_phone'].'</div></div>';
    }

}

?>