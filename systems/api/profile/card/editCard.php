<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$errors = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getUser = $Profile->oneUser("where clients_id=?", [$idUser]);

$name = clear($_POST['name']);
$surname = clear($_POST['surname']);
$middle_name = clear($_POST['middle_name']);
$type_person = $_POST['type_person'] == 'company' ? clear($_POST['type_person']) : 'user';
$name_company = clear($_POST['name_company']);
$nicname = translite(custom_substr(clear($_POST['nicname']), 100));
$view_phone = (int)$_POST['view_phone'];
$secure_status = (int)$_POST['secure_status'];
$delivery_status = (int)$_POST['delivery_status'];
$delivery_id_point_send = clear($_POST["delivery_id_point_send"]);
$avatar = $_POST["avatar"] ? json_decode($_POST["avatar"], true) : [];

if(!$name){
	$errors[] = apiLangContent("Пожалуйста, укажите имя");
}

if($type_person == 'company'){
	if(!$name_company){
		$errors[] = apiLangContent("Пожалуйста, укажите название компании");
	}
}else{
	$name_company = "";
}

if(!$nicname){		
	$errors[] = apiLangContent("Пожалуйста, укажите короткое имя");
}else{
	if(findOne("uni_clients", "clients_id_hash=? and clients_id!=?", [$nicname,$idUser])){
	   $errors[] = apiLangContent("Указанное имя уже используется");
	}
}

if($delivery_status){
	if(!$delivery_id_point_send){
	   $errors[] = apiLangContent("Пожалуйста, укажите пункт приема");
	}else{
	   $getPoint = findOne('uni_boxberry_points', 'boxberry_points_code=?', [$delivery_id_point_send]);
	   if(!$getPoint){
	      $errors[] = apiLangContent("Пункт приема не определен");
	   }
	}
}else{
	$delivery_id_point_send = '';
}

if(!$errors){

	if($avatar){
	   $path = $config["basePath"] . "/" . $config["media"]["temp_images"];        
	   if(file_exists($path."/".$avatar[0]['name'])){
	     if(copy($path."/".$avatar[0]['name'], $config["basePath"]."/".$config["media"]["avatar"]."/".$avatar[0]['name'])){
	     	unlink($config["basePath"]."/".$config["media"]["avatar"]."/".$getUser['clients_avatar']);
			$getUser['clients_avatar'] = $avatar[0]['name'];
	     }
	   } 
	}

	smart_update('uni_clients',[
		'clients_name'=>custom_substr($name, 15),
		'clients_surname'=>custom_substr($surname, 20),
		'clients_patronymic'=>custom_substr($middle_name, 20),
		'clients_type_person'=>$type_person,
		'clients_name_company'=>custom_substr($name_company, 30),
		'clients_id_hash'=>$nicname,
		'clients_view_phone'=>$view_phone,
		'clients_avatar'=>$getUser['clients_avatar'],
		'clients_secure'=>$secure_status,
		'clients_delivery_status'=>$delivery_status,
		'clients_delivery_id_point_send'=>$delivery_id_point_send,
	], 'clients_id='.$idUser);

	echo json_encode(['status'=>true]); 

}else{
	echo json_encode(['status'=>false, 'errors'=>implode("\n", $errors)]);
}

?>