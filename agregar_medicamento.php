<!DOCTYPE html>
<html>
<head>
    <title>Agregar Medicamento</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
        }

        .form-group textarea {
            width: 100%;
            height: 100px;
            padding: 8px;
            font-size: 16px;
        }

        .form-group button {
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Medication</h2>
        <form id="medication-form" action="guardar_medicamento.php" method="post">
            <div class="form-group">
                <label for="drug-name">Drug Name:</label>
                <input type="text" id="drug-name" name="drugName" required>
            </div>
            <div class="form-group">
                <label for="drug-type">Drug Type:</label>
                <input type="text" id="drug-type" name="drugType" required>
            </div>
            <div class="form-group">
                <label for="uses">Uses:</label>
                <textarea id="uses" name="Uses" required></textarea>
            </div>
            <div class="form-group">
                <label for="top-brand-names">Top Brand Names:</label>
                <input type="text" id="top-brand-names" name="topBrandNames">
            </div>
            <div class="form-group">
                <label for="dosages">Dosages:</label>
                <input type="text" id="dosages" name="dosages">
            </div>
            <div class="form-group">
                <label for="physician-speciality">Physician Speciality:</label>
                <input type="text" id="physician-speciality" name="physicianSpeciality">
            </div>
            <div class="form-group">
                <label for="combinations">Combinations:</label>
                <input type="text" id="combinations" name="combinations">
            </div>
            <div class="form-group">
                <label for="underwriting-considerations">Underwriting Considerations:</label>
                <input type="text" id="underwriting-considerations" name="underwritingConsiderations">
            </div>
            <div class="form-group">
                <label for="drug-priority">Drug Priority:</label>
                <input type="text" id="drug-priority" name="drugPriority">
            </div>
            <div class="form-group">
                <button type="submit">Finalizar</button>
            </div>
        </form>
    </div>
</body>
</html>
