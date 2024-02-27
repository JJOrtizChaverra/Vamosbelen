<?php

$reviews = TemplateController::average_reviews(json_decode($product->reviews_product, true));

if ($product->reviews_product != null) {
    $all_reviews = json_decode($product->reviews_product, true);
} else {
    $all_reviews = array();
}

// Data de mensajes

if (isset($_SESSION['user'])) {
    $select = "id_product,date_created_message,content_message,method_user,picture_user,displayname_user,id_user,answer_message,date_answer_message,id_product_message";

    $url = CurlController::api() . "relations?rel=messages,products,users&type=message,product,user&linkTo=id_product_message&equalTo=$product->id_product&orderBy=id_message&orderMode=DESC&select=$select&token={$_SESSION['user']->token_user}";
    $method = "GET";
    $fields = array();
    $header = array();

    $messages = CurlController::request($url, $method, $fields, $header)->result;

    if (!is_array($messages)) {
        $messages = array();
    }
} else {
    $messages = array();
}


?>

<!-- Navegacion del menu -->

<ul class="ps-tab-list">

    <li class="active"><a href="#tab-1">Description</a></li>
    <li><a href="#tab-2">Details</a></li>
    <li><a href="#tab-3">Vendor</a></li>
    <li><a href="#tab-4">Reviews (<?php echo count($all_reviews); ?>)</a></li>
    <li><a href="#tab-5">Questions and Answers</a></li>

</ul>

