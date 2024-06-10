<?php 
if( !defined('unisitecms') ) exit;

$url[] = "route=reviews";

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

if(isset($_GET['sort'])){
    
if($_GET['sort'] == 0){                    
    $query = "WHERE clients_reviews_status='0'";  
    $sort_name = "На модерации";                          
}elseif($_GET['sort'] == 1){                    
    $query = "WHERE clients_reviews_status='1'";  
    $sort_name = "Активные";                          
}
           
$url[] = 'sort='.$_GET['sort'];

}

$Ads = new Ads();
$Main = new Main();

$url[] = 'sort='.$_GET['sort'];

if($_GET["search"]){

   $_GET["search"] = clearSearch($_GET["search"]);
   $query = "WHERE (ads_title LIKE '%".$_GET["search"]."%' OR ads_id LIKE '%".$_GET["search"]."%')"; 
   $url[] = 'search='.$_GET["search"]; 

}

$LINK = "?".implode("&",$url);
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Отзывы</h2>
      </div>
   </div>
</div>  

<div class="row" >
  <div class="col-lg-12" >
    
      <form method="get" action="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" >
        <input type="text" class="form-control" placeholder="Укажите название или id объявления" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
        <input type="hidden" name="route" value="reviews" >
      </form>

  </div>
</div>

<div class="form-group"  style="margin-bottom: 25px; margin-top: 25px;" >

 <div class="btn-group mb5" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=reviews">Без сортировки</a>
      <a class="dropdown-item" href="?route=reviews&sort=0">На модерации</a>
      <a class="dropdown-item" href="?route=reviews&sort=1">Активные</a>
    </div>
   </div>
 </div>

</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $count = getOne("SELECT count(*) as total FROM uni_clients_reviews $query")["total"];

                    $get = getAll("SELECT * FROM uni_clients_reviews INNER JOIN `uni_ads` ON `uni_ads`.ads_id = `uni_clients_reviews`.clients_reviews_id_ad $query order by clients_reviews_id desc ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Отзыв</th>
                            <th>Автор</th>
                            <th>Дата</th>
                            <th>Статус</th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $value){

                           
                              $getAd = $Ads->get("ads_id=? ", array($value["clients_reviews_id_ad"]));
                              $getFromUser = findOne("uni_clients", "clients_id=?", array($value["clients_reviews_from_id_user"]));
                              $getToUser = findOne("uni_clients", "clients_id=?", array($value["clients_reviews_id_user"]));

                              ?>
                              <tr>
                                  <td style="max-width: 350px; min-width: 250px;" >
                                    Отзыв на объявление <i class="la la-arrow-right"></i> <a target="_blank"  href="<?php echo $Ads->alias($getAd); ?>"><?php echo $getAd["ads_title"]; ?></a> <i class="la la-user"></i> <a href="?route=client_view&id=<?php echo $value["ads_complain_to_user_id"]; ?>"><?php echo $getToUser["clients_name"]; ?></a>
                                    <div class="complaints-text" >

                                        <?php
                                          if($value["clients_reviews_files"]){
                                              $images = explode(",", $value["clients_reviews_files"]);
                                              ?>
                                              <div class="review-attach-image-box" >
                                              <?php
                                              foreach ($images as $image) {
                                                 if(file_exists($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $image)){
                                                    ?>
                                                    <a class="review-attach-image-item"  href="<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $image; ?>" target="_blank" ><img class="image-autofocus" src="<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $image; ?>"></a>
                                                    <?php
                                                 }
                                              }
                                              ?>
                                              </div>
                                              <?php
                                          }
                                        ?>

                                        <?php echo $value["clients_reviews_text"]; ?>
                                    </div>
                                  </td>                                     
                                  <td><a href="?route=client_view&id=<?php echo $getFromUser["clients_id"]; ?>"><?php echo $getFromUser["clients_name"]; ?></a></td>
                                  <td><?php echo datetime_format_cp($value["clients_reviews_date"]); ?></td>
                                  <td>
                                      
                                     <?php
                                       if($value["clients_reviews_status"] == 0){
                                            ?>
                                             <div class="dropdown">

                                             <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               На модерации
                                             </button>                                 
                                             
                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                               <a class="dropdown-item publication-review" data-id="<?php echo $value["clients_reviews_id"]; ?>" href="#">Опубликовать</a>
                                               <a class="dropdown-item delete-review" data-id="<?php echo $value["clients_reviews_id"]; ?>" href="#">Удалить</a>
                                             </div>

                                             </div>
                                            <?php
                                       }elseif($value["clients_reviews_status"] == 1){
                                            ?>
                                             <div class="dropdown">

                                             <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               Опубликован
                                             </button>                                 
                                             
                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                               <a class="dropdown-item delete-review" data-id="<?php echo $value["clients_reviews_id"]; ?>" href="#">Удалить</a>
                                             </div>

                                             </div>
                                            <?php
                                       }
                                     ?>


                                  </td>                          
                              </tr>
                              <?php                           

                                        
                        } 

                        ?>

                           </tbody>
                        </table>

                        <?php               
                     }else{
                         
                         ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Отзывов нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>

<ul class="pagination">  
 <?php echo out_navigation( array("count"=>$count, "output"=>$_SESSION["ByShow"], "url"=>$LINK, "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
</ul>

<script type="text/javascript" src="include/modules/reviews/script.js"></script>

