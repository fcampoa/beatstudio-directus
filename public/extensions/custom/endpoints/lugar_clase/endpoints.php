<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    '/obtener-filas' => [
        'method' => 'GET',
        'handler' => function(Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

            try {
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('lugar_clase', $dbConnection);
                $params = $request->getQueryParams();

                if(sizeof($params) === 0) {
                    throw new Exception("No se recibieron los parametros solicitados");
                }
                else{
                    if(!isset($params['idHorario']))
                        throw new Exception("No se recibió el parametro idHorario");
                }

                $horario = $params['idHorario'];
                $res = $tableGateway->select(function(Select $select) {
                    $select->columns(array('horario', 'ocupado', 'bicicleta', 'visible'));
                    $select->where('horario', $horario);
                    $select->join('bicicleta', 'bicicleta.id = lugar_clase.bicicleta', array('fila', 'numero'));
                    $select->order('fila');                
                });
                $rows = Array();
                $fila;
                $arr;
                $i = 0;
                $size = count($res);
                foreach($res as $valor) {            
                $aux = $valor['fila'];
                if ($fila == null) {
                    $fila = $aux;
                    $arr = Array();
                }
                if ($fila !== $aux) {
                    $fila = $aux;
                    array_push($rows, $arr);
                    $arr = Array();
                    array_push($arr, $valor);
                } else {
                    array_push($arr, $valor);
                }
                if (++$i === $size) {
                    array_push($rows, $arr);
                }
            }
                return $response->withJson([
                    'filas' => $rows
                ]);
            }
            catch(Throwable  $e){
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: BeatStudio <notify.beatstudio@gmail.com>' . "\r\n";
                $message= '<div class="col-12">';
                $message.= '<p class="mt-5"> Horario: '.$params['idHorario'] ? $params['idHorario'] : "No recibido".'</p>';
                $message.= '<p class="mt-5"> Fecha: '.date('Y-m-d H:i:s');
                $message.= '<p class="mt-5"> Error: '.$e->getMessage();
                $message.= '</div>';
                
                $notified = mail('jruiz@sahuarolabs.com, urosas@sahuarolabs.com', "Beatstudio error en regresar creditos", $e->getMessage(), $headers);
                $errorGateway->insert(array(
                    "cliente" => $params['idHorario'] ? $params['idHorario'] : 0,
                    "error" => $e->getMessage(),
                    "seccion" => "Consulta de filas",
                    "notified" => $notified ? "Sí" : "No",
                    "created_on" =>  date('Y-m-d H:i:s')
                ));
                
                return $response->withJson([
                    'message' => $e->getMessage()
                ]);

            }
        }
    ],
    '/checar-disponible' => [
        'method' => 'GET',
        'handler' => function(Request $request, Response $response) {
            return $response->withJson([
                'data' => 0
            ]);
        }
    ]
];