<!--=====================================
Login - Register Content
======================================-->

<div class="ps-my-account">

    <div class="container">

        <form class="ps-form--account ps-tab-root needs-validation" novalidate method="post">

            <ul class="ps-tab-list">

                <li class="">
                    <p><a href="<?php echo $path ?>account&login#header">Login</a></p>
                </li>

                <li class="active">
                    <p><a href="<?php echo $path ?>account&enrollment#header">Register</a></p>
                </li>

            </ul>

            <div class="ps-tabs">

                <input type="hidden" value="<?php echo CurlController::api(); ?>" id="url-api">

                <!--=====================================
                Register Form
                ======================================-->

                <div class="ps-tab active" id="register">

                    <div class="ps-form__content">

                        <h5>Register An Account</h5>

                        <div class="form-group">

                            <input class="form-control" name="reg-first-name" type="text" placeholder="First name" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event, 'text')" required>
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                        <div class="form-group">

                            <input class="form-control" name="reg-last-name" type="text" placeholder="Last name" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event, 'text')" required>
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                        <div class="form-group">

                            <input class="form-control" name="reg-email" type="email" placeholder="Email address" pattern="[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" onchange="validateDataRepeat(event, 'email')" required>
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                        <div class="form-group">

                            <input class="form-control" name="reg-password" type="password" placeholder="Password" pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}" onchange="validateJS(event, 'password')" required>
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                        <?php

                        $register = new UsersController();
                        $register->register();

                        ?>

                        <div class="form-group submtit">

                            <button type="submit" class="ps-btn ps-btn--fullwidth">Register</button>

                        </div>

                    </div>

                    <div class="ps-form__footer">

                        <!-- <p>Connect with:</p>

                        <ul class="ps-list--social">

                            <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a class="google" href="#"><i class="fab fa-google"></i></a></li>

                        </ul> -->

                    </div>

                </div><!-- End Register Form -->

            </div>

        </form>

    </div>

</div>