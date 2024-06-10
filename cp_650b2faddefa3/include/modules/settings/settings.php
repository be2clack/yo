<?php 
if( !defined('unisitecms') ) exit;

if(!$_SESSION["cp_control_settings"]){
    header("Location: ?route=index");
}

include("fn.php");

foreach (json_decode($settings["settings_tabs"], true) as $key => $value) {
   if($key == "marketplace"){
      if($settings["functionality"]["marketplace"]){
         $settings_tabs[$key] = $value;
      }
   }elseif($key == "booking"){
      if($settings["functionality"]["booking"]){
         $settings_tabs[$key] = $value;
      }
   }else{
      $settings_tabs[$key] = $value;
   }
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : "systems";

$social_auth_params = $settings["social_auth_params"] ? json_decode(decrypt($settings["social_auth_params"]), true) : [];

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Настройки</h2>
      </div>
   </div>
</div>

<div class="row">
<div class="col-lg-12">

<div class="widget has-shadow">

   <div class="widget-body">
      <ul class="nav nav-tabs nav-fill" role="tablist">
          <?php
            foreach (array_slice($settings_tabs, 0,20) as $key => $name) {
               ?>
               <li class="nav-item">
                  <a class="nav-link <?php if($tab == $key){ echo 'active show'; } ?>" id="tab-<?php echo $key; ?>"  href="?route=settings&tab=<?php echo $key; ?>"><?php echo $name; ?></a>
               </li>               
               <?php
            }
          ?>
          <li class="nav-item dropdown">
             <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
             Еще
             <i class="ion-android-arrow-dropdown"></i>
             </a>
             <div class="dropdown-menu" x-placement="bottom-start">

                <?php
                  foreach (array_slice($settings_tabs, 20, count($settings_tabs)) as $key => $name) {
                     ?>
                     <a class="dropdown-item" href="?route=settings&tab=<?php echo $key; ?>" ><?php echo $name; ?></a>               
                     <?php
                  }
                ?>

             </div>
          </li>                                                                    
      </ul>
      <form class="form-data" >
      <div class="tab-content pt-3">
          
          <br>

          <?php
             if(file_exists(__dir__."/tabs/{$tab}.php")){
               include __dir__."/tabs/{$tab}.php";
             }
          ?>

      </div>
      <input type="hidden" name="tab" value="<?php echo $tab; ?>" >
      </form>

   </div>
</div>

<div class="text-right" >
<button type="button" class="btn btn-success save-settings">Сохранить</button>
</div>

</div>
</div>       


<div id="modal-log" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Результат теста</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
         	<textarea class="result-log form-control" style="min-height: 200px;" ></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-add-lang" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление языка'</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-add-lang" >

                  <div class="form-group row mb-5">
                    <label class="col-lg-4 form-control-label">Статус</label>
                    <div class="col-lg-8">
                        <label>
                          <input class="toggle-checkbox-sm" type="checkbox" name="status" checked="" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Иконка</label>
                    <div class="col-lg-8">
                          <img class="change-img" src="<?php echo $settings["path_other"]; ?>/icon_photo_add.png" width="60px" >
                          <input type="file" name="image" class="input-img" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Название</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control setTranslate" name="name" >
                    </div>
                  </div>
  
                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Алиас</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control outTranslate" name="alias" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">ISO</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" name="iso" >
                    </div>
                  </div>

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary add-lang">Добавить</button>
         </div>
      </div>
   </div>
</div>


<div id="modal-edit-lang" class="modal fade" >
   <div class="modal-dialog"  style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование языка</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-edit-lang" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary edit-lang">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-add-currency" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление валюты</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-add-currency" >
                <div class="row" >
                   <div class="col-lg-6" >
                   <label>Название</label>
                   <input type="text" class="form-control" name="name" placeholder="Доллары" />
                   </div>

                   <div  class="col-lg-3" >
                   <label>Знак</label>
                   <input type="text" class="form-control" name="sign" placeholder="$" />
                   </div>

                   <div  class="col-lg-3" >
                   <label>Код</label>
                   <input type="text" class="form-control" name="code" placeholder="USD" />
                   </div>

                </div>   
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary settings-add-currency">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-chat-snippets-message" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Быстрые сообщения в чате</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">

            <?php

            $getCategories = (new CategoryBoard())->getCategories("where category_board_visible=1");

            $app_chat_snippets_message = $settings["app_chat_snippets_message"] ? json_decode($settings["app_chat_snippets_message"], true) : [];
            ?>
            
            <form class="form-chat-snippets-message" >

              <?php
              foreach ($getCategories["category_board_id_parent"][0] as $value) {

                  foreach ($getCategories["category_board_id_parent"][$value["category_board_id"]] as $subvalue) {
                      ?>
                       <div class="form-group row d-flex align-items-center mb-5">
                         <label class="col-lg-3 form-control-label"> <?php echo $value["category_board_name"]; ?> - <?php echo $subvalue["category_board_name"]; ?></label>
                         <div class="col-lg-9">
                             <textarea class="form-control" rows="2" name="app_chat_snippets_message[<?php echo $subvalue["category_board_id"]; ?>]" ><?php if(isset($app_chat_snippets_message[$subvalue["category_board_id"]])){ echo implode(",", $app_chat_snippets_message[$subvalue["category_board_id"]]); } ?></textarea>
                         </div>
                       </div>                 
                      <?php
                  }

              }
              ?>
              
            </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary save-chat-snippets-message">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/settings/script.js"></script>
