<!DOCTYPE html>
<html>
<head>
    <title>Listado de datos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">    
</head>
<body> 
    <div class="container">
        <div class="sidebar">
            <div class="header">
                <img src="/images/favicon-16x16.png" alt="Logo de la empresa" class="logo">
                <h1 class="title">Drug Guide</h1>
                <input type="text" id="search-input" placeholder="Search Drug" class="search-bar">
                <ul id="search-results"></ul>
    
                <button class="update-btn">Add</button>
            </div>

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
                echo '<form id="selection-form">';
                echo '<table>';
                

                // Recorre los registros y muestra cada fila de datos
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr class="table-row">';
                    echo '<td><input type="checkbox" class="medication-checkbox" name="selectedMedications[]" value="' . $row['Drug Name'] . '"></td>';
                    echo '<td class="table-cell name-cell"><strong class="title-cell">Name: </strong><br><span class="content-cell">' . $row['Drug Name'] . '</span></td>';
                    echo '<td class="table-cell top-brand-cell"><strong class="title-cell">Top Brand: </strong><br><span class="content-cell">' . $row['Top Brand Names'] . '</span></td>';
                    echo '<td class="table-cell type-cell"><strong class="title-cell">Type: </strong><br><span class="content-cell">' . $row['Drug Type'] . '</span></td>';
                    echo '<td class="table-cell priority-cell"><strong class="title-cell">Priority: </strong><br><span class="content-cell priority-value">' . $row['Drug Priority'] . '</span></td>';
                    echo '<td class="table-cell"><a href="eliminar_medicamento.php?id=' . $row['id'] . '"><i class="fas fa-trash-alt delete-medication"></i></a></td>'; // Botón de eliminación
                
                    // Aquí agregamos las columnas adicionales a "Drug Details"
                    echo '<td style="display: none;">' . $row['Dosages'] . '</td>';
                    echo '<td style="display: none;">' . $row['Physician Speciality'] . '</td>';
                    echo '<td style="display: none;">' . $row['Combinations'] . '</td>';
                    echo '<td style="display: none;">' . $row['Underwriting Considerations'] . '</td>';
                    echo '<td class="table-cell uses-cell" style="display: none;">' . $row['Uses'] . '</td>';

                    echo '</tr>';
                }

                echo '</table>';
                echo '</form>';
            } else {
                echo 'No se encontraron registros.';
            }

            // Cierra la conexión a la base de datos
            mysqli_close($connection);
            ?>
        </div>
        <div class="main-content">
        <div class="section">
            <h2>Drug Details</h2>
            <div id="drug-details" class="drug-details">
                <h1 id="drug-name" class="drug-name"></h1>
                <div id="drug-type" class="drug-type"></div>
                <div id="top-brand-names" class="top-brand-names"></div>
                <div id="drug-priority" class="drug-priority"></div>
                <div class="button-container">
                    <button id="uses-button" class="details-button">Uses</button>
                    <button id="dosages-button" class="details-button">Dosages</button>
                    <button id="physician-speciality-button" class="details-button">Physician Speciality</button>
                    <button id="combinations-button" class="details-button">Combinations</button>
                    <button id="underwriting-considerations-button" class="details-button">Underwriting Considerations</button>
                </div>
                <div id="details-content" class="details-content"></div>
                <button id="edit-button" class="edit-btn">Editar</button>
            </div>
        </div>

    <div class="section-two">
        <h2 class="title-saved">Saved List</h2>
        <div id="saved-list" class="saved-list">
            <div id="saved-medications"></div>
        </div>
    </div>
