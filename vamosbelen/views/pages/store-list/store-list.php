<?php

// Validar si hay parametros de paginacion

if (isset($url_params[1])) {
    if (is_numeric($url_params[1])) {
        $start_at = ($url_params[1] * 6) - 6;
    } else {
        echo "
        <script>
            window.location = '$path$url_params[0]';
        </script>
        ";
    }
} else {
    $start_at = 0;
}

// Validar si hay parametros de orden

if (isset($url_params[2])) {
    if (is_string($url_params[2])) {
        if ($url_params[2] == "new") {
            $order_by = "id_store";
            $order_mode = "DESC";
        } else if ($url_params[2] == "latest") {
            $order_by = "id_store";
            $order_mode = "ASC";
        } else {
            echo "
            <script>
                window.location = '$path$url_params[0]';
            </script>
            ";
        }
    } else {
        echo "
        <script>
            window.location = '$path$url_params[0]';
        </script>
        ";
    }
} else {
    $order_by = "id_store";
    $order_mode = "DESC";
}

// Traer el total de tiendas
$end_at = 6;
$select = "cover_store,logo_store,url_store,name_store,address_store,email_store,phone_store,country_store,city_store,socialnetwork_store,id_store";

$url = CurlController::api() . "stores?orderBy=$order_by&orderMode=$order_mode&startAt=$start_at&endAt=$end_at&select=$select";
$method = "GET";
$fields = array();
$header = array();

$stores = CurlController::request($url, $method, $fields, $header);

?>

<!--=====================================
Breadcrumb
======================================-->

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <li>Store List</li>

        </ul>

    </div>

</div>

<!--=====================================
Store List
======================================-->

<section class="ps-store-list">

    <div class="container">

        <div class="ps-section__header">

            <h3>Store list</h3>

        </div>

        <div class="ps-section__wrapper" data-select2-id="14">

            <div class="ps-section__center" data-select2-id="33">

                <section class="ps-store-box" data-select2-id="32">

                    <?php if ($stores->status == 200) : ?>

                        <?php

                        $select = "id_store";
                        $url = CurlController::api() . "stores?select=$select";

                        $total_stores = CurlController::request($url, $method, $fields, $header)->total;

                        ?>


                        <div class="ps-section__header">

                            <p>Showing 1 - <?php echo $end_at; ?> of <?php echo $total_stores; ?> results</p>

                            <select class="select2 w-25" data-placeholder="Sort Items" onchange="sortProducts(event)">

                                <?php if ($url_params[2]) : ?>

                                    <?php if ($url_params[2] == "new") : ?>

                                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                                    <?php endif ?>

                                    <?php if ($url_params[2] == "latest") : ?>

                                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                                    <?php endif ?>

                                <?php else : ?>

                                    <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                                    <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                                <?php endif ?>
                            </select>

                        </div>

                        <div class="ps-section__content">

                            <div class="row">

                                <?php foreach ($stores->result as $key_store => $store) : ?>

                                    <div class="col-lg-4 col-md-6 col-12">

                                        <article class="ps-block--store">

                                            <!-- Portada de la tienda -->
                                            <div class="ps-block__thumbnail bg--cover" style="background: url(img/stores/<?php echo $store->url_store; ?>/<?php echo $store->cover_store; ?>);"></div>

                                            <div class="ps-block__content">

                                                <div class="ps-block__author">

                                                    <!-- Logo de la tienda -->
                                                    <a class="ps-block__user" href="<?php echo $path . $store->url_store; ?>">

                                                        <img src="img/stores/<?php echo $store->url_store; ?>/<?php echo $store->logo_store; ?>" alt="<?php echo $store->name_store; ?>"></a><a class="ps-btn" href="<?php echo $path . $store->url_store; ?>">Visit Store

                                                    </a>
                                                </div>

                                                <!-- Nombre de la tienda -->
                                                <h4><?php echo $store->name_store; ?></h4>

                                                <div class="br-wrapper br-theme-fontawesome-stars">


                                                    <!-- Sacando las calificaciones de cada tienda -->
                                                    <?php

                                                    $select = "reviews_product";

                                                    $url = CurlController::api() . "products?linkTo=id_store_product&equalTo=$store->id_store&select=$select";
                                                    $data_reviews = CurlController::request($url, $method, $fields, $header);

                                                    $reviews = 0;
                                                    $total_reviews = 0;

                                                    if ($data_reviews->status == 200) {

                                                        foreach ($data_reviews->result as $index => $item) {

                                                            if ($item->reviews_product != null) {
                                                                foreach (json_decode($item->reviews_product, true) as $key => $value) {
                                                                    $reviews += $value['review'];
                                                                    $total_reviews++;
                                                                }
                                                            }
                                                        }

                                                        if ($reviews > 0 && $total_reviews > 0) {

                                                            $reviews = round($reviews / $total_reviews);
                                                        } else {
                                                            $reviews = 0;
                                                            $total_reviews = 0;
                                                        }
                                                    }

                                                    ?>

                                                    <!-- Calificacion de la tienda -->
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

                                                <!-- Direccion, pais y ciudad de la tienda -->
                                                <p><?php echo $store->address_store; ?> | <?php echo $store->city_store; ?> | <?php echo $store->country_store; ?></p>

                                                <ul class="ps-block__contact">

                                                    <li>
                                                        <i class="icon-envelope"></i>
                                                        <a href="mailto:<?php echo $store->email_store ?>"><?php echo $store->email_store ?></a>
                                                    </li>

                                                    <li>
                                                        <i class="icon-telephone"></i> <?php echo explode("_", $store->phone_store)[0] . " " . explode("_", $store->phone_store)[1]; ?>
                                                    </li>

                                                </ul>

                                                <!-- Redes sociales de la tienda -->
                                                <?php if ($store->socialnetwork_store != null) : ?>

                                                    <figure>

                                                        <ul class="ps-list--social-color">

                                                            <?php foreach (json_decode($store->socialnetwork_store, true) as $key_socialnetwork => $socialnetwork) : ?>

                                                                <li>
                                                                    <a class="<?php echo array_keys($socialnetwork)[0]; ?>" href="<?php echo $socialnetwork[array_keys($socialnetwork)[0]]; ?>" style="border-radius: 20px;">
                                                                        <i class="fab fa-<?php echo array_keys($socialnetwork)[0]; ?>"></i></a>
                                                                </li>

                                                            <?php endforeach ?>

                                                        </ul>

                                                    </figure>

                                                <?php endif ?>

                                            </div>

                                        </article>

                                    </div>

                                <?php endforeach ?>

                            </div>
                            <div class="ps-pagination">

                                <?php

                                if (isset($url_params[1])) {
                                    $current_page = $url_params[1];
                                } else {
                                    $current_page = 1;
                                }

                                ?>

                                <ul class="pagination" data-total-pages="<?php echo ceil($total_stores / $end_at) ?>" data-current-page="<?php echo $current_page ?>" data-url-page="<?php echo $_SERVER['REQUEST_URI']; ?>">

                                </ul>
                            </div>
                        </div>
                    <?php else : ?>

                        <h1 class="text-center">Aún no se registran tiendas</h1>

                    <?php endif ?>

                </section>
            </div>
        </div>
    </div>
</section>