<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$id = (int)$_POST["id"];

$get = findOne("uni_email_message","id=?", array($id));

?>

   <form method="post" class="form-email-templates" >

        <div class="form-group row d-flex align-items-center mb-5">
          <label class="col-lg-3 form-control-label">Тема письма</label>
          <div class="col-lg-9">
               <input type="text" class="form-control" value="<?php echo $get->subject; ?>" name="email_subject" >
          </div>
        </div>

        <div class="form-group row d-flex align-items-center mb-5">
          <div class="col-lg-12">
              
              <div class="text-right" >
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                   <label class="btn btn-light active btn-sm">
                   <input type="radio" name="toggle-editor" value="1" id="option-toggle-editor1" autocomplete="off"> Визуальный редактор
                   </label>
                   <label class="btn btn-light btn-sm">
                   <input type="radio" name="toggle-editor" value="2" id="option-toggle-editor2" autocomplete="off"> Без редактора
                   </label>
                </div>
              </div>  

              <br>
              
              <textarea class="form-control" name="email_text" style="min-height: 300px;" ><?php echo urldecode($get->text); ?></textarea>
          </div>
        </div>

        <input type="hidden" name="id" value="<?php echo $get->id; ?>" >

   </form>

      <script>
           var instance = CKEDITOR.instances["email_text"];
          if(instance)
          {
              CKEDITOR.remove(instance);
          }
          CKEDITOR.replace("email_text");               
      </script>

<?php

}  
?>