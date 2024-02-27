<?php
if (isset($_GET['product']) && $_GET['product'] != "new") {

    include "views/pages/account/my-store/modules/edit-product.php";
} else if (isset($_GET['product']) && $_GET['product'] == "new") {
    include "views/pages/account/my-store/modules/new-product.php";
} else {

?>

    <div class="ps-section__right">


        <div class="d-flex justify-content-between">

            <div>
                <a href="<?php echo TemplateController::path(); ?>account&my-store?product=new#profile-user" class="btn btn-lg bg-btn-primary my-3">Create new product</a>
            </div>

            <div>
                <ul class="nav nav-tabs">

                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo TemplateController::path(); ?>account&my-store#profile-user">Products</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo TemplateController::path(); ?>account&my-store&orders#profile-user">Orders</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo TemplateController::path(); ?>account&my-store&disputes#profile-user">Disputes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo TemplateController::path(); ?>account&my-store&messages#profile-user">Messages</a>
                    </li>

                </ul>

            </div>

        </div>

        <input type="hidden" id="path" value="<?php echo TemplateController::path(); ?>">
        <input type="hidden" id="id-store" value="<?php echo $store[0]->id_store; ?>">
        <input type="hidden" id="url-api" value="<?php echo CurlController::api(); ?>">

        <table class="table dt-responsive dt-server-products" width="100%">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Actions</th>

                    <th>Feedback</th>

                    <th>State</th>

                    <th>Image</th>

                    <th>Product name</th>

                    <th>Category</th>

                    <th>Subcategory</th>

                    <th>Price</th>

                    <th>Shipping</th>

                    <th>Stock: </th>

                    <th>Delivery time: </th>

                    <th>Offer:</th>

                    <th>Summary: </th>

                    <th>Specifications: </th>

                    <th>Details: </th>

                    <th>Description: </th>

                    <th>Gallery: </th>

                    <th>Top Banner: </th>

                    <th>Default Banner: </th>

                    <th>Horizontal Slider: </th>

                    <th>Vertical Slider: </th>

                    <th>Video: </th>

                    <th>Tags: </th>

                    <th>Views: </th>

                    <th>Sales: </th>

                    <th>Reviews: </th>

                    <th>Date Created: </th>

                </tr>

            </thead>

        </table>

    </div>

<?php } ?>