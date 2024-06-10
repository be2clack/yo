
<h3 class="mt35 mb35 user-title" > <strong><?php echo $ULang->t("Реферальная программа"); ?></strong> </h3>

<div class="ref-block-link" >
    <h5 class="mt35 mb35 user-title" > <strong><?php echo $ULang->t("Ваша ссылка"); ?></strong> </h5>
    <div><strong><a href="<?php echo $Profile->refAlias($user["clients_ref_id"]); ?>" ><?php echo $Profile->refAlias($user["clients_ref_id"]); ?></strong></a></div>
</div>

<div class="user-menu-tab mt30" >
 <div data-id-tab="referrals" class="active" ><?php echo $ULang->t('Рефералы'); ?> (<?php echo count($data["referrals"]); ?>)</div>
 <div data-id-tab="award" ><?php echo $ULang->t('Выплаты'); ?> (<?php echo $Main->price($data["referrals_award_total"]); ?>)</div>
</div>

<div class="user-menu-tab-content active" data-id-tab="referrals" >
    
<?php
if(count($data["referrals"])){
  ?>
      <table class="table table-borderless">
        <thead>
            <tr>
            <th><?php echo $ULang->t("Реферал"); ?></th>
            <th><?php echo $ULang->t("Дата регистрации"); ?></th>
            <th><?php echo $ULang->t("Заработано"); ?></th>
            </tr>
        </thead>
        <tbody class="sort-container" >                     
      <?php

        foreach($data["referrals"] AS $value){

        $getRefUser = findOne('uni_clients', 'clients_id=?', [$value['id_user_referral']]);

        $getAwardTotal = getOne("select sum(clients_reff_award_amount) as total from uni_clients_reff_award where clients_reff_award_id_user_referrer=? and clients_reff_award_id_user_referral=?", [$_SESSION['profile']['id'],$value['id_user_referral']])['total'];

        ?>

            <tr>
                <td><a href="<?php echo _link("user/".$getRefUser["clients_id_hash"]); ?>"><?php echo $getRefUser['clients_name']; ?> <?php echo $getRefUser['clients_surname']; ?></a></td>
                <td><?php echo datetime_format($value["timestamp"]); ?></td>
                <td><?php echo $Main->price($getAwardTotal); ?></td>                          
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

        <p><?php echo $ULang->t("Рефералов нет"); ?></p>
       
     </div>                            
  <?php
}
?>
 
</div>                

<div class="user-menu-tab-content" data-id-tab="award" >
    
<?php
if(count($data["referrals_award"])){
  ?>
      <table class="table table-borderless">
        <thead>
            <tr>
            <th><?php echo $ULang->t("Реферал"); ?></th>
            <th><?php echo $ULang->t("Дата"); ?></th>
            <th><?php echo $ULang->t("Вознаграждение"); ?></th>
            </tr>
        </thead>
        <tbody class="sort-container" >                     
      <?php

        foreach($data["referrals_award"] AS $value){

        $getRefUser = findOne('uni_clients', 'clients_id=?', [$value['id_user_referral']]);

        ?>

            <tr>
                <td><a href="<?php echo _link("user/".$getRefUser["clients_id_hash"]); ?>"><?php echo $getRefUser['clients_name']; ?> <?php echo $getRefUser['clients_surname']; ?></a></td>
                <td><?php echo datetime_format($value["timestamp"]); ?></td>
                <td><?php echo $Main->price($value['amount']); ?></td>                          
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

        <p><?php echo $ULang->t("Выплат нет"); ?></p>
       
     </div>                            
  <?php
}
?>
 
</div>

<?php