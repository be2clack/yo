<?php
$id = (int)$_POST['id'];
?>
<div class="table-responsive">

    <?php   
        
        $get = getAll('select * from uni_action_statistics where action_statistics_from_user_id=? and action_statistics_to_user_id=?', [$id,$_SESSION["profile"]["id"]]);

        if($get){   

        ?>
            <table class="table table-borderless mt15">
            <thead>
                <tr>
                <th><?php echo $ULang->t("Объявление"); ?></th>
                <th><?php echo $ULang->t("Действие"); ?></th>
                </tr>
            </thead>
            <tbody class="sort-container" >                     
            <?php 
            foreach($get AS $value){
                $getAd = findOne("uni_ads", "ads_id=?", [$value['action_statistics_ad_id']]);
                if($getAd){
                ?>
                <tr>
                    <td><?php echo $getAd['ads_title']; ?></td>
                    <td>
                        <?php
                            if($value['action_statistics_action'] == 'favorite'){
                                echo $ULang->t('Добавил в избранное');
                            }elseif($value['action_statistics_action'] == 'show_phone'){
                                echo $ULang->t('Просмотрел телефон');
                            }elseif($value['action_statistics_action'] == 'ad_sell'){
                                echo $ULang->t('Купил');
                            }elseif($value['action_statistics_action'] == 'add_to_cart'){
                                echo $ULang->t('Добавил в корзину');
                            }
                        ?>
                    </td>                      
                </tr> 
                <?php 
                }                                        
            } 
            ?>
            </tbody>
            </table>
            <?php               
        }                  
    ?>

</div>    