<div class="ps-tabs">

    <!-- Descripcion -->

    <div class="ps-tab active" id="tab-1">

        <div class="ps-document">

            <?php echo $product->description_product; ?>

        </div>

    </div>

    <!-- Detalles -->

    <div class="ps-tab" id="tab-2">

        <div class="table-responsive">

            <table class="table table-bordered ps-table ps-table--specification">

                <tbody>

                    <?php

                    $details = json_decode($product->details_product, true);

                    ?>

                    <?php foreach ($details as $key => $value) : ?>

                        <tr>
                            <td><?php echo $value['title']; ?></td>
                            <td><?php echo $value['value']; ?></td>
                        </tr>

                    <?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- El vendedor -->

    <div class="ps-tab" id="tab-3">

        <div class="media">
            <img src="img/stores/<?php echo $product->url_store; ?>/<?php echo $product->logo_store; ?>" alt="<?php echo $product->name_store; ?>" class="mr-5 mt-1 rounded-circle" width="120">
            <div class="media-body">
                <h4 class="mt-0"><?php echo $product->name_store; ?></h4>
                <p><?php echo $product->abstract_store; ?></p>
                <a href="<?php echo $path . $product->url_store; ?>"><?php echo $product->name_store; ?></a>
            </div>
        </div>

    </div>

    <!-- Las reseñas globales -->

    <div class="ps-tab" id="tab-4">

        <div class="row">

            <!-- Bloque de reseña -->

            <div class="col-lg-5 col-12 ">

                <div class="ps-block--average-rating">

                    <div class="ps-block__header">

                        <h3><?php echo $reviews ?></h3>

                        <select class="ps-rating" data-read-only="true">

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

                            option

                        </select>

                        <span><?php echo count($all_reviews); ?> Reviews</span>

                    </div>

                    <!-- Bloque de estrellas -->

                    <?php if ($product->reviews_product != null) : ?>
                        <?php if (count($all_reviews) > 0) {

                            // Creamos una matriz vacia para promediar las estrellas
                            $block_star = array(
                                "1" => 0,
                                "2" => 0,
                                "3" => 0,
                                "4" => 0,
                                "5" => 0
                            );

                            // Separamos las estrelass repetidas
                            $rep_start = array();

                            foreach ($all_reviews as $key => $value) {
                                array_push($rep_start, $value['review']);
                            }

                            // Unimos las estrellas repetidas con la matriz vacia de estrellas
                            foreach ($block_star as $key => $value) {
                                if (!empty(array_count_values($rep_start)[$key])) {
                                    $block_star[$key] = array_count_values($rep_start)[$key];
                                }
                            }
                        } ?>

                        <?php for ($i = 5; $i > 0; $i--) : ?>

                            <div class="ps-block__star">

                                <span><?php echo $i; ?> Star</span>

                                <div class="ps-progress" data-value="<?php echo round(($block_star[$i] * 100) / count($all_reviews)); ?>">

                                    <span></span>

                                </div>

                                <span><?php echo round(($block_star[$i] * 100) / count($all_reviews)); ?>%</span>

                            </div>


                        <?php endfor ?>

                    <?php endif ?>

                </div>

            </div>

            <!-- Tomar 5 reseñas aleatoriamente -->

            <?php if ($product->reviews_product != null) : ?>

                <div class="col-12">

                    <?php
                    $rand = array_rand($all_reviews, 5);
                    ?>

                    <?php foreach ($rand as $key => $value) : ?>
                        <div class="media border p-3 mb-3">

                            <?php if (empty($all_reviews[$value]['user'])) : ?>

                                <img src="img/users/default/default.png" class="mr-5 mt-1 rounded-circle" width="100">

                            <?php else : ?>

                                <?php

                                $select = "displayname_user,picture_user,method_user,id_user";

                                $url = CurlController::api() . "users?linkTo=id_user&equalTo={$all_reviews[$value]['user']}&select=$select";
                                $methos = "GET";
                                $fields = array();
                                $header = array();

                                $user = CurlController::request($url, $method, $fields, $header)->result[0];

                                ?>

                                <!-- Imagen del usuario -->

                                <?php if ($user->method_user == "direct") : ?>

                                    <?php if ($user->picture_user == null) : ?>

                                        <img class="mr-5 mt-1 rounded-circle" width="100" style="height: auto;" src="img/users/default/default.png" alt="profile">

                                    <?php else : ?>

                                        <img class="mr-5 mt-1 rounded-circle" width="100" style="height: auto;" src="img/users/<?php echo $user->id_user; ?>/<?php echo $user->picture_user; ?>" alt="profile">

                                    <?php endif ?>

                                <?php else : ?>

                                    <?php if (explode("/", $user->picture_user)[0] == "https:") : ?>

                                        <img class="mr-5 mt-1 rounded-circle" width="100" style="height: auto;" src="<?php echo $user->picture_user; ?>" alt="profile">

                                    <?php else : ?>

                                        <img class="mr-5 mt-1 rounded-circle" width="100" style="height: auto;" src="img/users/<?php echo $user->id_user; ?>/<?php echo $user->picture_user; ?>" alt="profile">

                                    <?php endif ?>

                                <?php endif ?>

                            <?php endif ?>


                            <div class="media-body">
                                <select class="ps-rating" data-read-only="false">

                                    <?php

                                    if ($all_reviews[$value]['review'] > 0) {
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($all_reviews[$value]['review'] < ($i + 1)) {
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

                                <?php if (empty($all_reviews[$value]['user'])) : ?>

                                    <h4 class="mt-0">Anonimous</h4>

                                <?php else : ?>

                                    <h4 class="mt-0"><?php echo $user->displayname_user; ?></h4>

                                <?php endif ?>

                                <p><?php echo $all_reviews[$value]['comment'] ?></p>
                            </div>
                        </div>
                    <?php endforeach ?>

                </div>

            <?php endif ?>

        </div>

    </div>

    <!-- Preguntas y respuestas -->

    <div class="ps-tab" id="tab-5">

        <div class="ps-block--questions-answers">

            <h3>Questions and Answers</h3>

            <form method="post" class="needs-validation" novalidate id="form-message">

                <input type="hidden" name="id-product" value="<?php echo $product->id_product; ?>">

                <?php if (isset($_SESSION['user'])) : ?>

                    <input type="hidden" name="id-user" value="<?php echo $_SESSION['user']->id_user; ?>">
                <?php else : ?>

                    <input type="hidden" name="id-user" value="">

                <?php endif ?>

                <input type="hidden" name="id-store" value="<?php echo $product->id_store; ?>">
                <input type="hidden" name="name-store" value="<?php echo $product->name_store; ?>">
                <input type="hidden" name="email-store" value="<?php echo $product->email_store; ?>">

                <input class="form-control mb-3" type="text" name="question" placeholder="Have a question? Search for answer?" required>


                <div class="input-group-append w-100 d-flex justify-content-end">
                    <button type="submit" class="btn bg-btn-primary btn-lg ">Send</button>
                </div>

                <?php

                $new_question = new UsersController();
                $new_question->new_question();

                ?>
            </form>

            <!-- Visualizar los mensajes -->

            <?php if (count($messages)) : ?>

                <?php foreach ($messages as $key_message => $message) : ?>

                    <?php if ($product->id_product == $message->id_product_message) : ?>

                        <div class="my-3">
                            <div class="media border p-3">
                                <div class="media-body">

                                    <h4><small>Question on <?php echo $message->date_created_message; ?> - <span class="font-weight-bold"><?php echo $message->displayname_user; ?></span></small></h4>
                                    <p><?php echo $message->content_message; ?></p>

                                </div>

                                <?php if ($message->method_user == "direct") : ?>

                                    <?php if ($message->picture_user == null) : ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/default/default.png" style="width: 60px;" alt="logo-user">

                                    <?php else : ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $message->id_user; ?>/<?php echo $message->picture_user; ?>" style="width: 60px;" alt="logo-user">

                                    <?php endif ?>

                                <?php else : ?>

                                    <?php if (explode("/", $message->picture_user)[0] == "https:") : ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="<?php echo $message->picture_user; ?>" style="width: 60px;" alt="logo-user">

                                    <?php else : ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $message->id_user; ?>/<?php echo $message->picture_user; ?>" style="width: 60px;" alt="logo-user">

                                    <?php endif ?>

                                <?php endif ?>

                            </div>

                            <?php if ($message->answer_message != null) : ?>

                                <div class="media border p-3">

                                    <img class="img-fluid rounded-circle ml-3 mt-3" src="img/stores/<?php echo $product->url_store; ?>/<?php echo $product->logo_store; ?>" style="width: 60px;" alt="logo-store">

                                    <div class="media-body text-right">

                                        <h4><small>Answer on <?php echo $message->date_answer_message; ?> - <span class="font-weight-bold"><?php echo $product->name_store; ?></span></small></h4>
                                        <p><?php echo $message->answer_message; ?></p>

                                    </div>

                                </div>

                            <?php endif ?>
                        </div>

                    <?php endif ?>

                <?php endforeach ?>

            <?php endif ?>

        </div>

    </div>
</div>