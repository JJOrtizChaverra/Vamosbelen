<?php
$random_id = rand(1, $total_products);

$url = CurlController::api() . "relations?rel=products,categories&type=product,category&linkTo=id_product&equalTo=$random_id&select=url_category,top_banner_product,url_product";
$method = "GET";
$fields = array();
$header = array();

$random_product = CurlController::request($url, $method, $fields, $header)->result[0];

if ($random_product != "N") : ?>

    <?php $top_banner = json_decode((CurlController::request($url, $method, $fields, $header)->result[0]->top_banner_product), true); ?>

    <?php if ($top_banner != null) : ?>

        <div class="ps-block--promotion-header bg--cover" style="background: url(img/products/<?php echo $random_product->url_category; ?>/top/<?php echo $top_banner['IMG tag'] ?>);">
            <div class="container">
                <div class="ps-block__left">
                    <h3><?php echo $top_banner['H3 tag']; ?></h3>
                    <figure>
                        <p><?php echo $top_banner['P1 tag']; ?></p>
                        <h4><?php echo $top_banner['H4 tag']; ?></h4>
                    </figure>
                </div>
                <div class="ps-block__center">
                    <p><?php echo $top_banner['P2 tag']; ?><span><?php echo $top_banner['Span tag']; ?></span></p>
                </div><a class="ps-btn ps-btn--sm" href="<?php echo $path . $random_product->url_product ?>"><?php echo $top_banner['Button tag']; ?></a>
            </div>
        </div>

    <?php endif ?>

<?php endif ?>