</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const drugDetails = document.getElementById('drug-details');
    const medicationCheckboxes = document.querySelectorAll('.medication-checkbox');
    const savedMedicationsContainer = document.getElementById('saved-medications');
    const medicationNames = document.querySelectorAll('.medication-name');
    const tableRows = document.querySelectorAll('.table-row');
    const updateButton = document.querySelector('.update-btn');

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const tableRows = document.querySelectorAll('.table-row');

        tableRows.forEach(row => {
            const medicationNameElement = row.querySelector('td:nth-child(2) .content-cell'); // Modifica el índice para ajustar la columna Drug Name
            if (medicationNameElement) {
                const medicationName = medicationNameElement.textContent.toLowerCase();

                if (medicationName.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });

    tableRows.forEach(row => {
    row.addEventListener('click', (event) => {
      if (!event.target.classList.contains('medication-checkbox')) {  
        const drugName = row.querySelector('td:nth-child(2)').textContent;
        const topBrandNames = row.querySelector('td:nth-child(3)').textContent;
        const drugType = row.querySelector('td:nth-child(4)').textContent;
        const uses = row.querySelector('.uses-cell').textContent;
        const drugPriority = row.querySelector('.priority-cell .priority-value').textContent;
        
        // Nuevas variables para las columnas adicionales
        const dosages = row.querySelector('td:nth-child(7)').textContent;
        const physicianSpeciality = row.querySelector('td:nth-child(8)').textContent;
        const combinations = row.querySelector('td:nth-child(9)').textContent;
        const underwritingConsiderations = row.querySelector('td:nth-child(10)').textContent;

        // Actualiza los detalles del medicamento con todos los datos
drugDetails.innerHTML = `
    <p>${drugName}</p>
    <p>${topBrandNames}</p>
    <p>${drugType}</p>
    <button id="uses-button" class="details-button">Uses</button>
    <button id="dosages-button" class="details-button">Dosages</button>
    <button id="physician-speciality-button" class="details-button">Physician Speciality</button>
    <button id="combinations-button" class="details-button">Combinations</button>
    <button id="underwriting-considerations-button" class="details-button">Underwriting Considerations</button>
    <div id="details-content"></div>
    <p>Drug Priority: <span id="drug-priority">${drugPriority}</span></p>
    <button class="edit-btn">
        <i class="fas fa-edit"></i> Edit
    </button>
`;



const usesButton = document.getElementById('uses-button');
const dosagesButton = document.getElementById('dosages-button');
const physicianSpecialityButton = document.getElementById('physician-speciality-button');
const combinationsButton = document.getElementById('combinations-button');
const underwritingConsiderationsButton = document.getElementById('underwriting-considerations-button');
const detailsContent = document.getElementById('details-content');

usesButton.addEventListener('click', () => {
    detailsContent.textContent = uses;
});

dosagesButton.addEventListener('click', () => {
    detailsContent.textContent = dosages;
});

physicianSpecialityButton.addEventListener('click', () => {
    detailsContent.textContent = physicianSpeciality;
});

combinationsButton.addEventListener('click', () => {
    detailsContent.textContent = combinations;
});

underwritingConsiderationsButton.addEventListener('click', () => {
    detailsContent.textContent = underwritingConsiderations;
});

        const editButton = drugDetails.querySelector('.edit-btn');
        editButton.addEventListener('click', () => {
            // Abre la tabla de edición
            openEditTable(drugName, topBrandNames, drugType, uses, dosages, physicianSpeciality,combinations, underwritingConsiderations, drugPriority, row);
        });
      }
    });
});

    
    medicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', event => {
            const checked = event.target.checked;
            const row = event.target.closest('tr');
            const drugName = row.querySelector('td:nth-child(2)').textContent;
            const drugType = row.querySelector('td:nth-child(4)').textContent;
            const drugPriority = row.querySelector('td:nth-child(6)').textContent;

            if (checked) {
                // Agrega el medicamento a la lista guardada
                const medicationItem = createSavedMedicationItem(drugName, drugType, drugPriority);
                savedMedicationsContainer.appendChild(medicationItem);
            } else {
                // Elimina el medicamento de la lista guardada
                const medicationItem = findSavedMedicationItem(drugName);
                if (medicationItem) {
                    savedMedicationsContainer.removeChild(medicationItem);
                }
            }
        });
    });

    function createSavedMedicationItem(drugName, drugType, drugPriority) {
        const medicationItem = document.createElement('div');
        medicationItem.classList.add('saved-medication');

        const medicationName = document.createElement('div');
        medicationName.classList.add('medication-name');
        medicationName.textContent = drugName;

        const medicationDetails = document.createElement('div');
        medicationDetails.classList.add('medication-details');
        medicationDetails.textContent = `${drugType} - ${drugPriority}`;

        const deleteMedication = document.createElement('div');
        deleteMedication.classList.add('delete-medication');
        deleteMedication.innerHTML = '<i class="fas fa-trash"></i>';

        medicationItem.appendChild(medicationName);
        medicationItem.appendChild(medicationDetails);
        medicationItem.appendChild(deleteMedication);

        deleteMedication.addEventListener('click', () => {
            savedMedicationsContainer.removeChild(medicationItem);
            const correspondingCheckbox = findCorrespondingCheckbox(drugName);
            if (correspondingCheckbox) {
                correspondingCheckbox.checked = false;
            }
        });

        return medicationItem;
    }

    function findSavedMedicationItem(drugName) {
        const medicationItems = savedMedicationsContainer.querySelectorAll('.saved-medication');
        for (let i = 0; i < medicationItems.length; i++) {
            const medicationNameElement = medicationItems[i].querySelector('.medication-name');
            if (medicationNameElement.textContent === drugName) {
                return medicationItems[i];
            }
        }
        return null;
    }

    function findCorrespondingCheckbox(drugName) {
        for (let i = 0; i < medicationCheckboxes.length; i++) {
            if (medicationCheckboxes[i].value === drugName) {
                return medicationCheckboxes[i];
            }
        }
        return null;
    }

    function openEditTable(drugName, topBrandNames, drugType, uses, dosages, physicianSpeciality, combinations, underwritingConsiderations, drugPriority, row) {
        // Crea una tabla de edición con los valores actuales
        const editTable = document.createElement('table');
        editTable.classList.add('edit-table');

        const drugNameRow = createEditTableRow('Drug Name', drugName);
        const topBrandNamesRow = createEditTableRow('Top Brand Names', topBrandNames);
        const drugTypeRow = createEditTableRow('Drug Type', drugType);
        const usesRow = createEditTableRow('Uses', uses);
        const dosagesRow = createEditTableRow('Dosages', dosages);
        const physicianSpecialityRow = createEditTableRow('Physician Speciality', physicianSpeciality);
        const combinationsRow = createEditTableRow('Combinations', combinations);
        const underwritingConsiderationsRow = createEditTableRow('Underwriting Considerations', underwritingConsiderations);
        const drugPriorityRow = createEditTableRow('Drug Priority', drugPriority);

        editTable.appendChild(drugNameRow);
        editTable.appendChild(topBrandNamesRow);
        editTable.appendChild(drugTypeRow);
        editTable.appendChild(usesRow);
        editTable.appendChild(dosagesRow);
        editTable.appendChild(physicianSpecialityRow);
        editTable.appendChild(combinationsRow);
        editTable.appendChild(underwritingConsiderationsRow);
        editTable.appendChild(drugPriorityRow);

        const saveButton = document.createElement('button');
        saveButton.classList.add('save-btn');
        saveButton.innerHTML = '<i class="fas fa-check"></i> Save';

        saveButton.addEventListener('click', () => {
            // Guarda los cambios realizados
            const updatedDrugName = drugNameRow.querySelector('input').value;
            const updatedTopBrandNames = topBrandNamesRow.querySelector('input').value;
            const updatedDrugType = drugTypeRow.querySelector('input').value;
            const updatedUses = usesRow.querySelector('input').value;
            const updatedDosages = dosagesRow.querySelector('input').value;
            const updatedPhysicianSpeciality = physicianSpecialityRow.querySelector('input').value;
            const updatedCombinations = combinationsRow.querySelector('input').value;
            const updatedUnderwritingConsiderations = underwritingConsiderationsRow.querySelector('input').value;
            const updatedDrugPriority = drugPriorityRow.querySelector('input').value;

            // Actualiza los detalles del medicamento
            drugDetails.innerHTML = `
                <h2>Drug Details</h2>
                <p><span class="detail-label">Drug Name:</span> ${updatedDrugName}</p>
            <p><span class="detail-label">Top Brand Names:</span> ${updatedTopBrandNames}</p>
            <p><span class="detail-label">Drug Type:</span> ${updatedDrugType}</p>
            <p><span class="detail-label">Uses:</span> ${updatedUses}</p>
            <p><span class="detail-label">Dosages:</span> ${updatedDosages}</p>
            <p><span class="detail-label">Physician Speciality:</span> ${updatedPhysicianSpeciality}</p>
            <p><span class="detail-label">Combinations:</span> ${updatedCombinations}</p>
            <p><span class="detail-label">Underwriting Considerations:</span> ${updatedUnderwritingConsiderations}</p>
            <p><span class="detail-label">Drug Priority:</span> ${updatedDrugPriority}</p>
                <button class="edit-btn">
                    <i class="fas fa-edit"></i> Edit
                </button>
            `;

            // Actualiza la tabla original
            const medicationNameElement = row.querySelector('td:nth-child(2) .content-cell');
            const topBrandNamesElement = row.querySelector('td:nth-child(3) .content-cell');
            const drugTypeElement = row.querySelector('td:nth-child(4) .content-cell');
            const usesElement = row.querySelector('.uses-cell');
            const dosagesElement = row.querySelector('td:nth-child(7)');
            const physicianSpecialityElement = row.querySelector('td:nth-child(8)');
            const combinationsElement = row.querySelector('td:nth-child(9)');
            const underwritingConsiderationsElement = row.querySelector('td:nth-child(10)');
            const drugPriorityElement = row.querySelector('.priority-cell .priority-value');

            if (
                medicationNameElement &&
                topBrandNamesElement &&
                drugTypeElement &&
                usesElement &&
                dosagesElement &&
                physicianSpecialityElement &&
                combinationsElement &&
                underwritingConsiderationsElement &&
                drugPriorityElement
             ) {
                medicationNameElement.textContent = updatedDrugName;
                topBrandNamesElement.textContent = updatedTopBrandNames;
                drugTypeElement.textContent = updatedDrugType;
                usesElement.textContent = updatedUses;
                dosagesElement.textContent = updatedDosages;
                physicianSpecialityElement.textContent = updatedPhysicianSpeciality;
                combinationsElement.textContent = updatedCombinations;
                underwritingConsiderationsElement.textContent = updatedUnderwritingConsiderations;
                drugPriorityElement.textContent = updatedDrugPriority;
             }

            const editButton = drugDetails.querySelector('.edit-btn');
            editButton.addEventListener('click', () => {
                 // Abre la tabla de edición nuevamente con los nuevos valores
                 openEditTable(
                    updatedDrugName,
                    updatedTopBrandNames,
                    updatedDrugType,
                    updatedUses,
                    updatedDosages,
                    updatedPhysicianSpeciality,
                    updatedCombinations,
                    updatedUnderwritingConsiderations,
                    updatedDrugPriority,
                    row
                );
            });

            // Actualiza la base de datos
            updateMedication(
                updatedDrugName,
                updatedTopBrandNames,
                updatedDrugType,
                updatedUses,
                updatedDosages,
                updatedPhysicianSpeciality,
                updatedCombinations,
                updatedUnderwritingConsiderations,
                updatedDrugPriority
                );  
           });

        drugDetails.innerHTML = '';
        drugDetails.appendChild(editTable);
        drugDetails.appendChild(saveButton);
    }

    function createEditTableRow(label, value) {
        const row = document.createElement('tr');

        const labelCell = document.createElement('td');
        labelCell.textContent = label;

        const inputCell = document.createElement('td');
        const input = document.createElement('input');
        input.type = 'text';
        input.value = value;
        inputCell.appendChild(input);

        row.appendChild(labelCell);
        row.appendChild(inputCell);

        return row;
    }
    
    updateButton.addEventListener('click', () => {
        // Redireccionar al usuario a la página para agregar medicamentos
        window.location.href = 'agregar_medicamento.php';
    });

    function updateMedication(drugName,
        topBrandNames,
        drugType,
        uses,
        dosages,
        physicianSpeciality,
        combinations,
        underwritingConsiderations,
        drugPriority) {
        // Aquí puedes realizar la llamada AJAX o enviar los datos al servidor para actualizar la base de datos
        // Puedes usar fetch, axios o cualquier otra librería o método para enviar la solicitud al servidor
        // Por ejemplo:
        fetch('guardar_medicamento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                drugName: drugName,
                topBrandNames: topBrandNames,
                drugType: drugType,
                uses: uses,
                dosages: dosages,
                physicianSpeciality: physicianSpeciality,
                combinations: combinations,
                underwritingConsiderations: underwritingConsiderations,
                drugPriority: drugPriority,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Maneja la respuesta del servidor si es necesario
        })
        .catch(error => {
            // Maneja los errores si ocurren durante la solicitud
        });
    }
});

