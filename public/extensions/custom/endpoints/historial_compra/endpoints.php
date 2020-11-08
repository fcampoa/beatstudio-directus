<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

require_once __DIR__ . '/../Conekta/lib/Conekta.php';

return [
    '' => [
        'method' => 'GET',
        'handler' => function (Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
            $params = $request->getQueryParams();
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
                'creditos' => $creditos
            ]);
        }
    ],
    '/actualizar-creditos' => [
        'method' => 'PATCH',
        'handler' => function (Request $request, Response $response) {
           // $body = $request->getParsedBody();

            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
            $params = $request->getQueryParams();
            $where = new Zend\Db\Sql\Where;
            $where->greaterThanOrEqualTo('vigencia', $params['desde']);
            $where->equalTo('cliente', $params['cliente']);
            // $where->greaterThanOrEqualTo('creditos', 0);
            $select = $tableGateway->select($where);            
            $creditos = $params['creditos'];
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

               // array_push($ids, $a["id"]);

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
            // return $response->withJson([
            //     'resultado' => $res
            // ]);
        }
    ],
        '/regresar-creditos' => [
            'method' => 'PATCH',
            'handler' => function (Request $request, Response $response) {
                $body = $request->getParsedBody();
                $container = \Directus\Application\Application::getInstance()->getContainer();
                $dbConnection = $container->get('database');
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);

                $params = $request->getQueryParams();
                $totales = $body["creditos"];
                $where = new Zend\Db\Sql\Where;
                $where->greaterThanOrEqualTo('vigencia', $params['desde']);
                $where->equalTo('cliente', $params['cliente']);

                $select = $tableGateway->select($where);
                $creditos = $params['creditos'];
                $total = 0;
                $res = false;
                $size = count($select);
                // $aux = null;
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
                    if ($a["cantidad"] > 0) {
                    array_push($aux3, $a);
                    }
                }

                foreach($aux3 as $a) {
                    $rows= $tableGateway->update(
                        array("creditos" => $a["cantidad"]),
                        array("id" => $a["paquete"])
                    );
                }
                if ($rows > 0) {
                    $res = true;
                }
                return $response->withJson(['resultado' => $aux3, 'aux' => $aux]);
            }
        ],
    '/pagar' => [
        'method' => 'POST',
        'handler' => function (Request $request, Response $response) {

            $cardData = $request->getParsedBody();
            \Conekta\Conekta::setApiKey("key_eR1iBWWHKV2MxkHqQH5VwA");
            // \Conekta\Conekta::setApiKey("key_eYvWV7gSDkNYXsmr");
            \Conekta\Conekta::setApiVersion("2.0.0");
            \Conekta\Conekta::setLocale('es');

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
                return $response->withJson([
                    'error' => $error
                ]);
            } catch (\Conekta\ParameterValidationError $error) {
                return $response->withJson([
                    'error' => $error
                ]);
            } catch (\Conekta\Handler $error) {
                return $response->withJson([
                    'error' => $error
                ]);
            }
        }
    ]
];
