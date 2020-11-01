<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    '/obtener-filas' => [
        'method' => 'GET',
        'handler' => function(Request $request, Response $response) {
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('lugar_clase', $dbConnection);
            $params = $request->getQueryParams();
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