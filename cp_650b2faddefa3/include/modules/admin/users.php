<?php 
if( !defined('unisitecms') ) exit;


$LINK = '?route=users';   
$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Пользователи админ панели</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<a  href="?route=add_user" class="btn btn-gradient-04 mr-1 mb-2">Добавить пользователя</a>
</div>

<br>

<div class="row flex-row">
 
   <?php   

   $get = getAll("SELECT * FROM uni_admin order by id desc");
   
    if (count($get))
    { 
                         
       
         foreach($get AS $value){ 
            
            ?>

               <div class="col-xl-3 col-md-4 col-sm-6 col-remove">
                  <div class="widget-image has-shadow">
                     <div class="contact-card">
                        <div class="quick-actions hover dark">
                          <div class="dropdown">
                             <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                             <i class="la la-ellipsis-h"></i>
                             </button>
                             <div class="dropdown-menu">
                                <a href="#" class="dropdown-item remove delete-admin" data-id="<?php echo $value["id"]; ?>" >
                                <i class="la la-trash "></i>Удалить
                                </a>
                             </div>
                          </div>
                        </div>                     
                        <div class="cover-image-user mx-auto" >
                           

                            <?php if( (strtotime($value["datetime_view"]) + 180) > time() && $value["datetime_view"] != "0000-00-00 00:00:00" ){?>

                            <span class="badge-pulse-admin badge-pulse-green-admin" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Сейчас online" ></span>

                            <?php }else{ ?>

                            <span class="badge-pulse-admin badge-pulse-red-admin" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Был в сети: <?php echo datetime_format_cp($value["datetime_view"]); ?>" ></span> 

                            <?php } ?>

                           <div class="mx-auto img-circle-user" >
                             <img src="<?php echo Exists($config["media"]["avatar_admin"],$value["image"],$config["media"]["no_avatar"]); ?>" width="100%" />
                           </div>
                        </div>
                        <div class="widget-body">
                           <h4 class="name"><?php echo $value["fio"]; ?></h4>
                           <div class="job"><?php echo $Admin->adminRole($value["role"]); ?></div>
                           <div class="text-center">
                              <div class="btn-group" role="group">
                                 <a href="?route=user&id=<?php echo $value["id"]; ?>" class="btn btn-gradient-01">Подробнее</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

            <?php

        }

         
    }

    ?>

</div>

        
<script type="text/javascript" src="include/modules/admin/script.js"></script>
