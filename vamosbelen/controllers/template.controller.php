<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TemplateController
{
    // Traemos la vista principal de la plantilla

    public function index()
    {
        include "views/template.php";
    }

    // Ruta principal o dominio del sitio

    static public function path()
    {
        return "http://vamosbelen.com/";
    }

    // Ahorro en la oferta

    static public function saving_value($price, $offer, $type)
    {

        // Cuando la oferta es con descuento
        if ($type == "Discount") {
            $save = $offer * $price / 100;
            return number_format($save, 2);
        }

        // Cuando la oferta es con precio fijo
        if ($type == "Fixed") {
            $save = $price - $offer;
            return number_format($save, 2);
        }
    }

    // Precio final de oferta
    static public function offer_price($price, $offer, $type)
    {

        // Cuando la oferta es con descuento
        if ($type == "Discount") {
            $offer_price = $price - ($offer * $price / 100);

            return number_format($offer_price, 2);
        }

        // Cuando la oferta es con precio fijo
        if ($type == "Fixed") {
            $offer_price = $offer;

            return number_format($offer_price, 2);
        }
    }

    // Promediar reseñas
    static public function average_reviews($reviews)
    {
        $total_reviews = 0;

        if ($reviews != null) {
            foreach ($reviews as $key => $value) {
                $total_reviews += $value['review'];
            }

            $promedio_reviews = round($total_reviews / count($reviews));

            return $promedio_reviews;
        } else {
            return 0;
        }
    }

    // Descuento de la oferta

    static public function offer_discount($price, $offer, $type)
    {
        if ($type == "Discount") {
            $offer_discount = $offer;

            return $offer_discount;
        }

        if ($type == "Fixed") {
            $offer_discount = round(($offer * 100) / $price);

            return $offer_discount;
        }
    }

    // Metodo para mayuscula inicial

    static public function capitalize($value)
    {
        $text = str_replace("_", " ", $value);

        return ucwords($text);
    }

    // Funcion para enviar correos electronicos

    static public function send_email($name, $subject, $email, $message, $url)
    {
        // Saber o definir la fecha en la que el programa esta funcionando
        date_default_timezone_set("America/Bogota");

        // Instanciando la clase de la libreria PHPMailer
        $mail = new PHPMailer;

        // Metodo que nos permite definir la codificacion de los caracteres
        $mail->CharSet = "UTF-8";

        // Metodo para enviar correos electronicos
        $mail->isMail();

        // Metodo para decir quien esta enviando un correo electronico

        // Recomendable usar correos reales o corporativos
        // Primer parametro: Correo electronico de quien esta enviando
        // Segundo parametro: Nombre de la empresa, corporacion o dueño del correo
        $mail->setFrom("support@vamosbelen.com", "Vamosbelen Support");

        // Metodo para crear el asunto del correo
        $mail->Subject = "Hi $name - $subject";

        // Metodo para decir a que correo se va a enviar
        $mail->addAddress($email);

        // Metodo para escribir el mensaje
        $mail->msgHTML(
            '<div>
                Hi, ' . $name . ':

                <p>' . $message . '</p>

                <a href="' . $url . '">Click this link for more information</a>

                If ypur didn`t ask to verify this address, you can ignore this email

                Thanks.

                Your vamosbelen Team
            </div>'
        );

        // Metodo para enviar el correo electronico
        $send = $mail->Send();

        if (!$send) {
            return $mail->ErrorInfo;
        } else {
            return "Ok";
        }
    }

    // Funcion para almacenar imagenes

    static public function save_image($image, $folder, $path, $width, $height, $name)
    {
        if (isset($image['tmp_name']) && !empty($image['tmp_name'])) {

            // Configuramos la ruta del directorio donde se guardara el archivo
            $directory = strtolower("views/$folder/$path");

            // Validamos la existencia del directorio
            if (!file_exists($directory)) {

                // Creamos el directorio si no existe
                mkdir($directory, 0755);
            }

            // Eliminar todos los archivos que existan en ese directorio

            if ($folder != "img/stores" && $folder != "img/products") {
                $files = glob($directory . "/*");

                foreach ($files as $file) {
                    unlink($file);
                }
            }

            // Capturar el ancho y alto original de la imagen
            list($last_width, $last_height) = getimagesize($image['tmp_name']);

            $new_width = $width;
            $new_height = $height;

            // De acuerdo al tipo de imagen aplicamos las funciones por defecto
            if ($image['type'] == "image/jpeg") {

                // Definimos el nombre del archivo
                $new_name = "$name.jpg";

                // Definimos el lugar de almacenamiento
                $folder_path = "$directory/$new_name";

                if (isset($image["mode"]) && $image["mode"] == "base64") {

                    file_put_contents($folder_path, file_get_contents($image['tmp_name']));
                    
                } else {
                    // Crear una copia de la imagen
                    $start = imagecreatefromjpeg($image['tmp_name']);

                    // Instrucciones para aplicar a la imagen definitiva
                    $end = imagecreatetruecolor($new_width, $new_height);

                    imagecopyresized($end, $start, 0, 0, 0, 0, $new_width, $new_height, $last_width, $last_height);

                    imagejpeg($end, $folder_path);
                }
            }

            if ($image['type'] == "image/png") {

                // Definimos el nombre del archivo
                $new_name = "$name.png";

                // Definimos el lugar de almacenamiento
                $folder_path = "$directory/$new_name";

                if (isset($image["mode"]) && $image["mode"] == "base64") {

                    file_put_contents($folder_path, file_get_contents($image['tmp_name']));

                } else {
                    // Crear una copia de la imagen
                    $start = imagecreatefrompng($image['tmp_name']);

                    // Instrucciones para aplicar a la imagen definitiva
                    $end = imagecreatetruecolor($new_width, $new_height);

                    // Mantener la transparencia en caso que venga con transparencia
                    imagealphablending($end, FALSE);

                    imagesavealpha($end, TRUE);

                    imagecopyresampled($end, $start, 0, 0, 0, 0, $new_width, $new_height, $last_width, $last_height);

                    imagepng($end, $folder_path);
                }
            }

            return $new_name;
        } else {
            return "Error";
        }
    }


    // Metodo para limpiar html

    static public function html_clean($code) {
        $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');

		$replace = array('>','<','\\1');

		$code = preg_replace($search, $replace, $code);

		$code = str_replace("> <", "><", $code);

		return $code;
    }
}
