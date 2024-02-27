<!--=====================================
Breadcrumb
======================================-->

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <li>My Account</li>

        </ul>

    </div>

</div>

<?php

if (isset($url_params[1])) {
    if (
        $url_params[1] == "login" ||
        $url_params[1] == "enrollment" ||
        $url_params[1] == "wishlist" ||
        $url_params[1] == "logout" ||
        $url_params[1] == "my-shopping" ||
        $url_params[1] == "new-store" ||
        $url_params[1] == "my-store" ||
        $url_params[1] == "my-sales"
    ) {

        if (($url_params[1] == "login" || $url_params[1] == "enrollment") && (isset($_SESSION['user']))) {
            echo '
            <script>
                window.location = "' . TemplateController::path() . 'account&my-shopping";
            </script>';
            return;
        }

        // Preguntar si el usuario tiene una tienda creada
        if ($url_params[1] == "my-store") {

            if (!isset($_SESSION['user'])) {
                echo '
                <script>
                    window.location = "' . $path . 'account&login#header";
                </script>';
                return;
            } else {

                $url = CurlController::api() . "stores?linkTo=id_user_store&equalTo={$_SESSION['user']->id_user}&select=id_store";
                $method = "GET";
                $fields = array();
                $header = array();

                $response = CurlController::request($url, $method, $fields, $header);

                if ($response->status == 404) {
                    $url_params[1] = "new-store";
                } else {
                    $url_params[1] = "my-store";
                }
            }
        }
        
        if ($url_params[1] == "my-sales") {

            if (!isset($_SESSION['user'])) {
                echo '
                <script>
                    window.location = "' . $path . 'account&login#header";
                </script>';
                return;
            } else {

                $url = CurlController::api() . "stores?linkTo=id_user_store&equalTo={$_SESSION['user']->id_user}&select=id_store";
                $method = "GET";
                $fields = array();
                $header = array();

                $response = CurlController::request($url, $method, $fields, $header);

                if ($response->status == 404) {
                    $url_params[1] = "new-store";
                } else {
                    $url_params[1] = "my-sales";
                }
            }
        }

        include "$url_params[1]/$url_params[1].php";
    } else {
        echo '<script> window.location = "' . $path . '" </script>';
    }
} else {
    echo '<script> window.location = "' . $path . '" </script>';
}

?>