<!-- Crear producto -->

<div class="tab-pane container fade" id="create-product">
    <!-- Modal header -->
    <div class="modal-header">
        <h4 class="modal-title text-center">3. Create product</h4>
    </div>

    <!-- Modal body -->
    <div class="modal-body p-5 text-left">

        <!-- Nombre del producto -->
        <div class="form-group">
            <label for="name-product">Name product<sup class="text-danger">*</sup></label>
            <div class="form-group__content">
                <input type="text" id="name-product" name="name-product" class="form-control form-product" pattern="[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{3,}" onchange="validateDataRepeat(event, 'product')" required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Url del product -->
        <div class="form-group">
            <label for="url-product">Url product<sup class="text-danger">*</sup></label>
            <div class="form-group__content">
                <input type="text" id="url-product" name="url-product" class="form-control form-product" readonly required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Categoria del producto -->
        <div class="form-group">
            <label for="category-product">Category product<sup class="text-danger">*</sup></label>

            <?php

            $url = CurlController::api() . "categories?select=id_category,name_category,url_category";
            $method = "GET";
            $fields = array();
            $header = array();

            $categories = CurlController::request($url, $method, $fields, $header)->result;

            ?>

            <div class="form-group__content">
                <select id="category-product" name="category-product" class="form-control" onchange="changeCategory(event)" required>

                    <option value="" selected disabled>Select Category</option>

                    <?php foreach ($categories as $key => $value) : ?>

                        <option value="<?php echo $value->id_category . "_" . $value->url_category; ?>"><?php echo $value->name_category ?></option>

                    <?php endforeach ?>
                </select>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>

        </div>

        <!-- Subcategoria del producto -->
        <div class="form-group subcategory-product" style="display: none;">
            <label for="subcategory-product">Subcategory product<sup class="text-danger">*</sup></label>

            <div class="form-group__content">
                <select id="subcategory-product" name="subcategory-product" class="form-control" required>
                    <option value="" selected disabled>Select subcategory</option>
                </select>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Descripcion del producto -->
        <div class="form-group">
            <label for="description-product">Description product<sup class="text-danger">*</sup></label>

            <div class="form-group__content">

                <textarea name="description-product" class="summernote" required></textarea>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Resumen del producto -->
        <div class="form-group">
            <label for="summary-product">Summary product<sup class="text-danger">*</sup>Ex: 20 hours of portable capabilities</label>

            <input type="hidden" name="input-summary" value="1">

            <div class="form-group__content input-group mb-3 input-summary">

                <div class="input-group-append">
                    <span class="input-group-text">
                        <button type="button" class="btn btn-danger" onclick="removeInput(0, 'input-summary')">&times;</button>
                    </span>
                </div>

                <input type="text" name="summary-product_0" id="summary-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'input-summary')">Add summary</button>
        </div>

        <!-- Detalles del producto -->
        <div class="form-group">
            <label for="details-product">Details product<sup class="text-danger">*</sup>Ex: <strong>Title: </strong>Bluetooth, <strong>Value: </strong>Yes</label>

            <input type="hidden" name="input-details" value="1">

            <div class="row mb-3 input-details">

                <!-- Title detail -->
                <div class="col-12 col-lg-6 form-group__content input-group">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            <button type="button" class="btn btn-danger" onclick="removeInput(0, 'input-details')">&times;</button>
                        </span>
                    </div>

                    <div class="input-group-append">
                        <span class="input-group-text">
                            Title:
                        </span>
                    </div>

                    <input type="text" name="details-title-product_0" id="details-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Value detail -->
                <div class="col-12 col-lg-6 form-group__content input-group">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            Value:
                        </span>
                    </div>

                    <input type="text" name="details-value-product_0" id="details-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'input-details')">Add detail</button>
        </div>

        <!-- Especificaciones del producto -->
        <div class="form-group">
            <label>Specifications product Ex: <strong>Type: </strong>Color, <strong>Value: </strong>Black, Red, White</label>

            <input type="hidden" name="input-specifications" value="1">

            <div class="row mb-3 input-specifications">

                <!-- Title especificacion -->
                <div class="col-12 col-lg-6 form-group__content input-group">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            <button type="button" class="btn btn-danger" onclick="removeInput(0, 'input-specifications')">&times;</button>
                        </span>
                    </div>

                    <div class="input-group-append">
                        <span class="input-group-text">
                            Type:
                        </span>
                    </div>

                    <input type="text" name="specifications-title-product_0" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

                </div>

                <!-- Value especificacion -->
                <div class="col-12 col-lg-6 form-group__content input-group">
                    <input type="text" name="specifications-value-product_0" class="form-control tags-input" data-role="tagsinput" placeholder="Type And Press Enter" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>
            </div>

            <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'input-specifications')">Add specification</button>
        </div>

        <!-- Palabras claves del producto -->
        <div class="form-group">
            <label for="tags-product">Tags product <sup class="text-danger">*</sup></label>

            <div class="form-group__content">
                <input type="text" name="tags-product" id="tags-product" class="form-control tags-input" data-role="tagsinput" placeholder="Type And Press Enter" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                <div class="valid-feedback">Valid</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
        </div>

        <!-- Imagen del producto -->
        <div class="form-group">

            <label>Image product<sup class="text-danger">*</sup></label>

            <div class="form-group__content">
                <label class="pb-5" for="image-product">
                    <img src="img/products/default/default-image.jpg" class="img-fluid change-image" style="width: 150px;" alt="image-product">
                </label>

                <div class="custom-file">
                    <input type="file" id="image-product" class="custom-file-input" name="image-product" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-image')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                    <label class="custom-file-label" for="image-product">Choose file</label>
                </div>

            </div>
        </div>

        <!-- Galeria del producto -->
        <label for="">Gallery product: <sup class="text-danger">*</sup></label>

        <div class="dropzone mb-3">
            <div class="dz-message">
                Drop your images JPG or PNG here, size max 500px * 500px
            </div>
        </div>

        <input type="hidden" name="gallery-product">

        <!-- Banner top del producto -->
        <div class="form-group">
            <label for="">Top Banner Product</label>

            <figure class="pb-5">
                <img src="img/products/default/example-top-banner.png" alt="example-top-banner">
            </figure>

            <div class="row mb-5">

                <!-- H3 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H3 tag:
                        </span>
                    </div>

                    <input type="text" name="top-banner-h3-tag" class="form-control" placeholder="Ex: 20%" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- P1 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            P1 tag:
                        </span>
                    </div>

                    <input type="text" name="top-banner-p1-tag" class="form-control" placeholder="Ex: Disccount" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- H4 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H4 tag:
                        </span>
                    </div>

                    <input type="text" name="top-banner-h4-tag" class="form-control" placeholder="Ex: For Books Of March" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- P2 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            P2 tag:
                        </span>
                    </div>

                    <input type="text" name="top-banner-p2-tag" class="form-control" placeholder="Ex: Enter Promotion" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- SPAN tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            SPAN tag:
                        </span>
                    </div>

                    <input type="text" name="top-banner-span-tag" class="form-control" placeholder="Ex: Sale2019" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- Button tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Button tag:
                        </span>
                    </div>

                    <input type="text" name="top-banner-button-tag" class="form-control" placeholder="Ex: Shop Now" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- IMG tag -->
                <div class="col-12">

                    <label for="">IMG tag</label>

                    <div class="form-group__content">

                        <label class="pb-5" for="top-banner-image">
                            <img src="img/products/default/default-top-banner.jpg" class="img-fluid change-top-banner" alt="">
                        </label>

                        <div class="custom-file">
                            <input type="file" id="top-banner-image" name="top-banner-img-tag" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-top-banner')">

                            <label class="custom-file-label" for="top-banner-image"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Default banner del producto -->
        <div class="form-group">

            <label for="">Default Banner Product</label>

            <div class="form-group__content">

                <label class="pb-5" for="default-banner-image">
                    <img src="img/products/default/default-banner.jpg" class="img-fluid change-default-banner" style="width: 500px;" alt="default-banner-image">
                </label>

                <div class="custom-file">
                    <input type="file" id="default-banner-image" name="default-banner-img" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-default-banner')">

                    <label class="custom-file-label" for="default-banner-image"></label>
                </div>
            </div>
        </div>

        <!-- Horizontal slider del producto -->
        <div class="form-group">
            <label for="">Horizontal Slider Product </label>

            <figure class="pb-5">
                <img src="img/products/default/example-horizontal-slider.png" class="img-fluid" alt="example-horizontal-slider">
            </figure>

            <div class="row mb-3">

                <!-- H4 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H4 tag:
                        </span>
                    </div>

                    <input type="text" name="horizontal-slider-h4-tag" class="form-control" placeholder="Ex: Limit Edition" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- H3-1 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H3-1 tag:
                        </span>
                    </div>

                    <input type="text" name="horizontal-slider-h3-1-tag" class="form-control" placeholder="Ex: Happy Summer" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- H3-2 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H3-2 tag:
                        </span>
                    </div>

                    <input type="text" name="horizontal-slider-h3-2-tag" class="form-control" placeholder="Ex: Combo Super Cool" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- H3-3 tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H3-3 tag:
                        </span>
                    </div>

                    <input type="text" name="horizontal-slider-h3-3-tag" class="form-control" placeholder="Ex: Up To" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

                </div>

                <!-- H3-4s tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            H3-4s tag:
                        </span>
                    </div>

                    <input type="text" name="horizontal-slider-h3-4s-tag" class="form-control" placeholder="Ex: 40%" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- Button tag -->
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Button tag:
                        </span>
                    </div>

                    <input type="text" name="horizontal-slider-button-tag" class="form-control" placeholder="Ex: Shop Now" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                </div>

                <!-- IMG tag -->
                <div class="col-12">

                    <label for="">IMG tag</label>

                    <div class="form-group__content">

                        <label class="pb-5" for="horizontal-slider-image">
                            <img src="img/products/default/default-horizontal-slider.jpg" class="img-fluid change-horizontal-slider" alt="">
                        </label>

                        <div class="custom-file">
                            <input type="file" id="horizontal-slider-image" name="horizontal-slider-img-tag" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-horizontal-slider')">

                            <label class="custom-file-label" for="horizontal-slider-image"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide Vertical del producto -->
        <div class="form-group">

            <label for="">Slider Vertical Product</label>

            <div class="form-group__content">

                <label class="pb-5" for="default-vertical-slider-image">
                    <img src="img/products/default/default-vertical-slider.jpg" class="img-fluid change-default-vertical-slider" style="width: 260px;" alt="default-vertical-slider-image">
                </label>

                <div class="custom-file">
                    <input type="file" id="default-vertical-slider-image" name="default-vertical-slider-img" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-default-vertical-slider')">

                    <label class="custom-file-label" for="default-vertical-slider-image"></label>
                </div>
            </div>
        </div>

        <!-- Video del producto -->
        <div class="form-group">

            <label for="video-product">Video Product Ex: <strong>Type: </strong>Youtube, Vimeo <strong>Id: </strong>S4uT5NAIYh</label>

            <div class="row mb-3">
                <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Type:
                        </span>
                    </div>

                    <select name="type-video" class="form-control" id="video-product">
                        <option value="" selected disabled>Select Plataform</option>
                        <option value="youtube">Youtube</option>
                        <option value="vimeo">Vimeo</option>
                    </select>

                </div>

                <div class="col-12 col-lg-6 form-group__content input-group mx-0">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Id:
                        </span>
                    </div>

                    <input type="text" name="id-video" id="video-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <!-- Precio de venta, precio de envio, dias de entrega y stock -->
        <div class="form-group">
            <div class="row mb-3">

                <!-- Precio del producto -->
                <div class="col-12 col-lg-3">
                    <label for="price-product">Price product <sup class="text-danger">*</sup></label>

                    <div class="form-group__content input-group mx-0 pr-0">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Price $:
                            </span>
                        </div>

                        <input type="number" class="form-control" name="price-product" id="price-product" min="0" step="any" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>
                </div>

                <!-- Precio de envio -->
                <div class="col-12 col-lg-3">
                    <label for="shipping-product">Price Shipping Product <sup class="text-danger">*</sup></label>

                    <div class="form-group__content input-group mx-0 pr-0">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Shipping $:
                            </span>
                        </div>

                        <input type="number" class="form-control" name="shipping-product" id="shipping-product" min="0" step="any" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>
                </div>

                <!-- Dias de entrega -->
                <div class="col-12 col-lg-3">
                    <label for="delivery-time-product">Delivery Time Product <sup class="text-danger">*</sup></label>

                    <div class="form-group__content input-group mx-0 pr-0">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Days:
                            </span>
                        </div>

                        <input type="number" class="form-control" name="delivery-time-product" id="delivery-time-product" min="0" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>
                </div>

                <!-- Stock -->
                <div class="col-12 col-lg-3">
                    <label for="stock-product">Stock Product <sup class="text-danger">*</sup></label>

                    <div class="form-group__content input-group mx-0 pr-0">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Stock:
                            </span>
                        </div>

                        <input type="number" class="form-control" name="stock-product" id="stock-product" min="0" max="100" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Oferta del producto -->
        <div class="form-group">

            <label for="">Product Offer Ex <strong>Type: </strong>Disccount, Fixed <strong>Percent %: </strong> 25 <strong>End offer: </strong>30/06/2025</label>

            <div class="row mb-3">

                <!-- Tipo de oferta -->
                <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            Type:
                        </span>
                    </div>

                    <select name="type-offer-product" class="form-control" onchange="changeOffer(event)">
                        <option value="" disabled selected>Select type offer</option>
                        <option value="Discount">Discount</option>
                        <option value="Fixed">Fixed</option>
                    </select>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <!-- Valor de la oferta -->
                <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">

                    <div class="input-group-append">
                        <span class="input-group-text type-offer">

                        </span>
                    </div>

                    <input type="number" name="value-offer-product" class="form-control" min="0" step="any" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')">

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <!-- Fecha de vencimiento -->
                <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            End Offer:
                        </span>
                    </div>

                    <input type="date" name="date-offer-product" class="form-control">

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>
            </div>
        </div>


        <!-- Modal Footer -->
        <div class="modal-footer">
            <div class="form-group submtit">
                <button type="submit" class="ps-btn ps-btn--fullwidth save-btn">Create Store and Product</button>

                <?php
                $new_vendor = new VendorsController();
                $new_vendor->new_vendor();
                ?>
            </div>
        </div>
    </div>
</div>