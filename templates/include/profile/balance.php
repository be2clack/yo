<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div class="user-menu-tab" >
 <div data-id-tab="balance" <?php if($action == "balance"){ echo 'class="active"'; } ?> > <?php echo $ULang->t("Пополнение баланса"); ?> </div>
 <div data-id-tab="history" > <?php echo $ULang->t("История платежей"); ?> </div>
 <?php if($data["invoices_requisites_balance"]){ ?>
    <div data-id-tab="invoice" > <?php echo $ULang->t("Выставленные счета"); ?> </div>
 <?php } ?>
</div>

<div class="user-menu-tab-content <?php if($action == "balance"){ echo 'active'; } ?>" data-id-tab="balance" >

<div class="module-balance" >

    <h5><?php echo $ULang->t("Выберите способ оплаты"); ?></h5>

   <?php
      if($settings["balance_payment_requisites"]){
         ?>

           <div class="user-balance-variant-list mt15" >
               <div>

                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="balance_variant" id="balance-variant1" value="1">
                        <label class="custom-control-label" for="balance-variant1"><?php echo $ULang->t("Через платежную систему"); ?></label>
                    </div>

                </div>
               <div>

                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="balance_variant" id="balance-variant2" value="2">
                        <label class="custom-control-label" for="balance-variant2"><?php echo $ULang->t("Через выставление счета"); ?></label>
                    </div>

               </div>
           </div>

           <div class="user-balance-variant-1" >

               <h5 class="mt15" ><?php echo $ULang->t("Выберите платежную систему"); ?></h5>
               
               <form method="POST" class="form-balance" >

               <div class="user-balance-payment" >

                  <?php
                    if($data["payments"]){
                       foreach ($data["payments"] as $key => $value) {
                           ?>
                           <div title="<?php echo $value["name"]; ?>" class="user-change-pay" >
                            <span><img src="<?php echo Exists($config["media"]["other"], $value["logo"], $config["media"]["no_image"]); ?>" ></span>
                            <input type="radio" name="payment" value="<?php echo $value["code"]; ?>" >
                           </div>
                           <?php
                       }
                    }else{
                       ?>
                       <p><?php echo $ULang->t("У вас нет ни одной платежной системы"); ?></p>
                       <?php
                    }
                  ?>

               </div>

               <h5 class="mt35" ><?php echo $ULang->t("Сумма пополнения"); ?></h5>

               <div class="user-balance-summa" >

                    <div>
                      <div>
                        <p><?php echo $Main->price(100); ?></p>
                        <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                        <span>+ <?php echo $Main->price( $Profile->calcBonus(100) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                        <?php } ?>
                        <input type="radio" name="amount" value="100" >
                      </div>
                    </div>

                    <div>
                      <div>
                         <p><?php echo $Main->price(300); ?></p>
                         <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                         <span>+ <?php echo $Main->price( $Profile->calcBonus(300) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                         <?php } ?>    
                         <input type="radio" name="amount" value="300" >                        
                      </div>
                    </div>

                    <div>
                      <div>
                         <p><?php echo $Main->price(500); ?></p>
                         <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                         <span>+ <?php echo $Main->price( $Profile->calcBonus(500) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                         <?php } ?> 
                         <input type="radio" name="amount" value="500" >
                      </div>
                    </div>

                    <div>
                      <span><?php echo $ULang->t("Популярный выбор"); ?></span>
                      <div>
                         <p><?php echo $Main->price(1000); ?></p>
                         <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                         <span>+ <?php echo $Main->price( $Profile->calcBonus(1000) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                         <?php } ?> 
                         <input type="radio" name="amount" value="1000" >
                      </div>
                    </div>

                    <div>
                      <div>
                         <p style="font-size: 17px" ><?php echo $ULang->t("Произвольная сумма"); ?></p>
                         <input type="radio" name="amount" value="" >
                      </div>
                    </div>

                    <span class="clr" ></span>

               </div>

               <div class="mt15" ></div>
               
               <div class="bg-container balance-input-amount balance-input-amount-variant1" >
                 
                 <div>
                   <h6> <strong><?php echo $ULang->t("Укажите сумму пополнения"); ?></strong> </h6>
                   <input type="number" step="any" name="change_amount" min="<?php echo $settings["min_deposit_balance"]; ?>" max="<?php echo $settings["max_deposit_balance"]; ?>" class="form-control" >
                 </div>

               </div>

               <div class="mt35" ></div>

               <div class="row" >
                 <div class="col-lg-4" ></div>
                 <div class="col-lg-4" >
                   <button class="btn-custom-big btn-color-blue mb5 width100" ><?php echo $ULang->t("Перейти к оплате"); ?></button>
                 </div>
                 <div class="col-lg-4" ></div>
               </div>

               <div class="redirect-form-pay" ></div>

               </form>

           </div>

           <div class="user-balance-variant-2" >

               <form method="POST" class="form-balance-invoice">
               
               <div class="bg-container balance-input-amount mt15" >
                 
                 <div>
                   <h6> <strong><?php echo $ULang->t("Укажите сумму пополнения"); ?></strong> </h6>
                   <input type="number" step="any" name="amount" min="<?php echo $settings["min_deposit_balance"]; ?>" max="<?php echo $settings["max_deposit_balance"]; ?>" class="form-control" >
                 </div>

                 <div class="mt15 text-center" ><?php echo $ULang->t("Счет будет выставлен по вашим"); ?> <span style="color: blue; cursor: pointer;" class="open-modal" data-id-modal="modal-user-requisites" ><?php echo $ULang->t("реквизитам"); ?></span></div>

               </div>

               <div class="mt35" ></div>

               <div class="row" >
                 <div class="col-lg-4" ></div>
                 <div class="col-lg-4" >
                   <button class="btn-custom-big btn-color-blue mb5 width100" ><?php echo $ULang->t("Выставить счет"); ?></button>
                 </div>
                 <div class="col-lg-4" ></div>
               </div>

               </form>

           </div>

         <?php
      }else{
         ?>

           <form method="POST" class="form-balance" >

           <div class="user-balance-payment" >

              <?php
                if($data["payments"]){
                   foreach ($data["payments"] as $key => $value) {
                       ?>
                       <div title="<?php echo $value["name"]; ?>" class="user-change-pay" >
                        <span><img src="<?php echo Exists($config["media"]["other"], $value["logo"], $config["media"]["no_image"]); ?>" ></span>
                        <input type="radio" name="payment" value="<?php echo $value["code"]; ?>" >
                       </div>
                       <?php
                   }
                }else{
                   ?>
                   <p><?php echo $ULang->t("У вас нет ни одной платежной системы"); ?></p>
                   <?php
                }
              ?>

           </div>

           <h5 class="mt35" ><?php echo $ULang->t("Сумма пополнения"); ?></h5>

           <div class="user-balance-summa" >

                <div>
                  <div>
                    <p><?php echo $Main->price(100); ?></p>
                    <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                    <span>+ <?php echo $Main->price( $Profile->calcBonus(100) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                    <?php } ?>
                    <input type="radio" name="amount" value="100" >
                  </div>
                </div>

                <div>
                  <div>
                     <p><?php echo $Main->price(300); ?></p>
                     <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                     <span>+ <?php echo $Main->price( $Profile->calcBonus(300) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                     <?php } ?>    
                     <input type="radio" name="amount" value="300" >                        
                  </div>
                </div>

                <div>
                  <div>
                     <p><?php echo $Main->price(500); ?></p>
                     <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                     <span>+ <?php echo $Main->price( $Profile->calcBonus(500) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                     <?php } ?> 
                     <input type="radio" name="amount" value="500" >
                  </div>
                </div>

                <div>
                  <span><?php echo $ULang->t("Популярный выбор"); ?></span>
                  <div>
                     <p><?php echo $Main->price(1000); ?></p>
                     <?php if($settings["bonus_program"]["balance"]["status"]){ ?>
                     <span>+ <?php echo $Main->price( $Profile->calcBonus(1000) ); ?> <?php echo $ULang->t("бонус"); ?></span>
                     <?php } ?> 
                     <input type="radio" name="amount" value="1000" >
                  </div>
                </div>

                <div>
                  <div>
                     <p style="font-size: 17px" ><?php echo $ULang->t("Произвольная сумма"); ?></p>
                     <input type="radio" name="amount" value="" >
                  </div>
                </div>

                <span class="clr" ></span>

           </div>

           <div class="mt15" ></div>
           
           <div class="bg-container balance-input-amount balance-input-amount-variant1" >
             
             <div>
               <h6> <strong><?php echo $ULang->t("Укажите сумму пополнения"); ?></strong> </h6>
               <input type="number" step="any" name="change_amount" min="<?php echo $settings["min_deposit_balance"]; ?>" max="<?php echo $settings["max_deposit_balance"]; ?>" class="form-control" >
             </div>

           </div>

           <div class="mt35" ></div>

           <div class="row" >
             <div class="col-lg-4" ></div>
             <div class="col-lg-4" >
               <button class="btn-custom-big btn-color-blue mb5 width100" ><?php echo $ULang->t("Перейти к оплате"); ?></button>
             </div>
             <div class="col-lg-4" ></div>
           </div>

           <div class="redirect-form-pay" ></div>

           </form>

        <?php
      }
   ?>
   
