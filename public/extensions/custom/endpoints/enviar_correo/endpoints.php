<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    'registro' => [
        'method' => 'POST',
        'handler' => function(Request $request, Response $response) {
            $body = $request->getParsedBody();
            $to = $body['email'];
            $name = $body['nombre'];
            $subject = 'Registro exitoso';
            // $message = 'Tu registro en Beatstudio ha sido registrado con exito!! Bienvenido.' . '\n';
            // $message .= 'Comienza a reservar tus clases www.beatstudio.com.mx';                    
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: BeatStudio <beatstudio.notify@gmail.com>' . "\r\n";

            $message = '<!DOCTYPE html>';
            $message.= '<html lang="en">';
            $message.= '<head>';
            $message.= '<meta charset="UTF-8">';
            $message.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            $message.= '<title>Document</title>';
            $message.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"';
            $message.= 'integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

            $message.= '<style>';
            $message.= '@font-face {';
            $message.= 'font-family: "GT-America-Regular";';
            $message.= "src: url('https://www.beatstudio.com.mx/assets/fonts/SharpGrotesk/Semibold/SharpGroteskSmBold08-Regular.ttf') format('truetype');";
            $message.= '}';

            $message.= 'body {';
            $message.= 'background-color: #eee;';
            $message.= 'min-width: 600px';
            $message.= '}';

            $message.= '.email-content {';
            $message.= 'width: 500px;';
            $message.= "background-image: url('https://www.beatstudio.com.mx/assets/img/correos/Correo_Imagen.jpg');";
            $message.= 'background-repeat: no-repeat;';
            $message.= 'background-size: cover';
            $message.= '}';

            $message.= '.email-content span {';
            $message.= 'font-weight: bold;';
            $message.= '}';

            $message.= '.email-content .logo {';
            $message.= 'width: 100%;';
            $message.= '}';

            $message.= '.table-title {';
            $message.= 'font-weight: bold;';
            $message.= 'color: rgb(73, 73, 73);';
            $message.= '}';

            $message.= '.socials {';
            $message.= 'width: 20px;';
            $message.= 'margin: 10px;';
            $message.= '}';

            $message.= '.socials>img {';
            $message.= 'width: 20px;';
            $message.= '}';
            $message.='</style>';
            $message.= '</head>';
            $message.= '<body>';
            $message.= '<div class="row justify-content-center my-5">';
            $message.= '<div class="email-content p-5">';
            $message.= '<div class="row">';
            $message.= '<div class="col-6">';
           // $message.= '<img class="logo" src="https://www.beatstudio.com.mx/assets/img/correos/BeatStudio_Logo-01.svg" alt="BeatStudio">';
            $message.= '</div>';
            $message.= '<div class="col-6 d-flex justify-content-end">';
           // $message.= '<span class="align-self-center">14 de Octubre del 2020</span>';
            $message.= '</div>';
            $message.= '<div class="col-12">';
            $message.= '<h1 class="text-center mt-5">¡Felicidades!</h1>';
            $message.= '<p class="mt-5">Te has registrado de forma exitosa, para disfrutar de tu cuenta, ve a <a href="https://beatstudio.com.mx">www.beatstudio.com.mx</a>';
            $message.= 'BeatStudio, Gracias';
            $message.= ' por ';
            $message.= 'tu preferencia.</p>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row mt-5" style="border-top: 1px solid #000">';
            $message.= '<div class="col-12  mt-4 d-flex justify-content-center">';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Instagram(Layout%20contacto).svg" alt="">';
            $message.= '</a>';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Twitter(Layout%20contacto).svg" alt="">';
            $message.= '</a>';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Facebook(Layout%20contacto9.svg" alt="">';
            $message.= '</a>';
            $message.= '</div>';
            $message.= '<div class="col-12 text-center">';
            $message.= 'Copyright 2020 BeatStudio. Todos los derechos reservados.';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</body>';

            $message.= '</html>';

            mail($to, $subject, $message, $headers);

            $toAdmin = "beatspinstudio@gmail.com";
            // $adminMessage = '<!DOCTYPE html>';
            // $adminMessage.= '<html lang="en">';
            // $adminMessage.= '<head>';
            // $adminMessage.= '<meta charset="UTF-8">';
            // $adminMessage.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            // $adminMessage.= '<title>Document</title>';
            // $adminMessage.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"';
            // $adminMessage.= 'integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';
            // $adminMessage.='</style>';
            // $adminMessage.= '</head>';
            // $adminMessage.= '<body>';
            // $adminMessage.= '<div class="row">';
            // $adminMessage.= '<div class="col-12">';
            // $adminMessage.= 'Nuevo usuario registrado';
            // $adminMessage.= '</div>';
            // $adminMessage.= '</div>';
            // $adminMessage.= '<div class="row">'
            // $adminMessage.= '<div class="col-12">';
            // $adminMessage.= 'usuario: ' . $name;
            // $adminMessage.= '</div>';
            // $adminMessage.= '</div>';
            // $adminMessage.= '<div class="row">'
            // $adminMessage.= '<div class="col-12">';
            // $adminMessage.= 'correo: ' . $to;
            // $adminMessage.= '</div>';
            // $adminMessage.= '</div>';
            // $adminMessage.= '</body>';
            // $adminMessage.= '</html>';

            $adminMessage = "Nuevo usuario registro!!" . "\r\n";
            $adminMessage.= "nombre: " . $name . "\r\n";
            $adminMessage.= "correo: " . $to;
            mail($toAdmin, $subject, $adminMessage, $headers);

            return $response->withJson([
                'message' => $message
            ]);
        }
    ],
    'compra' => [
        'method' => 'POST',
        'handler' => function(Request $request, Response $response) {
            $body = $request->getParsedBody(); 
            $to = $body['email'];
            $paquete = $body["paquete"];
            $subject = 'Confirmación de compra';
            $fecha = strtotime($paquete['vigencia']);
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: BeatStudio<beatstudio.notify@gmail.com>' . "\r\n";

            // $message = "Haz realizado una compra en www.beatstudio.com.mx por " . $paquete["creditos"] . " créditos, ";
            // $message.= "por un total de $" . $paquete["precio"] . ", con vigencia de " . $paquete["vigenciaDias"];
            // $message.= ", tienes hasta el " . date('d/m/Y', $fecha) . " para utilizarlos.";
            // $message.=" Empieza a reservar ahora!!";

            $message = '<!DOCTYPE html>';
            $message.= '<html lang="en">';
            $message.= '<head>';
            $message.= '<meta charset="UTF-8">';
            $message.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            $message.= '<title>Document</title>';
            $message.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"';
            $message.= 'integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

            $message.= '<style>';
            $message.= '@font-face {';
            $message.= 'font-family: "GT-America-Regular";';
            $message.= "src: url('https://www.beatstudio.com.mx/assets/fonts/SharpGrotesk/Semibold/SharpGroteskSmBold08-Regular.ttf') format('truetype');";
            $message.= '}';

            $message.= 'body {';
            $message.= 'background-color: #eee;';
            $message.= 'min-width: 600px';
            $message.= '}';

            $message.= '.email-content {';
            $message.= 'width: 500px;';
            $message.= "background-image: url('https://www.beatstudio.com.mx/assets/img/correos/Correo_Imagen.jpg');";
            $message.= 'background-repeat: no-repeat;';
            $message.= 'background-size: cover';
            $message.= '}';

            $message.= '.email-content span {';
            $message.= 'font-weight: bold;';
            $message.= '}';

            $message.= '.email-content .logo {';
            $message.= 'width: 100%;';
            $message.= '}';

            $message.= '.table-title {';
            $message.= 'font-weight: bold;';
            $message.= 'color: rgb(73, 73, 73);';
            $message.= '}';

            $message.= '.socials {';
            $message.= 'width: 20px;';
            $message.= 'margin: 10px;';
            $message.= '}';

            $message.= '.socials>img {';
            $message.= 'width: 20px;';
            $message.= '}';
            $message.='</style>';
            $message.= '</head>';
            $message.= '<body>';
            $message.= '<div class="row justify-content-center my-5">';
            $message.= '<div class="email-content p-5">';
            $message.= '<div class="row">';
            $message.= '<div class="col-6">';
            // $message.= '<img class="logo" src="https://www.beatstudio.com.mx/assets/img/correos/BeatStudio_Logo-01.svg" alt="BeatStudio">';
            $message.= '</div>';
            $message.= '<div class="col-6 d-flex justify-content-end">';
            // $message.= '<span class="align-self-center">14 de Octubre del 2020</span>';
            $message.= '</div>';
            $message.= '<div class="col-12">';
            $message.= '<h1 class="text-center mt-5">¡Gracias por tu compra!</h1>';
            $message.= '<p class="mt-5">Se ha realizado una compra de ' . $paquete["creditos"] .'créditos desde tu cuenta';
            $message.= ' BeatStudio, Gracias';
            $message.= ' por ';
            $message.= 'tu preferencia.</p>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row mt-5" style="border-top: 1px solid #000">';
            $message.= '<div class="col-12  mt-4 d-flex justify-content-center">';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Instagram(Layout%20contacto).svg" alt="">';
            $message.= '</a>';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Twitter(Layout%20contacto).svg" alt="">';
            $message.= '</a>';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Facebook(Layout%20contacto9.svg" alt="">';
            $message.= '</a>';
            $message.= '</div>';
            $message.= '<div class="col-12 text-center">';
            $message.= 'Copyright 2020 BeatStudio. Todos los derechos reservados.';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</body>';

            $message.= '</html>';

            mail($to, $subject, $message, $headers);

            return $response->withJson([
                "message" => $message
            ]);
        }
    ],
    'reservacion' => [
        'method' => 'POST',
        'handler' => function(Request $request, Response $response) {
            $body = $request->getParsedBody();
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion_detalle', $dbConnection);
            $where = new Zend\Db\Sql\Where;

            $reservacion = $body['reservacion'];
            $horario = $body['horario'];
            $coach = $body['coach'];
            $disciplina = $body['disciplina'];
            $fecha = strtotime($horario['fecha']);
            $detalles = $body['detalles'];
            $where->equalTo('reservacion', $reservacion);
            $select = $tableGateway->select($where);

            $to = $body['email'];
            $subject = 'Confirmación de Reservación';


            // $message = 'Has reservado con éxito para la clase de ' . $disciplina['nombre'] . ' en la fecha ' . date('d/m/Y', $fecha);
            // $message .= ' en la hora: ' . date('h:i', $fecha);
            // $message .= ' los siguientes lugares:' . ' \n';
            // foreach($detalles as $d) {
            //     $message .= ' el lugar: ' . $d['lugar'] . ' para: ' .  $d['nombre'];
            //     $message .= ' \n';
            // }

            $message = '<!DOCTYPE html>';
            $message.= '<html lang="en">';

            $message.= '<head>';
            $message.= '<meta charset="UTF-8">';
            $message.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            $message.= '<title>Document</title>';
            $message.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

            $message.= '<style>';
            $message.= '@font-face {';
            $message.= "font-family: 'GT-America-Regular';";
            $message.= "src: url('https://www.beatstudio.com.mx/assets/fonts/SharpGrotesk/Semibold/SharpGroteskSmBold08-Regular.ttf') format('truetype');";
            $message.= '}';

            $message.= 'body {';
            $message.= 'background-color: #eee;';
            $message.= 'min-width: 600px;';
            $message.= '}';

            $message.= '.email-content {';
            $message.= 'width: 500px;';
            $message.= "background-image: url('https://www.beatstudio.com.mx/assets/img/correos/Correo_Imagen.jpg');";
            $message.= 'background-repeat: no-repeat;';
            $message.= 'background-size: cover;';
            $message.= '}';

            $message.= '.email-content span {';
            $message.= 'font-weight: bold;';
            $message.= '}';

            $message.= '.email-content .logo {';
            $message.= 'width: 100%;';
            $message.= '}';

            $message.= '.table-title {';
            $message.= 'font-weight: bold;';
            $message.= 'color: rgb(73, 73, 73);';
            $message.= '}';

            $message.= '.socials {';
            $message.= 'width: 20px;';
            $message.= 'margin: 10px;';
            $message.= '}';

            $message.= '.socials>img {';
            $message.= 'width: 20px;';
            $message.= '}';
            $message.= '</style>';
            $message.= '</head>';
            $message.= '<body>';
            $message.= '<div class="row justify-content-center my-5">';
            $message.= '<div class="email-content p-5">';
            $message.= '<div class="row">';
            $message.= '<div class="col-6">';
           // $message.= '<img class="logo" src="https://www.beatstudio.com.mx/assets/img/correos/BeatStudio_Logo-01.svg" alt="BeatStudio">';
            $message.= '</div>';
            $message.= '<div class="col-6 d-flex justify-content-end">';
            // $message.= '<span class="align-self-center">'. date('d/m/Y', $fecha) .'</span>';
            $message.= '</div>';
            $message.= '<div class="col-12">';
            $message.= '<h1 class="text-center mt-5">¡Reservación exitosa!</h1>';
            $message.= '<p class="mt-5">Se ha registrado una reservación para <span>'. $disciplina['nombre'] .'</span> desde tu cuenta';
            $message.= ' BeatStudio, Gracias';
            $message.= ' por ';
            $message.= 'tu preferencia.</p>';
            $message.= '</div>';
            $message.= '<div class="col-12" style="border-top:1px solid #000">';
            $message.= '<h6 class="mt-5">Detalles de tu reservación:</h6>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
            $message.= '<div class="col-6 table-title">';
            $message.= 'Clase';
            $message.= '</div>';
            $message.= '<div class="col-6">';
            $message.= $disciplina["nombre"];
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
            $message.= '<div class="col-6 table-title">';
            $message.= 'Día';
            $message.= '</div>';
            $message.= '<div class="col-6">';
            $message.= date('d/m/Y', $fecha);
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
            $message.= '<div class="col-6 table-title">';
            $message.= 'Hora';
            $message.= '</div>';
            $message.= '<div class="col-6">';
            $message.= date('h:i A', $fecha);
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
            $message.= '<div class="col-6 table-title">';
            $message.= 'Coach';
            $message.= '</div>';
            $message.= '<div class="col-6">';
            $message.= $coach["nombre"];
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
            $message.= '<div class="col-6 table-title">';
            $message.= 'Lugares';
            $message.= '</div>';
            $message.= '<div class="col-6">';
            foreach($detalles as $d) {
                $message.= '<div>';
                $message.= $d['lugar'] . ' ' .  $d['nombre'];
                $message.= '</div>';
            }      
            // $message. = '<div>';
            // $message. = '20 Juan';
            // $message. = '</div>';
            // $message. = '<div>';
            // $message. = '10 Carlos';
            // $message. = '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '<div class="row mt-5" style="border-top: 1px solid #000">';
            $message.= '<div class="col-12  mt-4 d-flex justify-content-center">';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Instagram(Layout%20contacto).svg" alt="">';
            $message.= '</a>';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Twitter(Layout%20contacto).svg" alt="">';
            $message.= '</a>';
            $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
            $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Facebook(Layout%20contacto9.svg" alt="">';
            $message.= '</a>';
            $message.= '</div>';
            $message.= '<div class="col-12 text-center">';
            $message.= 'Copyright 2020 BeatStudio. Todos los derechos reservados.';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</div>';
            $message.= '</body>';

            $message.= '</html>';
            
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: BeatStudio <beatstudio.notify@gmail.com>' . "\r\n";

            mail($to, $subject, $message, $headers);

            $toAdmin = "beatspinstudio@gmail.com";
            mail($toAdmin, $subject, $message, $headers); //mail to admin

            return $response->withJson([
                'message' => $message
            ]);
        }
    ],
    'lista-espera' => [
        'method' => 'POST',
        'handler' => function(Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

            try {
                $body = $request->getParsedBody();
                if(sizeof($body) === 0) {
                    throw new Exception("No se recibieron los parametros solicitados");
                }
                else{
                    if(!isset($body['disciplina']))
                        throw new Exception("No se recibió el parametro Disciplina");
                    if(!isset($params['correos']))
                        throw new Exception("No se recibieron correos");
                }
                $disciplina = $body['disciplina'];
                $correos = $body['correos'];
                $to = '';
                foreach($correos as $c) {
                    $to.= $c . ',';
                }
                $res = substr(0, strlen($to) - 2);
                $subject = 'Lugares disponibles';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: beatstudio.notify@gmail.com' . "\r\n";

                $message = '<!DOCTYPE html>';
                $message.= '<html lang="en">';
                $message.= '<head>';
                $message.= '<meta charset="UTF-8">';
                $message.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                $message.= '<title>Document</title>';
                $message.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"';
                $message.= 'integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

                $message.= '<style>';
                $message.= '@font-face {';
                $message.= 'font-family: "GT-America-Regular";';
                $message.= "src: url('https://www.beatstudio.com.mx/assets/fonts/SharpGrotesk/Semibold/SharpGroteskSmBold08-Regular.ttf') format('truetype');";
                $message.= '}';

                $message.= 'body {';
                $message.= 'background-color: #eee;';
                $message.= 'min-width: 600px';
                $message.= '}';

                $message.= '.email-content {';
                $message.= 'width: 500px;';
                $message.= "background-image: url('https://www.beatstudio.com.mx/assets/img/correos/Correo_Imagen.jpg');";
                $message.= 'background-repeat: no-repeat;';
                $message.= 'background-size: cover';
                $message.= '}';

                $message.= '.email-content span {';
                $message.= 'font-weight: bold;';
                $message.= '}';

                $message.= '.email-content .logo {';
                $message.= 'width: 100%;';
                $message.= '}';

                $message.= '.table-title {';
                $message.= 'font-weight: bold;';
                $message.= 'color: rgb(73, 73, 73);';
                $message.= '}';

                $message.= '.socials {';
                $message.= 'width: 20px;';
                $message.= 'margin: 10px;';
                $message.= '}';

                $message.= '.socials>img {';
                $message.= 'width: 20px;';
                $message.= '}';
                $message.='</style>';
                $message.= '</head>';
                $message.= '<body>';
                $message.= '<div class="row justify-content-center my-5">';
                $message.= '<div class="email-content p-5">';
                $message.= '<div class="row">';
                $message.= '<div class="col-6">';
            // $message.= '<img class="logo" src="https://www.beatstudio.com.mx/assets/img/correos/BeatStudio_Logo-01.svg" alt="BeatStudio">';
                $message.= '</div>';
                $message.= '<div class="col-6 d-flex justify-content-end">';
                // $message.= '<span class="align-self-center">14 de Octubre del 2020</span>';
                $message.= '</div>';
                $message.= '<div class="col-12">';
                $message.= '<h1 class="text-center mt-5">¡Hay 1 lugar libre!</h1>';
                $message.= '<p class="mt-5">Se ha desocupado un lugar en la clase de '. $disciplina.' del día '.$body['fecha'] ? $body['fecha'] :' fecha no disponible'.' </p>';
                $message.= '<p class="mt-5">BeatStudio, Gracias por tu preferencia.</p>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row mt-5" style="border-top: 1px solid #000">';
                $message.= '<div class="col-12  mt-4 d-flex justify-content-center">';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Instagram(Layout%20contacto).svg" alt="">';
                $message.= '</a>';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Twitter(Layout%20contacto).svg" alt="">';
                $message.= '</a>';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Facebook(Layout%20contacto9.svg" alt="">';
                $message.= '</a>';
                $message.= '</div>';
                $message.= '<div class="col-12 text-center">';
                $message.= 'Copyright 2020 BeatStudio. Todos los derechos reservados.';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</body>';

                $message.= '</html>';

                $response_ = mail($to, $subject, $message, $headers);
                
                return $response->withJson([
                    'res' => $res,
                    'response' => $response_
                ]);
            }
            catch(Throwable  $e){
                 $errorGateway->insert(array(
                    "cliente" => 0,
                    "error" => "No enviado a destinatario".$body["email"] ? $body["email"] : "No recibido"." ".$e->getMessage(),
                    "seccion" => "Correo",
                    "notified" => "No necesario",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }

        }
    ],
    'cambio-pass' => [
        'method' => 'POST',
        'handler' => function(Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('directus_users', $dbConnection);
            try {
                $body = $request->getParsedBody();
                // $res = $tableGateway->select(function(Select $select) {
                //     $select->columns(array('id'));
                //     $select->where('email', $body['email']);
                // });
                $to = $body["email"];
                $subject = 'Cambio de contraseña';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: Beatstudio' . "\r\n";

                $message = '<!DOCTYPE html>';
                $message.= '<html lang="en">';
                $message.= '<head>';
                $message.= '<meta charset="UTF-8">';
                $message.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                $message.= '<title>Document</title>';
                $message.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"';
                $message.= 'integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

                $message.= '<style>';
                $message.= '@font-face {';
                $message.= 'font-family: "GT-America-Regular";';
                $message.= "src: url('https://www.beatstudio.com.mx/assets/fonts/SharpGrotesk/Semibold/SharpGroteskSmBold08-Regular.ttf') format('truetype');";
                $message.= '}';

                $message.= 'body {';
                $message.= 'background-color: #eee;';
                $message.= 'min-width: 600px';
                $message.= '}';

                $message.= '.email-content {';
                $message.= 'width: 500px;';
                $message.= "background-image: url('https://www.beatstudio.com.mx/assets/img/correos/Correo_Imagen.jpg');";
                $message.= 'background-repeat: no-repeat;';
                $message.= 'background-size: cover';
                $message.= '}';

                $message.= '.email-content span {';
                $message.= 'font-weight: bold;';
                $message.= '}';

                $message.= '.email-content .logo {';
                $message.= 'width: 100%;';
                $message.= '}';

                $message.= '.table-title {';
                $message.= 'font-weight: bold;';
                $message.= 'color: rgb(73, 73, 73);';
                $message.= '}';

                $message.= '.socials {';
                $message.= 'width: 20px;';
                $message.= 'margin: 10px;';
                $message.= '}';

                $message.= '.socials>img {';
                $message.= 'width: 20px;';
                $message.= '}';
                $message.='</style>';
                $message.= '</head>';
                $message.= '<body>';
                $message.= '<div class="row justify-content-center my-5">';
                $message.= '<div class="email-content p-5">';
                $message.= '<div class="row">';
                $message.= '<div class="col-6">';
            // $message.= '<img class="logo" src="https://www.beatstudio.com.mx/assets/img/correos/BeatStudio_Logo-01.svg" alt="BeatStudio">';
                $message.= '</div>';
                $message.= '<div class="col-6 d-flex justify-content-end">';
                // $message.= '<span class="align-self-center">14 de Octubre del 2020</span>';
                $message.= '</div>';
                $message.= '<div class="col-12">';
                $message.= '<h1 class="text-center mt-5">¡Hola!</h1>';
                $message.= '<p class="mt-5">Se ha solicitado el restablecimiento de contraseña para tu cuenta, si deseas continuar, ve al siguiente link <a href="https://www.beatstudio.com.mx/#/newpassword/' . $body["id"] . '"' .'> cambiar contraseña </a>';
                $message.= 'BeatStudio, Gracias';
                $message.= ' por ';
                $message.= 'tu preferencia.</p>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row mt-5" style="border-top: 1px solid #000">';
                $message.= '<div class="col-12  mt-4 d-flex justify-content-center">';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Instagram(Layout%20contacto).svg" alt="">';
                $message.= '</a>';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Twitter(Layout%20contacto).svg" alt="">';
                $message.= '</a>';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Facebook(Layout%20contacto9.svg" alt="">';
                $message.= '</a>';
                $message.= '</div>';
                $message.= '<div class="col-12 text-center">';
                $message.= 'Copyright 2020 BeatStudio. Todos los derechos reservados.';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</body>';

                $message.= '</html>';

                mail($to, $subject, $message, $headers);
                            
                return $response->withJson([
                    'user' => $message
                ]);
            }
            catch(Throwable  $e){
                 $errorGateway->insert(array(
                    "cliente" => 0,
                    "error" => "No enviado a destinatario".$body["email"] ? $body["email"] : "No recibido"." ".$e->getMessage(),
                    "seccion" => "Correo",
                    "notified" => "No necesario",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }
        }
    ],
    'cancelacion-exitosa' =>[
        'method' => 'POST',
        'handler' => function(Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

            try {
                $body = $request->getParsedBody();
                $reservacion = $body['reservacion'];
                $horario = $body['horario'];
                $coach = $body['coach'];
                $disciplina = $body['disciplina'];
                $fecha = strtotime($horario['fecha']);
                $detalles = $body['detalles'];
                $email = $body["email"];
                $to = $body["email"];
                $subject = 'Confirmación de Cancelación';
                // $message = '<div>Has solicitado un cambio de contraseña, ve al siguiente link, para continuar </div>';
                // $message .= '<a href="https://www.beatstudio.com.mx/newpassword/' . $body["id"] . '"' .'> cambiar contraseña</a>';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From:BeatStudio <beatstudio.notify@gmail.com>' . "\r\n";

                $message = '<!DOCTYPE html>';
                $message.= '<html lang="en">';
                $message.= '<head>';
                $message.= '<meta charset="UTF-8">';
                $message.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                $message.= '<title>Document</title>';
                $message.= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"';
                $message.= 'integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';

                $message.= '<style>';
                $message.= '@font-face {';
                $message.= 'font-family: "GT-America-Regular";';
                $message.= "src: url('https://www.beatstudio.com.mx/assets/fonts/SharpGrotesk/Semibold/SharpGroteskSmBold08-Regular.ttf') format('truetype');";
                $message.= '}';

                $message.= 'body {';
                $message.= 'background-color: #eee;';
                $message.= 'min-width: 600px';
                $message.= '}';

                $message.= '.email-content {';
                $message.= 'width: 500px;';
                $message.= "background-image: url('https://www.beatstudio.com.mx/assets/img/correos/Correo_Imagen.jpg');";
                $message.= 'background-repeat: no-repeat;';
                $message.= 'background-size: cover';
                $message.= '}';

                $message.= '.email-content span {';
                $message.= 'font-weight: bold;';
                $message.= '}';

                $message.= '.email-content .logo {';
                $message.= 'width: 100%;';
                $message.= '}';

                $message.= '.table-title {';
                $message.= 'font-weight: bold;';
                $message.= 'color: rgb(73, 73, 73);';
                $message.= '}';

                $message.= '.socials {';
                $message.= 'width: 20px;';
                $message.= 'margin: 10px;';
                $message.= '}';

                $message.= '.socials>img {';
                $message.= 'width: 20px;';
                $message.= '}';
                $message.='</style>';
                $message.= '</head>';
                $message.= '<body>';
                $message.= '<div class="row justify-content-center my-5">';
                $message.= '<div class="email-content p-5">';
                $message.= '<div class="row">';
                $message.= '<div class="col-6">';
                // $message.= '<img class="logo" src="https://www.beatstudio.com.mx/assets/img/correos/BeatStudio_Logo-01.svg" alt="BeatStudio">';
                $message.= '</div>';
                $message.= '<div class="col-6 d-flex justify-content-end">';
                // $message.= '<span class="align-self-center">14 de Octubre del 2020</span>';
                $message.= '</div>';
                $message.= '<div class="col-12">';
                $message.= '<h1 class="text-center mt-5">Cancelación exitosa</h1>';
                $message.= '<p class="mt-5"> Te informamos que tu reservación se ha cancelado exitosamente. ';
                $message.= ' BeatStudio, Gracias';
                $message.= ' por ';
                $message.= 'tu preferencia.</p>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="col-12">';
                // $message.= '<h1 class="text-center mt-5">¡Reservación exitosa!</h1>';
                // $message.= '<p class="mt-5"><span>'. $disciplina['nombre'] .'</span> desde tu cuenta';
                $message.= '<div class="col-12" style="border-top:1px solid #000">';
                $message.= '<h6 class="mt-5">Detalles de tu cancelación:</h6>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
                $message.= '<div class="col-6 table-title">';
                $message.= 'Clase';
                $message.= '</div>';
                $message.= '<div class="col-6">';
                $message.= $disciplina['nombre'];
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
                $message.= '<div class="col-6 table-title">';
                $message.= 'Día';
                $message.= '</div>';
                $message.= '<div class="col-6">';
                $message.= date('d/m/Y', $fecha);
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
                $message.= '<div class="col-6 table-title">';
                $message.= 'Hora';
                $message.= '</div>';
                $message.= '<div class="col-6">';
                $message.= date('h:i A', $fecha);;
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
                $message.= '<div class="col-6 table-title">';
                $message.= 'Coach';
                $message.= '</div>';
                $message.= '<div class="col-6">';
                $message.= $coach["nombre"];
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row py-2" style="border-bottom:1px solid #eee">';
                $message.= '<div class="col-6 table-title">';
                $message.= 'Lugares';
                $message.= '</div>';
                $message.= '<div class="col-6">';
                foreach($detalles as $d) {
                    $message.= '<div>';
                    $message.= $d['lugar'] . ' ' .  $d['nombre'];
                    $message.= '</div>';
                }      
                $message.= '</div>';
                $message.= '</div>';
                $message.= '<div class="row mt-5" style="border-top: 1px solid #000">';
                $message.= '<div class="col-12  mt-4 d-flex justify-content-center">';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Instagram(Layout%20contacto).svg" alt="">';
                $message.= '</a>';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Twitter(Layout%20contacto).svg" alt="">';
                $message.= '</a>';
                $message.= '<a class="socials" href="https://www.instagram.com/beatstudiomx/" target="_blank">';
                $message.= '<img src="https://www.beatstudio.com.mx/assets/img/SVG/Facebook(Layout%20contacto9.svg" alt="">';
                $message.= '</a>';
                $message.= '</div>';
                $message.= '<div class="col-12 text-center">';
                $message.= 'Copyright 2020 BeatStudio. Todos los derechos reservados.';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</div>';
                $message.= '</body>';

                $message.= '</html>';

                mail($to, $subject, $message, $headers);

                $toAdmin = "beatspinstudio@gmail.com";
                mail($toAdmin, $subject, $message, $headers); //mail to admin

                return $response->withJson([
                    'disciplina' => $message
                ]);
            }
            catch(Throwable  $e){
                 $errorGateway->insert(array(
                    "cliente" => 0,
                    "error" => "No enviado a destinatario".$body["email"] ? $body["email"] : "No recibido"." ".$e->getMessage(),
                    "seccion" => "Correo",
                    "notified" => "No necesario",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }
        }
    ]
    ];
