<?php

if (!isset($_SESSION['user'])) {
    echo '<script> window.location = "' . $path . 'account&login#header" </script>';
    return;
} else {
    $time = time();

    if ($_SESSION['user']->token_exp_user < $time) {
        echo '<script>
                sweetAlert("error", "Error: token has expired, please login again", "' . $path . 'account&logout");
        </script>';

        return;
    } else {

        // Data de ordenes

        $select = "name_product,image_product,price_order,quantity_order,details_order,process_order,id_order,name_store,url_store,id_category_product,url_product,id_user_order,id_store_order,email_store,id_product,reviews_product";

        $url = CurlController::api() . "relations?rel=orders,stores,products&type=order,store,product&linkTo=id_user_order&equalTo={$_SESSION['user']->id_user}&orderBy=id_order&orderMode=DESC&select=$select&token={$_SESSION['user']->token_user}";
        $method = "GET";
        $fields = array();
        $header = array();

        $shoppings = CurlController::request($url, $method, $fields, $header)->result;

        if (!is_array($shoppings)) {
            $shoppings = array();
        }

        // Data de disputas
        $select = "id_order_dispute,content_dispute,answer_dispute,date_answer_dispute,date_created_dispute,method_user,logo_store,url_store";

        $url = CurlController::api() . "relations?rel=disputes,orders,users,stores&type=dispute,order,user,store&linkTo=id_user_dispute&equalTo={$_SESSION['user']->id_user}&orderBy=id_dispute&orderMode=DESC&select=$select&token={$_SESSION['user']->token_user}";
        $method = "GET";
        $fields = array();
        $header = array();

        $disputes = CurlController::request($url, $method, $fields, $header)->result;

        if (!is_array($disputes)) {
            $disputes = array();
        }
    }
}

