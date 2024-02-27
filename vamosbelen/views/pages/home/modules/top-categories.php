<?php

// Traer las 6 categories con mas visitas

$select = "image_category,name_category,url_category,id_category";

$url = CurlController::api() . "categories?orderBy=views_category&orderMode=DESC&startAt=0&endAt=6&select=$select";
$method = "GET";
$fields = array();
$header = array();

$top_categories = CurlController::request($url, $method, $fields, $header)->result;

?>

<div class="ps-top-categories">

    <div class="container">

        <h3>Top categories of the month</h3>

        <!-- Preload -->

        <div class="d-flex justify-content-center preload-true">
            <div class="spinner-border text-muted my-5"></div>
        </div>

        <!-- Load -->

        <div class="row preload-false">

            <?php foreach ($top_categories as $key => $value) : ?>

                <div class="col-xl-2 col-lg-3 col-sm-4 col-6 ">
                    <div class="ps-block--category">
                        <a class="ps-block__overlay" href="<?php echo $path . $value->url_category ?>"></a>
                        <img src="img/categories/<?php echo $value->image_category ?>" alt="<?php echo $value->name_category ?>">
                        <p><?php echo $value->name_category ?></p>
                    </div>
                </div>

            <?php endforeach ?>

        </div>
    </div>

</div><!-- End Top Categories -->