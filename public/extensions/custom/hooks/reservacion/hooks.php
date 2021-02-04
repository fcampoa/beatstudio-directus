<?php

return[
    'filters' => [
        'item.update.reservacion' => function( \Directus\Hook\Reservacion $reservacion) {
            if ($reservacion['cancelada'] === true) {

                $container = \Directus\Application\Application::getInstance()->getContainer();
                $dbConnection = $container->get('database');
                $tableGateway = new \Zend\Db\TableGateway\TableGateway('lista_espera', $dbConnection);

                $horario = $reservacion['horario'];
                $res = $tableGateway->select(function(Select $select) {
                    $select->columns(array('horario', 'cliente'));
                    $select->where('horario', $horario);
                    $select->join('horario', 'horario.id = lista_espera.horario', array('fecha'));
                    $select->join('disciplina', 'disciplina.id = horario.disciplina', array('nombre'));
                    $select->join('cliente', 'cliente.id = lista_espera.cliente', array('correo'));                                  
                });

                foreach($res as $c) {
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= 'From: BeatStudio <no-reply@beatstudio.com.mx>' . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $subject = "Confirmación de Reservación";

                    $content = 'Se han liberado lugares para la clase de ' . $c['nombre'] . ' el día ' . $c['fecha'];
                    // notify($c['correo'], 'Lugares disponibles!!', $content);
                    mail($c['correo'], $subject, $content, $headers);
                }
            }
        }
    ]
    ];