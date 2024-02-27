<?php

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;

// Validar si existe una variable de session llamada user

if ($_COOKIE['list-sc'] != "[]") {
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
        }
    }
} else {
    echo '<script>
            sweetAlert("warning", "", "' . $path . '");
        </script>';
}

?>

<!--=====================================
Breadcrumb
======================================-->

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <li><a href="<?php echo $path ?>shopping-cart">Shopping cart</a></li>

            <li>Checkout</li>

        </ul>

    </div>

</div>

<!--=====================================
Checkout
======================================-->
<div class="ps-checkout ps-section--shopping">

    <div class="container">

        <div class="ps-section__header">

            <h1>Checkout</h1>

        </div>

        <div class="ps-section__content">

            <form class="ps-form--checkout needs-validation" novalidate method="post" onsubmit="return checkout()">

                <input type="hidden" id="id-user" value="<?php echo $_SESSION['user']->id_user; ?>">
                <input type="hidden" id="url-api" value="<?php echo CurlController::api(); ?>">
                <input type="hidden" id="url" value="<?php echo TemplateController::path(); ?>">

                <div class="row">

                    <div class="col-xl-7 col-lg-8 col-sm-12">

                        <div class="ps-form__billing-info">

                            <h3 class="ps-form__heading">Billing Details</h3>

                            <!-- Nombre completo -->

                            <div class="form-group">

                                <label>Display Name<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input class="form-control" value="<?php echo $_SESSION['user']->displayname_user; ?>" type="text" readonly pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event, 'text')" required>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!-- Correo electronico -->

                            <div class="form-group">

                                <label>Email Address<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input id="email-order" class="form-control" value="<?php echo $_SESSION['user']->email_user; ?>" type="email" readonly pattern="[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" onchange="validateJS(event, 'email')" required>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!-- Pais -->

                            <div class="form-group">

                                <label for="country-order">Country<sup>*</sup></label>

                                <?php

                                $data = file_get_contents("views/json/countries.json");
                                $countries = json_decode($data, true);

                                ?>

                                <div class="form-group__content">

                                    <select id="country-order" class="form-control select2" onchange="changeCountry(event)" required>

                                        <?php if ($_SESSION['user']->country_user != null) : ?>

                                            <option value="<?php echo $_SESSION['user']->country_user; ?>_<?php echo explode("_", $_SESSION['user']->phone_user)[0] ?>"><?php echo $_SESSION['user']->country_user; ?></option>

                                        <?php else : ?>

                                            <option value="" selected disabled>Select Country</option>

                                        <?php endif ?>

                                        <?php foreach ($countries as $key => $country) : ?>

                                            <option value="<?php echo $country['name'] ?>_<?php echo $country['dial_code']; ?>"><?php echo $country['name'] ?></option>

                                        <?php endforeach ?>
                                    </select>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!-- Ciudad -->

                            <div class="form-group">

                                <label for="city-order">City<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input id="city-order" class="form-control" value="<?php echo $_SESSION['user']->city_user; ?>" type="text" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event, 'text')" required>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!-- Telefono / Celular -->

                            <div class="form-group">

                                <label>Phone<sup>*</sup></label>

                                <div class="form-group__content input-group">

                                    <?php if ($_SESSION['user']->phone_user != null) : ?>

                                        <div class="input-group-append">
                                            <span class="input-group-text dial-code"><?php echo explode("_", $_SESSION['user']->phone_user)[0]; ?></span>
                                        </div>

                                        <?php

                                        $phone = explode("_", $_SESSION['user']->phone_user)[1];

                                        ?>

                                    <?php else : ?>

                                        <div class="input-group-append">
                                            <span class="input-group-text dial-code">+</span>
                                        </div>

                                        <?php

                                        $phone = "";

                                        ?>

                                    <?php endif ?>

                                    <input id="phone-order" class="form-control" value="<?php echo $phone; ?>" type="text" pattern="[-\\(\\)\\0-9 ]{1,}" onchange="validateJS(event, 'phone')" required>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!-- Direccion -->

                            <div class="form-group">

                                <label>Address<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input id="address-order" class="form-control" value="<?php echo $_SESSION['user']->address_user; ?>" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" type="text" required>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!-- Guardar informacion -->

                            <div class="form-group">

                                <div class="ps-checkbox">

                                    <input class="form-control" type="checkbox" id="create-account">

                                    <label for="create-account">Save address?</label>

                                </div>

                            </div>

                            <!-- Informacion adicional de la orden -->

                            <h3 class="mt-40"> Addition information</h3>

                            <div class="form-group">

                                <label>Order Notes</label>

                                <div class="form-group__content">

                                    <textarea id="info-order" class="form-control" rows="7" placeholder="Notes about your order, e.g. special notes for delivery." pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')"></textarea>
                                    <div class="valid-feedback">Valid</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-5 col-lg-4 col-sm-12">

                        <div class="ps-form__total">

                            <h3 class="ps-form__heading">Your Order</h3>

                            <div class="content">

                                <div class="ps-block--checkout-total">

                                    <div class="ps-block__header d-flex justify-content-between">

                                        <p>Product</p>

                                        <p>Total</p>

                                    </div>

                                    <?php

                                    $total_order = 0;

                                    if (isset($_COOKIE['list-sc'])) {

                                        $order = json_decode($_COOKIE['list-sc'], true);
                                    } else {
                                        echo '<script>
                                                window.location.href = "' . $path . '" 
                                            </script>';
                                    }

                                    ?>

                                    <div class="ps-block__content">

                                        <table class="table ps-block__products">

                                            <tbody>

                                                <?php foreach ($order as $key => $value) : ?>

                                                    <?php

                                                    $subtotal_order = 0;

                                                    // Traer productos del carrito de compras

                                                    $select = "name_product,id_product,url_product,name_store,id_store,url_store,price_product,offer_product,shipping_product,delivery_time_product,stock_product,sales_product";

                                                    $url = CurlController::api() . "relations?rel=products,categories,stores&type=product,category,store&linkTo=url_product&equalTo={$value['product']}&select=$select";
                                                    $method = "GET";
                                                    $fields = array();
                                                    $header = array();

                                                    $product_order = CurlController::request($url, $method, $fields, $header)->result[0];

                                                    ?>

                                                    <tr>

                                                        <td>

                                                            <input type="hidden" class="id-store" value="<?php echo $product_order->id_store; ?>">
                                                            <input type="hidden" class="id-product" value="<?php echo $product_order->id_product; ?>">
                                                            <input type="hidden" class="delivery-time" value="<?php echo $product_order->delivery_time_product; ?>">
                                                            <input type="hidden" class="url-store" value="<?php echo $product_order->url_store; ?>">
                                                            <input type="hidden" class="sales-product" value="<?php echo $product_order->sales_product; ?>">
                                                            <input type="hidden" class="stock-product" value="<?php echo $product_order->stock_product; ?>">

                                                            <!-- Nombre del producto -->
                                                            <a href="<?php echo $path . $product_order->url_product; ?>" class="name-product"> <?php echo $product_order->name_product ?></a>

                                                            <!-- Tienda del producto -->
                                                            <p class="mb-0">Sold By: <a href="<?php echo $path . $product_order->url_store; ?>"><strong><?php echo $product_order->name_store ?></strong></a></p>

                                                            <!-- Detalles del producto -->
                                                            <div class="details-order">
                                                                <?php if ($value['details'] != "") : ?>

                                                                    <?php foreach (json_decode($value['details'], true) as $key => $item) : ?>

                                                                        <?php foreach (array_keys($item) as $key => $detail) : ?>

                                                                            <div><?php echo "$detail: " . array_values($item)[$key] ?></div>

                                                                        <?php endforeach ?>

                                                                    <?php endforeach ?>

                                                                <?php endif ?>
                                                            </div>

                                                            <!-- Precio de envio del producto -->
                                                            <p class="mb-0">Shipping: $<?php echo ($product_order->shipping_product * $value['quantity']); ?></p>

                                                            <?php

                                                            $subtotal_order += ($product_order->shipping_product * $value['quantity']);

                                                            ?>

                                                            <!-- Cantidad del producto -->
                                                            <p class="mb-0">Quantity: <span class="quantity-order"><?php echo $value['quantity']; ?></span></p>
                                                        </td>

                                                        <!-- Precio del producto -->

                                                        <?php
                                                        if ($product_order->offer_product != null) {

                                                            $price = TemplateController::offer_price(
                                                                $product_order->price_product,
                                                                json_decode($product_order->offer_product, true)[1],
                                                                json_decode($product_order->offer_product, true)[0]
                                                            );

                                                            $subtotal_order += ($price * $value['quantity']);
                                                        } else {
                                                            $subtotal_order = ($product_order->price_product * $value['quantity']);
                                                        }

                                                        $total_order += $subtotal_order;
                                                        ?>

                                                        <td class="text-right">$<span class="price-order"><?php echo $subtotal_order; ?></span></td>

                                                    </tr>

                                                <?php endforeach ?>

                                            </tbody>

                                        </table>

                                        <h3 class="text-right total-order" total="<?php echo $total_order; ?>">Total <span>$<?php echo $total_order; ?></span></h3>

                                    </div>

                                </div>

                                <hr class="py-3">

                                <div class="form-group">

                                    <div class="ps-radio">

                                        <input class="form-control" type="radio" id="pay-paypal" name="payment-method" value="paypal" checked onchange="changePaymentMethod(event)">

                                        <label for="pay-paypal">Pay with paypal? <span><img src="img/payment-method/paypal.jpg" class="w-50"></span></label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="ps-radio">

                                        <input class="form-control" type="radio" id="pay-payu" name="payment-method" value="payu" onchange="changePaymentMethod(event)">

                                        <label for="pay-payu">Pay with payu? <span><img src="img/payment-method/payu.jpg" class="w-50"></span></label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="ps-radio">

                                        <input class="form-control" type="radio" id="pay-mercadopago" name="payment-method" value="mercado-pago" onchange="changePaymentMethod(event)">

                                        <label for="pay-mercadopago">Pay with Mercado Pago? <span><img src="img/payment-method/mercado_pago.jpg" class="w-50"></span></label>

                                    </div>

                                </div>

                                <?php if ($_COOKIE['list-sc'] != "[]") : ?>

                                    <button type="submit" class="ps-btn ps-btn--fullwidth" onclick="sweetAlert('loading', '');">Proceed to checkout</button>

                                <?php else : ?>

                                    <h3 class="text-center">You have not selected any product to buy</h3>

                                <?php endif ?>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<?php

