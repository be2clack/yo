<div class="default-sidebar">
   <nav class="side-navbar box-scroll">

      <ul class="list-unstyled">

         <?php
            $getMenu = getAll("select * from uni_dashboard_menu where parent_id=? order by sorting asc", [0]);
            if($getMenu){
               foreach ($getMenu as $value) {
                  if($value["submenu"] == 0){
                     if($Admin->menuCheckPrivileges($value["privileges"])){
                     ?>
                     <li><a <?php if($_GET["route"] == $value["route"]){ echo 'class="active"'; } ?> href="?route=<?php echo $value["route"]; ?>" ><?php echo $value["icon"]; ?><span><?php echo $value["name"]; ?></span></a></li>
                     <?php
                     }
                  }else{
                     if($Admin->menuCheckPrivileges($value["privileges"])){
                     ?>
                        <li>
                           <a href="#dropdown<?php echo $value["id"]; ?>" <?php if(in_array( $_GET["route"], $Admin->menuItemRoutes($value["id"]) )){ ?> aria-expanded="true" <?php }else{ ?> aria-expanded="false" <?php } ?> data-toggle="collapse" ><?php echo $value["icon"]; ?><span><?php echo $value["name"]; ?></span></a>
                           <ul id="dropdown<?php echo $value["id"]; ?>" class="collapse list-unstyled pt-0 <?php if(in_array( $_GET["route"], $Admin->menuItemRoutes($value["id"]) )){ ?> show <?php } ?>" >
                              <?php
                              $getSubmenu = getAll("select * from uni_dashboard_menu where parent_id=? order by sorting asc", [$value["id"]]);
                              if($getSubmenu){
                                 foreach ($getSubmenu as $subvalue) {
                                    if(isset($_SESSION[$subvalue["privileges"]])){
                                       ?>
                                       <li><a <?php if($_GET["route"] == $subvalue["route"]){ echo 'class="active"'; } ?> href="?route=<?php echo $subvalue["route"]; ?>"><?php echo $subvalue["name"]; ?></a></li>
                                       <?php
                                    }
                                 }
                              }
                              ?>
                           </ul>
                        </li>                     
                     <?php
                     }
                  }
               }
            }
         ?>
               
      </ul>

   </nav>
</div>