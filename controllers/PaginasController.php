<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {

    public static function index(Router $router) {

        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render('paginas/nosotros');
    }

    public static function propiedades(Router $router) {

        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {

        $id = validarORedireccionar('/propiedades');
    
            // Busca la propiedad por su id
        $propiedad = Propiedad::find($id);


        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad

        ]);
    }

    public static function blog(Router $router) {
        $router->render('paginas/blog');
    }

    public static function entrada(Router $router) {
        $router->render('paginas/entrada');
    }
    
    public static function contacto(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $respuestas = $_POST['contacto'];


                // crear una instancia PHPMailer
            $mail = new PHPMailer();
                // Configura SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'a99380f5bb1963';
            $mail->Password = 'ca561239363491';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;
            
                // Configurar el contenido del mail
            $mail->SetFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

                // Habilitar HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

                // Definir el contenido
            $contenido = '<html>'; 
            $contenido .= '<p>Tienes un mensaje</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . ' </p> ';
            $contenido .= '<p>Email: ' . $respuestas['email'] . ' </p> ';
            $contenido .= '<p>Telefono: ' . $respuestas['telefono'] . ' </p> ';
            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . ' </p> ';
            $contenido .= '<p>Tipo de operacion: ' . $respuestas['tipo'] . ' </p> ';
            $contenido .= '<p>Precio o presupuesto: $' . $respuestas['precio'] . ' </p> ';
            $contenido .= '<p>Preferencia de contacto: ' . $respuestas['contacto'] . ' </p> ';
            $contenido .= '<p>Fecha para contacto: ' . $respuestas['fecha'] . ' </p> ';
            $contenido .= '<p>Hora para contacto: ' . $respuestas['hora'] . ' </p> ';
            $contenido .= '</html>';



            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin html';

                // Enviar el email
            if($mail->send()) {
                echo 'Mensaje enviado correctalmente';
            } else {
                echo 'El mensaje no se pudo enviar';
            }

           
        }
            $router->render('paginas/contacto', [

        ]);
    }


}