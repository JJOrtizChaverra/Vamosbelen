<?php

// Validar si existe una variable de session llamada user
if (!isset($_SESSION['user'])) {
    echo '
    <script>
        sweetAlert("error", "You must be logged in to access this page", "' . $path . 'account&login#header");
    </script>';
    return;
} else {
    $time = time();

    if ($_SESSION['user']->token_exp_user < $time) {
        echo '<script>
                sweetAlert("error", "Error: token has expired, please login again", "' . $path . 'account&logout");
        </script>';

        return;
    } else {

        $select = "url_product,url_category,name_product,image_product,price_product,offer_product,stock_product";
        $products_wishlist = array();

        // Traer la lista de deseos
        foreach ($wishlist as $key => $value) {
            $url = CurlController::api() . "relations?rel=products,categories&type=product,category&linkTo=url_product&equalTo=$value&select=$select";
            $method = "GET";
            $fields = array();
            $header = array();

            $response = CurlController::request($url, $method, $fields, $header)->result[0];

            array_push($products_wishlist, $response);
        }
    }
}

?>

<div class="ps-vendor-dashboard pro">

    <div class="container">

        <div class="ps-section__header">

            <!--=====================================
                Profile
                ======================================-->

            <?php include "views/pages/account/profile/profile.php"; ?>

            <!--=====================================
                Nav Account
                ======================================-->

            <div class="ps-section__content">

                <ul class="ps-section__links">
                    <li class="active"><a href="<?php echo $path ?>account&wishlist#profile-user">My Wishlist</a></li>
                    <li><a href="<?php echo $path ?>account&my-shopping#profile-user">My Shopping</a></li>
                    <li><a href="<?php echo $path ?>account&my-store#profile-user">My Store</a></li>
                    <li><a href="<?php echo $path ?>account&my-sales#profile-user">My Sales</a></li>
                </ul>

                <!--=====================================
                    Wishlist
                    ======================================-->

                <div class="table-responsive">

                    <table class="table ps-table--whishlist dt-responsive dt-client">

                        <thead>

                            <tr>

                                <th>Product name</th>

                                <th>Unit Price</th>

                                <th>Stock Status</th>

                                <th></th>

                                <th></th>

                            </tr>

                        </thead>

                        <tbody>

                            <!-- Product -->

                            <?php foreach ($products_wishlist as $key => $value) : ?>

                                <tr>

                                    <!-- Imagen y nombre del producto -->
                                    <td>
                                        <div class="ps-product--cart">
                                            <div class="ps-product__thumbnail">
                                                <a href="<?php echo $path . $value->url_product ?>">
                                                    <img src="img/products/<?php echo $value->url_category ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">
                                                </a>
                                            </div>
                                            <div class="ps-product__content">
                                                <a href="<?php echo $path . $value->url_product ?>"><?php echo $value->name_product ?> </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="ps-product">
                                        <?php if ($value->offer_product != null) : ?>

                                            <!-- El precio en oferta del producto -->

                                            <h4 class="ps-product__price sale">
                                                $
                                                <?php
                                                echo TemplateController::offer_price(
                                                    $value->price_product,
                                                    json_decode($value->offer_product, true)[1],
                                                    json_decode($value->offer_product, true)[0]
                                                );
                                                ?>

                                                <br />

                                                <del>$<?php echo $value->price_product ?></del>
                                            </h4>

                                        <?php else : ?>

                                            <h4 class="ps-product__price">$<?php echo $value->price_product ?></h4>

                                        <?php endif ?>
                                    </td>

                                    <td>
                                        <?php if ($value->stock_product == 0) : ?>

                                            <span class="ps-product__badge out-stock font-weight-bold">Out Of Stock</span>

                                        <?php else : ?>

                                            <span class="ps-tag ps-tag--in-stock">In Stock</span>

                                        <?php endif ?>
                                    </td>

                                    <td><a class="ps-btn" onclick="addShoppingCart('<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc>Add to cart</a></td>

                                    <td><a class="btn" onclick="removeWishlist('<?php echo $value->url_product ?>', '<?php echo CurlController::api(); ?>', '<?php echo $path; ?>')"><i class="icon-cross"></i></a></td>

                                </tr>

                            <?php endforeach ?>

                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </div>

</div>