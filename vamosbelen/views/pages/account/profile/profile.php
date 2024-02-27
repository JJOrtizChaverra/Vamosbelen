<?php

$id_store = null;

$url = CurlController::api() . "stores?linkTo=id_user_store&equalTo={$_SESSION['user']->id_user}&select=id_store";
$method = "GET";
$fields = array();
$header = array();

$response_store = CurlController::request($url, $method, $fields, $header);

if ($response_store->status == 200) {

    $id_store = $response_store->result[0]->id_store;


    // Traer el total de ordenes a entregar
    $url = CurlController::api() . "orders?linkTo=id_store_order,status_order&equalTo=$id_store,pending&select=id_order&token={$_SESSION['user']->token_user}";
    $method = "GET";
    $fields = array();
    $header = array();

    $data_orders = CurlController::request($url, $method, $fields, $header);

    if($data_orders->status == 200) {
        $total_orders = $data_orders->total;
    } else {
        $total_orders = 0;
    }


    // Traer el total de productos de la tienda
    $url = CurlController::api() . "products?linkTo=id_store_product&equalTo=$id_store&select=id_product";
    $method = "GET";
    $fields = array();
    $header = array();

    $data_products = CurlController::request($url, $method, $fields, $header);

    if($data_products->status == 200) {
        $total_products = $data_products->total;
    } else {
        $total_products = 0;
    }



    // Traer el total de disputas
    $url = CurlController::api() . "disputes?linkTo=id_store_dispute&equalTo=$id_store&select=answer_dispute&token={$_SESSION['user']->token_user}";
    $method = "GET";
    $fields = array();
    $header = array();

    $disputes_data = CurlController::request($url, $method, $fields, $header);

    $total_disputes = 0;

    if ($disputes_data->status == 200) {

        foreach ($disputes_data->result as $key_dispute => $dispute) {

            if ($dispute->answer_dispute == null) {
                $total_disputes++;
            }
        }
    }


    // Traer el total de mensajes
    $url = CurlController::api() . "messages?linkTo=id_store_message&equalTo=$id_store&select=answer_message&token={$_SESSION['user']->token_user}";
    $method = "GET";
    $fields = array();
    $header = array();

    $messages = CurlController::request($url, $method, $fields, $header);

    $total_messages = 0;

    if ($messages->status == 200) {

        foreach ($messages->result as $key_message => $message) {

            if ($message->answer_message == null) {
                $total_messages++;
            }
        }
    }
}

?>

<aside class="ps-block--store-banner">

    <div class="ps-block__user">

        <div class="ps-block__user-avatar">

            <!-- Imagen del usuario -->

            <?php if ($_SESSION['user']->method_user == "direct") : ?>

                <?php if ($_SESSION['user']->picture_user == null) : ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height: auto;" src="img/users/default/default.png" alt="profile">

                <?php else : ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height: auto;" src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">

                <?php endif ?>

            <?php else : ?>

                <?php if (explode("/", $_SESSION['user']->picture_user)[0] == "https:") : ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height: auto;" src="<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">

                <?php else : ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height: auto;" src="img/users/<?php echo $_SESSION['user']->id_user; ?>/<?php echo $_SESSION['user']->picture_user; ?>" alt="profile">

                <?php endif ?>

            <?php endif ?>

            <div class="br-wrapper">

                <button class="btn btn-primary btn-lg rounded-circle" data-toggle="modal" data-target="#change-picture"><i class="fas fa-pencil-alt"></i></button>

            </div>

        </div>

        <div class="ps-block__user-content text-center text-lg-left">

            <h2 class="text-white"><?php echo $_SESSION['user']->displayname_user; ?></h2>

            <p><i class="fas fa-user"></i> <?php echo $_SESSION['user']->username_user; ?></p>

            <p><i class="fas fa-envelope"></i> <?php echo $_SESSION['user']->email_user; ?></p>

            <button class="btn bg-btn-primary btn-lg" data-toggle="modal" data-target="#change-password" id="profile-user">Change Password</button>

        </div>

        <?php if ($id_store != null) : ?>

            <div class="row ml-lg-auto pt-5">

                <div class="col-lg-3 col-6">
                    <div class="text-center">
                        <a href="<?php echo TemplateController::path(); ?>account&my-store&orders#profile-user">
                            <h1><i class="fas fa-shopping-cart text-white"></i></h1>
                            <h4 class="text-white">Orders <span class="badge badge-secondary rounded-circle"><?php echo $total_orders; ?></span></h4>
                        </a>
                    </div>
                </div><!-- box /-->

                <div class="col-lg-3 col-6">
                    <div class="text-center">
                        <a href="<?php echo TemplateController::path(); ?>account&my-store&products#profile-user">
                            <h1><i class="fas fa-shopping-bag text-white"></i></h1>
                            <h4 class="text-white">Products <span class="badge badge-secondary rounded-circle"><?php echo $total_products; ?></span></h4>
                        </a>
                    </div>
                </div><!-- box /-->

                <div class="col-lg-3 col-6">
                    <div class="text-center">
                        <a href="<?php echo TemplateController::path(); ?>account&my-store&disputes#profile-user">
                            <h1><i class="fas fa-bell text-white"></i></h1>
                            <h4 class="text-white">Disputes <span class="badge badge-secondary rounded-circle"><?php echo $total_disputes; ?></span></h4>
                        </a>
                    </div>
                </div><!-- box /-->

                <div class="col-lg-3 col-6">
                    <div class="text-center">
                        <a href="<?php echo TemplateController::path(); ?>account&my-store&messages#profile-user">
                            <h1><i class="fas fa-comments text-white"></i></h1>
                            <h4 class="text-white">Messages <span class="badge badge-secondary rounded-circle"><?php echo $total_messages; ?></span></h4>
                        </a>
                    </div>
                </div><!-- box /-->
            </div>

        <?php endif ?>

    </div>

</aside><!-- s -->

<!-- Ventana modal para recuperar contraseña -->

<!-- The Modal -->
<div class="modal" id="change-password">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form class="ps-form--account ps-tab-root needs-validation" novalidate method="post">

                    <div class="form-group form-forgot">

                        <input class="form-control" name="change-password" type="password" placeholder="Password" pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}" onchange="validateJS(event, 'password')" required>
                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill in this field correctly.</div>

                    </div>

                    <?php

                    $reset = new UsersController();
                    $reset->change_password();

                    ?>

                    <div class="form-group submtit">

                        <button type="submit" class="ps-btn ps-btn--fullwidth">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Ventana modal para cambiar la imagen de perfil -->

<!-- The Modal -->
<div class="modal" id="change-picture">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Change Picture</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form class="ps-form--account ps-tab-root needs-validation" novalidate method="post" enctype="multipart/form-data">

                    <small class="helsmall-block small">Dimensions: 200px * 200px | Max Size. 2MB | Format: JPG or PNG</small>

                    <div class="custom-file">

                        <input class="custom-file-input" type="file" name="change-picture" id="custom-file" accept="img/*" max-size="2000000" onchange="validateImageJS(event, 'change-picture')" required>
                        <label class="custom-file-label" for="custom-file">Choose file</label>

                    </div>

                    <figure class="text-center py-3">
                        <img class="img-fuid rounded-circle change-picture" style="width: 150px;" src="" alt="">
                    </figure>

                    <?php

                    $change_picture = new UsersController();
                    $change_picture->change_picture();

                    ?>

                    <div class="form-group submtit">

                        <button type="submit" class="ps-btn ps-btn--fullwidth">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>