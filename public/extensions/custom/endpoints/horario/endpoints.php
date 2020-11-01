<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;

return [
    '/ocupados' => [
        'method' => 'GET',
        'handler' => function (Request $request, Response $response) {

            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('reservacion_detalle', $dbConnection);
            $params = $request->getQueryParams();
            // $where = new Zend\Db\Sql\Where;
            // $where->equalTo('horario', $params['idHorario']);
            // $where->equalTo('cancelada', false);
            // $select = $tableGateway->select($where);

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
    ]
    ];