<div class="ps-product__thumbnail" data-vertical="true">

    <figure>

        <div class="ps-wrapper">

            <div class="ps-product__gallery" data-arrow="true">

                <?php

                $gallery = json_decode($product->gallery_product, true);

                ?>

                <?php foreach ($gallery as $key => $value) : ?>
                    <div class="item">
                        <a href="img/products/<?php echo $product->url_category ?>/gallery/<?php echo $value ?>">
                            <img src="img/products/<?php echo $product->url_category ?>/gallery/<?php echo $value ?>" alt="<?php echo $product->name_product ?>">
                        </a>
                    </div>
                <?php endforeach ?>

            </div>

        </div>

    </figure>

    <div class="ps-product__variants" data-item="4" data-md="4" data-sm="4" data-arrow="false">

        <?php foreach ($gallery as $key => $value) : ?>
            <div class="item">
                <img src="img/products/<?php echo $product->url_category ?>/gallery/<?php echo $value ?>" alt="<?php echo $product->name_product ?>">
            </div>
        <?php endforeach ?>

    </div>

</div><!-- End Gallery -->