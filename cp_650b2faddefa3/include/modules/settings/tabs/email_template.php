<div class="tab-pane fade <?php if($tab == "email_template"){ echo 'active show'; } ?>" id="tab-email_template" role="tabpanel" aria-labelledby="tab-email_template">

 <div class="form-group row d-flex align-items-center mb-5">

    <div class="col-lg-12">
        
        <div class="row" >

            <?php
               $get = getAll("SELECT * FROM uni_email_message");
                 if (count($get) > 0) {
                    foreach ($get as $key => $value) {
                        ?>
                          <div class="col-lg-3" >
                             <div class="template-item" >
                                 <p><?php echo $value["name"]; ?></p>
                                 <button type="button" data-id="<?php echo $value["id"]; ?>" class="btn btn-secondary setting-open-email mr-1 mb-2 btn-sm">Редактировать</button>
                             </div>
                          </div>                                                    
                        <?php 
                    }    
                 }
            ?>
                                                          
        </div>

    </div>

 </div>

</div>

<div id="modal-email-templates" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование письма</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body container-templates"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary settings-edit-email-template">Сохранить</button>
         </div>
      </div>
   </div>
</div>