<?php

function getCollection($collectionName){
    $container = \Directus\Application\Application::getInstance()->getContainer();
    $dbConnection = $container->get('database');
    return new \Zend\Db\TableGateway\TableGateway($collectionName, $dbConnection);
}

return [
    'actions' => [
        'item.create.cliente:after' => function ($data) {
            $clients = getCollection('cliente');
            $where = new Zend\Db\Sql\Where;
            $where->equalTo('id', strval($data['id']));
            $select = $clients->select($where);

            $nombre = $data['nombre'];
            $apellido = "";
            $correo = "";
            foreach ($select as $row) {
                $apellido = $row['apellido'];
                $correo = $row['correo'];
            }

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= 'From: BeatStudio <no-reply@beatstudio.com.mx>' . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // Email to Client
            $toClient = strval($correo);
            $templateClient = file_get_contents(getcwd() . "/templates/cliente-registro.html");
            $templateClient = str_replace('{{ cliente }}', $data['nombre'], $templateClient);
            $subjectClient = "Confirmacion de Registro";
            
            // Email to Admin
            $toAdmin = "beatspinstudio@gmail.com";
            $templateAdmin = file_get_contents(getcwd() . "/templates/cliente-admin.html");
            $templateAdmin = str_replace('{{ nombre }}', $nombre, $templateAdmin);
            $templateAdmin = str_replace('{{ apellido }}', $apellido, $templateAdmin);
            $templateAdmin = str_replace('{{ correo }}', $correo, $templateAdmin);
            $subjectAdmin = "Nuevo Cliente";


            // Send Emails
            mail($toClient, $subjectClient, $templateClient, $headers);
            mail($toAdmin, $subjectAdmin, $templateAdmin, $headers);
        }
    ]
];