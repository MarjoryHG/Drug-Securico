<?php
// Realiza la conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'securico-productos';
$connection = mysqli_connect($host, $user, $password, $database);
if (!$connection) {
    die('Error al conectarse a la base de datos: ' . mysqli_connect_error());
}

// Obtén el término de búsqueda del parámetro GET
$searchTerm = $_GET['search'] ?? '';

// Escapa caracteres especiales para evitar ataques de SQL injection
$searchTerm = mysqli_real_escape_string($connection, $searchTerm);

// Realiza la consulta SQL para obtener las sugerencias
$query = "SELECT * FROM datos WHERE `Drug Name` LIKE '$searchTerm%'";
$result = mysqli_query($connection, $query);
if (!$result) {
    die('Error al ejecutar la consulta: ' . mysqli_error($connection));
}

// Prepara un array para almacenar las sugerencias
$suggestions = [];

// Recorre los resultados y agrega las sugerencias al array
while ($row = mysqli_fetch_assoc($result)) {
    $suggestions[] = [
        'Drug Name' => $row['Drug Name'],
        'Top Brand Names' => $row['Top Brand Names'],
        'Drug Type' => $row['Drug Type'],
        'Uses' => $row['Uses'],
        'Dosages' => $row['Dosages'],
        'Physician Speciality' => $row['Physician Speciality'],
        'Combinations' => $row['Combinations'],
        'Underwriting Considerations' => $row['Underwriting Considerations'],
        'Drug Priority' => $row['Drug Priority']
    ];
}

// Devuelve las sugerencias como una respuesta JSON
header('Content-Type: application/json');
echo json_encode($suggestions);

// Cierra la conexión a la base de datos
mysqli_close($connection);
?>
