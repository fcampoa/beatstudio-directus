<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;
use Directus\Util\DateTimeUtils;

require_once __DIR__ . '/../Conekta/lib/Conekta.php';

return [
    '' => [
            'method' => 'GET',
            'handler' => function (Request $request, Response $response) {
                $container = \Directus\Application\Application::getInstance()->getContainer();
                $dbConnection = $container->get('database');
                $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);
                try{
                    $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
                    $params = $request->getQueryParams();
                    
                    if(sizeof($params) === 0) {
                        throw new Exception("No se recibieron los parametros solicitados");
                    }
                    else{
                        if(!isset($params['desde']))
                            throw new Exception("No se recibió el parametro cliente");
                        if(!isset($params['cliente']))
                            throw new Exception("No se recibió el parametro cliente");
                    }

                    $where = new Zend\Db\Sql\Where;
                    // $where->between('vigencia', $params['desde'], $params['hasta']);
                    $where->greaterThanOrEqualTo('vigencia', $params['desde']);
                    $where->equalTo('cliente', $params['cliente']);
                    $select = $tableGateway->select($where);
                    $creditos = 0;
                    foreach ($select as $valor) {
                        $creditos += $valor['creditos'];
                    }
                    return $response->withJson([
                        'creditos' => $creditos,
                        'message' => "Success"
                    ]);
                }
                catch(Throwable  $e){
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                    $message= '<div class="col-12">';
                    $message.= '<p class="mt-5"> Usuario: '.$params['cliente'] ? $params['cliente'] : "No recibido".'</p>';
                    $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                    $message.= '<p class="mt-5"> Error: '.$e->getMessage();
                    $message.= '</div>';
                    
                    $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error consulta de historial", $e->getMessage(), $headers);
                    $errorGateway->insert(array(
                        "cliente" => $params['cliente'],
                        "error" => $e->getMessage(),
                        "seccion" => "Consulta de historial",
                        "notified" => $notified ? "Sí" : "No",
                        "created_on" =>  date('Y-m-d H:i:s')
                    ));
                    
                    return $response->withJson([
                        'message' => $e->getMessage()
                    ]);
                }
            }
    ],
 '/actualizar-creditos' => [
        'method' => 'PATCH',
        'handler' => function (Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);
            try{
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
                $params = $request->getQueryParams();
                if(sizeof($params) === 0) {
                    throw new Exception("No se recibieron los parametros solicitados");
                }
                else{
                    if(!isset($params['desde']))
                        throw new Exception("No se recibió el parametro desde");
                    if(!isset($params['cliente']))
                        throw new Exception("No se recibió el parametro cliente");
                    if(!isset($params['creditos']))
                        throw new Exception("No se recibió el parametro creditos");
                }
                $where = new Zend\Db\Sql\Where;
                $where->greaterThanOrEqualTo('vigencia', $params['desde']);
                $where->equalTo('cliente', $params['cliente']);
                $select = $tableGateway->select($where);            
                $creditos = $params['creditos'] | 0;

                $aux = array();
                $aux2 = array();
                $res = false;
                foreach ($select as $cu) {
                    array_push($aux2, $cu);
                }

                do
                {
                    $swapped = false;
                    for( $i = 0, $c = count( $aux2 ) - 1; $i < $c; $i++ )
                    {
                        if( $aux2[$i]->vigencia > $aux2[$i + 1]->vigencia )
                        {
                            list( $aux2[$i + 1], $aux2[$i] ) =
                                    array( $aux2[$i], $aux2[$i + 1] );
                            $swapped = true;
                        }
                    }
                }
                while( $swapped );

                $ids = array();

                $size = count($aux2);
                for($i = 0; $i < $size; $i++){
                    $current = $aux2[$i];
                    if ($creditos > 0) {
                        if ($aux2[$i]->creditos > 0) {
                            if ($aux2[$i]->creditos >= $creditos) {
                                $aux2[$i]->creditos = $aux2[$i]->creditos - $creditos;
                                array_push($aux, $aux2[$i]);
                                for ($y = 0; $y < $creditos; $y ++) {
                                    array_push($ids, $aux2[$i]->id);
                                }
                                break;
                            }
                            else {
                                $creditos = $creditos - $aux2[$i]->creditos;
                                $aux2[$i]->creditos = 0;
                                array_push($aux, $aux2[$i]);
                                array_push($ids, $aux2[$i]->id);
                            }
                        }
                    }
                }

                foreach ($aux as $a) {
                    $id = $tableGateway->update(
                        array("creditos" => $a["creditos"]),
                        array("id" => $a["id"])
                    );
                    if ($id > 0) {
                        $res = true;
                    } else {
                        $res = false;
                    }
                }
                return $response->withJson([
                    'resultado' => $res,
                    'paquetes' => $ids                                
                ]);
            }
            catch(Throwable  $e){
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Usuario: '.$params['cliente'] ? $params['cliente'] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$e->getMessage();
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en actualizar creditos", $e->getMessage(), $headers);
                $errorGateway->insert(array(
                    "cliente" => $params['cliente'],
                    "error" => $e->getMessage(),
                    "seccion" => "Actualizar creditos",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }
        }
    ],
     '/regresar-creditos' => [
        'method' => 'PATCH',
        'handler' => function (Request $request, Response $response) {
            $body = $request->getParsedBody();
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);
            try{
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);

                $params = $request->getQueryParams();
                if(sizeof($params) === 0) {
                    throw new Exception("No se recibieron los parametros solicitados");
                }
                else{
                    if(!isset($params['desde']))
                        throw new Exception("No se recibió el parametro desde");
                    if(!isset($params['cliente']))
                        throw new Exception("No se recibió el parametro cliente");
                }
                
                if(sizeof($body) === 0) {
                    throw new Exception("No se recibió el parametró creditos en el body");
                }
                else{
                    if(isset($body["creditos"]))
                    { 
                       if(!is_array($body["creditos"])) {
                            throw new Exception("El formato del parametro creditos en el body es incorrecto");
                       }
                    }  
                    else{
                        throw new Exception("No se recibió el parametro creditos en el body");
                    }  
                }
                $where = new Zend\Db\Sql\Where;
                $where->greaterThanOrEqualTo('vigencia', $params['desde']);
                $where->equalTo('cliente', $params['cliente']);
                $totales = $body["creditos"];
                $select = $tableGateway->select($where);
                $creditos = $params['creditos'];
                $total = 0;
                $res = false;
                $size = count($select);
                $aux = array();
                $aux2 = array();
                $aux3 = array();
                foreach ($select as $cu) {
                    array_push($aux, $cu);
                }

                foreach($totales as $c) {
                    array_push($aux2, $c);
                }

                foreach($aux as $p){
                    $a["cantidad"] = 0;
                    foreach($aux2 as $t){
                        if ($t["paquete"] == $p["id"]) {
                            if ($a["cantidad"] == 0) {
                            $a["cantidad"] += ($t["cantidad"] + $p["creditos"]);
                            }
                            else {
                                $a["cantidad"] += $t["cantidad"];
                            }
                            $a["paquete"] = $p["id"];                        
                        }
                    }
                    if($a["cantidad"] > 0) {
                        array_push($aux3, $a);
                    }
                }
                
                foreach($aux3 as $a) {
                    $rows= $tableGateway->update(
                        array("creditos" => $a["cantidad"]),
                        array("id" => $a["paquete"])
                    );
                    $whereClient = new Zend\Db\Sql\Where;
                    $whereClient->equalTo('id', (int)$params["cliente"]);
                    $client = $clientGateway->select($whereClient);
                    $clientResult = $client->current();
                    $where->greaterThanOrEqualTo('vigencia', date('Y-m-d', strtotime($current_date)));
                    $where->equalTo('cliente', (int)$params["cliente"]);
                    $payments = $tableGateway->select($where);
                    $credits = 0;
                
                    foreach ($payments as $cu) {
                        $credits = $credits + $cu["creditos"];
                    }

                    $activityGateway->insert(array(
                        'collection' => 'historial_compra',
                        'action' => 'update',
                        'action_by' => $params["cliente"] | 0,
                        'item' => $a["paquete"],
                        'comment' =>  $clientResult['nombre'].' '.$clientResult['apellido']. ' canceló la reservación de '.$body["horario"]["disciplina"]["nombre"].' del día '.$body["horario"]["fecha"].' para '.$body["reservacion"]['total_personas'].' persona (s) - se devolvió 1 credito al paquete ' . $a["paquete"]. '. Total de creditos: '. $credits,                       
                        'action_on' => DateTimeUtils::now()->toString(),
                        'ip' => \Directus\get_request_host(),
                        'edited_on' => "creditos ",
                        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
                    ));
                }
                if ($rows > 0) {
                    $res = true;
                }
                return $response->withJson(['resultado' => $aux3, 'aux' => $aux]);
            }
            catch(Throwable  $e){
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Usuario: '.$params['cliente'] ? $params['cliente'] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$e->getMessage();
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en regresar creditos", $e->getMessage(), $headers);
                $errorGateway->insert(array(
                    "cliente" => $params['cliente'] ? $params['cliente'] : "No recibido",
                    "error" => $e->getMessage(),
                    "seccion" => "Regresar creditos",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }
        }
    ],
    '/pagar' => [
        'method' => 'POST',
        'handler' => function (Request $request, Response $response) {

            $cardData = $request->getParsedBody();
             // Production
            // \Conekta\Conekta::setApiKey("key_eR1iBWWHKV2MxkHqQH5VwA"); 
             // sandbox
            \Conekta\Conekta::setApiKey("key_xmyFzCobeNWyZapsrR8hUQ");
            \Conekta\Conekta::setApiVersion("2.0.0");
            \Conekta\Conekta::setLocale('es');
            
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

            try {
                $customer = \Conekta\Customer::create(
                    [
                        'name'  => $cardData['client_name'],
                        'email' => $cardData['client_email'],
                        'phone' => $cardData['client_phone'],
                        'payment_sources' => [
                            [
                                'token_id' => $cardData['card_token'],
                                'type' => "card"
                            ]
                        ]
                    ]
                );

                $order = \Conekta\Order::create(
                    [
                        'currency' => 'MXN',
                        'customer_info' => [
                            'customer_id' => $customer['id']
                        ],
                        'line_items' => [
                            [
                                'name' => $cardData['item'],
                                'unit_price' => $cardData['amount'] * 100,
                                'quantity' => 1
                            ]
                        ],
                        'charges' => [
                            [
                                'payment_method' => [
                                    'type' => 'default'
                                ]
                            ]
                        ]
                    ]
                );

                return $response->withJson([
                    'resultado' => $order['id']
                ]);
            } catch (\Conekta\ProccessingError $error) {
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Usuario: '.$cardData['client_name'] ? $cardData['client_name'] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$error;
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en regresar creditos", $error, $headers);
                
                $errorGateway->insert(array(
                    "cliente" => $cardData['client_name'],
                    "error" => $error,
                    "seccion" => "Pago Conekta",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'error' => $error
                ]);
            } catch (\Conekta\ParameterValidationError $error) {
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Usuario: '.$cardData['client_name'] ? $cardData['client_name'] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$error;
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en regresar creditos", $error, $headers);
                
                $errorGateway->insert(array(
                    "cliente" => $cardData['client_name'],
                    "error" => $error,
                    "seccion" => "Pago Conekta",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'error' => $error
                ]);
            } catch (\Conekta\Handler $error) {
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Usuario: '.$cardData['client_name'] ? $cardData['client_name'] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$error;
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en regresar creditos", $error, $headers);
                
                $errorGateway->insert(array(
                    "cliente" => $cardData['client_name'],
                    "error" => $error,
                    "seccion" => "Pago Conekta",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'error' => $error
                ]);
            }
        }
    ]
];
