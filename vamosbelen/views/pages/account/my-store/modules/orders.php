<div class="ps-section__right">
    <div class="d-flex justify-content-between">

        <div>
            <a href="<?php echo TemplateController::path(); ?>account&my-store?product=new#profile-user" class="btn btn-lg bg-btn-primary my-3">Create new product</a>
        </div>

        <div>
            <ul class="nav nav-tabs">

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo TemplateController::path(); ?>account&my-store#profile-user">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo TemplateController::path(); ?>account&my-store&orders#profile-user">Orders</a>
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

    <table class="table dt-responsive dt-server-orders" width="100%">
        <thead>

            <tr>

                <th>#</th>

                <th>Status</th>

                <th>Client</th>

                <th>Email</th>

                <th>Country</th>

                <th>City</th>

                <th>Address</th>

                <th>Phone</th>

                <th>Product</th>

                <th>Quantity</th>

                <th>Details</th>

                <th>Price</th>

                <th>Process</th>

                <th>Date</th>

            </tr>

        </thead>
    </table>

</div>

<!-- Ventana modal para el processo de entrega -->

<div class="modal" id="next-process">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="post">

                <div class="modal-header">

                    <h4 class="modal-title">Next Process for <span></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body order-body">


                </div>

                <div class="modal-footer">
                    <div class="form-group submit">
                        <button type="submit" class="ps-btn ps-btn--fullwidth order-update">Save</button>
                    </div>
                </div>

                <?php

                $order = new VendorsController();
                $order->order_update();

                ?>

            </form>

        </div>

    </div>

</div>