// Recibir variables de payu en pagina de respuesta

if (isset($_REQUEST['transactionState']) && $_REQUEST['transactionState'] == 4 && isset($_REQUEST['reference_pol'])) {

    $id_payment = $_REQUEST['transactionId'];

    endCheckout($id_payment);
}

// // Recibir variables de payu en pagina de confirmacion
// if(isset($_REQUEST['state_pol']) && $_REQUEST['state_pol'] == 4 && isset($_REQUEST['reference_pol'])) {

//     $id_payment = $_REQUEST['transactionId'];

//     endCheckout($id_payment);
// }

// Funcion para finalizar el checkout


// Recibir variables de mercado pago

if (isset($_COOKIE['mp'])) {

    $mp = json_decode($_COOKIE['mp'], true);

    try {
        MercadoPagoConfig::setAccessToken("TEST-6058871205122040-020818-e7d19aafcc8073936cf255d7939ea1f5-1209703097");

        $client = new PaymentClient();
        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: <SOME_UNIQUE_VALUE>"]);

        $payment = $client->create([
            "transaction_amount" => (float) $mp['transaction_amount'],
            "token" => $mp['token'],
            "description" => $mp['description'],
            "installments" => (int) $mp['installments'],
            "payment_method_id" => $mp['payment_method_id'],
            "issuer_id" => $mp['issuer_id'],
            "payer" => [
                "email" => $mp['payer']['email'],
                "identification" => [
                    "type" => $mp['payer']['identification']['type'],
                    "number" => $mp['payer']['identification']['number']
                ]
            ]
        ], $request_options);

        if($payment->status == "approved") {
            endCheckout($payment->id);
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function endCheckout($id_payment)
{

    $total_process = 0;

    if (isset($_COOKIE['id-product']) && isset($_COOKIE['quantity-order'])) {

        $id_product = json_decode($_COOKIE['id-product'], true);
        $quantity_order = json_decode($_COOKIE['quantity-order'], true);

        foreach ($id_product as $key => $value) {

            $url = CurlController::api() . "products?linkTo=id_product&qualTo=$value&select=stock_product,sales_product";
            $method = "GET";
            $fields = array();
            $header = array();

            $products = CurlController::request($url, $method, $fields, $header)->result[0];

            // Actualizamos las ventas y disminuimos el stock
            $stock = $products->stock_product - $quantity_order[$key];
            $sales = $products->sales_product + $quantity_order[$key];

            $url = CurlController::api() . "products?id=$value&nameId=id_product&token={$_SESSION['user']->token_user}";
            $method = "PUT";
            $fields = "sales_product=$sales&stock_product=$stock";
            $header = array();

            $update_products = CurlController::request($url, $method, $fields, $header);

            if ($update_products->status == 200) {
                $total_process++;
            }
        }
    }

    // Actualizamos el estado de la orden
    if (isset($_COOKIE['id-order'])) {
        $id_order = json_decode($_COOKIE['id-order'], true);

        foreach ($id_order as $key => $value) {
            $url = CurlController::api() . "orders?id=$value&nameId=id_order&token={$_SESSION['user']->token_user}";
            $method = "PUT";
            $fields = "status_order=pending";
            $header = array();

            $update_orders = CurlController::request($url, $method, $fields, $header);

            if ($update_orders->status == 200) {
                $total_process++;
            }
        }
    }

    // Actualizamos el estado de la venta
    if (isset($_COOKIE['id-sale'])) {
        $id_sale = json_decode($_COOKIE['id-sale'], true);

        foreach ($id_sale as $key => $value) {
            $url = CurlController::api() . "sales?id=$value&nameId=id_sale&token={$_SESSION['user']->token_user}";
            $method = "PUT";
            $fields = "status_sale=pending&id_payment_sale=$id_payment";
            $header = array();

            $update_sales = CurlController::request($url, $method, $fields, $header);

            if ($update_sales->status == 200) {
                $total_process++;
            }
        }
    }

    // Cerramos el proceso

    if ($total_process == (count($id_product) + count($id_order) + count($id_sale))) {

        echo '
        <script>
            document.cookie = "list-sc=; max-age= 0";
            document.cookie = "id-product=; max-age= 0";
            document.cookie = "quantity-order=; max-age= 0";
            document.cookie = "id-order=; max-age= 0";
            document.cookie = "id-sale=; max-age= 0";
            document.cookie = "mp=; max-age= 0";

            sweetAlert("success", "The purchase has been executed successfully", "' . TemplateController::path() . 'account&my-shopping#profile-user");
        </script>';
    }
}

?>