<?php if($_SESSION["cp_control_settings"]){ ?>
<div id="modal-exclamation" class="modal fade">
   <div class="modal-dialog" style="max-width: 750px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Предупреждения</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <?php echo $getWarning["html"]; ?>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
         </div>
      </div>
   </div>
</div>
<?php } ?>

<div id="modal-block-user" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Заблокировать пользователя</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-block-user" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Выберите причину</label>
                    <div class="col-lg-8">
                          <select name="text" class="selectpicker" title="Не выбрано" >
                             <option value="Продажа запрещенных товаров" >Продажа запрещенных товаров</option>
                             <option value="Порнографические материалы" >Порнографические материалы</option>
                             <option value="Мошенничество" >Мошенничество</option>
                             <option value="Спам" >Спам</option>
                             <option value="Нарушение правил сайта" >Нарушение правил сайта</option>
                             <option value="" >Другая причина</option>
                          </select>
                    </div>
                  </div>
                  
                  <div class="block-user-comment" >
                    <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-4 form-control-label">Комментарий</label>
                      <div class="col-lg-8">
                            <textarea name="comment" class="form-control" ></textarea>
                      </div>
                    </div>
                  </div>

                  <input type="hidden" name="id_user" value="0" >
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-danger action-block-user">Заблокировать</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-block-ads" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Заблокировать объявление</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-block-ads" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Выберите причину</label>
                    <div class="col-lg-8">
                          <select name="text" class="selectpicker" title="Не выбрано" >
                             <option value="Запрещенный товар" >Запрещенный товар</option>
                             <option value="Непристойное содержание" >Непристойное содержание</option>
                             <option value="Мошенничество" >Мошенничество</option>
                             <option value="Спам" >Спам</option>
                             <option value="Нарушение правил сайта" >Нарушение правил сайта</option>
                             <option value="" >Другая причина</option>
                          </select>
                    </div>
                  </div>
                  
                  <div class="block-ads-comment" >
                    <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-4 form-control-label">Комментарий</label>
                      <div class="col-lg-8">
                            <textarea name="comment" class="form-control" ></textarea>
                      </div>
                    </div>
                  </div>
                  
                  <input type="hidden" name="id_ad" value="0" >
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-danger action-block-ads">Заблокировать</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-refused-ads" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Отклонить объявление</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-refused-ads" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Выберите причину</label>
                    <div class="col-lg-8">
                          <select name="text" class="selectpicker" title="Не выбрано" >
                             <option value="Неверная категория" >Неверная категория</option>
                             <option value="Не соответствует цена" >Не соответствует цена</option>
                             <option value="Некорректный заголовок" >Некорректный заголовок</option>
                             <option value="Нарушение правил сайта" >Нарушение правил сайта</option>
                             <option value="" >Другая причина</option>
                          </select>
                    </div>
                  </div>
                  
                  <div class="refused-ads-comment" >
                    <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-4 form-control-label">Комментарий</label>
                      <div class="col-lg-8">
                            <textarea name="comment" class="form-control" ></textarea>
                      </div>
                    </div>
                  </div>

                  <input type="hidden" name="id_ad" value="0" >
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-danger action-refused-ads">Отклонить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-refused-verification" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Отклонить подтверждение</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-refused-verification" >

                    <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-4 form-control-label">Причина отклонения</label>
                      <div class="col-lg-8">
                            <textarea name="comment" class="form-control" ></textarea>
                      </div>
                    </div>

                  <input type="hidden" name="id" value="0" >
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-danger action-refused-verification">Отклонить</button>
         </div>
      </div>
   </div>
</div>
