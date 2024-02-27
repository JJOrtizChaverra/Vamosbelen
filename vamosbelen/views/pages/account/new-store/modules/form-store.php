<!-- Crear tienda -->

<div class="tab-pane container fade" id="create-store">

    <!-- Modal header -->
    <div class="modal-header">
        <h4 class="modal-title text-center">2. Create store</h4>
    </div>

    <!-- Modal body -->
    <div class="modal-body p-5 text-left">

        <!-- Nombre de la tienda -->
        <div class="form-group">
            <label for="name-store">Name store<sup class="text-danger">*</sup></label>
            <div class="form-group__content">
                <input type="text" name="name-store" class="form-control form-store" id="name-store" pattern="[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{3,}" onchange="validateDataRepeat(event, 'store')" required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- URL de la tienda -->
        <div class="form-group">
            <label for="url-store">Url store<sup class="text-danger">*</sup></label>
            <div class="form-group__content">
                <input type="text" name="url-store" class="form-control form-store" id="url-store" readonly required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Informacion de la tienda -->
        <div class="form-group">
            <label for="about-store">About store<sup class="text-danger">*</sup></label>
            <div class="form-group__content">
                <textarea 
                class="form-control form-store" 
                rows="7" 
                placeholder="Notes about your store in the maximum 1000 characters, ex: We are a store specialized in the technology..." 
                name="about-store" 
                id="about-store" 
                pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' 
                onchange="validateJS(event, 'paragraphs')" required></textarea>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Email de la tienda -->
        <div class="form-group">
            <label for="email-store">Email store<sup class="text-danger">*</sup></label>
            <div class="form-group__content">
                <input type="email" name="email-store" class="form-control form-store" id="email-store" value="<?php echo $_SESSION['user']->email_user; ?>" required>

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

                <select id="country-store" name="country-store" class="form-control select2 form-store" onchange="changeCountry(event)" style="width: 100%;" required>

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

        <!-- Ciudad de la tienda -->
        <div class="form-group">

            <label for="city-store">City store<sup>*</sup></label>

            <div class="form-group__content">

                <input id="city-store" name="city-store" class="form-control form-store" value="<?php echo $_SESSION['user']->city_user; ?>" type="text" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event, 'text')" required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill in this field correctly.</div>

            </div>

        </div>

        <!-- Telefono / Celular de la tienda -->
        <div class="form-group">

            <label for="phone-store">Phone store<sup>*</sup></label>

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

                <input id="phone-store" name="phone-store" class="form-control form-store" value="<?php echo $phone; ?>" type="text" pattern="[-\\(\\)\\0-9 ]{1,}" onchange="validateJS(event, 'phone')" required>
                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill in this field correctly.</div>

            </div>

        </div>

        <!-- Direccion de la tienda -->
        <div class="form-group">

            <label for="address-store">Address store<sup>*</sup></label>

            <div class="form-group__content">

                <input id="address-store" name="address-store" class="form-store form-control" value="<?php echo $_SESSION['user']->address_user; ?>" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" type="text" required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill in this field correctly.</div>

            </div>

        </div>

        <!-- Logo de la tienda -->
        <div class="form-group">

            <label>Logo store<sup class="text-danger">*</sup></label>

            <div class="form-group__content">
                <label class="pb-5" for="logo-store">
                    <img src="img/stores/default/default-logo.jpg" class="img-fluid change-logo" style="width: 150px;" alt="logo-store">
                </label>

                <div class="custom-file">
                    <input 
                    type="file"
                    id="logo-store"  
                    class="custom-file-input form-store"
                    name="logo-store" 
                    accept="image/*" 
                    maxSize="2000000" 
                    onchange="validateImageJS(event, 'change-logo')" 
                    required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
    
                    <label class="custom-file-label" for="logo-store">Choose file</label>
                </div>

            </div>
        </div>

        <!-- Portada de la tienda -->
        <div class="form-group">

            <label>Cover store<sup class="text-danger">*</sup></label>

            <div class="form-group__content">
                <label class="pb-5" for="cover-store">
                    <img src="img/stores/default/default-cover.jpg" class="img-fluid change-cover" alt="cover-store">
                </label>

                <div class="custom-file">
                    <input 
                    type="file"
                    id="cover-store"  
                    class="custom-file-input form-store"
                    name="cover-store" 
                    accept="image/*" 
                    maxSize="2000000" 
                    onchange="validateImageJS(event, 'change-cover')" 
                    required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
    
                    <label class="custom-file-label" for="cover-store">Choose file</label>
                </div>

            </div>
        </div>

        <!-- Redes sociales de la tienda -->
        <div class="form-group">
            <label for="social-networks">Social Networks</label>

            <!-- Facebook -->
            <div class="form-group__content input-group mb-5">
                <div class="input-group-append">
                    <span class="input-group-text">https://facebook.com/</span>
                </div>

                <input type="text" class="form-control" name="facebook-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
            </div>

            <!-- Instagram -->
            <div class="form-group__content input-group mb-5">
                <div class="input-group-append">
                    <span class="input-group-text">https://instagram.com/</span>
                </div>

                <input type="text" class="form-control" name="instagram-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
            </div>

            <!-- Twitter -->
            <div class="form-group__content input-group mb-5">
                <div class="input-group-append">
                    <span class="input-group-text">https://twitter.com/</span>
                </div>

                <input type="text" class="form-control" name="twitter-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
            </div>

            <!-- Linkedin -->
            <div class="form-group__content input-group mb-5">
                <div class="input-group-append">
                    <span class="input-group-text">https://linkedin.com/</span>
                </div>

                <input type="text" class="form-control" name="linkedin-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
            </div>

            <!-- Youtube -->
            <div class="form-group__content input-group mb-5">
                <div class="input-group-append">
                    <span class="input-group-text">https://youtube.com/</span>
                </div>

                <input type="text" class="form-control" name="youtube-store" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
            </div>
        </div>
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">

        <button type="button" class="btn btn-lg btn-warning" onclick="validateFormStore()">Next</button>
    </div>
</div>