const mainContent = document.querySelector('.main-content');

let startY;
let currentY;
let mainContentVisible = true;
let initialTranslateY = 0;

// Agrega un evento de inicio de desplazamiento táctil al contenido principal
mainContent.addEventListener('touchstart', (event) => {
  startY = event.touches[0].clientY;
  currentY = startY;
  const transformStyle = getComputedStyle(mainContent).transform;
  initialTranslateY = transformStyle !== 'none' ? parseFloat(transformStyle.split(',')[5].trim()) : 0;
});

// Agrega un evento de desplazamiento táctil al contenido principal
mainContent.addEventListener('touchmove', (event) => {
  currentY = event.touches[0].clientY;
  const deltaY = currentY - startY;

  if (mainContentVisible && deltaY < -50) {
    // Deslizamiento hacia arriba: oculta el contenido principal
    mainContent.style.transform = `translateY(calc(-100% + ${initialTranslateY}px))`;
    mainContentVisible = false;
  } else if (!mainContentVisible && deltaY > 50) {
    // Deslizamiento hacia abajo: muestra el contenido principal
    mainContent.style.transform = 'translateY(0)';
    mainContentVisible = true;
  }
});

// Agrega un evento de desplazamiento táctil al documento para mostrar u ocultar la barra lateral completa
document.addEventListener('touchmove', (event) => {
  const deltaY = event.touches[0].clientY - startY;

  if (deltaY < -50) {
    // Deslizamiento hacia arriba: muestra la barra lateral completa
    mainContent.classList.remove('translate-up');
  } else if (deltaY > 50) {
    // Deslizamiento hacia abajo: oculta la barra lateral
    mainContent.classList.add('translate-up');
  }
});


    </script>
</body>
</html>