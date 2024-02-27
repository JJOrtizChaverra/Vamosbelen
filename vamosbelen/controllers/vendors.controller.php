<?php

class VendorsController
{
    // Registro de nueva tienda y producto

    public function new_vendor()
    {

        // Validar que si vengan variables post
        if (isset($_POST['name-store'])) {

            $name_store = $_POST['name-store'];
            $about_store = $_POST['about-store'];
            $city_store = $_POST['city-store'];
            $phone_store = $_POST['phone-store'];
            $address_store = $_POST['address-store'];
            $url_store = $_POST['url-store'];
            $email_store = $_POST['email-store'];
            $country_store = $_POST['country-store'];

            // Validar sintaxis lado servidor
            if (
                preg_match('/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{3,}$/', $name_store) &&
                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $about_store) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $city_store) &&
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $phone_store) &&
                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $address_store)
            ) {

                // Validar que si vengan imagenes

                if (
                    isset($_FILES['logo-store']['tmp_name']) &&
                    !empty($_FILES['logo-store']['tmp_name']) &&
                    isset($_FILES['cover-store']['tmp_name']) &&
                    !empty($_FILES['cover-store']['tmp_name'])
                ) {

                    // Guardar imagen del logo

                    $image_logo = $_FILES['logo-store'];
                    $folder_logo = "img/stores";
                    $path_logo = $url_store;
                    $width_logo = 270;
                    $height_logo = 270;
                    $name_logo = "logo";

                    $logo_store = TemplateController::save_image($image_logo, $folder_logo, $path_logo, $width_logo, $height_logo, $name_logo);

                    if ($logo_store != "Error") {

                        // Guardar imagen de la portada

                        $image_cover = $_FILES['cover-store'];
                        $folder_cover = "img/stores";
                        $path_cover = $url_store;
                        $width_cover = 1424;
                        $height_cover = 768;
                        $name_cover = "cover";

                        $cover_store = TemplateController::save_image($image_cover, $folder_cover, $path_cover, $width_cover, $height_cover, $name_cover);

                        if ($cover_store != "Error") {

                            // Agrupar redes sociales
                            $socialnetwork = array();

                            if (isset($_POST['facebook-store']) && !empty($_POST['facebook-store'])) {
                                array_push($socialnetwork, ["facebook" => "https://facebook.com/{$_POST['facebook-store']}"]);
                            }

                            if (isset($_POST['instagram-store']) && !empty($_POST['instagram-store'])) {
                                array_push($socialnetwork, ["instagram" => "https://instagram.com/{$_POST['instagram-store']}"]);
                            }

                            if (isset($_POST['twitter-store']) && !empty($_POST['twitter-store'])) {
                                array_push($socialnetwork, ["twitter" => "https://twitter.com/{$_POST['twitter-store']}"]);
                            }

                            if (isset($_POST['linkedin-store']) && !empty($_POST['linkedin-store'])) {
                                array_push($socialnetwork, ["linkedin" => "https://linkedin.com/{$_POST['linkedin-store']}"]);
                            }

                            if (isset($_POST['youtube-store']) && !empty($_POST['youtube-store'])) {
                                array_push($socialnetwork, ["youtube" => "https://youtube.com/{$_POST['youtube-store']}"]);
                            }

                            if (count($socialnetwork) > 0) {
                                $socialnetwork = json_encode($socialnetwork);
                            } else {
                                $socialnetwork = null;
                            }

                            // Organizar los datos de la tienda que se subiran a base de datos

                            $data_store = [
                                "id_user_store" => $_SESSION['user']->id_user,
                                "name_store" => TemplateController::capitalize($name_store),
                                "url_store" => $url_store,
                                "logo_store" => $logo_store,
                                "cover_store" => $cover_store,
                                "about_store" => $about_store,
                                "abstract_store" => substr($about_store, 0, 100) . "...",
                                "email_store" => $email_store,
                                "country_store" => explode("_", $country_store)[0],
                                "city_store" => $city_store,
                                "phone_store" =>  explode("_", $country_store)[1] . "_" . $phone_store,
                                "address_store" => $address_store,
                                "socialnetwork_store" => $socialnetwork,
                                "products_store" => 1,
                                "date_created_store" => date("Y-m-d")
                            ];

                            $url = CurlController::api() . "stores?token={$_SESSION['user']->token_user}";
                            $method = "POST";
                            $fields = $data_store;
                            $header = array(
                                "Content-Type" => "application/x-www-form-urlencoded"
                            );

                            $save_store = CurlController::request($url, $method, $fields, $header);

                            print_r($save_store);

                            if ($save_store->status == 200) {

                                $save_product = VendorsController::new_product($save_store->result->lastId);
                                return $save_product;
                            } else {
                                echo '
                                <script>
                                    notieAlert(3, "Error saving store");
                                    formatInputs();
                                </script>';
                                return;
                            }
                        } else {
                            echo '
                            <script>
                                notieAlert(3, "Error saving cover image");
                                formatInputs();
                            </script>';
                            return;
                        }
                    } else {
                        echo '
                        <script>
                            notieAlert(3, "Error saving logo image");
                            formatInputs();
                        </script>';
                        return;
                    }
                } else {
                    echo '
                    <script>
                        notieAlert(3, "Error: there are no images of the store");
                        formatInputs();
                    </script>';
                    return;
                }
            } else {
                echo '
                <script>
                    notieAlert(3, "Error in the syntax of the fields");
                    formatInputs();
                </script>';
                return;
            }
        }
    }

    // Registro de nuevo producto
    static public function new_product($id_store)
    {

        if (isset($_POST['name-product'])) {

            $name_product = $_POST['name-product'];
            $url_product = $_POST['url-product'];
            $category_product = $_POST['category-product'];
            $subcategory_product = $_POST['subcategory-product'];
            $description_product = $_POST['description-product'];
            $tags_product = $_POST['tags-product'];

            // Validar sintaxis lado servidor

            if (preg_match('/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{3,}$/', $name_product)) {

                // Agrupar resumen del producto

                if (isset($_POST['input-summary'])) {
                    $summary_product = array();

                    for ($i = 0; $i < $_POST['input-summary']; $i++) {
                        array_push($summary_product, $_POST["summary-product_$i"]);
                    }
                }

                // Agrupar detalles del producto

                if (isset($_POST['input-details'])) {
                    $details_product = array();

                    for ($i = 0; $i < $_POST['input-details']; $i++) {
                        $details_product[$i] = (object)["title" => $_POST["details-title-product_$i"], "value" => $_POST["details-value-product_$i"]];
                    }
                }

                // Agrupar especificaciones del producto

                if (isset($_POST['input-specifications'])) {
                    $specifications_product = array();

                    for ($i = 0; $i < $_POST['input-specifications']; $i++) {

                        $specifications_product[$i] = (object)[$_POST["specifications-title-product_$i"] => explode(",",  $_POST["specifications-value-product_$i"])];
                    }

                    $specifications_product = json_encode($specifications_product);

                    if ($specifications_product == '[{"":[""]}]') {
                        $specifications_product = null;
                    }
                } else {
                    $specifications_product = null;
                }

                // Validamos la imagen del producto
                if (isset($_FILES['image-product']['tmp_name']) && !empty($_FILES['image-product']['tmp_name'])) {

                    $image = $_FILES['image-product'];
                    $folder = "img/products";
                    $path = explode("_", $category_product)[1];
                    $width = 300;
                    $height = 300;
                    $name = $url_product;

                    $save_image_product = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    if ($save_image_product == "Error") {
                        echo '
                        <script>
                            notieAlert(3, "Failed to save product image");
                            formatInputs();
                        </script>
                        ';

                        return;
                    }
                } else {
                    echo '
                    <script>
                        notieAlert(3, "Failed to save product image");
                        formatInputs();
                    </script>
                ';
                    return;
                }

                // Guardar imagenes de la galeria

                $gallery_product = array();
                $count_gallery = 0;

                foreach (json_decode($_POST['gallery-product'], true) as $key => $value) {
                    $count_gallery++;

                    $image["tmp_name"] = $value['file'];
                    $image["type"] = $value['type'];
                    $image["mode"] = "base64";

                    $folder = "img/products";
                    $path = explode("_", $category_product)[1] . "/gallery";
                    $width = $value["width"];
                    $height = $value["height"];
                    $name = mt_rand(10000, 99999);

                    $save_image_gallery = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    array_push($gallery_product, $save_image_gallery);
                }

                if (count($gallery_product) == $count_gallery) {


                    // Agrupar informacion de top banner
                    if (isset($_FILES['top-banner-img-tag']['tmp_name']) && !empty($_FILES['top-banner-img-tag']['tmp_name'])) {

                        $image = $_FILES['top-banner-img-tag'];
                        $folder = "img/products";
                        $path = explode("_", $category_product)[1] . "/top";
                        $width = 1920;
                        $height = 80;
                        $name = mt_rand(10000, 99999);

                        $save_image_topbanner = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                        if ($save_image_topbanner != "Error") {

                            if (
                                isset($_POST['top-banner-h3-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-h3-tag']) &&
                                isset($_POST['top-banner-p1-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-p1-tag']) &&
                                isset($_POST['top-banner-h4-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-h4-tag']) &&
                                isset($_POST['top-banner-p2-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-p2-tag']) &&
                                isset($_POST['top-banner-span-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-span-tag']) &&
                                isset($_POST['top-banner-button-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-button-tag'])
                            ) {
                                $top_banner = (object)[
                                    "H3 tag" => TemplateController::capitalize($_POST['top-banner-h3-tag']),
                                    "P1 tag" => TemplateController::capitalize($_POST['top-banner-p1-tag']),
                                    "H4 tag" => TemplateController::capitalize($_POST['top-banner-h4-tag']),
                                    "P2 tag" => TemplateController::capitalize($_POST['top-banner-p2-tag']),
                                    "Span tag" => TemplateController::capitalize($_POST['top-banner-span-tag']),
                                    "Button tag" => TemplateController::capitalize($_POST['top-banner-button-tag']),
                                    "IMG tag" => $save_image_topbanner
                                ];

                                $top_banner = json_encode($top_banner);
                            } else {
                                echo '
                                <script>
                                    notieAlert(3, "Error in the syntax of the fields of Top Banner");
                                    formatInputs();
                                </script>
                                ';
                                return;
                            }
                        }
                    } else {
                        $top_banner = null;
                    }

                    // Agrupar informacion para el default banner
                    if (isset($_FILES['default-banner-img']['tmp_name']) && !empty($_FILES['default-banner-img']['tmp_name'])) {

                        $image = $_FILES['default-banner-img'];
                        $folder = "img/products";
                        $path = explode("_", $category_product)[1] . "/default";
                        $width = 570;
                        $height = 210;
                        $name = mt_rand(10000, 99999);

                        $save_image_defaultbanner = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                        if ($save_image_defaultbanner == "Error") {
                            echo '
                            <script>
                                notieAlert(3, "Failed to save default banner image");
                                formatInputs();
                            </script>
                            ';
                            return;
                        }
                    } else {
                        $save_image_defaultbanner = null;
                    }

                    // Agrupar informacion para el horizontal slider
                    if (isset($_FILES['horizontal-slider-img-tag']['tmp_name']) && !empty($_FILES['horizontal-slider-img-tag']['tmp_name'])) {

                        $image = $_FILES['horizontal-slider-img-tag'];
                        $folder = "img/products";
                        $path = explode("_", $category_product)[1] . "/horizontal";
                        $width = 1920;
                        $height = 358;
                        $name = mt_rand(10000, 99999);

                        $save_image_horizontalslider = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                        if ($save_image_horizontalslider != "Error") {

                            if (
                                isset($_POST['horizontal-slider-h4-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h4-tag']) &&
                                isset($_POST['horizontal-slider-h3-1-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-1-tag']) &&
                                isset($_POST['horizontal-slider-h3-2-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-2-tag']) &&
                                isset($_POST['horizontal-slider-h3-3-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-3-tag']) &&
                                isset($_POST['horizontal-slider-h3-4s-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-4s-tag']) &&
                                isset($_POST['horizontal-slider-button-tag']) &&
                                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-button-tag'])
                            ) {
                                $horizontal_slider = (object)[
                                    "H4 tag" => TemplateController::capitalize($_POST['horizontal-slider-h4-tag']),
                                    "H3-1 tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-1-tag']),
                                    "H3-2 tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-2-tag']),
                                    "H3-3 tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-3-tag']),
                                    "H3-4s tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-4s-tag']),
                                    "Button tag" => TemplateController::capitalize($_POST['horizontal-slider-button-tag']),
                                    "IMG tag" => $save_image_horizontalslider
                                ];

                                $horizontal_slider = json_encode($horizontal_slider);
                            } else {
                                echo '
                                <script>
                                    notieAlert(3, "Error in the syntax of the fields of Horizontal Slider Banner");
                                    formatInputs();
                                </script>
                                ';
                                return;
                            }
                        }
                    } else {
                        $horizontal_slider = null;
                    }

                    // Agrupar informacion para el vertical slider
                    if (isset($_FILES['default-vertical-slider-img']['tmp_name']) && !empty($_FILES['default-vertical-slider-img']['tmp_name'])) {

                        $image = $_FILES['default-vertical-slider-img'];
                        $folder = "img/products";
                        $path = explode("_", $category_product)[1] . "/vertical";
                        $width = 263;
                        $height = 629;
                        $name = mt_rand(10000, 99999);

                        $save_image_verticalslider = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                        if ($save_image_verticalslider == "Error") {
                            echo '
                            <script>
                                notieAlert(3, "Failed to save vertical slider image");
                                formatInputs();
                            </script>
                            ';
                            return;
                        }
                    } else {
                        $save_image_verticalslider = null;
                    }

                    // Agrupar informacion del video
                    if (!empty($_POST['type-video']) && !empty($_POST['id-video'])) {

                        $video_product = array();

                        if (preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}$/', $_POST['type-video']) && preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}$/', $_POST['id-video'])) {

                            array_push($video_product, $_POST['type-video']);
                            array_push($video_product, $_POST['id-video']);

                            $video_product = json_encode($video_product);
                        } else {
                            echo '
                            <script>
                                notieAlert(3, "Error in the syntax of the fields of video");
                                formatInputs();
                            </script>
                            ';
                            return;
                        }
                    } else {
                        $video_product = null;
                    }

                    // Agrupar informacion de la oferta
                    if (!empty($_POST['type-offer-product']) && !empty($_POST['value-offer-product']) && !empty($_POST['date-offer-product'])) {


                        if (preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['value-offer-product'])) {

                            $offer_product = array($_POST['type-offer-product'], $_POST['value-offer-product'], $_POST['date-offer-product']);

                            $offer_product = json_encode($offer_product);
                        }
                    } else {
                        $offer_product = null;
                    }

                    // Agrupar informacion de precio, envio y dias de entrega
                    if (isset($_POST['price-product'])) {

                        if (
                            preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['price-product']) &&
                            preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['shipping-product']) &&
                            preg_match('/^[0-9]{1,}$/', $_POST['delivery-time-product']) &&
                            preg_match('/^[0-9]{1,}$/', $_POST['stock-product'])
                        ) {
                            // Data del producto

                            $data_product = [
                                "approval_product" => "review",
                                "feedback_product" => "Your produc is under review",
                                "state_product" => "show",
                                "id_store_product" => $id_store,
                                "name_product" => TemplateController::capitalize($name_product),
                                "url_product" => $url_product,
                                "id_category_product" => explode("_", $category_product)[0],
                                "id_subcategory_product" => explode("_", $subcategory_product)[0],
                                "title_list_product" => explode("_", $subcategory_product)[1],
                                "description_product" => $description_product,
                                "summary_product" => json_encode($summary_product),
                                "details_product" => json_encode($details_product),
                                "specifications_product" => $specifications_product,
                                "tags_product" => json_encode(explode(",", $tags_product)),
                                "image_product" => $save_image_product,
                                "gallery_product" => json_encode($gallery_product),
                                "top_banner_product" => $top_banner,
                                "default_banner_product" => $save_image_defaultbanner,
                                "horizontal_slider_product" => $horizontal_slider,
                                "vertical_slider_product" => $save_image_verticalslider,
                                "video_product" => $video_product,
                                "offer_product" => $offer_product,
                                "price_product" => $_POST['price-product'],
                                "shipping_product" => $_POST['shipping-product'],
                                "delivery_time_product" => $_POST['delivery-time-product'],
                                "stock_product" => $_POST['stock-product'],
                                "date_created_product" => date("Y-m-d")
                            ];

                            $url = CurlController::api() . "products?token={$_SESSION['user']->token_user}";
                            $method = "POST";
                            $fields = $data_product;
                            $header = [
                                "Content-Type" => "application/x-www-form-urlencoded"
                            ];

                            $save_product = CurlController::request($url, $method, $fields, $header);

                            if ($save_product->status == 200) {

                                echo '
                                <script>
                                    sweetAlert("success", "Your records were created succesfully", "' . TemplateController::path() . 'account&my-store");
                                    formatInputs();
                                </script>
                                ';
                            } else {
                                echo '
                                <script>
                                    notieAlert(3, "Error saving store");
                                    formatInputs();
                                </script>';
                                return;
                            }
                        } else {
                            echo '
                            <script>
                                notieAlert(3, "Error in the syntax of the fields of price product or shipping price, or delivery time product");
                                formatInputs();
                            </script>
                            ';
                            return;
                        }
                    }
                }
            } else {
                echo '
                <script>
                    notieAlert(3, "Error in the syntax of the fields");
                    formatInputs();
                </script>
                ';

                return;
            }
        }
    }

    // Editar la tienda
    public function edit_store()
    {

        // Validar que si vengan variables post
        if (isset($_POST['id-store'])) {

            $name_store = $_POST['name-store'];
            $about_store = $_POST['about-store'];
            $city_store = $_POST['city-store'];
            $phone_store = $_POST['phone-store'];
            $address_store = $_POST['address-store'];
            $url_store = $_POST['url-store'];
            $email_store = $_POST['email-store'];
            $country_store = $_POST['country-store'];

            // Validar sintaxis lado servidor
            if (
                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $about_store) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $city_store) &&
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $phone_store) &&
                preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $address_store)
            ) {

                // Guardar imagen del logo si el usuario la cambia

                if (isset($_FILES['logo-store']['tmp_name']) && !empty($_FILES['logo-store']['tmp_name'])) {

                    // Guardar imagen del logo

                    $image_logo = $_FILES['logo-store'];
                    $folder_logo = "img/stores";
                    $path_logo = $url_store;
                    $width_logo = 270;
                    $height_logo = 270;
                    $name_logo = "logo";

                    $logo_store = TemplateController::save_image($image_logo, $folder_logo, $path_logo, $width_logo, $height_logo, $name_logo);

                    if ($logo_store == "Error") {

                        echo '
                        <script>
                            notieAlert(3, "Error saving logo image");
                            formatInputs();
                        </script>';

                        return;
                    }
                } else {
                    $logo_store = $_POST['logo-store-old'];
                }


                // Guardar imagen de portada si el usuario la cambia

                if (isset($_FILES['cover-store']['tmp_name']) && !empty($_FILES['cover-store']['tmp_name'])) {

                    // Guardar imagen de la portada

                    $image_cover = $_FILES['cover-store'];
                    $folder_cover = "img/stores";
                    $path_cover = $url_store;
                    $width_cover = 1424;
                    $height_cover = 768;
                    $name_cover = "cover";

                    $cover_store = TemplateController::save_image($image_cover, $folder_cover, $path_cover, $width_cover, $height_cover, $name_cover);

                    if ($cover_store == "Error") {
                        echo '
                        <script>
                            notieAlert(3, "Error saving cover image");
                            formatInputs();
                        </script>';
                        return;
                    }
                } else {
                    $cover_store = $_POST['cover-store-old'];
                }

                // Agrupar redes sociales
                $socialnetwork = array();

                if (isset($_POST['facebook-store']) && !empty($_POST['facebook-store'])) {
                    array_push($socialnetwork, ["facebook" => "https://facebook.com/{$_POST['facebook-store']}"]);
                }

                if (isset($_POST['instagram-store']) && !empty($_POST['instagram-store'])) {
                    array_push($socialnetwork, ["instagram" => "https://instagram.com/{$_POST['instagram-store']}"]);
                }

                if (isset($_POST['twitter-store']) && !empty($_POST['twitter-store'])) {
                    array_push($socialnetwork, ["twitter" => "https://twitter.com/{$_POST['twitter-store']}"]);
                }

                if (isset($_POST['linkedin-store']) && !empty($_POST['linkedin-store'])) {
                    array_push($socialnetwork, ["linkedin" => "https://linkedin.com/{$_POST['linkedin-store']}"]);
                }

                if (isset($_POST['youtube-store']) && !empty($_POST['youtube-store'])) {
                    array_push($socialnetwork, ["youtube" => "https://youtube.com/{$_POST['youtube-store']}"]);
                }

                if (count($socialnetwork) > 0) {
                    $socialnetwork = json_encode($socialnetwork);
                } else {
                    $socialnetwork = null;
                }

                // Organizar los datos de la tienda que se subiran a base de datos

                $data_store = "logo_store=$logo_store&cover_store=$cover_store&about_store=$about_store&abstract_store=" . substr($about_store, 0, 100) . "...&email_store=$email_store&country_store=" . explode("_", $country_store)[0] . "&city_store=$city_store&phone_store=" . explode("_", $country_store)[1] . "_" . $phone_store . "&address_store=$address_store&socialnetwork_store=$socialnetwork";

                $url = CurlController::api() . "stores?id={$_POST['id-store']}&nameId=id_store&token={$_SESSION['user']->token_user}";
                $method = "PUT";
                $fields = $data_store;
                $header = array(
                    "Content-Type" => "application/x-www-form-urlencoded"
                );

                $edit = CurlController::request($url, $method, $fields, $header);

                if ($edit->status == 200) {
                    echo '
                    <script>
                        formatInputs();
                        sweetAlert("success", "Your store were edited successfully", "' . TemplateController::path() . 'account&my-store");
                    </script>';
                } else {
                    echo '
                    <script>
                        formatInputs();
                        notieAlert(3, "Error saving changes store");
                        </script>';
                    return;
                }
            } else {
                echo '
                <script>
                    formatInputs();
                    notieAlert(3, "Error in the syntax of the fields");
                </script>
                ';
                return;
            }
        }
    }


    // Metodo para editar el producto
    static public function edit_product()
    {

        if (isset($_POST['id-product'])) {

            // Agrupar resumen del producto

            if (isset($_POST['input-summary'])) {
                $summary_product = array();

                for ($i = 0; $i < $_POST['input-summary']; $i++) {
                    array_push($summary_product, $_POST["summary-product_$i"]);
                }
            }

            // Agrupar detalles del producto

            if (isset($_POST["input-details"])) {

                $details_product = array();

                for ($i = 0; $i < $_POST["input-details"]; $i++) {

                    $details_product[$i] = (object)["title" => $_POST["details-title-product_" . $i], "value" => $_POST["details-value-product_" . $i]];
                }
            }

            // Agrupar especificaciones del producto

            if (isset($_POST["input-specifications"])) {

                $specifications_product = array();

                for ($i = 0; $i < $_POST["input-specifications"]; $i++) {

                    $specifications_product[$i] = (object)[$_POST["specifications-title-product_" . $i] => explode(",", $_POST["specifications-value-product_" . $i])];
                }

                $specifications_product = json_encode($specifications_product);

                if ($specifications_product == '[{"":[""]}]') {

                    $specifications_product = null;
                }
            } else {

                $specifications_product = null;
            }

            // Validamos la imagen del producto
            if (isset($_FILES['image-product']['tmp_name']) && !empty($_FILES['image-product']['tmp_name'])) {

                $image = $_FILES['image-product'];
                $folder = "img/products";
                $path = explode("_", $_POST['category-product'])[1];
                $width = 300;
                $height = 300;
                $name = $_POST['url-product'];

                $save_image_product = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                if ($save_image_product == "Error") {
                    echo '
                    <script>
                        notieAlert(3, "Failed to save product image");
                        formatInputs();
                    </script>
                    ';

                    return;
                }
            } else {
                $save_image_product = $_POST['image-product-old'];
            }


            // Guardar imagenes de galeria

            $gallery_product = array();
            $count_gallery = 0;
            $count_gallery2 = 0;
            $continue_edit = false;

            if (!empty($_POST['gallery-product'])) {

                foreach (json_decode($_POST['gallery-product'], true) as $key => $value) {


                    $count_gallery++;

                    $image["tmp_name"] = $value['file'];
                    $image["type"] = $value['type'];
                    $image["mode"] = "base64";

                    $folder = "img/products";
                    $path = explode("_", $_POST['category-product'])[1] . "/gallery";
                    $width = $value["width"];
                    $height = $value["height"];
                    $name = mt_rand(10000, 99999);

                    $save_image_gallery = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    array_push($gallery_product, $save_image_gallery);

                    if (count($gallery_product) == $count_gallery) {

                        if (!empty($_POST['gallery-product-old'])) {
                            foreach (json_decode($_POST['gallery-product-old'], true) as $key => $value) {

                                $count_gallery2++;

                                array_push($gallery_product, $value);
                            }

                            if (count(json_decode($_POST['gallery-product-old'], true)) == $count_gallery2) {
                                $continue_edit = true;
                            }
                        } else {
                            $continue_edit = true;
                        }
                    }
                }
            } else {
                if (!empty($_POST['gallery-product-old'])) {

                    $count_gallery2 = 0;

                    foreach (json_decode($_POST['gallery-product-old'], true) as $key => $value) {

                        $count_gallery2++;

                        array_push($gallery_product, $value);
                    }

                    if (count(json_decode($_POST['gallery-product-old'], true)) == $count_gallery2) {
                        $continue_edit = true;
                    }
                }
            }

            // Eliminamos archivos basura del servidor

            if (!empty($_POST['delete-gallery-product'])) {
                foreach (json_decode($_POST['delete-gallery-product'], true) as $key => $value) {
                    unlink("views/img/products/" . explode("_", $_POST["category-product"])[1] . "/gallery/$value");
                }
            }

            // Validamos que la galeria no venga vacia
            if (count($gallery_product) == 0) {

                echo '<script>

                  fncFormatInputs();

                  fncNotie(3, "The gallery cannot be empty");

              </script>';

                return;
            }


            if ($continue_edit) {

                // Agrupar informacion de top banner
                if (isset($_FILES['top-banner-img-tag']['tmp_name']) && !empty($_FILES['top-banner-img-tag']['tmp_name'])) {

                    $image = $_FILES['top-banner-img-tag'];
                    $folder = "img/products";
                    $path = explode("_", $_POST['category-product'])[1] . "/top";
                    $width = 1920;
                    $height = 80;
                    $name = mt_rand(10000, 99999);

                    $save_image_topbanner = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    if ($save_image_topbanner == "Error") {
                        echo '
                        <script>
                            notieAlert(3, "Error saving top banner image");
                            formatInputs();
                        </script>
                        ';
                        return;
                    } else {

                        if ($_POST['top-banner-old'] != null) {
                            unlink("views/$folder/$path/{$_POST['top-banner-old']}");
                        }
                    }
                } else {

                    if ($_POST['top-banner-old'] != null) {
                        $save_image_topbanner = $_POST['top-banner-old'];
                        $top_banner = "";
                    } else {
                        $top_banner = null;
                    }
                }

                if ($top_banner != null) {
                    if (
                        isset($_POST['top-banner-h3-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-h3-tag']) &&
                        isset($_POST['top-banner-p1-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-p1-tag']) &&
                        isset($_POST['top-banner-h4-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-h4-tag']) &&
                        isset($_POST['top-banner-p2-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-p2-tag']) &&
                        isset($_POST['top-banner-span-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-span-tag']) &&
                        isset($_POST['top-banner-button-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['top-banner-button-tag'])
                    ) {
                        $top_banner = (object)[
                            "H3 tag" => TemplateController::capitalize($_POST['top-banner-h3-tag']),
                            "P1 tag" => TemplateController::capitalize($_POST['top-banner-p1-tag']),
                            "H4 tag" => TemplateController::capitalize($_POST['top-banner-h4-tag']),
                            "P2 tag" => TemplateController::capitalize($_POST['top-banner-p2-tag']),
                            "Span tag" => TemplateController::capitalize($_POST['top-banner-span-tag']),
                            "Button tag" => TemplateController::capitalize($_POST['top-banner-button-tag']),
                            "IMG tag" => $save_image_topbanner
                        ];

                        $top_banner = json_encode($top_banner);
                    } else {
                        echo '
                        <script>
                            notieAlert(3, "Error in the syntax of the fields of Top Banner");
                            formatInputs();
                        </script>
                        ';
                        return;
                    }
                }


                // Agrupar informacion para el default banner
                if (isset($_FILES['default-banner-img']['tmp_name']) && !empty($_FILES['default-banner-img']['tmp_name'])) {

                    $image = $_FILES['default-banner-img'];
                    $folder = "img/products";
                    $path = explode("_", $_POST['category-product'])[1] . "/default";
                    $width = 570;
                    $height = 210;
                    $name = mt_rand(10000, 99999);

                    $save_image_defaultbanner = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    if ($save_image_defaultbanner == "Error") {
                        echo '
                        <script>
                            notieAlert(3, "Failed to save default banner image");
                            formatInputs();
                        </script>
                        ';
                        return;
                    } else {
                        if ($_POST['default-banner-old'] != null) {
                            unlink("views/$folder/$path/{$_POST['default-banner-old']}");
                        }
                    }
                } else {
                    if ($_POST['default-banner-old'] != null) {
                        $save_image_defaultbanner = $_POST['default-banner-old'];
                    } else {
                        $save_image_defaultbanner = null;
                    }
                }

                // Agrupar informacion para el horizontal slider
                if (isset($_FILES['horizontal-slider-img-tag']['tmp_name']) && !empty($_FILES['horizontal-slider-img-tag']['tmp_name'])) {

                    $image = $_FILES['horizontal-slider-img-tag'];
                    $folder = "img/products";
                    $path = explode("_", $_POST['category-product'])[1] . "/horizontal";
                    $width = 1920;
                    $height = 358;
                    $name = mt_rand(10000, 99999);

                    $save_image_horizontalslider = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    if ($save_image_horizontalslider == "Error") {

                        echo '
                        <script>
                            notieAlert(3, "Error saving horizontal slider image");
                            formatInputs();
                        </script>
                        ';
                        return;
                    } else {

                        if ($_POST['horizontal-slider-old'] != null) {
                            unlink("views/$folder/$path/{$_POST['horizontal-slider-old']}");
                        }
                    }
                } else {

                    if ($_POST['horizontal-slider-old'] != null) {
                        $save_image_horizontalslider = $_POST['horizontal-slider-old'];
                        $horizontal_slider = "";
                    } else {
                        $horizontal_slider = null;
                    }
                }

                if ($horizontal_slider != null) {
                    if (
                        isset($_POST['horizontal-slider-h4-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h4-tag']) &&
                        isset($_POST['horizontal-slider-h3-1-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-1-tag']) &&
                        isset($_POST['horizontal-slider-h3-2-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-2-tag']) &&
                        isset($_POST['horizontal-slider-h3-3-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-3-tag']) &&
                        isset($_POST['horizontal-slider-h3-4s-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-h3-4s-tag']) &&
                        isset($_POST['horizontal-slider-button-tag']) &&
                        preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['horizontal-slider-button-tag'])
                    ) {
                        $horizontal_slider = (object)[
                            "H4 tag" => TemplateController::capitalize($_POST['horizontal-slider-h4-tag']),
                            "H3-1 tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-1-tag']),
                            "H3-2 tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-2-tag']),
                            "H3-3 tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-3-tag']),
                            "H3-4s tag" => TemplateController::capitalize($_POST['horizontal-slider-h3-4s-tag']),
                            "Button tag" => TemplateController::capitalize($_POST['horizontal-slider-button-tag']),
                            "IMG tag" => $save_image_horizontalslider
                        ];

                        $horizontal_slider = json_encode($horizontal_slider);
                    } else {
                        echo '
                        <script>
                            notieAlert(3, "Error in the syntax of the fields of Horizontal Slider Banner");
                            formatInputs();
                        </script>
                        ';
                        return;
                    }
                }

                // Agrupar informacion del vertical slider
                if (isset($_FILES['default-vertical-slider-img']['tmp_name']) && !empty($_FILES['default-vertical-slider-img']['tmp_name'])) {

                    $image = $_FILES['default-vertical-slider-img'];
                    $folder = "img/products";
                    $path = explode("_", $_POST['category-product'])[1] . "/vertical";
                    $width = 263;
                    $height = 629;
                    $name = mt_rand(10000, 99999);

                    $save_image_verticalslider = TemplateController::save_image($image, $folder, $path, $width, $height, $name);

                    if ($save_image_verticalslider == "Error") {
                        echo '
                        <script>
                            notieAlert(3, "Failed to save vertical slider image");
                            formatInputs();
                        </script>
                        ';
                        return;
                    } else {

                        if ($_POST['vertical-slide-old'] != null) {
                            unlink("views/$folder/$path/{$_POST['vertical-slide-old']}");
                        }
                    }
                } else {
                    if ($_POST['vertical-slide-old'] != null) {
                        $save_image_verticalslider = $_POST['vertical-slide-old'];
                    } else {
                        $save_image_verticalslider = null;
                    }
                }

                // Agrupar informacion del video
                if (!empty($_POST['type-video']) && !empty($_POST['id-video'])) {

                    $video_product = array();

                    if (preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}$/', $_POST['type-video']) && preg_match('/^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}$/', $_POST['id-video'])) {

                        array_push($video_product, $_POST['type-video']);
                        array_push($video_product, $_POST['id-video']);

                        $video_product = json_encode($video_product);
                    } else {
                        echo '
                        <script>
                            notieAlert(3, "Error in the syntax of the fields of video");
                            formatInputs();
                        </script>
                        ';
                        return;
                    }
                } else {
                    $video_product = null;
                }

                // Agrupar informacion de la oferta
                if (!empty($_POST['type-offer-product']) && !empty($_POST['value-offer-product']) && !empty($_POST['date-offer-product'])) {

                    if (preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['value-offer-product'])) {

                        $offer_product = array($_POST['type-offer-product'], $_POST['value-offer-product'], $_POST['date-offer-product']);

                        $offer_product = json_encode($offer_product);
                    }
                } else {
                    $offer_product = null;
                }


                // Agrupar informacion de precio, envio y dias de entrega
                if (isset($_POST['price-product'])) {

                    if (
                        preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['price-product']) &&
                        preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['shipping-product']) &&
                        preg_match('/^[0-9]{1,}$/', $_POST['delivery-time-product']) &&
                        preg_match('/^[0-9]{1,}$/', $_POST['stock-product'])
                    ) {

                        // Creamos los datos a actualizar
                        $data_product = "description_product=" . TemplateController::html_clean(html_entity_decode(str_replace('+', '%2b', $_POST["description-product"]))) .
                            "&summary_product=" . json_encode($summary_product) .
                            "&details_product=" . json_encode($details_product) .
                            "&specifications_product=" . $specifications_product .
                            "&tags_product=" . json_encode(explode(",", $_POST['tags-product'])) .
                            "&image_product=" . $save_image_product .
                            "&gallery_product=" . json_encode($gallery_product) .
                            "&top_banner_product=" . $top_banner .
                            "&default_banner_product=" . $save_image_defaultbanner .
                            "&horizontal_slider_product=" . $horizontal_slider .
                            "&vertical_slider_product=" . $save_image_verticalslider .
                            "&video_product=" . $video_product .
                            "&offer_product=" . $offer_product .
                            "&price_product=" . $_POST["price-product"] .
                            "&shipping_product=" . $_POST["shipping-product"] .
                            "&delivery_time_product=" . $_POST["delivery-time-product"] .
                            "&stock_product=" . $_POST["stock-product"];

                        $url = CurlController::api() . "products?id=" . $_POST["id-product"] . "&nameId=id_product&token=" . $_SESSION["user"]->token_user;
                        $method = "PUT";
                        $fields = $data_product;
                        $header = [
                            "Content-Type" => "application/x-www-form-urlencoded"
                        ];

                        $save_product = CurlController::request($url, $method, $fields, $header);

                        if ($save_product->status == 200) {

                            echo '
                                <script>
                                    sweetAlert("success", "Your records were edited succesfully", "' . TemplateController::path() . 'account&my-store");
                                    formatInputs();
                                </script>
                                ';
                        } else {
                            echo '
                                <script>
                                    notieAlert(3, "Error saving product");
                                    formatInputs();
                                </script>';
                            return;
                        }
                    }
                }
            }
        }
    }


    // Actualizar la orden

    public function order_update()
    {

        if (isset($_POST['stage'])) {

            $process = json_decode(base64_decode($_POST['process-order']), true);

            $change_process = array();

            foreach ($process as $key => $value) {

                if ($value['stage'] == $_POST['stage']) {

                    $value['date'] = $_POST['date'];
                    $value['status'] = $_POST['status'];
                    $value['comment'] = $_POST['comment'];
                }

                array_push($change_process, $value);
            }

            $url_orders = CurlController::api() . "orders?id={$_POST['id-order']}&nameId=id_order&token={$_SESSION['user']->token_user}";
            $method_orders = "PUT";

            if ($_POST['stage'] == "delivered" && $_POST['status'] == "ok") {

                $fields_orders = "status_order=ok&process_order=" . json_encode($change_process);

                // Aprobar la venta
                $url_sales = CurlController::api() . "sales?id={$_POST['id-order']}&nameId=id_order_sale&token={$_SESSION['user']->token_user}";
                $method_sales = "PUT";
                $fields_sales = "id_store_sale=" . $_POST['id-store'] . "&status_sale=ok&name_product_sale=".$_POST['product-order'];
                $header_sales = [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ];

                $sale_update = CurlController::request($url_sales, $method_sales, $fields_sales, $header_sales);
            } else {
                $fields_orders = "process_order=" . json_encode($change_process);
            }

            $header_orders = [
                "Content-Type" => "application/x-www-form-urlencoded"
            ];

            $order_update = CurlController::request($url_orders, $method_orders, $fields_orders, $header_orders);

            if ($order_update->status == 200) {

                // Variables para el envio del correo electronico
                $name = $_POST['client-order'];
                $subject = "A change has ocurred in your purchase order";
                $email = $_POST['email-order'];
                $message = "A change has ocurred in your purchase order for your product {$_POST['product-order']}";
                $url = TemplateController::path() . "account&my-shopping";

                // Notificamos del cambio de orden al correo electronico
                $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                if ($send_email == "Ok") {
                    echo '
                    <script>
                        notieAlert(1, "Your records were edited succesfully");
                        formatInputs();
                    </script>';
                } else {
                    echo '
                    <script>
                        notieAlert(2, "The order was updated correctly but the purchasing user was not notified");
                        formatInputs();
                    </script>';
                }
            } else {
                echo '
                <script>
                    notieAlert(3, "Error updating order status");
                    formatInputs();
                </script>';
            }
        }
    }

    // Responder la disputa

    public function answer_dispute()
    {

        if (isset($_POST['answer-dispute'])) {

            $url = CurlController::api() . "disputes?id={$_POST['id-dispute']}&nameId=id_dispute&token={$_SESSION['user']->token_user}";
            $method = "PUT";
            $fields = "answer_dispute={$_POST['answer-dispute']}&date_answer_dispute=" . date("Y-m-d");
            $header = [
                "Content-Type" => "application/x-www-form-urlencoded"
            ];

            $answer_dispute = CurlController::request($url, $method, $fields, $header);

            if ($answer_dispute->status == 200) {

                // Variables para el envio del correo electronico
                $name = $_POST['client-dispute'];
                $subject = "Your dispute has been answered";
                $email = $_POST['email-dispute'];
                $message = "Your dispute has been answered with the message: {$_POST['answer-dispute']}";
                $url = TemplateController::path() . "account&my-shopping";

                // Notificamos la respuesta de la disputa al correo electronico del cliente
                $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                if ($send_email == "Ok") {
                    echo '
                    <script>
                        notieAlert(1, "The answer has been send");
                        formatInputs();
                    </script>';
                } else {
                    echo '
                    <script>
                        notieAlert(2, "The response dispute has been sent but the user was not notified");
                        formatInputs();
                    </script>';
                }
            } else {
                echo '
                <script>
                    notieAlert(3, "Error responding to dispute");
                    formatInputs();
                </script>';
            }
        }
    }

    // Responder el mensaje

    public function answer_message()
    {

        if (isset($_POST['answer-message'])) {

            $url = CurlController::api() . "messages?id={$_POST['id-message']}&nameId=id_message&token={$_SESSION['user']->token_user}";
            $method = "PUT";
            $fields = "answer_message={$_POST['answer-message']}&date_answer_message=" . date("Y-m-d");
            $header = [
                "Content-Type" => "application/x-www-form-urlencoded"
            ];

            $answer_message = CurlController::request($url, $method, $fields, $header);

            if ($answer_message->status == 200) {

                // Variables para el envio del correo electronico
                $name = $_POST['client-message'];
                $subject = "Your message has been answered";
                $email = $_POST['email-message'];
                $message = "Your message has been answered with the message: {$_POST['answer-message']}";
                $url = TemplateController::path() . $_POST['url-product'];

                // Notificamos la respuesta de la disputa al correo electronico del cliente
                $send_email = TemplateController::send_email($name, $subject, $email, $message, $url);

                if ($send_email == "Ok") {
                    echo '
                    <script>
                        notieAlert(1, "The answer has been send");
                        formatInputs();
                    </script>';
                } else {
                    echo '
                    <script>
                        notieAlert(2, "The response message has been sent but the user was not notified");
                        formatInputs();
                    </script>';
                }
            } else {
                echo '
                <script>
                    notieAlert(3, "Error responding to message");
                    formatInputs();
                </script>';
            }
        }
    }
}
