<?php

function sendEmail($to, $subject, $message) {
    //TODO update headers and place message body
    $headers = "MIME-Version: 1.0" . "\r\n";


    mail($to, $subject, $message, $headers);
}

return [
    'actions' => [
        'item.create.historial_compra:after' => function($data) {
            //TODO compra logic (refund)
        },
        'item.update.historial_compra:after' => function($data) {
            
        }
    ]
];