<?php
use Directus\Util\DateTimeUtils;

return[
    'actions' => [
        'item.create.reservacion:after'  => function($reservacion) {           
            
            $current_date = date("Y-m-d");
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            /* Gateways */
            
            $paymentsGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
            $activityGateway = new \Zend\Db\TableGateway\TableGateway('transaction_activity', $dbConnection);
            $clientGateway = new \Zend\Db\TableGateway\TableGateway('cliente', $dbConnection);
            $disciplineGateway = new \Zend\Db\TableGateway\TableGateway('disciplina', $dbConnection);
            $scheduleGateway = new \Zend\Db\TableGateway\TableGateway('horario', $dbConnection);
            /* Where declarations */
            $where = new Zend\Db\Sql\Where;
            $wherediscipline = new Zend\Db\Sql\Where;
            $wherePayment = new Zend\Db\Sql\Where;
            $whereClient = new Zend\Db\Sql\Where;
            $wherePayment = new Zend\Db\Sql\Where;

            /* Schedules */

            $where->equalTo('id', (int)$reservacion["horario"]);
            $schedules = $scheduleGateway->select($where);
            $scheduleResult = $schedules->current();
            
            $whereClient->equalTo('id', (int)$reservacion["cliente"]);
            $client = $clientGateway->select($whereClient);
            $clientResult = $client->current();
            $credits = 0;
           
            $wherediscipline->equalTo('id', (int)$scheduleResult["disciplina"]);
            $disciplines = $disciplineGateway->select($wherediscipline);
            $disciplineResult = $disciplines->current();

            $wherePayment->greaterThanOrEqualTo('vigencia', date('Y-m-d', strtotime($current_date)));
            $wherePayment->equalTo('cliente', (int)$reservacion["cliente"]);
            $payments = $paymentsGateway->select($wherePayment);

                
            foreach ($payments as $cu) {
                $credits = $credits + $cu["creditos"];
            }
            /* Paquete es desde el detalle */
            $activityGateway->insert(array(
                'collection' => 'reservacion',
                'action' => 'create',
                'action_by' => $reservacion["cliente"],
                'item' => $reservacion["id"],
                'comment' => $clientResult['nombre'].' '.$clientResult['apellido']. ' reservó la clase '.$disciplineResult["nombre"].' del día '.$scheduleResult["fecha"].' para '.$reservacion["total_personas"].' persona (s) con el paquete . Total de creditos activos: '.$credits,
                'action_on' => DateTimeUtils::now()->toString(),
                'ip' => \Directus\get_request_host(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
            ));
                
            return true;
        }
        ]
    ];