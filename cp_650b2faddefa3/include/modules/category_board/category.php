<?php if( !defined('unisitecms') ) exit;

include("fn.php");

?> 

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Категории</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<a  href="?route=add_category_board" class="btn btn-gradient-04 mr-1 mb-2">Добавить категорию</a>
<a  href="?route=filters" class="btn btn-gradient-02 mr-1 mb-2">Фильтры</a>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">
                 
                 <?php if( getOne("select * from uni_category_board") ){ ?>
                 <table class="table mb-0">
                    <thead>
                       <tr>
                        <th>Название</th>
                        <th>Платная категория</th>
                        <th>Безопасная сделка</th>
                        <?php if($settings["functionality"]["auction"]){ ?>
                        <th>Аукцион</th>
                        <?php } ?>
                        <?php if($settings["functionality"]["marketplace"]){ ?>
                        <th>Маркетплейс</th>
                        <?php } ?>
                        <?php if($settings["functionality"]["booking"]){ ?>
                        <th>Бронирование/Аренда</th>
                        <?php } ?>                                                
                        <th>На главной</th>
                        <th style="text-align: right;" ></th>
                       </tr>
                    </thead>
                    <tbody class="sort-container" >

                         <?php echo outCategory(); ?>

                    </tbody>
                </table>
                <?php }else{ ?>

                      <div class="plug" >
                         <i class="la la-exclamation-triangle"></i>
                         <p>Категорий нет</p>
                      </div>

                <?php } ?>

            </div>
         </div>
      </div>
   </div>
</div>
      
 <script type="text/javascript" src="include/modules/category_board/script.js"></script>