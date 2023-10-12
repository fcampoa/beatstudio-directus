<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {    // The request is using the POST method

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
        $response = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
		$response[] = $row;
            }
             // echo "nombre: " . $row["nombre"]. " - apellido: " . $row["apellido"]. " " . $row["fecha_nacimiento"]. "<br>";
             header('Content-Type: application/json');
             echo json_encode($response);
        }
        else {
            echo "0 results";
          }
          $conn->close();
          exit;
        }
?>
