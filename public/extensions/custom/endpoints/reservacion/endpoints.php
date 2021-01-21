<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    '/agregar' => [
        'method' => 'POST',
        'handler' => function (Request $request, Response $response) {   
            $body = $request->getParsedBody();
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion', $dbConnection);
            $params = $request->getQueryParams();
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
            "created_on" =>  $date->format('Y-m-d H:i:s')
            // "total_personas" => $r["total_personas"]
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
