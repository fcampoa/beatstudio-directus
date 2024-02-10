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

        $sql = "select c.nombre, c.apellido, c.correo, c.fecha_nacimiento,
        count(r.fecha) as totalReservaciones,
        max(if(r.fecha <= curdate(), r.fecha, null)) as ultimaReservacion,
        min(if(r.fecha > curdate(), r.fecha, null)) as proximaReservacion
        from cliente c left join reservacion r on c.id = r.cliente
        group by c.id;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            $table .= "<table>";
            $table .= "<thead>";
            $table .= "<tr>";
            $table .= "<th>nombre</th>";
            $table .= "<th>apellido</th>";
            $table .= "<th>correo</th>";
            $table .= "<th>fecha nacimiento</th>";
            $table .= "th>Total reservaciones</th>";
            $table .= "<th>ultima reservacion</th>";
            $tablr .= "<th>proxima reservacion</th>";
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
          $table .= "<td>" . $row["fecha_nacimiento"] . "</td>";
          $table .= "<td>" . $row["totalReservaciones"] . "</td>";
          $table .= "<td>" . $row["ultimaReservacion"] . "</td>";
          $table .= "<td>" . $row["proximaReservacion"] . "</td>";
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
    
