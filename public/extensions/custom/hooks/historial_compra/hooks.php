<?php
use Directus\Util\DateTimeUtils;

return[
    'actions' => [
        'item.create.historial_compra:after' => function(array $historial) {    
            $body = $historial;       
            $container = \Directus\Application\Application::getInstance()->getContainer();
            $dbConnection = $container->get('database');
            $activityGateway = new \Zend\Db\TableGateway\TableGateway('transaction_activity', $dbConnection);
            $historyGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
            $clientGateway = new \Zend\Db\TableGateway\TableGateway('cliente', $dbConnection);
            $paymentsGateway = new \Zend\Db\TableGateway\TableGateway('historial_compra', $dbConnection);
            $packGateway = new \Zend\Db\TableGateway\TableGateway('paquete', $dbConnection);    
            $current_date = date("Y-m-d");
            $whereClient = new Zend\Db\Sql\Where;
            $wherePayment = new Zend\Db\Sql\Where;
            $wherePack = new Zend\Db\Sql\Where;
            $whereClient->equalTo('id', (int)$body["cliente"]);
            $wherePack->equalTo('id', (int)$body["paquete"]);
            $client = $clientGateway->select($whereClient);
            $clientResult = $client->current();

            $pack = $packGateway->select($wherePack);
            $packResult = $pack->current();

            $wherePayment->greaterThanOrEqualTo('vigencia', date('Y-m-d', strtotime($current_date)));
            $wherePayment->equalTo('cliente', (int)$body["cliente"]);
            $payments = $paymentsGateway->select($wherePayment);
            $credits = 0;

            foreach ($payments as $cu) {
                $credits = $credits + $cu["creditos"];
            }
            $vigenciaString = $packResult['vigenciaDias'];

            $historyGateway->update(
                array("vigencia" => date("Y-m-d", strtotime($body["created_on"]." + ".$vigenciaString ." days"))),
                array("id" => $body['id'])
            );
            $activityGateway->insert(array(
                'collection' => 'historial_compra',
                'action' => 'create',
                'action_by' => $body["cliente"] ? $body["cliente"] : 0,
                'item' => $body["id"] ? $body['id'] : 0,
                'comment' => $clientResult['nombre'].' '.$clientResult['apellido']. ' compró el paquete '.$body["paquete"].' por $'.$body["total"].' y vence el día '.date('Y-m-d', strtotime($current_date.'+ '.$vigenciaString.' days')).' con ID de compra '. $body['id']. '. Total de creditos: '.$credits,
                'action_on' => DateTimeUtils::now()->toString(),
                'ip' => \Directus\get_request_host(),
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
            ));
                

            $historyGateway->update(
                array("vigencia" => date("Y-m-d", strtotime($current_date." + ".$vigenciaString ." days"))),
                array("id" => $body['id'])
            );
                
            return true;
        }
        ]
    ];