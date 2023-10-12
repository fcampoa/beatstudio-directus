<?php
$servername = "localhost";
        $username = "root";
        $password = "B34tsp1n";
        $database = "beatstudio-test";
        
        $conn = new mysqli($servername, $username, $password, $database);
        if($conn->connect_error) {
            die("connection failed: " . $conn->connect_error);
        }
        $month = date('n');
        $sql = "SELECT nombre, apellido, fecha_nacimiento FROM cliente WHERE MONTH(fecha_nacimiento) = " . $month;
        $result = $conn->query($sql);
        $response = Array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $cliente = new StdClass();
                $cliente->nombre = $row["nombre"];
                $cliente->apellido = $row["apellido"];
                $cliente->cumpleanos = $row["fecha_nacimiento"];
                array_push($response, $cliente)
            }
             // echo "nombre: " . $row["nombre"]. " - apellido: " . $row["apellido"]. " " . $row["fecha_nacimiento"]. "<br>";
             header('Content-Type: application/json; charset=utf-8');
             echo json_encode($response);
        }
        else {
            echo "0 results";
          }
          $conn->close();
          exit;
?>