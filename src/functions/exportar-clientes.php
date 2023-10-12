<?php
$servername = "localhost";
        $username = "root";
        $password = "B34tsp1n";
        $database = "beatstudio-test";
        
        $conn = new mysqli($servername, $username, $password, $database);
        if($conn->connect_error) {
            die("connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT nombre, apellido, correo FROM cliente";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $clientes = array();
          while($row = $result->fetch_assoc()) {
             echo "nombre: " . $row["nombre"]. " - apellido: " . $row["apellido"]. " " . $row["correo"]. "<br>";
           //  $clientes[] = $row;
          }
        //   header("Content-Type: application/vnd.ms-excel");
        //   header("Content-Disposition: attachment; filename=clientes.xls");

        //   $mostrar_columnas = false;
        //   foreach($clientes as $cliente) {
        //     if(!$mostrar_columnas) {
        //         echo_implode("\t", array_keys($cliente)) . "\n";
        //         $mostrar_columnas = true;
        //     }
        //     echo_implode("\t", array_values($cliente)) . "\n";
        //   }
        } else {
          echo "0 results";
        }
        $conn->close();
        exit;
       
       ?>
    