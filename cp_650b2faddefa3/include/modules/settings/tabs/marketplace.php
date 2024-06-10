<div class="tab-pane fade <?php if($tab == "marketplace"){ echo 'active show'; } ?>" id="tab-marketplace" role="tabpanel" aria-labelledby="tab-marketplace">

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Маркетплейс</label>
  <div class="col-lg-9">
     <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="marketplace_status" value="1" <?php if($settings["marketplace_status"]){ echo 'checked=""'; } ?> >
        <span><span></span></span>
     </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Корзина доступна</label>
  <div class="col-lg-2">
     
     <select name="marketplace_available_cart" class="selectpicker" >
           <option value="all" <?php if($settings["marketplace_available_cart"] == 'all'){ echo 'selected=""'; } ?> >Всем пользователям</option>
           <option value="shop" <?php if($settings["marketplace_available_cart"] == 'shop'){ echo 'selected=""'; } ?> >Только владельцам магазина</option>
     </select>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Вид корзины</label>
  <div class="col-lg-2">
     
     <select name="marketplace_view_cart" class="selectpicker" >
           <option value="modal" <?php if($settings["marketplace_view_cart"] == 'modal'){ echo 'selected=""'; } ?> >Модальное окно</option>
           <option value="sidebar" <?php if($settings["marketplace_view_cart"] == 'sidebar'){ echo 'selected=""'; } ?> >Боковая панель</option>
           <option value="page" <?php if($settings["marketplace_view_cart"] == 'page'){ echo 'selected=""'; } ?> >Отдельная страница</option>
     </select>

  </div>
</div>

</div>