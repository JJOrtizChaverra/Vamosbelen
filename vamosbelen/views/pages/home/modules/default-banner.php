<?php

// Traer 2 productos aleatoriamente

$count = 0;

foreach ($products_hslider as $key => $value) {

    if ($value->default_banner_product != null) {
        $count++;
    }
}

$products_default_banner = array_slice($products_hslider, 0, 2);

?>

<?php if ($count > 1) : ?>

    <!-- Preload -->

    <div class="d-flex justify-content-center preload-true">
        <div class="spinner-border text-muted my-5"></div>
    </div>

    <!-- Load -->


    <div class="ps-promotions preload-false">


        <div class="container">

            <div class="row">


                <?php foreach ($products_default_banner as $key => $value) : ?>



                    <div class="col-md-6 col-12 ">
                        <a class="ps-collection" href="<?php echo $path . $value->url_category ?>">
                            <img src="img/products/<?php echo $value->url_category ?>/default/<?php echo $value->default_banner_product ?>" alt="<?php echo $value->name_product ?>">
                        </a>
                    </div>


                <?php endforeach ?>

            </div>

        </div>

    </div><!-- End Home Promotions-->

<?php endif ?>