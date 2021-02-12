<?php
use Directus\Util\DateTimeUtils;

return[
    'actions' => [
        'item.update.reservacion:after' => function(array $reservacion) {
            if ($reservacion['cancelada'] === true) {

                $container = \Directus\Application\Application::getInstance()->getContainer();
                $dbConnection = $container->get('database');
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
                $activityGateway = new \Zend\Db\TableGateway\TableGateway('transaction_activity', $dbConnection);
                $current_date = date("Y-m-d");
                $where = new Zend\Db\Sql\Where;
                $wherediscipline = new Zend\Db\Sql\Where;
                $whereClient = new Zend\Db\Sql\Where;
                $wherePayment = new Zend\Db\Sql\Where;
                $clientGateway = new \Zend\Db\TableGateway\TableGateway('cliente', $dbConnection);
                $whereClient->equalTo('id', (int)$reservacion["cliente"]);
                $client = $clientGateway->select($whereClient);
                $clientResult = $client->current();
                $wherePayment->greaterThanOrEqualTo('vigencia', date('Y-m-d', strtotime($current_date)));
                $wherePayment->equalTo('cliente', (int)$reservacion["cliente"]);
                $payments = $tableGateway->select($wherePayment);
                $credits = 0;
                $disciplineGateway = new \Zend\Db\TableGateway\TableGateway('disciplina', $dbConnection);
                $scheduleGateway = new \Zend\Db\TableGateway\TableGateway('horario', $dbConnection);

                $where->equalTo('id', (int)$reservacion["horario"]);
                $schedules = $scheduleGateway->select($where);
               
                $scheduleResult = $schedules->current();
             
                $wherediscipline->equalTo('id', (int)$scheduleResult["disciplina"]);
                $disciplines = $disciplineGateway->select($wherediscipline);
                $disciplineResult = $disciplines->current();

                foreach ($payments as $cu) {
                    $credits = $credits + $cu["creditos"];
                }
                $activityGateway->insert(array(
                    'collection' => 'reservacion / historial_compra',
                    'action' => 'update',
                    'action_by' => $reservacion["cliente"] | 0,
                    'item' => $reservacion["id"],
                    'comment' => $clientResult['nombre'].' '.$clientResult['apellido']. ' canceló la reservación de '.$disciplineResult["nombre"].' del día '.$scheduleResult["fecha"].' para '.$reservacion["total_personas"].' persona (s) - se devolvieron '.$reservacion["total_personas"].' creditos al paquete ' . $reservacion["paquete"]. '. Total de creditos: '. $credits,
                    'action_on' => DateTimeUtils::now()->toString(),
                    'ip' => \Directus\get_request_host(),
                    'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
                ));
            }
            return true;
        },
        'item.create.reservacion:after' => function(array $reservacion) {           
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $tableGateway = new \Zend\Db\TableGateway\TableGateway('lista_espera', $dbConnection);
            $activityGateway = new \Zend\Db\TableGateway\TableGateway('transaction_activity', $dbConnection);
            $current_date = date("Y-m-d");
            $where = new Zend\Db\Sql\Where;
            $wherediscipline = new Zend\Db\Sql\Where;
            $wherePayment = new Zend\Db\Sql\Where;
            $whereClient = new Zend\Db\Sql\Where;
            $wherePayment = new Zend\Db\Sql\Where;
            $clientGateway = new \Zend\Db\TableGateway\TableGateway('cliente', $dbConnection);
            $whereClient->equalTo('id', (int)$reservacion["cliente"]);
            $client = $clientGateway->select($whereClient);
            $clientResult = $client->current();
            $wherePayment->greaterThanOrEqualTo('vigencia', date('Y-m-d', strtotime($current_date)));
            $wherePayment->equalTo('cliente', (int)$reservacion["cliente"]);
            $payments = $tableGateway->select($wherePayment);
            $credits = 0;
            $disciplineGateway = new \Zend\Db\TableGateway\TableGateway('disciplina', $dbConnection);
            $scheduleGateway = new \Zend\Db\TableGateway\TableGateway('horario', $dbConnection);
            $where->equalTo('id', (int)$reservacion["horario"]);
            $schedules = $scheduleGateway->select($where);
            $scheduleResult = $schedules->current();
            $wherediscipline->equalTo('id', (int)$scheduleResult["disciplina"]);
            $disciplines = $disciplineGateway->select($wherediscipline);
            $disciplineResult = $disciplines->current();
                
                foreach ($payments as $cu) {
                    $credits = $credits + $cu["creditos"];
                }
                $activityGateway->insert(array(
                    'collection' => 'reservacion',
                    'action' => 'update',
                    'action_by' => $reservacion["cliente"] | 0,
                    'item' => $reservacion["id"],
                    'comment' => $clientResult["nombre"].' reservó la clase '.$disciplineResult["nombre"].' del día '.$scheduleResult["fecha"].' para '.$reservacion["total_personas"].' persona (s) con el paquete ' . $reservacion["paquete"]. '. Total de creditos activos: '.$credits,
                    'action_on' => DateTimeUtils::now()->toString(),
                    'ip' => \Directus\get_request_host(),
                    'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
                ));
                
                return true;
        }
        ]
    ];