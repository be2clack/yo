<div class="sidebar-filter" >
    <form class="form-filter" >

        <?php echo $Filters->outFormFilters('catalog',['data'=>$data, 'categories'=>$getCategoryBoard]); ?>

    </form>
</div>

<?php echo $Banners->out( ["position_name"=>"catalog_sidebar", "current_id_cat"=>$data["category"]["category_board_id"], "categories"=>$getCategoryBoard] ); ?>