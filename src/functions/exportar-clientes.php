<?php
 header("Content-Type: application/vnd.ms-excel");
 header("Content-Disposition: attachment; filename=clientes.xls");
 header("Pragma: no-cache");
 header("Expires: 0");

$servername = "localhost";
        $username = "root";
        $password = "B34tsp1n";
        $database = "beatstudio-test";
        
        $conn = new mysqli($servername, $username, $password, $database);
        if($conn->connect_error) {
            die("connection failed: " . $conn->connect_error);
        }
        $table = "";

        $sql = "SELECT nombre, apellido, correo FROM cliente";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            $table .= "<table>";
            $table .= "<thead>";
            $table .= "<tr>";
            $table .= "<th>nombre</th>";
            $table .= "<th>apellido</th>";
            $table .= "<th>correo</th>";
            $table .= "</tr>";
            $table .= "</thead>";
            $table .= "<tbody>";

            $clientes = array();
          while($row = $result->fetch_assoc()) {
          //  echo "nombre: " . $row["nombre"]. " - apellido: " . $row["apellido"]. " " . $row["correo"]. "<br>";
          $table .= "<tr>";
          $table .= "<td>" . $row["nombre"] . "</td>";
          $table .= "<td>" . $row["apellido"] . "</td>";
          $table .= "<td>" . $row["correo"] . "</td>";
          $table .= "</tr>";
          }

          $table .= "</tbody>";
          $table .= "</table>";

          echo $table;
        } else {
          echo "0 results";
        }
        $conn->close();
        exit;
       
       ?>
    
