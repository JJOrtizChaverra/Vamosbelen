<div class="ps-product__info">

    <h1><?php echo $product->name_product ?></h1>

    <div class="ps-product__meta">

        <div class="ps-product__rating">

            <?php
            $reviews = TemplateController::average_reviews(json_decode($product->reviews_product, true));
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
                if ($product->reviews_product != null) {
                    echo count(json_decode($product->reviews_product, true));
                } else {
                    echo 0;
                }
                ?>
                )
            </span>

        </div>

    </div>

    <!-- El precio en oferta del producto -->

    <?php if ($product->offer_product != null) : ?>

        <h4 class="ps-product__price sale">
            $
            <?php
            echo TemplateController::offer_price(
                $product->price_product,
                json_decode($product->offer_product, true)[1],
                json_decode($product->offer_product, true)[0]
            );
            ?>

            <del>$<?php echo $product->price_product; ?></del>
        </h4>

    <?php else : ?>

        <h4 class="ps-product__price">$<?php echo $product->price_product; ?></h4>

    <?php endif ?>

    <div class="ps-product__desc">

        <!-- Nombre de la tienda -->

        <p>

            Sold By:<a class="mr-20" href="<?php echo $path . $product->url_store ?>"><strong> <?php echo $product->name_store ?></strong></a>

            <!-- Preguntar si tiene stock -->

            <?php if ($product->stock_product > 0) : ?>

                Status:<strong class="ps-tag--in-stock"> In stock</strong>

            <?php else : ?>

                Status:<a href=""><strong class="ps-tag--out-stock"> Out Of stock</strong></a>

            <?php endif ?>

        </p>

        <!-- Resumen del producto -->

        <ul class="ps-list--dot">

            <?php
            $list_summary = json_decode($product->summary_product, true);
            ?>

            <?php foreach ($list_summary as $key => $summary) : ?>

                <li><?php echo $summary; ?></li>

            <?php endforeach ?>

        </ul>

        <?php if ($product->video_product != null) :
            $video = json_decode($product->video_product, true);
        ?>

            <?php if ($video[0] == "youtube") : ?>

                <iframe class="mb-3" src="https://www.youtube.com/embed/<?php echo $video[1] ?>?rel=0&autoplay=0" frameborder="0" height="360" allowfullscreen></iframe>

            <?php else : ?>

                <iframe class="mb-3" src="https://player.vimeo.com/video/<?php echo $video[1] ?>" frameborder="0" height="360" allowfullscreen></iframe>


            <?php endif ?>

        <?php endif ?>

    </div>

    <div class="ps-product__variations">

        <!-- Especificaciones del producto -->

        <?php if ($product->specifications_product != null) : ?>


            <?php
            $specifications = json_decode($product->specifications_product, true);
            ?>

            <?php if ($specifications != null) : ?>

                <?php foreach ($specifications as $key => $spec) : ?>

                    <?php if (array_keys($spec)[0] != null) : ?>

                        <figure>

                            <figcaption><?php echo array_keys($spec)[0] ?> <strong> Choose an option</strong></figcaption>

                            <?php foreach ($spec as $key => $spec2) : ?>

                                <?php foreach ($spec2 as $key => $value) : ?>

                                    <?php if (array_keys($spec)[0] == "Color") : ?>

                                        <div class="ps-variant round-circle mr-3 details <?php echo array_keys($spec)[0]; ?>" detail-type="<?php echo array_keys($spec)[0]; ?>" detail-value="<?php echo $value; ?>" style="background-color: <?php echo $value ?>; 
                                                width: 30px; 
                                                height: 30px; 
                                                cursor: pointer; 
                                                border: 1px solid #bbb;">
                                            <span class="ps-variant__tooltip"><?php echo $value ?></span>
                                        </div>

                                    <?php else : ?>

                                        <div class="ps-variant ps-variant--size details <?php echo array_keys($spec)[0]; ?>" detail-type="<?php echo array_keys($spec)[0]; ?>" detail-value="<?php echo $value; ?>">
                                            <span class="ps-variant__tooltip"><?php echo substr($value, 0, 3) ?></span>
                                            <span class="ps-variant__size"><?php echo substr($value, 0, 3) ?></span>
                                        </div>

                                    <?php endif ?>

                                <?php endforeach ?>
                            <?php endforeach ?>

                        <?php endif ?>
                        </figure>

                    <?php endforeach ?>

                <?php endif ?>
            <?php endif ?>
    </div>

    <!-- Validar ofertas del producto -->

    <?php $today = date("Y-m-d");
    if ($product->offer_product != null & $product->stock_product > 0 && $today < date(json_decode($product->offer_product, true)[2])) : ?>

        <div class="ps-product__countdown">

            <figure>

                <figcaption> Don't Miss Out! This promotion will expires in</figcaption>

                <ul class="ps-countdown" data-time="<?php echo date(json_decode($product->offer_product, true)[2]); ?>">

                    <li><span class="days"></span>
                        <p>Days</p>
                    </li>

                    <li><span class="hours"></span>
                        <p>Hours</p>
                    </li>

                    <li><span class="minutes"></span>
                        <p>Minutes</p>
                    </li>

                    <li><span class="seconds"></span>
                        <p>Seconds</p>
                    </li>

                </ul>

            </figure>

            <figure>

                <figcaption>Sold Items</figcaption>

                <div class="ps-product__progress-bar ps-progress" data-value="<?php echo $product->stock_product ?>">

                    <div class="ps-progress__value"><span></span></div>

                    <p><b><?php echo $product->stock_product; ?>/100</b> Sold</p>

                </div>

            </figure>

        </div>
    <?php endif ?>

    <div class="ps-product__shopping">

        <!-- Controles para modificar la cantidad de compra del producto -->

        <figure>

            <figcaption>Quantity</figcaption>

            <div class="form-group--number quantity">

                <button class="up" onclick="changeQuantityProductInfo($('#quantity-0').val(), 'up', <?php echo $product->stock_product ?>, 0)">
                    <i class="fa fa-plus"></i>
                </button>

                <button class="down" onclick="changeQuantityProductInfo($('#quantity-0').val(), 'down', <?php echo $product->stock_product ?>, 0)">
                    <i class="fa fa-minus"></i>
                </button>

                <input id="quantity-0" class="form-control" type="text" value="1" readonly>

            </div>

        </figure>

        <a class="ps-btn ps-btn--black" onclick="addShoppingCart('<?php echo $product->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $_SERVER['REQUEST_URI'] ?>', this)" details-sc quantity-sc>Add to cart</a>

        <a class="ps-btn" onclick="addShoppingCart('<?php echo $product->url_product; ?>', '<?php echo CurlController::api(); ?>', '<?php echo $path ?>checkout', this)" details-sc quantity-sc>Buy Now</a>

        <div class="ps-product__actions">

            <a class="btn" onclick="addWishlist('<?php echo $product->url_product ?>', '<?php echo CurlController::api(); ?>')">
                <i class="icon-heart"></i>
            </a>

        </div>

    </div>

    <div class="ps-product__specification">

        <a class="report" href="#">Report Abuse</a>

        <p><strong>SKU:</strong> SF1133569600-1</p>

        <p class="categories">

            <strong> Categories:</strong>

            <a href="<?php echo $path . $product->url_category ?>"><?php echo $product->name_category ?></a>,
            <a href="<?php echo $path . $product->url_subcategory ?>"> <?php echo $product->name_subcategory ?></a>,
            <a href="<?php echo $path . $product->title_list_product ?>"><?php echo $product->title_list_product ?></a>

        </p>

        <p class="tags"><strong> Tags</strong>

            <?php
            $tags = json_decode($product->tags_product, true);
            ?>

            <?php foreach ($tags as $key => $value) : ?>

                <a href="<?php $path . $value ?>"><?php echo $value; ?></a>

            <?php endforeach ?>

        </p>

    </div>

    <div class="ps-product__sharing">

        <a class="facebook social-share" data-share="facebook" href="#">
            <i class="fab fa-facebook"></i>
        </a>

        <a class="twitter social-share" data-share="twitter" href="#">
            <i class="fab fa-twitter"></i>
        </a>

        <a class="linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo TemplateController::path() . $product->url_product; ?>" target="_blank">
            <i class="fab fa-linkedin"></i>
        </a>

    </div>

</div> <!-- End Product Info -->