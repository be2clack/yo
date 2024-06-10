<?php 
if( !defined('unisitecms') ) exit;

$Geo = new Geo();
$Profile = new Profile();
$Main = new Main();

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

$url[] = "route=clients";

if($_GET['sort'] == 1){
    $query = "clients_status=1"; 
    $sort_name = "Активные";  
}elseif ($_GET['sort'] == 2){                    
    $query = "clients_status=0";  
    $sort_name = "Не подтвержденные";                          
}elseif ($_GET['sort'] == 3){                    
    $query = "clients_status=2";  
    $sort_name = "Заблокированные";                          
}elseif ($_GET['sort'] == 4){                    
    $query = "unix_timestamp(clients_datetime_view)+3*60 > unix_timestamp(NOW()) and clients_status!=3";                         
}elseif ($_GET['sort'] == 5){                    
    $query = "clients_verification_status=1 and clients_status!=3";  
    $sort_name = "Верифицированные";                        
}elseif ($_GET['sort'] == 6){                    
    $query = "clients_verification_status=0 and clients_status!=3"; 
    $sort_name = "Не верифицированные";                         
}
           
$url[] = 'sort='.$_GET['sort'];

if($_GET["search"]){

   $_GET["search"] = clearSearch($_GET["search"]);
   $query = "(clients_name LIKE '%".$_GET["search"]."%' OR clients_surname LIKE '%".$_GET["search"]."%' OR clients_id LIKE '%".$_GET["search"]."%' OR clients_email LIKE '%".$_GET["search"]."%' OR clients_id_hash LIKE '%".$_GET["search"]."%')"; 
   $url[] = 'search='.$_GET["search"]; 

}

if($query){
   $query = " where $query";
}else{
   $query = " where clients_status!=3";
}

$LINK = "?".implode("&",$url);

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Пользователи (<?php echo (int)getOne("select count(*) as total from uni_clients where clients_status!='3'")["total"]; ?>)</h2>
      </div>
   </div>
</div>

<div class="row" >
  <div class="col-lg-12" >
    
      <form method="get" action="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" >
        <input type="text" class="form-control" placeholder="Укажите данные пользователя" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
        <input type="hidden" name="route" value="clients" >
      </form>

  </div>
</div>

<div class="form-group" style="margin-bottom: 25px; margin-top: 25px;" >
 
 <div class="btn-group" >
   <div class="dropdown">
    <button class="btn btn-gradient-04 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Действия
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
       <a  href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-add-user">Добавить пользователя</a>
    </div>
   </div>
 </div>

 <div class="btn-group" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=clients">Без сортировки</a>
      <a class="dropdown-item" href="?route=clients&sort=1">Активные</a>
      <a class="dropdown-item" href="?route=clients&sort=2">Не подтвержденные</a>
      <a class="dropdown-item" href="?route=clients&sort=3">Заблокированные</a>
      <a class="dropdown-item" href="?route=clients&sort=5">Верифицированные</a>
      <a class="dropdown-item" href="?route=clients&sort=6">Не верифицированные</a>
    </div>
   </div>
 </div>

 <div class="btn-group" >
   <?php if($_GET['sort'] == 4){ ?>
      <a href="?route=clients" class="btn btn-secondary">Все пользователи</a>
   <?php }else{ ?>
      <a href="?route=clients&sort=4" class="btn btn-secondary">Пользователи онлайн (<?php echo $Profile->countOnline(); ?>)</a>
   <?php } ?>
 </div>

</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $count = getOne("SELECT count(*) as total FROM uni_clients $query ")["total"];

                    $get = getAll("SELECT * FROM uni_clients $query ORDER By clients_id DESC ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Пользователь</th>
                            <th>Баланс</th>
                            <th>Зарегистрирован</th>
                            <th>Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $value){

                        ?>

                             <tr>                                               
                                 <td>

                                  <div class="widget-list-users-avatar" >
                                    <img src="<?php echo $Profile->userAvatar($value, false); ?>" />
                                  </div>

                                  <div class="widget-list-users-title" >
                                    <a href="?route=client_view&id=<?php echo $value["clients_id"]; ?>" ><?php echo $Profile->name($value, false); ?></a>
                                    <span><?php echo $Geo->userGeo( ["city_id"=>$value["clients_city_id"], "ip"=>$value["clients_ip"]] ); ?></span>
                                  </div>

                                 </td>
                                 <td>
                                   <?php echo number_format($value["clients_balance"],2,".",",") . $settings["currency_main"]["sign"]; ?>
                                 </td>                                 
                                 <td>
                                   <?php echo datetime_format_cp($value["clients_datetime_add"]); ?>
                                 </td>                                 
                                 <td>
                                   <div class="dropdown">

                                    <?php 

                                     if($value["clients_status"] == 0){

                                        ?>

                                        <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Не подтвержден
                                        </button>

                                        <div class="dropdown-menu" >
                                          <a class="dropdown-item change-status-user" data-id="<?php echo $value["clients_id"]; ?>" data-status="1" href="#">Активировать</a>
                                        </div>

                                        <?php

                                     }elseif($value["clients_status"] == 1){

                                        ?>

                                        <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Активен
                                        </button>

                                        <div class="dropdown-menu" >
                                          <a class="dropdown-item change-status-user" data-id="<?php echo $value["clients_id"]; ?>" data-status="2" href="#">Заблокировать</a>
                                        </div>

                                        <?php

                                     }elseif($value["clients_status"] == 2){

                                        ?>

                                        <button class="btn btn-danger dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Заблокирован
                                        </button>

                                        <div class="dropdown-menu" >
                                          <a class="dropdown-item change-status-user" data-id="<?php echo $value["clients_id"]; ?>" data-status="1" href="#">Активировать</a>
                                        </div>

                                        <?php

                                     }elseif($value["clients_status"] == 3){

                                        ?>

                                        <button class="btn btn-dark dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Удален
                                        </button>

                                        <div class="dropdown-menu" >
                                          <a class="dropdown-item change-status-user" data-id="<?php echo $value["clients_id"]; ?>" data-status="1" href="#">Восстановить</a>
                                        </div>

                                        <?php

                                     }

                                     ?>

                                    </div>                               
                                 </td>
                                 <td class="td-actions" style="text-align: right;" >
                                  <a href="?route=client_view&id=<?php echo $value["clients_id"]; ?>"><i class="la la-eye edit"></i></a>
                                  <a href="#" class="delete-user" data-id="<?php echo $value["clients_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Пользователей нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal-add-user" class="modal fade">
   <div class="modal-dialog" style="max-width: 700px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавить нового пользователя</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-add-user" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Аватар</label>
                    <div class="col-lg-8">
                          <input type="file" name="image" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Имя</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" value="" name="user_name" >
                    </div>
                  </div>
  
                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Фамилия</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" value="" name="user_surname" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Телефон</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" value="" name="user_phone" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Email</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" value="" name="user_email" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Город</label>
                    <div class="col-lg-8">
                        <div class="container-custom-search" >
                          <input type="text" autocomplete="nope" class="form-control action-input-search-city" >
                          <div class="custom-results SearchCityResults" ></div>
                        </div>
                        
                        <input type="hidden" name="city_id" value="0" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Пароль</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" value="<?php echo generatePass(10); ?>" name="user_pass" >
                    </div>
                  </div>

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary clients-add-user">Добавить</button>
         </div>
      </div>
   </div>
</div>

<ul class="pagination">  
 <?php echo out_navigation( array("count"=>$count, "output"=>$_SESSION["ByShow"], "url"=>$LINK, "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
</ul>
            
 <script type="text/javascript" src="include/modules/clients/script.js"></script>
     
