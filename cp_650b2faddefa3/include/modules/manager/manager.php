<?php 
if( !defined('unisitecms') ) exit;

if($settings["manager_extension"]){ $ext = explode(",",$settings["manager_extension"]); }else{ $ext=array(); }
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Файловый менеджер</h2>
      </div>
   </div>
</div>


<div class="form-group" >

 <button type="button" class="btn btn-gradient-04 mr-1 mb-2" data-toggle="modal" data-target="#modal-change-file" ><i class="la la-plus"></i> Добавить файл</button>

</div>


<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                   <?php

                      $getAll = getAll("select * from uni_filemanager order by filemanager_id desc");

                      if( count($getAll) ){

                      ?>
                       <table class="table mb-0">
                          <thead>
                             <tr>
                              <th>Файл</th>
                              <th>Размер</th>
                              <th>Ссылка на файл</th>
                              <th></th>
                             </tr>
                          </thead>
                          <tbody>                              
                      <?php

                          foreach ($getAll as $key => $value) {

                            $ext_file = strtolower(pathinfo($value["filemanager_name"], PATHINFO_EXTENSION));

                                if( $value["filemanager_dir"] == 1 ){
                                    $dir = $config["basePath"] . "/";
                                    $url = $config["urlPath"] . "/";
                                }else{
                                    $dir = $config["basePath"] . "/" . $config["media"]["manager"] . "/";
                                    $url = $config["urlPath"] . "/" . $config["media"]["manager"] . "/";
                                }
                            
                                ?>

                                     <tr>
                                         <td>
                                           <div class="manager-ext-block" >
                                              <div class="manager-ext" >
                                                <?php
                                                  if($ext_file == "jpg" || $ext_file == "png" || $ext_file == "jpeg"){
                                                    ?>
                                                    <img src="<?php echo $url . $value["filemanager_name"]; ?>">
                                                    <?php
                                                  }elseif($ext_file == "rar" || $ext_file == "zip"){
                                                    ?>
                                                    <img src="<?php echo $settings["path_admin_image"] . "/zip-format.png"; ?>">
                                                    <?php
                                                  }elseif($ext_file == "txt" || $ext_file == "doc" || $ext_file == "docx"){
                                                    ?>
                                                    <img src="<?php echo $settings["path_admin_image"] . "/docs-format.png"; ?>">
                                                    <?php
                                                  }elseif($ext_file == "pdf"){
                                                    ?>
                                                    <img src="<?php echo $settings["path_admin_image"] . "/pdf-format.png"; ?>">
                                                    <?php
                                                  }else{
                                                    ?>
                                                    <img src="<?php echo $settings["path_admin_image"] . "/docs-format.png"; ?>">
                                                    <?php
                                                  }
                                                ?>
                                              </div>
                                              <div class="manager-name" > <a href="<?php echo $url . $value["filemanager_name"]; ?>" target="_blank" ><?php echo $value["filemanager_name"];?></a> </div>
                                           </div>
                                         </td>
                                         <td><?php echo $Admin->manager_filesize( filesize($dir.$value["filemanager_name"]) );?></td>
                                         <td> <span class="manager-copy-link" ><i class="la la-copy"></i> Скопировать</span> <input type="hidden" style="display: none;" value="<?php echo $url . $value["filemanager_name"]; ?>" > </td> 
                                         <td class="td-actions" >
                                          <a href="#" class="delete-file" data-id="<?php echo $value["filemanager_id"];?>" ><i class="la la-close delete"></i></a>
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
                               <p>Файлов нет</p>
                            </div>
                         <?php                        
                      }


                    ?>
                    
            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal-change-file" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Выбор файла</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-data" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Изменить название</label>
                    <div class="col-lg-8">
                        <label class="mb0">
                          <input class="toggle-checkbox-sm" type="checkbox" name="rename" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Директория загрузки</label>
                    <div class="col-lg-8">
                          <select name="dir" class="selectpicker" title="Не выбрано" >
                             <option value="0" selected="" >По умолчанию</option>
                             <option value="1" >Корень сайта</option>
                          </select>
                    </div>
                  </div>
                  
                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Файл</label>
                    <div class="col-lg-8">
                          <input type="file" name="manager">
                    </div>
                  </div>
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-success add-manager-file">Загрузить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/manager/script.js"></script>
