<!DOCTYPE html>
<html>
<head>
    <title>Listado de datos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilos del encabezado */
        /* Estilos del encabezado */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: black;
            padding: 20px;
            color: #fff;
            text-align: center;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        /* Estilos de la tabla y formulario */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        form {
            margin-top: 20px;
            text-align: center;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #337ab7;
        }

        input[type="text"] {
            padding: 8px;
            width: 80%;
            max-width: 400px;
        }
    </style>
</head>
<body>    
    

    <input type="text" id="search-input" placeholder="Buscar por nombre del medicamento">
    <ul id="search-results"></ul>


    <form action="guardar_seleccionados.php" method="post" id="selection-form">
        <input type="submit" value="Seleccionar">
        <input type="hidden" id="selected-medications" name="selectedMedications" value="">
    </form>

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

    // Realiza la consulta SQL para obtener los datos
    $query = "SELECT DISTINCT * FROM datos";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die('Error al ejecutar la consulta: ' . mysqli_error($connection));
    }

    // Verifica si existen registros
    if (mysqli_num_rows($result) > 0) {
        // Crea la tabla para mostrar los datos
        echo '<table>';
        echo '<tr>';
        echo '<th>Seleccionar</th>'; // Nueva columna para la casilla de verificación
        echo '<th>Drug Name</th>';
        echo '<th>Top Brand Names</th>';
        echo '<th>Drug Type</th>';
        echo '<th>Uses</th>';
        echo '<th>Dosages</th>';
        echo '<th>Physician Speciality</th>';
        echo '<th>Combinations</th>';
        echo '<th>Underwriting Considerations</th>';
        echo '<th>Drug Priority</th>';
        echo '</tr>';

        // Recorre los registros y muestra cada fila de datos
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td><input type="checkbox" name="selectedMedications[]" value="' . $row['Drug Name'] . '"></td>'; // Casilla de verificación
            echo '<td>' . $row['Drug Name'] . '</td>';
            echo '<td>' . $row['Top Brand Names'] . '</td>';
            echo '<td>' . $row['Drug Type'] . '</td>';
            echo '<td>' . $row['Uses'] . '</td>';
            echo '<td>' . $row['Dosages'] . '</td>';
            echo '<td>' . $row['Physician Speciality'] . '</td>';
            echo '<td>' . $row['Combinations'] . '</td>';
            echo '<td>' . $row['Underwriting Considerations'] . '</td>';
            echo '<td>' . $row['Drug Priority'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'No se encontraron registros.';
    }

    // Cierra la conexión a la base de datos
    mysqli_close($connection);
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            const selectionForm = document.getElementById('selection-form');
            const selectedMedicationsInput = document.getElementById('selected-medications');

            searchInput.addEventListener('input', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const rows = document.querySelectorAll('table tr');

                rows.forEach(row => {
                    const medicationNameElement = row.querySelector('td:nth-child(2)'); // Modifica el índice para ajustar la columna Drug Name
                    if (medicationNameElement) {
                        const medicationName = medicationNameElement.textContent.toLowerCase();

                        if (medicationName.startsWith(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
                
                // Realiza la llamada AJAX a suggestions.php
                const xhr = new XMLHttpRequest();
                xhr.open("GET", "search/suggestions.php?search=" + searchTerm, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const suggestions = JSON.parse(xhr.responseText);
                        displaySuggestions(suggestions);
                    }
                };
                xhr.send();
            });


            selectionForm.addEventListener('submit', (event) => {
                event.preventDefault(); // Prevent the default form submission

                const selectedMedications = document.querySelectorAll('input[name="selectedMedications[]"]:checked');
                const selectedMedicationsArray = Array.from(selectedMedications).map(medication => medication.value);

                selectedMedicationsInput.value = JSON.stringify(selectedMedicationsArray);

                // Submit the form programmatically
                selectionForm.submit();
            });


            function displaySuggestions(suggestions) {
                searchResults.innerHTML = '';
                suggestions.forEach(suggestion => {
                    const li = document.createElement('li');
                    li.textContent = suggestion;
                    searchResults.appendChild(li);
                });
            }
        });
    </script>
</body>
</html>
