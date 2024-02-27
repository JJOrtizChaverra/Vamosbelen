<?php

$select = "id_store,name_store,url_store,logo_store,cover_store,about_store,abstract_store,email_store,country_store,city_store,address_store,phone_store,socialnetwork_store,reviews_product";

$url = CurlController::api() . "relations?rel=products,stores&type=product,store&linkTo=url_store&equalTo={$url_params[0]}&select=$select";
$method = "GET";
$fields = array();
$header = array();

$store = CurlController::request($url, $method, $fields, $header)->result;

$reviews = 0;
$total_reviews = 0;

?>

<div class="ps-section__left">

    <div class="ps-block--vendor">

        <div class="ps-block__thumbnail">

            <!-- Imagen de la tienda -->
            <img src="img/stores/<?php echo $store[0]->url_store; ?>/<?php echo $store[0]->logo_store; ?>" alt="<?php echo $store[0]->name_store; ?>">

        </div>

        <div class="ps-block__container">

            <div class="ps-block__header">

                <!-- Nombre de la tienda -->
                <h4><?php echo $store[0]->name_store; ?></h4>

                <div class="br-wrapper br-theme-fontawesome-stars">

                    <!-- Reviews de la tienda -->
                    <?php

                    foreach ($store as $key => $item) {

                        if ($item->reviews_product != null) {
                            foreach (json_decode($item->reviews_product, true) as $key => $value) {
                                $reviews += $value['review'];
                                $total_reviews++;
                            }
                        }
                    }

                    if ($reviews > 0 && $total_reviews > 0) {
                        $reviews = round($reviews / $total_reviews);
                    }

                    ?>

                    <select class="ps-rating" data-read-only="true" style="display: none;">

                        <?php

                        if ($reviews > 0) {
                            for ($i = 0; $i < 5; $i++) {
                                if ($reviews < ($i + 1)) {
                                    echo '<option value="2">' . ($i + 1) . '</option>';
                                } else {
                                    echo '<option value="1">' . ($i + 1) . '</option>';
                                }
                            }
                        } else {
                            echo '<option value="0">0</option>';
                            for ($i = 0; $i < 5; $i++) {
                                echo '<option value="1">' . ($i + 1) . '</option>';
                            }
                        }

                        ?>

                    </select>

                </div>

                <p><strong><?php echo (($reviews * 100) / 5) ?>% Positive</strong> (<?php echo $total_reviews; ?> rating)</p>

            </div><span class="ps-block__divider"></span>

            <div class="ps-block__content">

                <!-- Abstract store -->
                <p><strong><?php echo $store[0]->name_store; ?></strong>, <?php echo $store[0]->abstract_store; ?></p>

                <span class="ps-block__divider"></span>

                <!-- Direccion de la tienda -->
                <p><strong>Address:</strong> <?php echo $store[0]->address_store; ?></p>

                <!-- Redes sociales -->

                <?php if ($store[0]->socialnetwork_store != null) : ?>

                    <figure>

                        <figcaption>Follow us on social</figcaption>

                        <ul class="ps-list--social-color">

                            <?php foreach (json_decode($store[0]->socialnetwork_store, true) as $key => $socialnetwork) : ?>

                                <li>
                                    <a class="<?php echo array_keys($socialnetwork)[0]; ?>" href="<?php echo $socialnetwork[array_keys($socialnetwork)[0]]; ?>">
                                        <i class="fab fa-<?php echo array_keys($socialnetwork)[0]; ?>"></i></a>
                                </li>

                            <?php endforeach ?>

                        </ul>

                    </figure>

                <?php endif ?>

            </div>

            <div class="ps-block__footer">

                <!-- Telefono de la tienda -->
                <p>Call us directly<strong><small><?php echo explode("_", $store[0]->phone_store)[0] . " " . explode("_", $store[0]->phone_store)[1]; ?></small></strong></p>

                <!-- Email de la tienda -->
                <p>or Or if you have any question <strong><small><?php echo $store[0]->email_store; ?></small></strong></p>

            </div>

        </div>

    </div>

</div><!-- End Vendor Profile -->