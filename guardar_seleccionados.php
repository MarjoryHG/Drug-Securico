<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se recibieron los parámetros de los medicamentos seleccionados
    if (isset($_POST['selectedMedications'])) {
        // Obtiene los medicamentos seleccionados
        $selectedMedications = $_POST['selectedMedications'];

        // Decodifica los medicamentos seleccionados desde JSON
        $selectedMedicationsArray = json_decode($selectedMedications);

        // Verifica si se obtuvo un array válido
        if (is_array($selectedMedicationsArray)) {
            // Muestra los medicamentos seleccionados
            echo 'Medicamentos seleccionados: ';
            foreach ($selectedMedicationsArray as $medication) {
                echo $medication . ', ';
            }
        } else {
            echo 'Error al procesar los medicamentos seleccionados.';
        }
    } else {
        echo 'No se han seleccionado medicamentos.';
    }
} else {
    echo 'Acceso denegado.';
}
?>