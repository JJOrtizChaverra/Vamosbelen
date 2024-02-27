<!--=====================================
My Account Content
======================================-->

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
                    <li><a href="<?php echo $path ?>account&wishlist#profile-user">My Wishlist</a></li>
                    <li><a href="<?php echo $path ?>account&my-shopping#profile-user">My Shopping</a></li>
                    <li class="active"><a href="<?php echo $path ?>account&my-store#profile-user">My Store</a></li>
                    <li><a href="<?php echo $path ?>account&my-sales#profile-user">My Sales</a></li>
                </ul>

                <!--=====================================
                My Store
                ======================================-->

                <div class="ps-vendor-store" id="vendor-store">

                    <div class="container">

                        <div class="ps-section__container">

                            <!--=====================================
                            Vendor Profile
                            ======================================-->

                            <?php include "modules/store.php"; ?>

                            <!--=====================================
                            Products
                            ======================================-->

                            <?php

                            if (isset($url_params[2])) {

                                if (
                                    $url_params[2] == "orders" ||
                                    $url_params[2] == "disputes" ||
                                    $url_params[2] == "messages"
                                ) {
                                    include "modules/$url_params[2].php";
                                } else {
                                    include "modules/products.php";
                                }
                            } else {
                                include "modules/products.php";
                            }

                            ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>