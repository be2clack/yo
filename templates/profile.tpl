<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    
    <title><?php echo $data["page_name"]; ?></title>
    
    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>">

    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
       <nav aria-label="breadcrumb" class="mt15" >
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo _link(); ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span></a>
              <meta itemprop="position" content="1">
            </li>
            
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo _link( "user/" . $user["clients_id_hash"] ); ?>">
              <span itemprop="name"><?php echo $Profile->name($user); ?></span></a>
              <meta itemprop="position" content="2">
            </li>
            
            <?php if($data["advanced"]){ ?>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <span itemprop="name"><?php echo $data["page_name"]; ?></span>
              <meta itemprop="position" content="3">
            </li>
            <?php } ?>
                             
          </ol>

        </nav>
        
        <div class="mt40" ></div>

        <div class="row" >
           <div class="col-lg-3" >

               <div class="user-sidebar mb30" >

                  <?php include $config["template_path"] . "/profile_sidebar.tpl"; ?>

               </div>
             
           </div>
           <div class="col-lg-9 min-height-600" >
               
               <?php if($data["advanced"]){ ?>
               <div class="user-warning-seller-safety" >
                   <span class="warning-seller-safety-close" ><i class="las la-times"></i></span>
                   <div class="row no-gutters" >
                       <div class="col-lg-8 col-md-8 col-sm-8 col-12" >
                          <div class="seller-safety-text" >
                             <h4><strong><?php echo $ULang->t("Не дайте себя обмануть!"); ?></strong></h4>
                             <p><?php echo $ULang->t("Узнайте, как уберечь свой кошелёк от злоумышленников"); ?></p>
                             <a href="#" class="open-modal" data-id-modal="modal-seller-safety" ><?php echo $ULang->t("Советы по безопасности"); ?></a>
                          </div>
                       </div>
                       <div class="col-lg-4 col-md-4 col-sm-4 d-none d-md-block" >
                          <div class="seller-safety-img" ></div>
                       </div>
                   </div>
               </div>
               <?php } ?>

               <?php if($action == "ad" || !$action){ 

                   include $config["template_path"] . "/include/profile/ad.php";

                }elseif($action == "balance"){ 

                   include $config["template_path"] . "/include/profile/balance.php";

                }elseif($action == "favorites"){

                   include $config["template_path"] . "/include/profile/favorites.php";

                }elseif($action == "settings"){

                   include $config["template_path"] . "/include/profile/settings.php";

               }elseif($action == "orders"){

                   include $config["template_path"] . "/include/profile/orders.php";

               }elseif($action == "booking"){

                   include $config["template_path"] . "/include/profile/booking.php"; 

               }elseif($action == "reviews"){

                   include $config["template_path"] . "/include/profile/reviews.php";

               }elseif($action == "subscriptions"){

                   include $config["template_path"] . "/include/profile/subscriptions.php";

               }elseif($action == $settings['user_shop_alias_url_page']){

                   include $config["template_path"] . "/include/profile/shop.php";

               }elseif($action == "tariff"){

                   include $config["template_path"] . "/include/profile/tariff.php";

               }elseif($action == "scheduler"){

                   include $config["template_path"] . "/include/profile/scheduler.php";

               }elseif($action == "statistics"){

                   include $config["template_path"] . "/include/profile/statistics.php";

               }elseif($action == "ref"){

                   include $config["template_path"] . "/include/profile/ref.php";

               }elseif($action == "booking-calendar"){
                  
                  include $config["template_path"] . "/include/profile/booking-calendar.php";

               }elseif($action == "packages"){
                  
                  include $config["template_path"] . "/include/profile/packages.php";

               }

               ?>

             
           </div>
        </div>

    </div>
    
    <div class="mt35" ></div>
 
    <?php include $config["template_path"] . "/footer.tpl"; ?>

    <?php if($action == "statistics"){ ?> 
    <script type="text/javascript">
    $(document).ready(function () {
      var options = {
        series: [
            {
              name: '<?php echo $ULang->t("Показы"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('display'); ?>]
            }, 
            {
              name: '<?php echo $ULang->t("Просмотры"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('view'); ?>]
            }, 
            {
              name: '<?php echo $ULang->t("Добавили в избранное"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('favorites'); ?>]
            }, 
            {
              name: '<?php echo $ULang->t("Просмотрели телефон"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('show_phone'); ?>]
            }, 
            {
              name: '<?php echo $ULang->t("Продаж"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('ad_sell'); ?>]
            }, 
            <?php if($settings['marketplace_status'] && $settings["functionality"]["marketplace"]){ ?>
            {
              name: '<?php echo $ULang->t("Добавили в корзину"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('cart'); ?>]
            },
            <?php } ?>
            <?php if($settings["functionality"]["booking"]){ ?>
            {
              name: '<?php echo $ULang->t("Бронировали/Арендовали"); ?>',
              data: [<?php echo $Profile->dataActionStatistics('booking'); ?>]
            },
            <?php } ?>            
        ],
        chart: {
        height: 350,
        type: 'area',
        toolbar: { show: false },
        zoom: { enabled: false },
      },
      legend: {
          show: true,
          position: 'top',
          horizontalAlign: 'center', 
          floating: false,
          fontSize: '15px',
          fontFamily: 'Helvetica, Arial',
          fontWeight: 400,
          itemMargin: {
              horizontal: 10,
              vertical: 0
          },         
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2,
      },
      xaxis: {
        type: 'datetime',
        categories: [<?php echo $Profile->dataActionStatistics('date'); ?>]
      },
      tooltip: {
          x: {
                format: 'dd.MM.yyyy'
             },
          y: {
            formatter: function (y) {
              if (typeof y !== "undefined") {
                return y;
              }
              return y;

            }
          }      
       },
      };

      var chart = new ApexCharts(document.querySelector(".profile-statistics-area1"), options);
      chart.render();
    });
    </script>
    <?php } ?>

    <?php 

    if($settings["bonus_program"]["email"]["status"] && $data["advanced"] && !$user["clients_email"] && !$_SESSION["modal"]["bonus_program"]["email"]){ 

    ?>
    <script type="text/javascript">
       $(document).ready(function () {

          setTimeout( function(){

          $("#modal-notification-email").show();
          $("body").css("overflow", "hidden");

          } , 5000);
 
       })
    </script>
    <?php 

    $_SESSION["modal"]["bonus_program"]["email"] = 1;

    } 

    ?>
    
    <script type="text/javascript">
    $(document).ready(function () {

      <?php 
      if($_GET["modal"] == "notifications" && $data["advanced"]){ ?>
      $(window).load(function() { 
         $( "#modal-edit-notifications" ).show();
         $("body").css("overflow", "hidden");
      });
      <?php 
      }
      ?>


    });
    </script>

    <?php include $config["template_path"] . "/profile_modals.tpl"; ?>

  </body>
</html>