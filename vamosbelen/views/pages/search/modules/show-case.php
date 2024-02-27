<div id="showcase" class="ps-shopping ps-tab-root">

    <!--=====================================
    Shoping Header
    ======================================-->

    <div class="ps-shopping__header">

        <p><strong><?php echo $total_search ?></strong> Products found</p>

        <div class="ps-shopping__actions">

            <select class="select2" data-placeholder="Sort Items" onchange="sortProducts(event)">

                <?php if ($url_params[2]) : ?>

                    <?php if ($url_params[2] == "new") : ?>

                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+low">Sort by price: low to high</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+high">Sort by price: high to low</option>

                    <?php endif ?>

                    <?php if ($url_params[2] == "latest") : ?>

                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+low">Sort by price: low to high</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+high">Sort by price: high to low</option>

                    <?php endif ?>

                    <?php if ($url_params[2] == "low") : ?>

                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+low">Sort by price: low to high</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+high">Sort by price: high to low</option>

                    <?php endif ?>

                    <?php if ($url_params[2] == "high") : ?>

                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+high">Sort by price: high to low</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                        <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+low">Sort by price: low to high</option>

                    <?php endif ?>

                <?php else : ?>

                    <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+new">Sort by new</option>
                    <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+latest">Sort by latest</option>
                    <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+low">Sort by price: low to high</option>
                    <option value="<?php echo $_SERVER['REQUEST_URI'] ?>+high">Sort by price: high to low</option>

                <?php endif ?>
            </select>

            <div class="ps-shopping__view">

                <p>View</p>

                

                <ul class="ps-tab-list">

                    <?php if(isset($_COOKIE['tab'])) : ?>

                        <?php if($_COOKIE['tab'] == "grid") : ?>

                            <li class="active" type="grid">
                                <a href="#tab-1">
                                    <i class="icon-grid"></i>
                                </a>
                            </li>

                            <li type="list">
                                <a href="#tab-2">
                                    <i class="icon-list4"></i>
                                </a>
                            </li>

                        <?php else : ?>

                            <li type="grid">
                                <a href="#tab-1">
                                    <i class="icon-grid"></i>
                                </a>
                            </li>

                            <li class="active" type="list">
                                <a href="#tab-2">
                                    <i class="icon-list4"></i>
                                </a>
                            </li>

                        <?php endif ?>

                    <?php else : ?>

                        <li class="active" type="grid">
                            <a href="#tab-1">
                                <i class="icon-grid"></i>
                            </a>
                        </li>

                        <li type="list">
                            <a href="#tab-2">
                                <i class="icon-list4"></i>
                            </a>
                        </li>

                    <?php endif ?>

                    

                </ul>

            </div>

        </div>

    </div>

    <!--=====================================
    Shoping Body
    ======================================-->

    <!-- Preload -->

    <div class="d-flex justify-content-center preload-true">
        <div class="spinner-border text-muted my-5"></div>
        </div>

    <!-- Load -->

    <div class="preload-false ps-tabs">

        <!--=====================================
    	Grid View
    	======================================-->

        <?php if (isset($_COOKIE['tab'])) : ?>
            <?php if ($_COOKIE['tab'] == "grid") : ?>

                <div class="ps-tab active" id="tab-1">

                <?php else : ?>

                    <div class="ps-tab" id="tab-1">

                    <?php endif ?>

                <?php else : ?>

                    <div class="ps-tab" id="tab-1">

                    <?php endif ?>

                    <div class="ps-shopping-product">

                        <div class="row">

                            <!--=====================================
    				        Product
    				        ======================================-->

                            <?php foreach ($url_search->result as $key => $value) : ?>

                                <div class="col-lg-2 col-md-4 col-6">

                                    <div class="ps-product">

                                        <div class="ps-product__thumbnail">

                                            <!-- Imagen del producto -->

                                            <a href="<?php echo $path . $value->url_product; ?>">
                                                <img src="img/products/<?php echo $value->url_category; ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">
                                            </a>

                                            <!-- Descuento de oferta o fuera del stock -->

                                            <?php if ($value->stock_product == 0) : ?>

                                                <div class="ps-product__badge out-stock">Out Of Stock</div>

                                            <?php else : ?>

                                                <?php if ($value->offer_product != null) : ?>

                                                    <div class="ps-product__badge">
                                                        -
                                                        <?php echo TemplateController::offer_discount(
                                                            $value->price_product,
                                                            json_decode($value->offer_product, true)[1],
                                                            json_decode($value->offer_product, true)[0]
                                                        );
                                                        ?>
                                                        %
                                                    </div>
                                                <?php endif ?>



                                            <?php endif ?>

                                            <!-- Botones de acciones -->

                                            <ul class="ps-product__actions">

                                                <li>
                                                    <a class="btn" onclick="addShoppingCart('<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc data-toggle="tooltip" data-placement="top" title="Add to Cart">
                                                        <i class="icon-bag2"></i>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="<?php echo $path . $value->url_product ?>" data-toggle="tooltip" data-placement="top" title="Quick View">
                                                        <i class="icon-eye"></i>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="btn" onclick="addWishlist('<?php echo $value->url_product ?>', '<?php echo CurlController::api(); ?>')" data-toggle="tooltip" data-placement="top" title="Add to Whishlist">
                                                        <i class="icon-heart"></i>
                                                    </a>
                                                </li>

                                            </ul>

                                        </div>

                                        <div class="ps-product__container">

                                            <!-- Nombre de la tienda -->

                                            <a class="ps-product__vendor" href="<?php echo $path . $value->url_store ?>"><?php echo $value->name_store ?></a>

                                            <div class="ps-product__content">

                                                <!-- Nombre del producto -->

                                                <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>">
                                                    <?php echo $value->name_product ?></a>

                                                <!-- Reseñas del producto -->

                                                <div class="ps-product__rating">

                                                    <?php
                                                    $reviews = TemplateController::average_reviews(json_decode($value->reviews_product, true));
                                                    ?>

                                                    <select class="ps-rating" data-read-only="true">

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

                                                    <span>
                                                        (
                                                        <?php
                                                        if ($value->reviews_product != null) {
                                                            echo count(json_decode($value->reviews_product, true));
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                        )
                                                    </span>

                                                </div>

                                                <?php if ($value->offer_product != null) : ?>

                                                    <!-- El precio en oferta del producto -->

                                                    <p class="ps-product__price sale">
                                                        $
                                                        <?php
                                                        echo TemplateController::offer_price(
                                                            $value->price_product,
                                                            json_decode($value->offer_product, true)[1],
                                                            json_decode($value->offer_product, true)[0]
                                                        );
                                                        ?>

                                                        <del>$<?php echo $value->price_product ?></del>
                                                    </p>

                                                <?php else : ?>

                                                    <p class="ps-product__price">$<?php echo $value->price_product ?></p>

                                                <?php endif ?>

                                            </div>

                                            <div class="ps-product__content hover">

                                                <!-- Nombre del producto -->

                                                <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>">
                                                    <?php echo $value->name_product ?></a>

                                                <?php if ($value->offer_product != null) : ?>

                                                    <!-- El precio en oferta del producto -->

                                                    <p class="ps-product__price sale">
                                                        $
                                                        <?php
                                                        echo TemplateController::offer_price(
                                                            $value->price_product,
                                                            json_decode($value->offer_product, true)[1],
                                                            json_decode($value->offer_product, true)[0]
                                                        );
                                                        ?>

                                                        <del>$<?php echo $value->price_product ?></del>
                                                    </p>

                                                <?php else : ?>

                                                    <p class="ps-product__price">$<?php echo $value->price_product ?></p>

                                                <?php endif ?>

                                            </div>

                                        </div>

                                    </div>

                                </div><!-- End Product -->

                            <?php endforeach ?>

                        </div>

                    </div>

                    <div class="ps-pagination">

                        <?php

                        if (isset($url_params[1])) {
                            $current_page = $url_params[1];
                        } else {
                            $current_page = 1;
                        }

                        ?>

                        <ul class="pagination" data-total-pages="<?php echo ceil($total_search / 6) ?>" data-current-page="<?php echo $current_page ?>" data-url-page="<?php echo $_SERVER['REQUEST_URI']; ?>">

                        </ul>
                    </div>

                    </div><!-- End Grid View-->

        <!--=====================================
    	List View
    	======================================-->
    
        <?php if (isset($_COOKIE['tab'])) : ?>
            <?php if ($_COOKIE['tab'] == "list") : ?>

                <div class="ps-tab active" id="tab-2">

                <?php else : ?>

                    <div class="ps-tab" id="tab-2">

                    <?php endif ?>

                <?php else : ?>

                    <div class="ps-tab" id="tab-2">

                    <?php endif ?>

                        <div class="ps-shopping-product">

                            <!--=====================================
    			            Product
    			            ======================================-->

                            <?php foreach ($url_search->result as $key => $value) : ?>

                                <div class="ps-product ps-product--wide">

                                    <div class="ps-product__thumbnail">

                                        <!-- Imagen del producto -->

                                        <a href="<?php echo $path . $value->url_product; ?>">
                                            <img src="img/products/<?php echo $value->url_category; ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">
                                        </a>

                                    </div>

                                    <div class="ps-product__container">

                                        <div class="ps-product__content">

                                            <!-- Nombre del producto -->

                                            <a class="ps-product__title" href="<?php echo $path . $value->url_product ?>">
                                                <?php echo $value->name_product ?></a>


                                            <!-- Nombre de la tienda -->

                                            <p class="ps-product__vendor">
                                                Sold by: <a href="<?php echo $path . $value->url_store ?>"><?php echo $value->name_store ?></a>
                                            </p>

                                            <!-- Reseñas del producto -->

                                            <div class="ps-product__rating">

                                                <?php
                                                $reviews = TemplateController::average_reviews(json_decode($value->reviews_product, true));
                                                ?>

                                                <select class="ps-rating" data-read-only="true">

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

                                                <span>
                                                    (
                                                    <?php
                                                    if ($value->reviews_product != null) {
                                                        echo count(json_decode($value->reviews_product, true));
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>
                                                    )
                                                </span>

                                            </div>

                                            <!-- Resumen del producto -->

                                            <ul class="ps-product__desc">

                                                <?php
                                                $list_summary = json_decode($value->summary_product, true);
                                                ?>

                                                <?php foreach ($list_summary as $key => $summary) : ?>

                                                    <li><?php echo $summary; ?></li>

                                                <?php endforeach ?>
                                            </ul>

                                        </div>

                                        <!-- El precio en oferta del producto -->

                                        <div class="ps-product__shopping">

                                            <?php if ($value->offer_product != null) : ?>

                                                <p class="ps-product__price sale">
                                                    $
                                                    <?php
                                                    echo TemplateController::offer_price(
                                                        $value->price_product,
                                                        json_decode($value->offer_product, true)[1],
                                                        json_decode($value->offer_product, true)[0]
                                                    );
                                                    ?>

                                                    <del>$<?php echo $value->price_product ?></del>
                                                </p>

                                            <?php else : ?>

                                                <p class="ps-product__price">$<?php echo $value->price_product ?></p>

                                            <?php endif ?>

                                            <a class="ps-btn btn" onclick="addShoppingCart('<?php echo $value->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc>Add to cart</a>

                                            <ul class="ps-product__actions">
                                                <li><a href="<?php echo $path . $value->url_product ?>"><i class="icon-eye"></i>View</a></li>
                                                <li><a class="btn" onclick="addWishlist('<?php echo $value->url_product ?>', '<?php echo CurlController::api(); ?>')"><i class="icon-heart"></i> Wishlist</a></li>
                                            </ul>

                                        </div>

                                    </div>

                                </div> <!-- End Product -->

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

                            <ul class="pagination" data-total-pages="<?php echo ceil($total_search / 6) ?>" data-current-page="<?php echo $current_page ?>" data-url-page="<?php echo $_SERVER['REQUEST_URI']; ?>">

                            </ul>
                        </div>

        </div><!-- End List View-->

    </div>

</div>