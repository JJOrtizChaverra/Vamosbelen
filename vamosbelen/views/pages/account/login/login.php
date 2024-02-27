<!--=====================================
Login - Register Content
======================================-->

<div class="ps-my-account">

    <div class="container">

        <!-- Validar la veracidad del correo electronico -->

        <?php

        if (isset($url_params[2])) {
            $verify = base64_decode($url_params[2]);

            // Validamos que el usuario exista
            $url = CurlController::api() . "users?linkTo=email_user&equalTo=$verify&select=id_user";
            $method = "GET";
            $fields = array();
            $header = array();

            $value = CurlController::request($url, $method, $fields, $header);

            if ($value->status == 200) {
                // Actualizar el campo de verificacion

                $url = CurlController::api() . "users?id={$value->result[0]->id_user}&nameId=id_user&token=no&except=verification_user";
                $method = "PUT";
                $fields = "verification_user=1";
                $header = array();

                $verification_user = CurlController::request($url, $method, $fields, $header);

                if ($verification_user->status == 200) {
                    echo '<div class="alert alert-success text-center">Your account has been verified successfully, you can now login</div>';
                }
            } else {
                echo '<div class="alert alert-danger text-center">Failed to verify account, email does not exist</div>';
            }
        }

        ?>

        <form class="ps-form--account ps-tab-root needs-validation" novalidate method="post">

            <ul class="ps-tab-list">

                <li class="active">
                    <p><a href="<?php echo $path ?>account&login#header">Login</a></p>
                </li>

                <li class="">
                    <p><a href="<?php echo $path ?>account&enrollment#header">Register</a></p>
                </li>

            </ul>

            <div class="ps-tabs">

                <!--=====================================
                Login Form
                ======================================-->

                <div class="ps-tab active" id="sign-in">

                    <div class="ps-form__content">

                        <h5>Log In Your Account</h5>

                        <div class="form-group">

                            <input class="form-control" name="login-email" type="email" placeholder="Email address" pattern="[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" onchange="validateJS(event, 'email')" required>
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                        <div class="form-group form-forgot">

                            <input class="form-control" name="login-password" type="password" placeholder="Password" pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}" onchange="validateJS(event, 'password')" required>
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                            <a href="#reset-password" data-toggle="modal">Forgot?</a>

                        </div>

                        <div class="form-group">

                            <div class="ps-checkbox">

                                <input class="form-control" type="checkbox" id="remember-me" name="remember-me" onchange="remember(event)">

                                <label for="remember-me">Remember me</label>

                            </div>

                        </div>

                        <?php

                        $login = new UsersController();
                        $login->login();

                        ?>

                        <div class="form-group submtit">

                            <button type="submit" class="ps-btn ps-btn--fullwidth">Login</button>

                        </div>

                    </div>

                    <div class="ps-form__footer">

                        <!-- <p>Connect with:</p>

                        <ul class="ps-list--social">

                            <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a class="google" href="#"><i class="fab fa-google"></i></a></li>

                        </ul> -->

                    </div>

                </div><!-- End Login Form -->

            </div>

        </form>

    </div>

</div>

<!-- Ventana modal para recuperar contraseña -->

<!-- The Modal -->
<div class="modal" id="reset-password">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Reset Password</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form class="ps-form--account ps-tab-root needs-validation" novalidate method="post">

                    <div class="form-group">
                        <input class="form-control" name="reset-password" type="email" placeholder="Email address" pattern="[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" onchange="validateJS(event, 'email')" required>
                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill in this field correctly.</div>
                    </div>

                    <?php

                    $reset = new UsersController();
                    $reset->reset_password();

                    ?>

                    <div class="form-group submtit">

                        <button type="submit" class="ps-btn ps-btn--fullwidth">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>