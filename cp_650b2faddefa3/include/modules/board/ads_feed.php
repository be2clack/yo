<?php 
if( !defined('unisitecms') ) exit;

$Ads = new Ads();
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Фид объявлений</h2>
      </div>
   </div>
</div>  

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">

              <p>
                Фид объявлений позволяет выгрузить все объявления в формате json, данную ссылку вы можете использовать в своих проектах для парсинга объявлений.
              </p> 

              <h4 style="color: black; margin: 15px 0; text-align: left;" > <strong>Ссылка</strong> </h4>

              <textarea class="form-control" ><?php echo $config["urlPath"]; ?>/<?php echo $config["folder_admin"]; ?>/include/modules/board/feed.php?key=<?php echo $config["feed_ads_key"]; ?>&display_date=all&status=1&page=1&output=50</textarea>
              
              <h4 style="color: black; margin: 15px 0; text-align: left;" > <strong>Описание параметров</strong> </h4>

              <div class="table-responsive">
                    
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Параметр</th>
                            <th>Описание</th>
                            <th>Значения</th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >

                         <tr>
                             <td>key</td>                          
                             <td>Уникальный ключ для получения данных</td>                          
                             <td><?php echo $config["feed_ads_key"]; ?></td>                          
                         </tr> 

                         <tr>
                             <td>page</td>                          
                             <td>Номер страницы</td>                          
                             <td>По умолчанию 1</td>                          
                         </tr>

                         <tr>
                             <td>output</td>                          
                             <td>Кол-во выводимых объявлений</td>                          
                             <td>По умолчанию 50</td>                          
                         </tr>                         

                         <tr>
                             <td>display_date</td>                          
                             <td>Фильтр по дате</td>                          
                             <td>
                                <strong style="color: black;" >all</strong> - все объявления<br>
                                <strong style="color: black;" >now</strong> - Объявления за сегодняшний день<br>
                                <strong style="color: black;" >hour</strong> - Объявления за последний час                               
                             </td>                          
                         </tr>

                         <tr>
                             <td>status</td>                          
                             <td>Статус объявлений</td>                          
                             <td>
                               <strong style="color: black;" >all</strong> - объявления со всеми статусами<br>
                               <?php  
                                foreach ($Ads->arrayStatus() as $key => $value) {
                                   ?>
                                   <strong style="color: black;" ><?php echo $key; ?></strong> - <?php echo $value; ?><br>
                                   <?php
                                }
                               ?>
                             </td>                          
                         </tr>

                        </tbody>
                      </table> 

              </div>

         </div>

      </div>
   </div>
</div>
 