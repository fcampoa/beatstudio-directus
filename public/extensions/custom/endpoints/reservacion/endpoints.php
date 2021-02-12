<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;
use Directus\Util\DateTimeUtils;


return [
    '/agregar' => [
        'method' => 'POST',
        'handler' => function (Request $request, Response $response) {   
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);
            $activityGateway = new \Zend\Db\TableGateway\TableGateway('transaction_activity', $dbConnection);
            $scheduleGateway = new \Zend\Db\TableGateway\TableGateway('horario', $dbConnection);

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
                $r = $body['reservacion'] ? $body['reservacion'] : null ;
                $detalles = $body['detalles'] ? $body['detalles'] : null;
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
                $where = new Zend\Db\Sql\Where;
                $wherediscipline = new Zend\Db\Sql\Where;
                $wherePayment = new Zend\Db\Sql\Where;
                
                    // $where->between('vigencia', $params['desde'], $params['hasta']);
                    
                $disciplineGateway = new \Zend\Db\TableGateway\TableGateway('disciplina', $dbConnection);
                $paymentsGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);

                $where->equalTo('id', (int)$r["horario"]);
                $schedules = $scheduleGateway->select($where);
               
                $scheduleResult = $schedules->current();
                $wherediscipline->equalTo('id', (int)$scheduleResult["disciplina"]);
                $disciplines = $disciplineGateway->select($wherediscipline);
                $disciplineResult = $disciplines->current();


                $last = $tableGateway->getLastInsertValue();
                if ($last > 0) {
                    $current_date = date("Y-m-d");
                    $wherePayment->greaterThanOrEqualTo('vigencia', date('Y-m-d', strtotime($current_date)));
                    $wherePayment->equalTo('cliente', (int)$r["cliente"]);
                    $payments = $paymentsGateway->select($wherePayment);
                    $credits = 0;
                    foreach ($payments as $cu) {
                        $credits = $credits + $cu["creditos"];
                    }
                    $activityGateway->insert(array(
                        'collection' => 'reservacion',
                        'action' => 'create',
                        'action_by' => $r["cliente"] ? $r["cliente"] : 0,
                        'item' => $last,
                        'comment' => $detalles[0]["nombre"].' reservó la clase '.$disciplineResult["nombre"].' del día '.$detalles[0]["horario"].' para '.$r["total_personas"].' persona (s) con el paquete ' . $detalles[0]["paquete"]. '. Total de creditos activos: '.$credits,
                        'action_on' => DateTimeUtils::now()->toString(),
                        'ip' => \Directus\get_request_host(),
                        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
                    ));
    
                    
                    $detailGateway = new \Zend\Db\TableGateway\TableGateway('reservacion_detalle', $dbConnection);
                    foreach ($detalles as $d) {
                        $res = $detailGateway->insert(array(
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
                $message.= '<p class="mt-5"> Cliente: '.(isset($body['reservacion']) ? ($r["cliente"]  ? $r["cliente"]  : "No recibido") : "No hay datos de reservación").'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$e->getMessage();
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en Agregar reservacion", $e->getMessage(), $headers);
                $errorGateway->insert(array(
                    "cliente" => isset($body['reservacion']) &&  $r["cliente"] ? $r["cliente"] : 0,
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
