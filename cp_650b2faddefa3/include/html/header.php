<?php if( !defined('unisitecms') ) exit; ?>

<header class="header">
<nav class="navbar fixed-top">
   <div class="navbar-holder d-flex align-items-center align-middle justify-content-between">
      <div class="navbar-header">
         <a href="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" class="navbar-brand">
            <div class="brand-image brand-big">
               <div class="brand-big-logo" > 
                  <?php if($settings["site_name"]){ echo custom_substr($settings["site_name"],10); }else{ echo custom_substr($settings["domen"],10); } ?> 
               </div>
            </div>
            <div class="brand-image brand-small">
               <?php if($settings["site_name"]){ ?>
               <div class="brand-small-logo" > <span><?php echo mb_substr($settings["site_name"],0, 2, "UTF-8"); ?></span> </div>
               <?php }else{ ?>
               <div class="brand-small-logo" > <span><?php echo mb_substr($settings["domen"],0, 2, "UTF-8"); ?></span> </div>  
               <?php } ?>
            </div>
         </a>
         <a id="toggle-btn" href="#" class="menu-btn active">
         <span></span>
         <span></span>
         <span></span>
         </a>
      </div>
      
      <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right">
         
         <?php if($_SESSION["cp_control_settings"]){ ?>
         <li class="nav-item"><a href="#modal-exclamation" data-toggle="modal"  ><i class="la la-exclamation-triangle"></i> <?php if( $getWarning["count"] ){ echo '<div class="exclamation-count" >'.$getWarning["count"].'</div>'; } ?>  </a></li>
         <?php } ?>
         
         <li class="nav-item dropdown">
            <a id="user" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">
            <div class="toolbal-avatar d-none d-md-block" ><img src="<?php echo Exists($config["media"]["avatar_admin"], $_SESSION['cp_auth'][ $config["private_hash"] ]["image"] ,$config["media"]["no_avatar"] );?>" width="100%" ></div>
            <div class="toolbal-avatar-xs d-block d-md-none" ><img src="<?php echo Exists($config["media"]["avatar_admin"], $_SESSION['cp_auth'][ $config["private_hash"] ]["image"] ,$config["media"]["no_avatar"] );?>" width="100%" ></div>
            </a>
            <ul aria-labelledby="user" class="user-size dropdown-menu">
               <?php if($_SESSION["cp_control_admin"]){ ?>
               <li>
                  <a href="?route=user&id=<?php echo $_SESSION['cp_auth'][ $config["private_hash"] ]["id"]; ?>" class="dropdown-item">
                  <i class="la la-user"></i> Профиль
                  </a>
               </li>
               <?php } ?>
               <?php if($_SESSION["cp_control_settings"]){ ?>
               <li>
                  <a href="?route=settings" class="dropdown-item">
                  <i class="la la-gear"></i> Настройки
                  </a>
               </li>
               <?php } ?>
               <li>
                  <a href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/?logout=1" class="dropdown-item">
                  <i class="la la-sign-out"></i> Выход
                  </a>                 
               </li>
            </ul>
         </li>
         <li class="nav-item"><a href="<?php echo $config["urlPath"]; ?>" target="_blank" ><i class="la la-external-link"></i></a></li>
      </ul>
   </div>
</nav>
</header>