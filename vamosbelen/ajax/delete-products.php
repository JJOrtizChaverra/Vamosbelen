<?php

require_once "../controllers/curl.controller.php";

class DeleteController
{

    public $id;

    public function ajax_delete_product()
    {

        $select = "url_category,image_product,gallery_product,top_banner_product,default_banner_product,horizontal_slider_product,vertical_slider_product";

        $url = CurlController::api() . "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=id_product&equalTo={$this->id}&select=$select";
        $method = "GET";
        $fields = array();
        $header = array();

        $product = CurlController::request($url, $method, $fields, $header)->result[0];

        // Borrar imagen del producto
        unlink("../views/img/products/" . $product->url_category . "/$product->image_product");

        // Borrar la galeria de producto
        foreach (json_decode($product->gallery_product, true) as $key => $value) {
            unlink("../views/img/products/" . $product->url_category . "/gallery/$value");
        }

        // Borrar top banner
        if($product->top_banner_product != null) {
            unlink("../views/img/products/" . $product->url_category . "/top/".json_decode($product->top_banner_product, true)["IMG tag"]);
        }

        // Borrar default banner
        if($product->default_banner_product != null) {
            unlink("../views/img/products/" . $product->url_category . "/default/$product->default_banner_product");
        }

        // Borrar horizontal slider
        if($product->horizontal_slider_product != null) {
            unlink("../views/img/products/" . $product->url_category . "/horizontal/".json_decode($product->horizontal_slider_product, true)["IMG tag"]);
        }

        // Borrar vertical slider
        if($product->vertical_slider_product != null) {
            unlink("../views/img/products/" . $product->url_category . "/vertical/$product->vertical_slider_product");
        }
    }
}


if (isset($_POST['id-product'])) {

    $id_product = new DeleteController();
    $id_product->id = $_POST['id-product'];
    $id_product-> ajax_delete_product();
}
