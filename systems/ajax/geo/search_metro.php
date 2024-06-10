<?php

$search = clear( $_POST["search"] );
$city_id = (int)$_POST["city_id"];

if($search){

	$getAll = getAll("select * from uni_metro where name like '%$search%' and parent_id!=0 and city_id='".$city_id."'");

	if(count($getAll)){
		foreach ($getAll as $key => $value) {
			$main = findOne("uni_metro", "id=?", [$value["parent_id"]]);
			?>
            <div  data-name="<?php echo $value["name"]; ?>" data-id="<?php echo $value["id"]; ?>" data-color="<?php echo $main["color"]; ?>" >
            	<strong><i style="background-color:<?php echo $main["color"]; ?>;"></i> <?php echo $value["name"]; ?></strong> <span class="span-subtitle" ><?php echo $main["name"]; ?></span>
            </div>    			
			<?php
		}
	}else{
		echo false;
	}

}else{
	echo false;
}

?>