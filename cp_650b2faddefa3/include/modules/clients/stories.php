<?php 
if( !defined('unisitecms') ) exit;

$Profile = new Profile();
$Main = new Main();

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

$url[] = "route=stories";

if(isset($_GET['sort'])){
    if($_GET['sort'] == 1){
        $query = "clients_stories_media_status=1"; 
        $sort_name = "Активные";  
    }elseif ($_GET['sort'] == 0){                    
        $query = "clients_stories_media_status=0";  
        $sort_name = "На модерации";                          
    }
}
           
$url[] = 'sort='.$_GET['sort'];

if($query){
   $query = " where $query";
}

$LINK = "?".implode("&",$url);

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Сторисы</h2>
      </div>
   </div>
</div>

<div class="form-group" style="margin-bottom: 25px;" >
 
 <div class="btn-group" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=stories">Без сортировки</a>
      <a class="dropdown-item" href="?route=stories&sort=1">Активные</a>
      <a class="dropdown-item" href="?route=stories&sort=0">На модерации</a>
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

                    $count = getOne("SELECT count(*) as total FROM uni_clients_stories_media $query ")["total"];

                    $getStories = getAll("SELECT * FROM uni_clients_stories_media $query ORDER By clients_stories_media_timestamp DESC ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if($getStories){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Сторис</th>
                            <th>Автор</th>
                            <th>Стоимость</th>
                            <th>Опубликовано</th>
                            <th>Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($getStories AS $value){

                        $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_stories_media_user_id']]);

                        ?>

                             <tr>                                               
                                 <td>

                                    <div class="stories-media-link" >
                                        <?php
                                        if($value['clients_stories_media_type'] == 'image'){
                                            if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'])){
                                                ?>
                                                <a href="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']; ?>" target="_blank" ><span class="stories-media-type" ><i class="la la-photo"></i></span><img class="image-autofocus" src="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']; ?>"></a>
                                                <?php
                                            }
                                        }else{
                                            if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_preview'])){
                                                ?>
                                                <a href="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']; ?>" target="_blank" ><span class="stories-media-type" ><i class="la la-play"></i></span><img class="image-autofocus" src="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_preview']; ?>"></a>
                                                <?php                                            
                                            }
                                        }
                                        ?>
                                    </div>

                                 </td>  
                                 <td>
                                   <?php
                                        if($getUser){
                                            ?>
                                            <a href="?route=client_view&id=<?php echo $value["clients_stories_media_user_id"]; ?>"><?php echo $Profile->name($getUser, false); ?></a>
                                            <?php
                                        }
                                   ?>
                                 </td>     
                                 <td>
                                   <?php echo $Main->price($value["clients_stories_media_payment_amount"]); ?>
                                 </td>                                                                                                                              
                                 <td>
                                   <?php echo datetime_format_cp($value["clients_stories_media_timestamp"]); ?>
                                 </td>                                 
                                 <td class="td-actions">
                                   <div class="dropdown">

                                    <?php 

                                     if($value["clients_stories_media_status"] == 0){

                                        ?>

                                        <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          На модерации
                                        </button>

                                        <div class="dropdown-menu" >
                                          <a class="dropdown-item change-status-user-story" data-media-id="<?php echo $value["clients_stories_media_id"]; ?>" data-user-id="<?php echo $value["clients_stories_media_user_id"]; ?>" data-status="1" href="#">Опубликовать</a>
                                        </div>

                                        <?php

                                     }elseif($value["clients_stories_media_status"] == 1){

                                        ?>

                                        <div class="btn btn-success btn-sm">
                                          Активно
                                        </div>

                                        <?php

                                     }

                                     ?>

                                    </div>                               
                                 </td>
                                 <td class="td-actions" style="text-align: right;" >
                                  <a href="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']; ?>" target="_blank" ><i class="la la-eye edit"></i></a>
                                  <a href="#" class="delete-user-story" data-id="<?php echo $value["clients_stories_media_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Историй нет</p>
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
            
 <script type="text/javascript" src="include/modules/clients/script.js"></script>
     
