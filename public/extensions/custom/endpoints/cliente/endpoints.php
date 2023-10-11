<?php

// use Directus\Application\Http\Request;
// use Directus\Application\Http\Response;
// use Directus\Util\DateTimeUtils;

// return [
//    '/cumpleanos-mes' => [
//     'method' => 'GET',
//     'handler' => function (Request $request, Response $response) {
//         $container = \Directus\Application\Application::getInstance()->getContainer();
//         $dbConnection = $container->get('database');
//         $errorGateway = new \Zend\Db\TableGateway\TableGateway('errorlog', $dbConnection);

//         $tableGateway = new \Zend\Db\TableGateway\TableGateway('cliente', $dbConnection);
//         $where = new Zend\Db\Sql\Where;
//     }
//    ]
// ]