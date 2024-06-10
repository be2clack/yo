<div class="tab-pane fade <?php if($tab == "languages"){ echo 'active show'; } ?>" id="tab-languages" role="tabpanel" aria-labelledby="tab-languages">

<div class="form-group">
    <a  href="#" data-toggle="modal" data-target="#modal-add-lang" class="btn btn-gradient-04 mr-1 mb-2">Добавить язык</a>
</div>

<div class="table-responsive">

     <?php
        $get = getAll("SELECT * FROM uni_languages order by id_position asc");     

         if(count($get) > 0){   

         ?>
         <table class="table mb-0">
            <thead>
               <tr>
                <th></th>
                <th>Язык</th>
                <th>iso</th>
                <th>Статус</th>
                <th style="text-align: right;" ></th>
               </tr>
            </thead>
            <tbody class="sort-container" >                     
         <?php

            foreach($get AS $array_data){

            ?>

             <tr id="item<?php echo $array_data["id"]; ?>" >
                 <td><span class="icon-move move-sort" ><i class="la la-arrows-v"></i></span></td>
                 <td>
                  
                  <div class="float-flex" >  
                    <div class="adaptive-box-icon" >
                      <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" >
                    </div>

                    <div class="adaptive-box-name" >
                    <?php echo $array_data["name"]; ?>
                    </div>
                  </div>
                        
                </td>
                 <td><?php echo $array_data["iso"]; ?></td>
                 <td>
                   <?php if($array_data["status"]){ ?>
                    <span class="badge-text badge-text-small info">Виден</span>
                   <?php }else{ ?>
                    <span class="badge-text badge-text-small danger">Скрыт</span>
                   <?php } ?>
                 </td> 
                 <td style="text-align: right;" class="td-actions" >
                  <a class="load_edit_lang" data-id="<?php echo $array_data["id"]; ?>" ><i class="la la-edit edit"></i></a>
                  <a class="delete-lang" data-id="<?php echo $array_data["id"]; ?>" ><i class="la la-close delete"></i></a>
                 </td>                          
             </tr> 
     
           
             <?php                                         
            } 

            ?>

               </tbody>
            </table>

            <?php               
         }else{
             
             ?>
                <div class="plug" >
                   <i class="la la-exclamation-triangle"></i>
                   <p>Языков нет</p>
                </div>
             <?php

         }                  
      ?>

</div>

</div>

