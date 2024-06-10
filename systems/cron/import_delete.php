<?php
defined('unisitecms') or exit();

$Elastic = new Elastic();

$getImport = findOne("uni_ads_import", "ads_import_status=? order by ads_import_id asc", [3]);

if($getImport){
	$getUsers = getAll("select * from uni_clients where clients_id_import=? limit 1000", [$getImport["ads_import_uniq"]]);
	if(count($getUsers)){
		foreach ($getUsers as $key => $user) {

			$get = getAll("SELECT ads_images,ads_id,ads_id_user FROM uni_ads WHERE ads_id_user=?", array($user["clients_id"]));

			if(count($get)){

		          foreach ($get as $key => $ad) {

		              $images = $Ads->getImages($ad["ads_images"]);

		              if(count($images) > 0){
		                 foreach ($images as $key => $value) {

		                    @unlink( $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $value);
		                    @unlink( $config["basePath"] . "/" . $config["media"]["medium_image_ads"] . "/" . $value);
		                    @unlink( $config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $value);
		                   
		                 }
		              }

		              update("delete from uni_ads where ads_id=?", array($ad["ads_id"]));
		              update("delete from uni_ads_filters_variants where ads_filters_variants_product_id=?", array($ad["ads_id"]));
		              update("delete from uni_city_area_variants where city_area_variants_id_ad=?", array($ad["ads_id"]));
		              update("delete from uni_metro_variants where ads_id=?", array($ad["ads_id"]));

		              $Elastic->delete( [ "index" => "uni_ads", "type" => "ad", "id" => $ad["ads_id"] ] );
		          }

			}

			update("delete from uni_clients where clients_id=?", array($user["clients_id"]));

		}
		$Cache->update( "uni_ads" );
	}
	$count = (int)getOne("select count(*) as total from uni_clients where clients_id_import=?", [$getImport["ads_import_uniq"]])["total"];
	if(!$count){
		update("delete from uni_ads_import where ads_import_id=?", array($getImport["ads_import_id"]));
	}
}

?>