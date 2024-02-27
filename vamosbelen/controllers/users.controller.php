<?php

class UsersController
{

    // Registro de usuarios

    public function register()
    {
        // Validamos la sintaxis de los campos

        if (isset($_POST['reg-email'])) {
            if ((preg_match("/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/", $_POST['reg-first-name'])) &&
                (preg_match("/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/", $_POST['reg-last-name'])) &&
                (preg_match("/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $_POST['reg-email'])) &&
                (preg_match("/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/", $_POST['reg-password']))
            ) {
                $url = CurlController::api() . "users?register=true";
                $method = "POST";

                $displayname = TemplateController::capitalize(strtolower("{$_POST['reg-first-name']} {$_POST['reg-last-name']}"));
                $username = strtolower(explode("@", $_POST['reg-email'])[0]);
                $email = strtolower($_POST['reg-email']);
                $date_today = date("Y-m-d");

                $fields = array(
                    "rol_user" => "default",
                    "displayname_user" => $displayname,
                    "username_user" => $username,
                    "email_user" => $email,
                    "password_user" => $_POST['reg-password'],
                    "method_user" => "direct",
                    "date_created_user" => $date_today
                );

                $header = array(
                    "Content-Type" => " application/x-www-form-urlencoded"
                );

                $register = CurlController::request($url, $method, $fields, $header);

                if ($register->status == 200) {
                    $name = $displayname;
                    $subject = "Verify your account";
                    $email = $email;
                    $message = "We must verify yout account so that you can enter our Marketplace - Vamosbelén";
                    $url = TemplateController::path() . "account&login&" . base64_encode($email);

                    $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                    if ($send_email == "Ok") {
                        echo '<div class="alert alert-success">Registered user succesfully, confirm your account in your email (check spam)</div>
                        
                        <script>
                            formatInputs();
                        </script>

                        ';
                    } else {
                        echo '<div class="alert alert-danger">' . $send_email . '</div>
                        
                        <script>
                            formatInputs();
                        </script>

                        ';
                    }
                }
            } else {
                echo '<div class="alert alert-danger">Error in the syntax of the fields</div>
                
                <script>
                    formatInputs();
                </script>
                
                ';
            }
        }
    }

    // Login de usuarios

    public function login()
    {

        if (isset($_POST['login-email'])) {
            if ((preg_match("/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $_POST['login-email'])) && (preg_match("/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/", $_POST['login-password']))) {

                echo '<script> sweetAlert("loading", ""); </script>';

                $url = CurlController::api() . "users?login=true";
                $method = "POST";

                $fields = array(
                    "email_user" => $_POST['login-email'],
                    "password_user" => $_POST['login-password']
                );

                $header = array(
                    "Content-Type" => " application/x-www-form-urlencoded"
                );

                $login = CurlController::request($url, $method, $fields, $header);

                if ($login->status == 200) {

                    if ($login->result[0]->verification_user == 1) {

                        // Capturando la informacion del usuario al loguearse
                        $_SESSION['user'] = $login->result[0];

                        // Redirigiendo al usuario a la pagina wishlist despues de loguearse
                        echo '
                        <script>
                            formatInputs();

                            localStorage.setItem("token_user", "' . $_SESSION['user']->token_user . '");

                            window.location = "' . TemplateController::path() . '";
                        </script>

                        ';
                    } else {
                        echo '<div class="alert alert-danger">Your account has not been verified yet, please check your email inbox.</div>
                        
                        <script>

                            sweetAlert("close", "");
                            formatInputs();

                        </script>

                        ';
                    }
                } else {
                    echo '<div class="alert alert-danger">' . $login->result . '</div>
                    
                    <script>

                        sweetAlert("close", "");
                        formatInputs();

                    </script>
                    
                    ';
                }
            } else {
                echo '<div class="alert alert-danger">Error in the syntax of the fields</div>
                
                <script>

                    sweetAlert("close", "");
                    formatInputs();

                </script>
                
                ';
            }
        }
    }

    // Metodo para solicitar recuperar contraseña

    public function reset_password()
    {
        if (isset($_POST['reset-password'])) {

            // Validamos sintaxis del email
            if (preg_match("/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $_POST['reset-password'])) {

                // Preguntamos si el usuario esta registrado
                $url = CurlController::api() . "users?linkTo=email_user&equalTo={$_POST['reset-password']}";
                $method = "GET";
                $fields = array();
                $header = array();

                $user = CurlController::request($url, $method, $fields, $header);

                if ($user->status == 200) {

                    if ($user->result[0]->method_user == "direct") {
                        function gen_password($length)
                        {

                            $chain = "123456789abcdefghijklmnopqrstuvwxyz";
                            $password = substr(str_shuffle($chain), 0, $length);

                            return $password;
                        }

                        $new_password = gen_password(9);

                        $crypt = crypt($new_password, '$2a$07$azybxcags23425sdg23sdfhsd$');

                        // Actualizar contraseña en base de datos

                        $url = CurlController::api() . "users?id={$user->result[0]->id_user}&nameId=id_user&token=no&except=password_user";
                        $method = "PUT";
                        $fields = "password_user=$crypt";
                        $header = array();

                        $update_password = CurlController::request($url, $method, $fields, $header);

                        if ($update_password->status == 200) {

                            // Enviamos nueva contraseña al correo electronico
                            $name = $user->result[0]->displayname_user;
                            $subject = "Request new password";
                            $email = $user->result[0]->email_user;
                            $message = "Your new password is: $new_password";
                            $url = TemplateController::path() . "account&login";

                            $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                            if ($send_email == "Ok") {
                                echo '<script>

                                    formatInputs();
                                    sweetAlert("success", "Your new password has been succesfully sent, please check your email inbox");

                                </script>
                    
                                ';
                            } else {
                                echo '<script>

                                    formatInputs();
                                    sweetAlert("error", "' . $send_email . '");
                                    
                                </script>';
                            }
                        } else {
                            echo '<script>
                
                                    formatInputs();
                                    sweetAlert("error", "Password was not updated, please try again");

                                 </script>';
                        }
                    } else {
                        echo '<script>

                            formatInputs();
                            sweetAlert("error", "It is not allowed to recover password because you registered with other' . $user->result[0]->method_user . '");

                        </script>';
                    }
                } else {
                    echo '<script>
                
                        formatInputs();
                        sweetAlert("error", "The email does not exist in the database");

                    </script>';
                }
            } else {
                echo '<div class="alert alert-danger">Error in the syntax of the fields</div>
                
                <script>
                    sweetAlert("close", "");
                    formatInputs();
                </script>
                
                ';
            }
        }
    }

    // Funcion para cambiar contraseña

    public function change_password()
    {
        if (isset($_POST['change-password'])) {

            // Validamos la sintaxis de los campos
            if ((preg_match("/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/", $_POST['change-password']))) {

                // Encriptamos la contraseña
                $crypt = crypt($_POST['change-password'], '$2a$07$azybxcags23425sdg23sdfhsd$');

                // Actualizar contraseña en base de datos
                $url = CurlController::api() . "users?id={$_SESSION['user']->id_user}&nameId=id_user&token={$_SESSION['user']->token_user}";
                $method = "PUT";
                $fields = "password_user=$crypt";
                $header = array();

                $update_password = CurlController::request($url, $method, $fields, $header);

                if ($update_password->status == 200) {

                    // Enviamos nueva contraseña al correo electronico
                    $name = $_SESSION['user']->displayname_user;
                    $subject = "Change of password";
                    $email = $_SESSION['user']->email_user;
                    $message = "Your have changed your password";
                    $url = TemplateController::path() . "account&login";

                    $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                    if ($send_email == "Ok") {
                        echo '<script>

                        formatInputs();
                        notieAlert("1", "Your password has been succesfully changed");

                    </script>
        
                    ';
                    } else {
                        echo '<script>

                        formatInputs();
                        sweetAlert("error", "' . $send_email . '");
                        
                    </script>';
                    }
                } else {
                    if ($update_password->status == 303) {
                        echo '<script>
                
                            formatInputs();
                            sweetAlert("error", "' . $update_password->result . '", "' . TemplateController::path() . 'account&logout");

                        </script>';
                    } else {
                        echo '<script>
                
                            formatInputs();
                            sweetAlert("error", "Password was not updated, please try again");

                        </script>';
                    }
                }
            } else {
                echo '<div class="alert alert-danger">Error in the syntax of the fields</div>
                
                    <script>
                        sweetAlert("close", "");
                        formatInputs();
                    </script>
                
                    ';
            }
        }
    }

    // Funcion para cambiar foto de perfil

    public function change_picture()
    {
        // Validamos la sintaxis de los campos
        if (isset($_FILES['change-picture']['tmp_name']) && !empty($_FILES['change-picture']['tmp_name'])) {


            $image = $_FILES['change-picture'];
            $folder = "img/users";
            $path = $_SESSION['user']->id_user;
            $width = 200;
            $height = 200;
            $name = $_SESSION['user']->username_user;

            $save_image = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

            if ($save_image != "error") {

                // Actualizar la foto en base de datos
                $url = CurlController::api() . "users?id={$_SESSION['user']->id_user}&nameId=id_user&token={$_SESSION['user']->token_user}";
                $method = "PUT";
                $fields = "picture_user=$save_image";
                $header = array();

                $update_picture = CurlController::request($url, $method, $fields, $header);

                if ($update_picture->status == 200) {

                    $_SESSION["user"]->picture_user = $save_image;

                    echo '<script>
                
                        formatInputs();
                        sweetAlert("success", "Your new picture has been changed succesfully", "' . $_SERVER['REQUEST_URI'] . '");

                    </script>';
                } else {

                    if ($update_picture->status == 303) {
                        echo '<script>
                
                            formatInputs();
                            sweetAlert("error", "' . $update_picture->result . '", "' . TemplateController::path() . 'account&logout");

                        </script>';
                    } else {
                        echo '<script>
                
                            formatInputs();
                            sweetAlert("error", "An error ocurred while saving the image, please try again");

                        </script>';
                    }
                }
            } else {
                echo '<script>
                
                        formatInputs();
                        sweetAlert("error", "An error ocurred while creating the image, please try again");

                    </script>';
            }
        }
    }

    public function new_dispute()
    {

        if (isset($_POST['id-order'])) {

            $url = CurlController::api() . "disputes?token={$_SESSION['user']->token_user}";
            $method = "POST";
            $fields = [
                "id_order_dispute" => $_POST['id-order'],
                "id_user_dispute" => $_POST['id-user'],
                "id_store_dispute" => $_POST['id-store'],
                "content_dispute" => $_POST['content-dispute'],
                "date_created_dispute" => date("Y-m-d"),
            ];
            $header = [
                "Content-Type" => "application/x-www-form-urlencoded"
            ];

            $dispute = CurlController::request($url, $method, $fields, $header);

            if ($dispute->status == 200) {

                // Enviamos notificacion de la creacion de la disputa a la tienda

                // Variables para el envio del correo electronico
                $name = $_POST['name-store'];
                $subject = "A dispute has been created";
                $email = $_POST['email-store'];
                $message = "A dispute has been created";
                $url = TemplateController::path() . "account&my-store&disputes";

                // Notificamos del cambio de orden al correo electronico
                $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                if ($send_email == "Ok") {
                    echo '
                    <script>
                        notieAlert(1, "The dispute has beed successfully");
                        formatInputs();
                    </script>';
                } else {
                    echo '
                    <script>
                        notieAlert(2, "The dispute has been generated but the store was not notified");
                        formatInputs();
                    </script>';
                }
            } else {
                echo '
                <script>
                    notieAlert(3, "Error creating dispute");
                    formatInputs();
                </script>';
            }
        }
    }


    // Crear una pregunta

    public function new_question()
    {

        if (isset($_POST['question'])) {

            if (!empty($_POST['id-user'])) {
                $url = CurlController::api() . "messages?token={$_SESSION['user']->token_user}";
                $method = "POST";
                $fields = [
                    "id_product_message" => $_POST['id-product'],
                    "id_user_message" => $_POST['id-user'],
                    "id_store_message" => $_POST['id-store'],
                    "content_message" => $_POST['question'],
                    "date_created_message" => date("Y-m-d"),
                ];
                $header = [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ];

                $message = CurlController::request($url, $method, $fields, $header);

                if ($message->status == 200) {

                    // Enviamos notificacion de la creacion del mensaje a la tienda

                    // Variables para el envio del correo electronico
                    $name = $_POST['name-store'];
                    $subject = "A message has been created";
                    $email = $_POST['email-store'];
                    $message = "A message has been created: {$_POST['question']}";
                    $url = TemplateController::path() . "account&my-store&messages";

                    // Notificamos del cambio de orden al correo electronico
                    $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                    if ($send_email == "Ok") {
                        echo '
                        <script>
                            notieAlert(1, "The message has beed successfully");
                            formatInputs();
                        </script>';
                    } else {
                        echo '
                        <script>
                            notieAlert(2, "The message has been generated but the store was not notified");
                            formatInputs();
                        </script>';
                    }
                } else {
                    echo '
                    <script>
                        notieAlert(3, "Error creating message");
                        formatInputs();
                    </script>';
                }
            }
        }
    }


    // Crear una reseña

    public function new_review()
    {

        if (isset($_POST['rating'])) {

            $array_review = [
                "review" => $_POST['rating'],
                "comment" => $_POST['comment-review'],
                "user" => $_POST['id-user']
            ];
            // Traer las reseñas actuales del producto

            $url = CurlController::api() . "products?linkTo=id_product&equalTo={$_POST['id-product']}&select=reviews_product";
            $method = "GET";
            $fields = array();
            $header = array();

            $reviews = CurlController::request($url, $method, $fields, $header)->result;

            // Preguntar si la reseña viene vacia

            if ($reviews[0]->reviews_product != null) {

                $count = 0;

                $new_review = json_decode($reviews[0]->reviews_product, true);

                // Editar una reseña ya escrita por el usuario
                foreach ($new_review as $key_review => $review) {

                    if (isset($review['user'])) {

                        if ($review['user'] == $_POST['id-user']) {

                            $review['review'] = $_POST['rating'];
                            $review['comment'] = $_POST['comment-review'];

                            $new_review[$key_review] = $review;
                        } else {
                            $count++;
                        }
                    } else {
                        $count++;
                    }
                }

                print_r($count);
                print_r(count($new_review));

                if ($count == count($new_review)) {
                    array_push($new_review, $array_review);
                }
            } else {

                $new_review = array();

                // Crear una reseña sobre las ya existentes del producto
                array_push($new_review, $array_review);
            }

            // Actualizar la reseña en la base de datos

            $url = CurlController::api() . "products?id={$_POST['id-product']}&nameId=id_product&token={$_SESSION['user']->token_user}";
            $method = "PUT";
            $fields = "reviews_product=" . json_encode($new_review);
            $header = [
                "Content-Type" => "application/x-www-form-urlencoded"
            ];

            $update_review = CurlController::request($url, $method, $fields, $header);

            if ($update_review->status == 200) {
                echo '
                <script>
                    sweetAlert("success", "The review has been send", "'.TemplateController::path() . 'account&my-shopping");
                    formatInputs();
                </script>';
            } else {
                echo '
                <script>
                    notieAlert(3, "Error creating review");
                    formatInputs();
                </script>';
            }
        }
    }
}
