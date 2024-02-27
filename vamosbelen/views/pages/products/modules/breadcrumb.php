<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <?php if(!empty($showcase_products[0]->name_category)) : ?>
                
                <li><?php echo $showcase_products[0]->name_category ?></li>

            <?php else : ?>

                <li><?php echo $showcase_products[0]->name_subcategory ?></li>

            <?php endif ?>

        </ul>

    </div>

</div>