?>

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
                    <li class="active"><a href="<?php echo $path ?>account&my-shopping#profile-user">My Shopping</a></li>
                    <li><a href="<?php echo $path ?>account&my-store#profile-user">My Store</a></li>
                    <li><a href="<?php echo $path ?>account&my-sales#profile-user">My Sales</a></li>
                </ul>

                <!--=====================================
                My Shopping
                ======================================-->

                <div class="table-responsive">

                    <table class="table ps-table--whishlist dt-responsive dt-client" width="100%">

                        <thead>

                            <tr>

                                <th>Product name</th>

                                <th>Proccess</th>

                                <th>Price</th>

                                <th>Quantity</th>

                                <th>Review</th>

                            </tr>

                        </thead>

                        <tbody>

                            <!-- Product -->

                            <?php foreach ($shoppings as $key_shopping => $shopping) : ?>

                                <tr>

                                    <td>

                                        <div class="ps-product--cart">

                                            <!-- Imagen del producto -->

                                            <?php

                                            // Traemos la categoria del producto
                                            $url = CurlController::api() . "categories?linkTo=id_category&equalTo={$shopping->id_category_product}&select=url_category";
                                            $method = "GET";
                                            $fields = array();
                                            $header = array();

                                            $category = CurlController::request($url, $method, $fields, $header)->result[0];

                                            ?>

                                            <div class="ps-product__thumbnail">

                                                <a href="<?php echo $path . $shopping->url_product; ?>">
                                                    <img src="img/products/<?php echo $category->url_category; ?>/<?php echo $shopping->image_product; ?>" alt="<?php echo $shopping->name_product; ?>">
                                                </a>

                                            </div>

                                            <div class="ps-product__content">

                                                <!-- Nombre del producto -->
                                                <a href="<?php echo $path . $shopping->url_product; ?>"><?php echo $shopping->name_product; ?> - </a>

                                                <!-- Nombre de la tienda -->
                                                <a href="<?php echo $path . $shopping->url_store; ?>" class="font-weight-bold"><?php echo $shopping->name_store; ?></a>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <?php

                                        $process_order = json_decode($shopping->process_order, true);

                                        ?>

                                        <ul class="timeline">

                                            <?php foreach ($process_order as $key_process => $process) : ?>

                                                <?php if ($process['status'] == "ok") : ?>

                                                    <li class="success">
                                                        <h5><?php echo $process['date']; ?></h5>
                                                        <p class="text-success"><?php echo $process['stage']; ?> <i class="fas fa-check"></i></p>
                                                        <p>Comment: <span><?php echo $process['comment']; ?></span></p>

                                                    </li>

                                                <?php else : ?>

                                                    <li class="process">
                                                        <h5><?php echo $process['date']; ?></h5>
                                                        <p><?php echo $process['stage']; ?></p>

                                                        <button class="btn btn-primary" disabled>
                                                            <span class="spinner-border spinner-border-sm"></span>
                                                            In process
                                                        </button>
                                                    </li>

                                                <?php endif ?>

                                            <?php endforeach ?>

                                        </ul>

                                        <?php if ($process_order[2]['status'] == "ok") : ?>

                                            <a class="btn btn-warning btn-lg" href="<?php echo $path . $shopping->url_product; ?>">Repurchase</a>

                                        <?php else : ?>

                                            <!-- Abrir una disputa -->

                                            <a class="btn btn-danger btn-lg open-dispute text-white" id-order="<?php echo $shopping->id_order; ?>" id-user="<?php echo $shopping->id_user_order; ?>" id-store="<?php echo $shopping->id_store_order; ?>" email-store="<?php echo $shopping->email_store; ?>" name-store="<?php echo $shopping->name_store ?>">Open Dispute</a>

                                            <!-- Visualizar las disputas -->

                                            <?php if (count($disputes) > 0) : ?>

                                                <?php foreach ($disputes as $key_dispute => $dispute) : ?>

                                                    <?php if ($shopping->id_order == $dispute->id_order_dispute) : ?>

                                                        <div class="my-3">
                                                            <div class="media border p-3">
                                                                <div class="media-body">

                                                                    <h4><small>Dispute on <?php echo $dispute->date_created_dispute; ?></small></h4>
                                                                    <p><?php echo $dispute->content_dispute; ?></p>

                                                                </div>

                                                                <?php if ($_SESSION['user']->method_user == "direct") : ?>

                                                                    <?php if ($_SESSION['user']->picture_user == null) : ?>

                                                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/default/default.png" style="width: 60px;" alt="logo-user">

                                                                    <?php else : ?>

                                                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" style="width: 60px;" alt="logo-user">

                                                                    <?php endif ?>

                                                                <?php else : ?>

                                                                    <?php if (explode("/", $_SESSION['user']->picture_user)[0] == "https:") : ?>

                                                                        <img class="img-fluid rounded-circle ml-auto" src="<?php echo $_SESSION['user']->picture_user; ?>" style="width: 60px;" alt="logo-user">

                                                                    <?php else : ?>

                                                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" style="width: 60px;" alt="logo-user">

                                                                    <?php endif ?>

                                                                <?php endif ?>

                                                            </div>

                                                            <?php if ($dispute->answer_dispute != null) : ?>

                                                                <div class="media border p-3">

                                                                    <img class="img-fluid rounded-circle ml-3 mt-3" src="img/stores/<?php echo $dispute->url_store; ?>/<?php echo $dispute->logo_store; ?>" style="width: 60px;" alt="logo-store">

                                                                    <div class="media-body text-right">

                                                                        <h4><small>Answer on <?php echo $dispute->date_answer_dispute; ?></small></h4>
                                                                        <p><?php echo $dispute->answer_dispute; ?></p>

                                                                    </div>

                                                                </div>

                                                            <?php endif ?>
                                                        </div>

                                                    <?php endif ?>

                                                <?php endforeach ?>

                                            <?php endif ?>

                                        <?php endif ?>


                                    </td>

                                    <td class="price text-center">$<?php echo $shopping->price_order; ?></td>

                                    <td class="text-center"><?php echo $shopping->quantity_order; ?></td>

                                    <td>

                                        <div class="text-center  mt-2">

                                            <?php if ($process_order[2]['status'] == "ok") : ?>

                                                <?php if ($shopping->reviews_product != null) : ?>


                                                    <?php

                                                    $rating = 0;
                                                    $comment = "";

                                                    $reviews = json_decode($shopping->reviews_product, true);

                                                    foreach ($reviews as $key_review => $review) {

                                                        if (isset($review['user'])) {

                                                            if ($review['user'] == $shopping->id_user_order) {

                                                                $rating = $review['review'];
                                                                $comment = $review['comment'];
                                                            }
                                                        }
                                                    }

                                                    ?>

                                                <?php endif ?>

                                                <div class="br-theme-fontawesome-stars">

                                                    <select class="ps-rating" data-read-only="true" style="display: none;">
                                                        <?php

                                                        if ($rating > 0) {
                                                            for ($i = 0; $i < 5; $i++) {
                                                                if ($rating < ($i + 1)) {
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

                                                <p><?php echo $comment; ?></p>

                                                <a class="btn btn-warning btn-lg new-review" id-user="<?php echo $shopping->id_user_order; ?>" id-product="<?php echo $shopping->id_product; ?>">

                                                    <?php if ($rating != 0) : ?>

                                                        Edit review

                                                    <?php else : ?>

                                                        Add review

                                                    <?php endif ?>
                                                </a>

                                            <?php endif ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach ?>

                        </tbody>

                    </table>

                </div><!-- End My Shopping -->

            </div>

        </div>

    </div>

</div>

<!-- Modal para las disputas -->


<div class="modal" id="new-dispute">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="post">

                <div class="modal-header">

                    <h4 class="modal-title">New dispute</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>

                <div class="modal-body">

                    <input type="hidden" name="id-order">
                    <input type="hidden" name="id-user">
                    <input type="hidden" name="id-store">
                    <input type="hidden" name="email-store">
                    <input type="hidden" name="name-store">

                    <div class="form-group">
                        <label>Type your message</label>

                        <div class="form-group__content">
                            <textarea name="content-dispute" class="form-control" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="float-right">
                        <button type="submit" class="ps-btn ps-btn--fullwidth order-update">Send</button>
                    </div>
                </div>

                <?php

                $new_dispute = new UsersController();
                $new_dispute->new_dispute();

                ?>
            </form>
        </div>
    </div>
</div>

<!-- Ventana modal para comentar el producto -->

<div class="modal" id="new-review">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="post">

                <div class="modal-header">

                    <h4 class="modal-title">New Review</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>

                <div class="modal-body">

                    <input type="hidden" name="id-product">
                    <input type="hidden" name="id-user">

                    <div class="form-group form-group__rating">

                        <label>Your rating of this product</label>

                        <select class="ps-rating" name="rating" data-read-only="false">

                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>

                        </select>

                    </div>

                    <div class="form-group">
                        <label>Type your comment</label>

                        <div class="form-group__content">
                            <textarea name="comment-review" class="form-control"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="float-right">
                        <button type="submit" class="ps-btn ps-btn--fullwidth order-update">Send</button>
                    </div>
                </div>

                <?php

                $new_review = new UsersController();
                $new_review->new_review();

                ?>
            </form>
        </div>
    </div>
</div>