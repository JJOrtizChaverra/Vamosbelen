<footer class="ps-footer">

    <div class="container">

        <div class="ps-footer__widgets">

            <!--=====================================
			Contact us
			======================================-->

            <aside class="widget widget_footer widget_contact-us">

                <h4 class="widget-title">Contact us</h4>

                <div class="widget_content">

                    <p>Call us 24/7</p>
                    <h3>1800 97 97 69</h3>
                    <p>502 New Design Str, Melbourne, Australia <br>
                        <a href="mailto:contact@vamosbelen.co">contact@vamosbelen.co</a>
                    </p>

                    <ul class="ps-list--social">
                        <li><a class="facebook" href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a class="twitter" href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a class="google-plus" href="#"><i class="fab fa-youtube"></i></a></li>
                        <li><a class="instagram" href="#"><i class="fab fa-instagram"></i></a></li>
                    </ul>

                </div>

            </aside>

            <!--=====================================
			Quick Links
			======================================-->

            <aside class="widget widget_footer">

                <h4 class="widget-title">Quick links</h4>

                <ul class="ps-list--link">

                    <li><a href="#">Privacy Policy</a></li>

                    <li><a href="#">Term &amp; Condition</a></li>

                    <li><a href="faqs.html">FAQs</a></li>

                </ul>

            </aside>

            <!--=====================================
			Company
			======================================-->

            <aside class="widget widget_footer">

                <h4 class="widget-title">Company</h4>

                <ul class="ps-list--link">

                    <li><a href="about-us.html">About Us</a></li>

                    <li><a href="contact-us.html">Contact</a></li>

                </ul>

            </aside>

            <!--=====================================
			Bussiness
			======================================-->

            <aside class="widget widget_footer">

                <h4 class="widget-title">Bussiness</h4>

                <ul class="ps-list--link">

                    <li><a href="#">Our Press</a></li>

                    <li><a href="<?php echo TemplateController::path() ?>checkout">Checkout</a></li>

                    <li><a href="<?php echo TemplateController::path() ?>account&login#header">My account</a></li>

                    <li><a href="<?php echo TemplateController::path() ?>store-list">Shops</a></li>

                </ul>

            </aside>

        </div>

        <!--=====================================
		Categories Footer
		======================================-->

        <div class="ps-footer__links">

            <?php foreach ($menu_categories as $key => $value) : ?>

                <p>
                    <strong><?php echo $value->name_category ?>:</strong>

                    <?php

                    // Traemos el listado de titulos
                    $title_list = json_decode($value->title_list_category);

                    ?>

                    <?php foreach ($title_list as $key => $value) : ?>

                        <?php
                        // Traer las subcategorias

                        $url = CurlController::api() . "subcategories?linkTo=title_list_subcategory&equalTo=" . rawurlencode($value)."&select=url_subcategory,name_subcategory";
                        $method = "GET";
                        $fields = array();
                        $header = array();

                        $menu_subcategories = CurlController::request($url, $method, $fields, $header)->result;
                        ?>

                        <?php foreach ($menu_subcategories as $key => $value) : ?>

                            <a href="<?php echo $path.$value->url_subcategory ?>"><?php echo $value->name_subcategory ?></a>

                        <?php endforeach ?>
                    <?php endforeach ?>
                </p>

            <?php endforeach ?>
        </div>

        <!--=====================================
		CopyRight - Payment method Footer
		======================================-->

        <div class="ps-footer__copyright">

            <p>© 2020 Vamosbelén. All Rights Reserved</p>

            <p>
                <span>We Using Safe Payment For:</span>

                <!-- <a href="#">
                    <img src="img/payment-method/1.jpg" alt="">
                </a>

                <a href="#">
                    <img src="img/payment-method/2.jpg" alt="">
                </a> -->

                <a href="#">
                    <img src="img/payment-method/3.jpg" alt="">
                </a>

                <!-- <a href="#">
                    <img src="img/payment-method/4.jpg" alt="">
                </a> -->

                <a href="#">
                    <img src="img/payment-method/5.jpg" alt="">
                </a>

            </p>

        </div>

    </div>

</footer>