<?php

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;
use Directus\Util\DateTimeUtils;

return [
   '/cumpleanos-mes' => [
    'method' => 'GET',
    'handler' => function (Request $request, Response $response) {
        $sql = "SELECT id, firstname, lastname FROM MyGuests";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
          }
        } else {
          echo "0 results";
        }
        $conn->close();
    }
],
    '/exportar-clientes' => [
    'method' => 'GET',
    'handler' => function (Request $request, Response $response) {
        $servername = "localhost";
        $username = "root";
        $password = "B34tsp1n";
        $database = "beatstudio-test";
        
        $conn = new mysqli($servername, $username, $password, $database);
        if($conn->connect_error) {
            die("connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, firstname, lastname FROM MyGuests";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $clientes = array();
          while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $clientes[] = $row;
          }
          $conn->close();
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=clientes.xls");

          $mostrar_columnas = false;
          foreach($clientes as $cliente) {
            if(!$mostrar_columnas) {
                echo_implode("\t", array_keys($cliente)) . "\n";
                $mostrar_columnas = true;
            }
            echo_implode("\t", array_values($cliente)) . "\n";
          }
        } else {
          echo "0 results";
        }
        exit;
    }
]
];