</div>
  
</div>

<div class="user-menu-tab-content <?php if($action == "history"){ echo 'active'; } ?>" data-id-tab="history" >

    <div class="bg-container" >
      
      <div class="table-responsive">

           <?php
              $get = getAll("SELECT * FROM uni_history_balance where id_user=? order by id desc", [$_SESSION["profile"]["id"]]);     

               if(count($get)){   

               ?>
               <table class="table table-borderless">
                  <thead>
                     <tr>
                      <th><?php echo $ULang->t("Назначение"); ?></th>
                      <th><?php echo $ULang->t("Сумма"); ?></th>
                      <th><?php echo $ULang->t("Дата"); ?></th>
                     </tr>
                  </thead>
                  <tbody class="sort-container" >                     
               <?php

                  foreach($get AS $value){

                  ?>

                   <tr>
                       <td style="max-width: 350px;" ><?php echo $ULang->t($value["name"]); ?></td>
                       <td>

                         <?php
                          if($value["action"] == "+"){
                              echo '<span style="color: green;" >+ '.$Main->price($value["summa"]).'</span>';
                          }else{
                              echo '<span style="color: red;" >- '.$Main->price($value["summa"]).'</span>';
                          }
                         ?>                                   

                       </td>
                       <td><?php echo datetime_format($value["datetime"]); ?></td>                          
                   </tr> 
           
                 
                   <?php                                         
                  } 

                  ?>

                     </tbody>
                  </table>

                  <?php               
               }else{
                  ?>

                   <div class="user-block-no-result" >

                      <img src="<?php echo $settings["path_tpl_image"]; ?>/zdun-icon.png">
                      <p><?php echo $ULang->t("Вы еще не делали никаких платежей"); ?></p>
                     
                   </div>

                  <?php
               }                  
            ?>

      </div> 

    </div>
  
