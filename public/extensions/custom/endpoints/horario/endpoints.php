<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    '/ocupados' => [
        'method' => 'GET',
        'handler' => function (Request $request, Response $response) {

            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

            try {
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion_detalle', $dbConnection);
                $params = $request->getQueryParams();
                if(sizeof($params) === 0) {
                    throw new Exception("No se recibieron los parametros solicitados");
                }
                else{
                    if(!isset($params['idHorario']))
                        throw new Exception("No se recibió el parametro idHorario");
                }

                $res = $tableGateway->select(function(Select $select) {
                    $select->columns(array('nombre'));
                    $select->where('reservacion.horario', $params['idHorario']);
                    $select->join('reservacion', 'reservacion.id = reservacion_detalle.reservacion', array('horario'));
                });

                $ocupados = count($res);

                return $response->withJson([
                    'ocupados' => $ocupados
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
    ];