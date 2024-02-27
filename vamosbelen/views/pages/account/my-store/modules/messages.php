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
                    <a class="nav-link" href="<?php echo TemplateController::path(); ?>account&my-store&orders#profile-user">Orders</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo TemplateController::path(); ?>account&my-store&disputes#profile-user">Disputes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  active" href="<?php echo TemplateController::path(); ?>account&my-store&messages#profile-user">Messages</a>
                </li>

            </ul>

        </div>

    </div>


    <input type="hidden" id="path" value="<?php echo TemplateController::path(); ?>">
    <input type="hidden" id="id-store" value="<?php echo $store[0]->id_store; ?>">

    <table class="table dt-responsive dt-server-messages" width="100%">
        <thead>

            <tr>

                <th>#</th>

                <th>Product</th>

                <th>Client</th>

                <th>Email</th>

                <th>Question</th>

                <th>Answer</th>

                <th>Date Answer</th>

                <th>Date Created</th>

            </tr>

        </thead>
    </table>

</div>

<!-- Ventana modal para responder la pregunta -->

<div class="modal" id="answer-message">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="post">

                <div class="modal-header">

                    <h4 class="modal-title">Answer question</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>

                <div class="modal-body">

                    <input type="hidden" name="id-message">
                    <input type="hidden" name="client-message">
                    <input type="hidden" name="email-message">
                    <input type="hidden" name="url-product">

                    <div class="form-group">
                        <label>Type your answer</label>

                        <div class="form-group__content">
                            <textarea name="answer-message" class="form-control" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="float-right">
                        <button type="submit" class="ps-btn ps-btn--fullwidth order-update">Send</button>
                    </div>
                </div>

                <?php

                $answer_message = new VendorsController();
                $answer_message->answer_message();

                ?>
            </form>
        </div>
    </div>
</div>