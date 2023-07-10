<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Verificar si se ha proporcionado el parámetro "id" en la URL
    if (isset($_GET["id"])) {
        // Obtener el ID del medicamento a eliminar
        $id = $_GET["id"];

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

        // Preparar la consulta SQL para eliminar el medicamento con el ID proporcionado
        $sql = "DELETE FROM datos WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // La eliminación se realizó correctamente
            echo "<script>alert('El medicamento ha sido eliminado con éxito');</script>";
        } else {
            // Ocurrió un error al eliminar el medicamento
            echo "<script>alert('No se pudo eliminar el medicamento');</script>";
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    }
}

// Redireccionar a la página de inicio después de eliminar el medicamento
header("Location: index.php");
exit;
?>
