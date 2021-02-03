<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    '/agregar' => [
        'method' => 'POST',
        'handler' => function (Request $request, Response $response) {   
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

            try {
                $body = $request->getParsedBody();
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion', $dbConnection);
                if(sizeof($body) === 0) {
                    throw new Exception("No se recibieron los parametros solicitados");
                }
                else{
                    if(!isset($body['reservacion']))
                        throw new Exception("No se recibio reservación");
                    if(!isset($body['detalles']))
                        throw new Exception("No se recibieron detalles");
                }
                $result = Array();
                $r = $body['reservacion'];
                $detalles = $body['detalles'];
                $date = new DateTime();
                $tableGateway->insert(array(
                    "fecha" => $r["fecha"],
                    "cliente" => $r["cliente"],
                    "folio" => $r["folio"],
                    "horario" => $r["horario"],
                    "status" => $r["status"],
                    "cancelada" => $r["cancelada"],
                    "created_on" =>  $date->format('Y-m-d H:i:s'),
                    "total_personas" => $r["total_personas"]
                ));
                $last = $tableGateway->getLastInsertValue();
                if ($last > 0) {
                    $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion_detalle', $dbConnection);
                    foreach ($detalles as $d) {
                    $res = $tableGateway->insert(array(
                            "reservacion" => $last,
                            "nombre" => $d["nombre"],
                            "status" => $r["status"],
                            "lugar" => $d["lugar"],
                            "invitado" => $d["invitado"],
                            "created_on" => $date->format('Y-m-d H:i:s'),
                            "horario" => $d["horario"],
                            "paquete" => $d["paquete"]
                        ));
                        array_push($result, $res);           
                    }
                }

                return $response->withJson([
                    'resultado' => $last
                ]);
            }
            catch(Throwable  $e){
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Cliente: '.$r["cliente"] ? $r["cliente"] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$e->getMessage();
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en regresar creditos", $e->getMessage(), $headers);
                $errorGateway->insert(array(
                    "cliente" => $r["cliente"] ? $r["cliente"] : 0,
                    "error" => $e->getMessage(),
                    "seccion" => "Consulta de horario",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }
        }
     ]
    // '/cancelar' => [
    //     'method' => 'PATCH',
    //     'handler' => function(Request $request, Response $response) {
    //         $r = $request->getParsedBody();
    //         $container = \Directus\Application\Application::getInstance()->getContainer();
    //         $dbConnection = $container->get('database');
    //         $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion', $dbConnection);
    //         $params = $request->getQueryParams();
    //         $date = new DateTime();
    //         $res = $tableGateway->update(array(
    //         "fecha" => $r["fecha"],
    //         "cliente" => $r["cliente"],
    //         "folio" => $r["folio"],
    //         "horario" => $r["horario"],
    //         "status" => $r["status"],
    //         "cancelada" => $r["cancelada"]));

    //         return $response->withJson(
    //             [
    //                 'resultado' => $res
    //             ]
    //         );
    //     }
    // ]

];
