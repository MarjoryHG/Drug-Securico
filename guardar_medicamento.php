<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $drugName = $_POST["drugName"];
    $topBrandNames = $_POST["topBrandNames"];
    $drugType = $_POST["drugType"];
    $uses = $_POST["Uses"];
    $dosages = $_POST["dosages"];
    $physicianSpeciality = $_POST["physicianSpeciality"];
    $combinations = $_POST["combinations"];
    $underwritingConsiderations = $_POST["underwritingConsiderations"];
    $drugPriority = $_POST["drugPriority"];

    // Realizar la conexión a la base de datos
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'securico-productos';   

    $conn = new mysqli($host, $user, $password, $database);

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para insertar el medicamento en la tabla "datos"
    $sql = "INSERT INTO datos (`Drug Name`, `Top Brand Names`, `Drug Type`, `Uses`, `Dosages`, `Physician Speciality`, `Combinations`, `Underwriting Considerations`, `Drug Priority`)
            VALUES ('$drugName', '$topBrandNames', '$drugType', '$uses', '$dosages', '$physicianSpeciality', '$combinations', '$underwritingConsiderations', '$drugPriority')";

    if ($conn->query($sql) === TRUE) {
        // El registro se insertó correctamente
        echo "<script>alert('El registro se realizó con éxito');</script>";
    } else {
        // Ocurrió un error al insertar el registro
        echo "<script>alert('No se pudo realizar el registro');</script>";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}

    // Redireccionar a la página de inicio después de eliminar el medicamento
header("Location: index.php");
exit;

?>