</div>

<div class="user-menu-tab-content <?php if($action == "invoice"){ echo 'active'; } ?>" data-id-tab="invoice" >

    <div class="bg-container" >
      
      <div class="table-responsive">

           <?php
               if($data["invoices_requisites_balance"]){   

               ?>
               <table class="table table-borderless">
                  <thead>
                     <tr>
                      <th><?php echo $ULang->t("Сумма"); ?></th>
                      <th><?php echo $ULang->t("Дата"); ?></th>
                      <th><?php echo $ULang->t("Счет"); ?></th>
                     </tr>
                  </thead>
                  <tbody class="sort-container" >                     
                  
                      <?php

                      foreach($data["invoices_requisites_balance"] AS $value){

                      ?>

                       <tr>
                           <td>
                             <?php
                              echo $Main->price($value["amount"]);
                             ?>                                   
                           </td>
                           <td><?php echo datetime_format($value["create_time"]); ?></td>   
                           <td><a href="<?php echo $config['urlPath'].'/'.$config['media']['user_invoice'].'/'.$value["invoice"]; ?>"><?php echo $ULang->t("Скачать"); ?></a></td>                      
                       </tr> 
               
                     
                       <?php                                         
                      } 

                      ?>

                  </tbody>
                </table>

                  <?php               
               }else{
                  ?>

                   <div class="user-block-no-result" >

                      <img src="<?php echo $settings["path_tpl_image"]; ?>/zdun-icon.png">
                      <p><?php echo $ULang->t("Счетов нет"); ?></p>
                     
                   </div>

                  <?php
               }                  
            ?>

      </div> 

    </div>
  
</div>