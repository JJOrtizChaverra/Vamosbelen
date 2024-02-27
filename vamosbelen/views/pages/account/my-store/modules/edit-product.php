<?php

if (isset($_GET['product'])) {
    $select = "id_product,approval_product,state_product,url_product,feedback_product,image_product,name_product,id_category,url_category,name_category,id_subcategory,title_list_subcategory,name_subcategory,price_product,shipping_product,stock_product,delivery_time_product,offer_product,summary_product,specifications_product,details_product,description_product,tags_product,gallery_product,top_banner_product,default_banner_product,horizontal_slider_product,vertical_slider_product,video_product,views_product,sales_product,reviews_product,date_created_product";

    $url = CurlController::api() . "relations?rel=products,categories,subcategories&type=product,category,subcategory&linkTo=id_product&equalTo={$_GET['product']}&select=$select";
    $method = "GET";
    $fields = array();
    $header = array();

    $product = CurlController::request($url, $method, $fields, $header)->result[0];
}

?>

<!-- Editar producto -->

<form class="needs-validation" novalidate method="post" enctype="multipart/form-data">

    <div>

        <input type="hidden" id="url-api" value="<?php echo CurlController::api(); ?>">
        <input type="hidden" id="id-product" name="id-product" value="<?php echo $product->id_product; ?>">

        <!-- Modal header -->
        <div class="modal-header">
            <h4 class="modal-title text-center">Edit product</h4>
            <a href="<?php echo TemplateController::path(); ?>account&my-store#profile-user" class="btn btn-dark">Cancel</a>
        </div>

        <!-- Modal body -->
        <div class="modal-body p-5 text-left">

            <!-- Nombre del producto -->
            <div class="form-group">
                <label for="name-product">Name product<sup class="text-danger">*</sup></label>
                <div class="form-group__content">
                    <input type="text" id="name-product" name="name-product" class="form-control" value="<?php echo $product->name_product; ?>" readonly required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Url del product -->
            <div class="form-group">
                <label for="url-product">Url product<sup class="text-danger">*</sup></label>
                <div class="form-group__content">
                    <input type="text" id="url-product" name="url-product" class="form-control" value="<?php echo $product->url_product; ?>" readonly required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Categoria del producto -->
            <div class="form-group">
                <label for="category-product">Category product<sup class="text-danger">*</sup></label>

                <div class="form-group__content">

                    <select id="category-product" name="category-product" class="form-control" readonly required>

                        <option value="<?php echo $product->id_category . "_" . $product->url_category; ?>"><?php echo $product->name_category ?></option>

                    </select>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

            </div>

            <!-- Subcategoria del producto -->
            <div class="form-group subcategory-product" style="display: none;">
                <label for="subcategory-product">Subcategory product<sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    <select id="subcategory-product" name="subcategory-product" class="form-control">
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

                    <textarea id-product="<?php echo $product->id_product; ?>" name="description-product" class="summernote edit-summernote" required></textarea>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Resumen del producto -->
            <div class="form-group">
                <label for="summary-product">Summary product<sup class="text-danger">*</sup>Ex: 20 hours of portable capabilities</label>

                <?php foreach (json_decode($product->summary_product, true) as $key => $summary) : ?>

                    <input type="hidden" name="input-summary" value="<?php echo $key + 1; ?>">

                    <div class="form-group__content input-group mb-3 input-summary">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                <button type="button" class="btn btn-danger" onclick="removeInput(<?php echo $key; ?>, 'input-summary')">&times;</button>
                            </span>
                        </div>

                        <input type="text" name="summary-product_<?php echo $key; ?>" id="summary-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" value="<?php echo $summary; ?>" required>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                <?php endforeach ?>

                <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'input-summary')">Add summary</button>
            </div>

            <!-- Detalles del producto -->
            <div class="form-group">
                <label for="details-product">Details product<sup class="text-danger">*</sup>Ex: <strong>Title: </strong>Bluetooth, <strong>Value: </strong>Yes</label>

                <?php foreach (json_decode($product->details_product, true) as $key => $detail) : ?>

                    <input type="hidden" name="input-details" value="<?php echo $key + 1; ?>">

                    <div class="row mb-3 input-details">

                        <!-- Title detail -->
                        <div class="col-12 col-lg-6 form-group__content input-group">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <button type="button" class="btn btn-danger" onclick="removeInput(<?php echo $key; ?>, 'input-details')">&times;</button>
                                </span>
                            </div>

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Title:
                                </span>
                            </div>

                            <input type="text" value="<?php echo $detail['title']; ?>" name="details-title-product_<?php echo $key; ?>" id="details-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

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

                            <input type="text" value="<?php echo $detail['value']; ?>" name="details-value-product_<?php echo $key; ?>" id="details-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                            <div class="valid-feedback">Valid</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                <?php endforeach ?>

                <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'input-details')">Add detail</button>
            </div>

            <!-- Especificaciones del producto -->
            <div class="form-group">
                <label>Specifications product Ex: <strong>Type: </strong>Color, <strong>Value: </strong>Black, Red, White</label>

                <?php if ($product->specifications_product != null) : ?>

                    <?php foreach (json_decode($product->specifications_product, true) as $key => $specification) : {
                            # code...
                        } ?>

                        <input type="hidden" name="input-specifications" value="<?php echo $key + 1; ?>">

                        <div class="row mb-3 input-specifications">

                            <!-- Title especificacion -->
                            <div class="col-12 col-lg-6 form-group__content input-group">

                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <button type="button" class="btn btn-danger" onclick="removeInput(<?php echo $key; ?>, 'input-specifications')">&times;</button>
                                    </span>
                                </div>

                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Type:
                                    </span>
                                </div>

                                <input type="text" value="<?php echo array_keys($specification)[0] ?>" name="specifications-title-product_<?php echo $key; ?>" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

                            </div>

                            <!-- Value especificacion -->
                            <div class="col-12 col-lg-6 form-group__content input-group">
                                <input type="text" value="<?php echo implode(",", array_values($specification)[0]); ?>" name="specifications-value-product_<?php echo $key; ?>" class="form-control tags-input" data-role="tagsinput" placeholder="Type And Press Enter" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                            </div>
                        </div>

                    <?php endforeach ?>

                <?php else : ?>

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

                <?php endif ?>

                <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'input-specifications')">Add specification</button>
            </div>

            <!-- Palabras claves del producto -->
            <div class="form-group">
                <label for="tags-product">Tags product <sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    <input type="text" value="<?php echo implode(",", json_decode($product->tags_product, true)); ?>" name="tags-product" id="tags-product" class="form-control tags-input" data-role="tagsinput" placeholder="Type And Press Enter" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Imagen del producto -->
            <div class="form-group">

                <input type="hidden" name="image-product-old" value="<?php echo $product->image_product; ?>">

                <label>Image product<sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    <label class="pb-5" for="image-product">
                        <img src="img/products/<?php echo $product->url_category; ?>/<?php echo $product->image_product; ?>" class="img-fluid change-image" style="width: 150px;" alt="<?php echo $product->name_product; ?>" required>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="image-product" class="custom-file-input" name="image-product" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-image')">

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                        <label class="custom-file-label" for="image-product">Choose file</label>
                    </div>

                </div>
            </div>

            <!-- Galeria del producto -->
            <label for="">Gallery product: <sup class="text-danger">*</sup></label>

            <div class="dropzone mb-3">

                <?php foreach (json_decode($product->gallery_product, true) as $value) : ?>

                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image">
                            <img src="img/products/<?php echo $product->url_category ?>/gallery/<?php echo $value; ?>">
                        </div>

                        <a class="dz-remove" data-dz-remove remove="<?php echo $value; ?>" onclick="removeGallery(this)">Remove file</a>

                    </div>

                <?php endforeach ?>

                <div class="dz-message">
                    Drop your images JPG or PNG here, size max 500px * 500px
                </div>
            </div>

            <input type="hidden" name="gallery-product-old" value='<?php echo $product->gallery_product; ?>'>

            <input type="hidden" name="gallery-product" >

            <input type="hidden" name="detele-gallery-product">

            <!-- Banner top del producto -->
            <div class="form-group">
                <label for="">Top Banner Product</label>

                <?php if ($product->top_banner_product != null) : ?>

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

                            <input type="text" value="<?php echo json_decode($product->top_banner_product, true)['H3 tag']; ?>" name="top-banner-h3-tag" class="form-control" placeholder="Ex: 20%" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- P1 tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    P1 tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->top_banner_product, true)['P1 tag']; ?>" name="top-banner-p1-tag" class="form-control" placeholder="Ex: Disccount" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- H4 tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    H4 tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->top_banner_product, true)['H4 tag']; ?>" name="top-banner-h4-tag" class="form-control" placeholder="Ex: For Books Of March" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- P2 tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    P2 tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->top_banner_product, true)['P2 tag']; ?>" name="top-banner-p2-tag" class="form-control" placeholder="Ex: Enter Promotion" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- SPAN tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    SPAN tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->top_banner_product, true)['Span tag']; ?>" name="top-banner-span-tag" class="form-control" placeholder="Ex: Sale2019" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- Button tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Button tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->top_banner_product, true)['Button tag']; ?>" name="top-banner-button-tag" class="form-control" placeholder="Ex: Shop Now" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- IMG tag -->
                        <div class="col-12">

                            <input type="hidden" name="top-banner-old" value="<?php echo json_decode($product->top_banner_product, true)['IMG tag']; ?>">

                            <label for="">IMG tag</label>

                            <div class="form-group__content">

                                <label class="pb-5" for="top-banner-image">
                                    <img src="img/products/<?php echo $product->url_category; ?>/top/<?php echo json_decode($product->top_banner_product, true)['IMG tag']; ?>" class="img-fluid change-top-banner" alt="">
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="top-banner-image" name="top-banner-img-tag" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-top-banner')">

                                    <label class="custom-file-label" for="top-banner-image"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else : ?>

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

                            <input type="hidden" name="top-banner-old" value="<?php echo $product->top_banner_product; ?>">

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

                <?php endif ?>
            </div>

            <!-- Default banner del producto -->
            <div class="form-group">

                <label for="">Default Banner Product</label>

                <?php if ($product->default_banner_product != null) : ?>

                    <input type="hidden" name="default-banner-old" value="<?php echo $product->default_banner_product; ?>">

                    <div class="form-group__content">

                        <label class="pb-5" for="default-banner-image">
                            <img src="img/products/<?php echo $product->url_category; ?>/default/<?php echo $product->default_banner_product; ?>" class="img-fluid change-default-banner" style="width: 500px;" alt="default-banner-image">
                        </label>

                        <div class="custom-file">
                            <input type="file" id="default-banner-image" name="default-banner-img" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-default-banner')">

                            <label class="custom-file-label" for="default-banner-image"></label>
                        </div>
                    </div>

                <?php else : ?>

                    <input type="hidden" name="default-banner-old" value="<?php echo $product->default_banner_product; ?>">

                    <div class="form-group__content">

                        <label class="pb-5" for="default-banner-image">
                            <img src="img/products/default/default-banner.jpg" class="img-fluid change-default-banner" style="width: 500px;" alt="default-banner-image">
                        </label>

                        <div class="custom-file">
                            <input type="file" id="default-banner-image" name="default-banner-img" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-default-banner')">

                            <label class="custom-file-label" for="default-banner-image"></label>
                        </div>
                    </div>

                <?php endif ?>
            </div>

            <!-- Horizontal slider del producto -->
            <div class="form-group">
                <label for="">Horizontal Slider Product </label>

                <?php if ($product->horizontal_slider_product != null) : ?>

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

                            <input type="text" value="<?php echo json_decode($product->horizontal_slider_product, true)['H4 tag']; ?>" name="horizontal-slider-h4-tag" class="form-control" placeholder="Ex: Limit Edition" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- H3-1 tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    H3-1 tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->horizontal_slider_product, true)['H3-1 tag']; ?>" name="horizontal-slider-h3-1-tag" class="form-control" placeholder="Ex: Happy Summer" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- H3-2 tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    H3-2 tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->horizontal_slider_product, true)['H3-2 tag']; ?>" name="horizontal-slider-h3-2-tag" class="form-control" placeholder="Ex: Combo Super Cool" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- H3-3 tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    H3-3 tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->horizontal_slider_product, true)['H3-3 tag']; ?>" name="horizontal-slider-h3-3-tag" class="form-control" placeholder="Ex: Up To" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

                        </div>

                        <!-- H3-4s tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    H3-4s tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->horizontal_slider_product, true)['H3-4s tag']; ?>" name="horizontal-slider-h3-4s-tag" class="form-control" placeholder="Ex: 40%" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- Button tag -->
                        <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Button tag:
                                </span>
                            </div>

                            <input type="text" value="<?php echo json_decode($product->horizontal_slider_product, true)['Button tag']; ?>" name="horizontal-slider-button-tag" class="form-control" placeholder="Ex: Shop Now" maxlength="50" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                        </div>

                        <!-- IMG tag -->
                        <div class="col-12">

                            <label for="">IMG tag</label>

                            <input type="hidden" name="horizontal-slider-old" value="<?php echo json_decode($product->horizontal_slider_product, true)['IMG tag']; ?>">

                            <div class="form-group__content">

                                <label class="pb-5" for="horizontal-slider-image">
                                    <img src="img/products/<?php echo $product->url_category; ?>/horizontal/<?php echo json_decode($product->horizontal_slider_product, true)['IMG tag']; ?>" class="img-fluid change-horizontal-slider" alt="">
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="horizontal-slider-image" name="horizontal-slider-img-tag" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-horizontal-slider')">

                                    <label class="custom-file-label" for="horizontal-slider-image"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else : ?>

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

                            <input type="hidden" name="horizontal-slider-old" value="<?php echo $product->horizontal_slider_product ?>">

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
                <?php endif ?>
            </div>

            <!-- Slide Vertical del producto -->
            <div class="form-group">

                <label for="">Slider Vertical Product</label>

                <?php if ($product->vertical_slider_product != null) : ?>

                    <input type="hidden" name="vertical-slide-old" value="<?php echo $product->vertical_slider_product; ?>">

                    <div class="form-group__content">

                        <label class="pb-5" for="default-vertical-slider-image">
                            <img src="img/products/<?php echo $product->url_category; ?>/vertical/<?php echo $product->vertical_slider_product; ?>" class="img-fluid change-default-vertical-slider" style="width: 260px;" alt="default-vertical-slider-image">
                        </label>

                        <div class="custom-file">
                            <input type="file" id="default-vertical-slider-image" name="default-vertical-slider-img" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-default-vertical-slider')">

                            <label class="custom-file-label" for="default-vertical-slider-image"></label>
                        </div>
                    </div>

                <?php else : ?>

                    <input type="hidden" name="vertical-slide-old" value="<?php echo $product->vertical_slider_product; ?>">

                    <div class="form-group__content">

                        <label class="pb-5" for="default-vertical-slider-image">
                            <img src="img/products/default/default-vertical-slider.jpg" class="img-fluid change-default-vertical-slider" style="width: 260px;" alt="default-vertical-slider-image">
                        </label>

                        <div class="custom-file">
                            <input type="file" id="default-vertical-slider-image" name="default-vertical-slider-img" class="custom-file-input" accept="image/*" maxSize="2000000" onchange="validateImageJS(event, 'change-default-vertical-slider')">

                            <label class="custom-file-label" for="default-vertical-slider-image"></label>
                        </div>
                    </div>

                <?php endif ?>
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

                            <?php if ($product->video_product != null) : ?>

                                <?php $value_video = json_decode($product->video_product, true)[1]; ?>

                                <?php if (json_decode($product->video_product, true)[0] == "youtube") : ?>

                                    <option value="youtube">Youtube</option>
                                    <option value="vimeo">Vimeo</option>

                                <?php else : ?>

                                    <option value="vimeo">Vimeo</option>
                                    <option value="youtube">Youtube</option>

                                <?php endif ?>

                            <?php else : ?>

                                <option value="" selected disabled>Select Plataform</option>
                                <option value="youtube">Youtube</option>
                                <option value="vimeo">Vimeo</option>

                            <?php endif ?>
                        </select>

                    </div>

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Id:
                            </span>
                        </div>



                        <input type="text" <?php if ($product->video_product != null) : ?> value="<?php echo json_decode($product->video_product, true)[1]; ?>" <?php endif ?> name="id-video" id="video-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

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

                            <input type="number" value="<?php echo $product->price_product; ?>" class="form-control" name="price-product" id="price-product" min="0" step="any" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

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

                            <input type="number" value="<?php echo $product->shipping_product; ?>" class="form-control" name="shipping-product" id="shipping-product" min="0" step="any" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

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

                            <input type="number" value="<?php echo $product->delivery_time_product; ?>" class="form-control" name="delivery-time-product" id="delivery-time-product" min="0" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

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

                            <input type="number" value="<?php echo $product->stock_product; ?>" class="form-control" name="stock-product" id="stock-product" min="0" max="100" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')" required>

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

                            <?php if ($product->offer_product != null) : ?>

                                <?php if (json_decode($product->offer_product, true)[0] == "Discount") : ?>

                                    <option value="Discount">Discount</option>
                                    <option value="Fixed">Fixed</option>

                                <?php else : ?>

                                    <option value="Fixed">Fixed</option>
                                    <option value="Discount">Discount</option>

                                <?php endif ?>

                            <?php else : ?>

                                <option value="" disabled selected>Select type offer</option>
                                <option value="Discount">Discount</option>
                                <option value="Fixed">Fixed</option>

                            <?php endif ?>
                        </select>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!-- Valor de la oferta -->
                    <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">

                        <?php if ($product->offer_product != null) : ?>
                            <div class="input-group-append">

                                <?php if (json_decode($product->offer_product, true)[0] == "Discount") : ?>
                                    <span class="input-group-text type-offer">
                                        Percent %:
                                    </span>

                                <?php else : ?>

                                    <span class="input-group-text type-offer">
                                        Price $:
                                    </span>
                                <?php endif ?>

                            </div>

                            <div class="input-group-append">
                                <input type="number" value="<?php echo json_decode($product->offer_product, true)[1] ?>" name="value-offer-product" class="form-control" min="0" step="any" pattern="[0-9]{1,}" onchange="validateJS(event, 'numbers')">

                            </div>

                        <?php else : ?>
                            <div class="input-group-append">
                                <span class="input-group-text type-offer">

                                </span>
                            </div>

                            <input type="number" value="<?php echo json_decode($product->offer_product, true)[1] ?>" name="value-offer-product" class="form-control" min="0" step="any" pattern="[.\\,\\0-9]{1,}" onchange="validateJS(event, 'numbers')">

                        <?php endif ?>

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

                        <?php if ($product->offer_product != null) : ?>

                            <input type="date" value="<?php echo json_decode($product->offer_product, true)[2] ?>" name="date-offer-product" class="form-control">

                        <?php else : ?>

                            <input type="date" name="date-offer-product" class="form-control">

                        <?php endif ?>

                        <div class="valid-feedback">Valid</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>
                </div>
            </div>


            <!-- Modal Footer -->
            <div class="modal-footer">
                <div class="form-group submtit">
                    <button type="submit" class="ps-btn ps-btn--fullwidth">Save Changes</button>

                    <?php
                    $edit_product = new VendorsController();
                    $edit_product->edit_product();
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>