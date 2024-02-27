<?php

$select = "id_store,name_store,url_store,logo_store,cover_store,about_store,abstract_store,email_store,country_store,city_store,address_store,phone_store,socialnetwork_store,reviews_product";

$url = CurlController::api() . "relations?rel=products,stores&type=product,store&linkTo=id_user_store&equalTo={$_SESSION['user']->id_user}&select=$select";
$method = "GET";
$fields = array();
$header = array();

$store = CurlController::request($url, $method, $fields, $header)->result;

if ($store === "Not found") {

    $select = "id_store,name_store,url_store,logo_store,cover_store,about_store,abstract_store,email_store,country_store,city_store,address_store,phone_store,socialnetwork_store";

    $url = CurlController::api() . "stores?linkTo=id_user_store&equalTo={$_SESSION['user']->id_user}&select=$select";
    $store = CurlController::request($url, $method, $fields, $header)->result;
}

$reviews = 0;
$total_reviews = 0;

?>

<div class="ps-section__left">

    <div class="ps-block--vendor">

        <div class="ps-block__thumbnail">

            <!-- Imagen de la tienda -->
            <img src="img/stores/<?php echo $store[0]->url_store; ?>/<?php echo $store[0]->logo_store; ?>" alt="<?php echo $store[0]->name_store; ?>">

        </div>

        <div class="ps-block__container">

            <div class="ps-block__header">

                <!-- Nombre de la tienda -->
                <h4><?php echo $store[0]->name_store; ?></h4>

                <div class="br-wrapper br-theme-fontawesome-stars">

                    <!-- Reviews de la tienda -->
                    <?php

                    if (isset($store[0]->reviews_product)) {
                        foreach ($store as $key => $item) {

                            if ($item->reviews_product != null) {
                                foreach (json_decode($item->reviews_product, true) as $key => $value) {
                                    $reviews += $value['review'];
                                    $total_reviews++;
                                }
                            }
                        }

                        if ($reviews > 0 && $total_reviews > 0) {
                            $reviews = round($reviews / $total_reviews);
                        }
                    }



                    ?>

                    <select class="ps-rating" data-read-only="true" style="display: none;">

                        <?php

                        if ($reviews > 0) {
                            for ($i = 0; $i < 5; $i++) {
                                if ($reviews < ($i + 1)) {
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

                <p><strong><?php echo (($reviews * 100) / 5) ?>% Positive</strong> (<?php echo $total_reviews; ?> rating)</p>

            </div><span class="ps-block__divider"></span>

            <div class="ps-block__content">

                <!-- Abstract store -->
                <p><strong><?php echo $store[0]->name_store; ?></strong>, <?php echo $store[0]->abstract_store; ?></p>

                <span class="ps-block__divider"></span>

                <!-- Direccion de la tienda -->
                <p><strong>Address:</strong> <?php echo $store[0]->address_store; ?></p>

                <!-- Redes sociales -->

                <?php if ($store[0]->socialnetwork_store != null) : ?>

                    <figure>

                        <figcaption>Follow us on social</figcaption>

                        <ul class="ps-list--social-color">

                            <?php foreach (json_decode($store[0]->socialnetwork_store, true) as $key => $socialnetwork) : ?>

                                <li>
                                    <a class="<?php echo array_keys($socialnetwork)[0]; ?>" href="<?php echo $socialnetwork[array_keys($socialnetwork)[0]]; ?>">
                                        <i class="fab fa-<?php echo array_keys($socialnetwork)[0]; ?>"></i></a>
                                </li>

                            <?php endforeach ?>

                        </ul>

                    </figure>

                <?php endif ?>

            </div>

            <div class="ps-block__footer">

                <!-- Telefono de la tienda -->
                <p>Call us directly<strong><small><?php echo explode("_", $store[0]->phone_store)[0] . " " . explode("_", $store[0]->phone_store)[1]; ?></small></strong></p>

                <!-- Email de la tienda -->
                <p>or Or if you have any question <strong><small><?php echo $store[0]->email_store; ?></small></strong></p>

                <a class="ps-btn ps-btn--fullwidth" data-toggle="modal" href="#edit-store">Edit</a>

            </div>

        </div>

    </div>

</div><!-- End Vendor Profile -->

<!-- Modal para editar la tienda -->

<div class="modal" id="edit-store">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="needs-validation" novalidate method="post" enctype="multipart/form-data">

                <input type="hidden" name="id-store" value="<?php echo $store[0]->id_store; ?>">

                <div class="modal-header">
                    <h4 class="modal-title text-center">Edit Store</h4>
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-5 text-left">

                    <!-- Nombre de la tienda -->
                    <div class="form-group">
                        <label for="name-store">Name store<sup class="text-danger">*</sup></label>
                        <div class="form-group__content">
                            <input type="text" name="name-store" class="form-control" id="name-store" value="<?php echo $store[0]->name_store; ?>" readonly required>

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- URL de la tienda -->
                    <div class="form-group">
                        <label for="url-store">Url store<sup class="text-danger">*</sup></label>
                        <div class="form-group__content">
                            <input type="text" name="url-store" class="form-control" id="url-store" value="<?php echo $store[0]->url_store; ?>" readonly required>

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Informacion de la tienda -->
                    <div class="form-group">
                        <label for="about-store">About store<sup class="text-danger">*</sup></label>
                        <div class="form-group__content">
                            <textarea class="form-control" rows="7" placeholder="Notes about your store in the maximum 1000 characters, ex: We are a store specialized in the technology..." name="about-store" id="about-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                                <?php echo $store[0]->about_store ?>
                            </textarea>

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Email de la tienda -->
                    <div class="form-group">
                        <label for="email-store">Email store<sup class="text-danger">*</sup></label>
                        <div class="form-group__content">
                            <input type="email" name="email-store" class="form-control" id="email-store" value="<?php echo $_SESSION['user']->email_user; ?>" value="<?php echo $store[0]->email_store; ?>">

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Pais de la tienda -->
                    <div class="form-group">

                        <label for="country-store">Country store<sup>*</sup></label>

                        <?php

                        $data = file_get_contents("views/json/countries.json");
                        $countries = json_decode($data, true);

                        ?>

                        <div class="form-group__content">

                            <select id="country-store" name="country-store" class="form-control select2" onchange="changeCountry(event)" style="width: 100%;">

                                <?php if ($store[0]->country_store != null) : ?>

                                    <option value="<?php echo $store[0]->country_store; ?>_<?php echo explode("_", $store[0]->phone_store)[0] ?>"><?php echo $store[0]->country_store; ?></option>

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

                    <!-- Ciudad de la tienda -->
                    <div class="form-group">

                        <label for="city-store">City store<sup>*</sup></label>

                        <div class="form-group__content">

                            <input id="city-store" name="city-store" class="form-control" value="<?php echo $store[0]->city_store; ?>" type="text" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event, 'text')">

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                    </div>

                    <!-- Telefono / Celular de la tienda -->
                    <div class="form-group">

                        <label for="phone-store">Phone store<sup>*</sup></label>

                        <div class="form-group__content input-group">

                            <?php if ($store[0]->phone_store != null) : ?>

                                <div class="input-group-append">
                                    <span class="input-group-text dial-code"><?php echo explode("_", $store[0]->phone_store)[0]; ?></span>
                                </div>

                                <?php

                                $phone = explode("_", $store[0]->phone_store)[1];

                                ?>

                            <?php else : ?>

                                <div class="input-group-append">
                                    <span class="input-group-text dial-code">+</span>
                                </div>

                                <?php

                                $phone = "";

                                ?>

                            <?php endif ?>

                            <input id="phone-store" name="phone-store" class="form-control" value="<?php echo $phone; ?>" type="text" pattern="[-\\(\\)\\0-9 ]{1,}" onchange="validateJS(event, 'phone')">
                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                    </div>

                    <!-- Direccion de la tienda -->
                    <div class="form-group">

                        <label for="address-store">Address store<sup>*</sup></label>

                        <div class="form-group__content">

                            <input id="address-store" name="address-store" class="form-control" value="<?php echo $store[0]->address_store; ?>" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" type="text">

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                    </div>

                    <!-- Logo de la tienda -->
                    <div class="form-group">

                        <input type="hidden" name="logo-store-old" value="<?php echo $store[0]->logo_store; ?>">

                        <label>Logo store<sup class="text-danger">*</sup></label>

                        <div class="form-group__content">
                            <label class="pb-5" for="logo-store">
                                <img src="img/stores/<?php echo $store[0]->url_store ?>/<?php echo $store[0]->logo_store; ?>" class="img-fluid change-logo" style="width: 150px;" alt="logo-store">
                            </label>

                            <div class="custom-file">
                                <input type="file" id="logo-store" class="custom-file-input" name="logo-store" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-logo')">

                                <div class="valid-feedback">Valid</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label class="custom-file-label" for="logo-store">Choose file</label>
                            </div>

                        </div>
                    </div>

                    <!-- Portada de la tienda -->
                    <div class="form-group">

                        <input type="hidden" name="cover-store-old" value="<?php echo $store[0]->cover_store; ?>">

                        <label>Cover store<sup class="text-danger">*</sup></label>

                        <div class="form-group__content">
                            <label class="pb-5" for="cover-store">
                                <img src="img/stores/<?php echo $store[0]->url_store ?>/<?php echo $store[0]->cover_store ?>" class="img-fluid change-cover" alt="cover-store">
                            </label>

                            <div class="custom-file">
                                <input type="file" id="cover-store" class="custom-file-input form-store" name="cover-store" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-cover')">

                                <div class="valid-feedback">Valid</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label class="custom-file-label" for="cover-store">Choose file</label>
                            </div>

                        </div>
                    </div>

                    <!-- Redes sociales de la tienda -->
                    <div class="form-group">
                        <label for="social-networks">Social Networks</label>

                        <?php

                        $facebook = "";
                        $linkedin = "";
                        $twitter = "";
                        $youtube = "";
                        $instagram = "";

                        if ($store[0]->socialnetwork_store != null) {

                            foreach (json_decode($store[0]->socialnetwork_store, true) as $key => $social) {

                                if (array_keys($social)[0] == "facebook") {
                                    $facebook = explode("/", $social[array_keys($social)[0]])[3];
                                } else if (array_keys($social)[0] == "linkedin") {
                                    $linkedin = explode("/", $social[array_keys($social)[0]])[3];
                                } else if (array_keys($social)[0] == "twitter") {
                                    $twitter = explode("/", $social[array_keys($social)[0]])[3];
                                } else if (array_keys($social)[0] == "youtube") {
                                    $youtube = explode("/", $social[array_keys($social)[0]])[3];
                                } else if (array_keys($social)[0] == "instagram") {
                                    $instagram = explode("/", $social[array_keys($social)[0]])[3];
                                }
                            }
                        }

                        ?>

                        <!-- Facebook -->
                        <div class="form-group__content input-group mb-5">
                            <div class="input-group-append">
                                <span class="input-group-text">https://facebook.com/</span>
                            </div>

                            <input type="text" class="form-control" name="facebook-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" value="<?php echo $facebook; ?>">
                        </div>

                        <!-- Instagram -->
                        <div class="form-group__content input-group mb-5">
                            <div class="input-group-append">
                                <span class="input-group-text">https://instagram.com/</span>
                            </div>

                            <input type="text" class="form-control" name="instagram-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" value="<?php echo $instagram; ?>">
                        </div>

                        <!-- Twitter -->
                        <div class="form-group__content input-group mb-5">
                            <div class="input-group-append">
                                <span class="input-group-text">https://twitter.com/</span>
                            </div>

                            <input type="text" class="form-control" name="twitter-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" value="<?php echo $twitter ?>">
                        </div>

                        <!-- Linkedin -->
                        <div class="form-group__content input-group mb-5">
                            <div class="input-group-append">
                                <span class="input-group-text">https://linkedin.com/</span>
                            </div>

                            <input type="text" class="form-control" name="linkedin-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" value="<?php echo $linkedin; ?>">
                        </div>

                        <!-- Youtube -->
                        <div class="form-group__content input-group mb-5">
                            <div class="input-group-append">
                                <span class="input-group-text">https://youtube.com/</span>
                            </div>

                            <input type="text" class="form-control" name="youtube-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" value="<?php echo $youtube; ?>">
                        </div>
                    </div>
                </div>

                <div class="modal-footer pb-5 pb-5">

                    <?php

                    $edit_store = new VendorsController();
                    $edit_store->edit_store();

                    ?>

                    <button class="ps-btn ps-btn--fullwidth" type="submit">Save</button>
                </div>

            </form>
        </div>
    </div>

</div>