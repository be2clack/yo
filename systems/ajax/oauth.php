<?php

session_start();
define('unisitecms', true);

$config = require "./../../config.php";
include_once( $config["basePath"] . "/systems/unisite.php" );

$social_auth_params = json_decode(decrypt($settings["social_auth_params"]), true);
$user_data = [];

if($_GET["network"] == "vk"){

	if (!empty($_GET['code'])) {

		$params = array(
			'client_id'     => $social_auth_params["vk"]["id_client"],
			'client_secret' => $social_auth_params["vk"]["key"],
			'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=vk",
			'code'          => $_GET['code']
		);
		
		$data = file_get_contents('https://oauth.vk.com/access_token?' . urldecode(http_build_query($params)));
		$data = json_decode($data, true);
		if (!empty($data['access_token'])) {

			$params = array(
				'v'            => '5.131',
				'uids'         => $data['user_id'],
				'access_token' => $data['access_token'],
				'fields'       => 'uid,first_name,last_name,screen_name,photo_big',
			);
	 
			$get = file_get_contents('https://api.vk.com/method/users.get?' . urldecode(http_build_query($params)));
			$result = json_decode($get, true);	

			$info = $result["response"][0];

			$user_data["email"] = $data['email'];
			$user_data["name"] = $info['first_name'];
			$user_data["surname"] = $info['last_name'];
			$user_data["link"] = "https://vk.com/" . $info["screen_name"];
			$user_data["photo"] = $info['photo_big'];

		}else{
			header("Location: " . _link("auth") );
		}

	}

}elseif($_GET["network"] == "google"){

	if (!empty($_GET['code'])) {

		$params = array(
			'client_id'     => $social_auth_params["google"]["id_client"],
			'client_secret' => $social_auth_params["google"]["key"],
			'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=google",
			'grant_type'    => 'authorization_code',
			'code'          => $_GET['code']
		);	
				
		$ch = curl_init('https://accounts.google.com/o/oauth2/token');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$data = curl_exec($ch);
		curl_close($ch);	
	 
		$data = json_decode($data, true);
		if (!empty($data['access_token'])) {
			$params = array(
				'access_token' => $data['access_token'],
				'id_token'     => $data['id_token'],
				'token_type'   => 'Bearer',
				'expires_in'   => 3599
			);
	 
			$info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
			$info = json_decode($info, true);

			$user_data["email"] = $info['email'];
			$user_data["name"] = $info['given_name'];
			$user_data["surname"] = $info['family_name'];
			$user_data["photo"] = $info['picture'];

		}else{
			header("Location: " . _link("auth") );
		}
	}

}elseif($_GET["network"] == "fb"){


	if (!empty($_GET['code'])) {

		$params = array(
			'client_id'     => $social_auth_params["fb"]["id_app"],
			'client_secret' => $social_auth_params["fb"]["key"],
			'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=fb",
			'code'          => $_GET['code']
		);
		
		$data = file_get_contents('https://graph.facebook.com/oauth/access_token?' . urldecode(http_build_query($params)));
		$data = json_decode($data, true);
	 
		if (!empty($data['access_token'])) {
			$params = array(
				'access_token' => $data['access_token'],
				'fields'       => 'id,email,first_name,last_name,picture,link'
			);
	 
			$info = file_get_contents('https://graph.facebook.com/me?' . urldecode(http_build_query($params)));
			$info = json_decode($info, true);

			$user_data["email"] = $info['email'];
			$user_data["name"] = $info['first_name'];
			$user_data["surname"] = $info['last_name'];
			$user_data["link"] = $info["link"];
			$user_data["photo"] = $info['picture']['data']['url'];

		}else{
			header("Location: " . _link("auth") );
		}

	}


}elseif($_GET["network"] == "yandex"){

	if (!empty($_GET['code'])) {

		$params = array(
			'grant_type'    => 'authorization_code',
			'code'          => $_GET['code'],
			'client_id'     => $social_auth_params["yandex"]["id_app"],
			'client_secret' => $social_auth_params["yandex"]["key"],
		);
		
		$ch = curl_init('https://oauth.yandex.ru/token');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$data = curl_exec($ch);
		curl_close($ch);	
				 
		$data = json_decode($data, true);
		if (!empty($data['access_token'])) {
			$ch = curl_init('https://login.yandex.ru/info');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('format' => 'json')); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $data['access_token']));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$info = curl_exec($ch);
			curl_close($ch);
	 
			$info = json_decode($info, true);

			$user_data["email"] = $info['default_email'];
			$user_data["name"] = $info['first_name'];
			$user_data["surname"] = $info['last_name'];
			$user_data["photo"] =  !$info['is_avatar_empty'] ? 'https://avatars.yandex.net/get-yapic/'.$info['default_avatar_id'].'/islands-retina-50' : '';

		}else{
			header("Location: " . _link("auth") );
		}
	}

}

if($user_data){
	if($user_data["email"]){

	   if( !file_get_contents_curl($user_data["photo"]) ){
           $user_data["photo"] = "";
	   }else{
	       file_put_contents($config["basePath"] . "/" . $config["media"]["avatar"] . "/" . md5($user_data["photo"]) . ".jpg", file_get_contents_curl(urldecode($user_data["photo"])) );
	       $user_data["photo"] = md5($user_data["photo"]) . ".jpg";
	   }

	   $result = $Profile->auth_reg(array("method"=>2,"network"=>true,"email"=>$user_data["email"],"name"=>$user_data["name"],"surname"=>$user_data["surname"], "activation" => 1, "social_link" => $user_data["link"], "avatar" => $user_data["photo"]));

	   if($result["status"] == true){
	      header("Location: " . _link("user/" . $result["data"]["clients_id_hash"] ) );
	   }else{
	      if($result["status_user"]){
		   $content = '
		       <h4 class="mt50" ><strong>'.$ULang->t('Ваш аккаунт заблокирован или удален').'</strong></h4> 
		       <p>'.$ULang->t('Если вы не согласны с нашим решением — напишите в службу поддержки').'</p> 
		       <br>
		       <a class="btn-custom btn-color-blue" style="display: inline-block;" href="'._link("feedback").'">'.$ULang->t('Написать в поддержку').'</a>
		   ';
	        include $config["template_path"] . "/oauth.tpl";
	      }
	   }

	}else{
	   $content = '
	       <h4 class="mt50" ><strong>'.$ULang->t('Для регистрации требуется e-mail адрес!').'</strong></h4> 
	       <p>'.$ULang->t('Для авторизации/регистрации на нашем сайте перейдите на страницу входа и укажите данные').'</p> 
	       <br>
	       <a class="btn-custom btn-color-blue" style="display: inline-block;" href="'._link("auth").'">'.$ULang->t('Перейти на страницу входа').'</a>
	   ';
	   include $config["template_path"] . "/oauth.tpl";
	}
}else{
	header("Location: " . _link("auth") );
}
?>