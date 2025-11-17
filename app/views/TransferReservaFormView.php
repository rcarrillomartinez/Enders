<?php
require_once __DIR__ . '/../models/Auth.php';

// Determinar si estamos en modo edición
$isEdit = isset($reserva['id_reserva']);
$title = $isEdit ? 'Modificar Reserva ' . htmlspecialchars($reserva['localizador']) : 'Crear Nueva Reserva';

$data = $data ?? [];
$errors = $errors ?? [];
$currentUser = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { text-align: center; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 15px; background: #667eea; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        button:hover { background: #5a67d8; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        #llegada_section, #salida_section { margin-top: 15px; padding: 10px; background: #f0f0f0; border-radius: 4px; }
        #llegada_section h4, #salida_section h4 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="?action=store" method="POST">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_reserva" value="<?= htmlspecialchars($reserva['id_reserva']) ?>">
            <?php endif; ?>

            <label for="id_tipo_reserva">Tipo de Trayecto:</label>
            <select name="id_tipo_reserva" id="id_tipo_reserva" onchange="toggleFields(this.value)" required>
                <option value="1" <?= ($data['id_tipo_reserva'] ?? 0) == 1 ? 'selected' : '' ?>>Aeropuerto a Hotel</option>
                <option value="2" <?= ($data['id_tipo_reserva'] ?? 0) == 2 ? 'selected' : '' ?>>Hotel a Aeropuerto</option>
                <option value="3" <?= ($data['id_tipo_reserva'] ?? 0) == 3 ? 'selected' : '' ?>>Ida y Vuelta</option>
            </select>

            <label for="num_viajeros">Número de Viajeros:</label>
            <input type="number" name="num_viajeros" value="<?= htmlspecialchars($data['num_viajeros'] ?? 1) ?>" min="1" required>

            <label for="id_hotel">Hotel de Destino/Recogida:</label>
            <select name="id_hotel" required>
                <option value="1" <?= ($data['id_hotel'] ?? 0) == 1 ? 'selected' : '' ?>>Hotel A</option>
                <option value="2" <?= ($data['id_hotel'] ?? 0) == 2 ? 'selected' : '' ?>>Hotel B</option>
            </select>

            <label for="email_cliente">Email del Cliente (Identificador):</label>
<input type="email" 
       name="email_cliente" 
       value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" 
       required
       readonly>


            <?php if ($isEdit && $currentUser['user_type'] === 'admin'): ?>
                <label for="estado">Estado de la Reserva:</label>
                <select name="estado">
                    <option value="pendiente" <?= ($data['estado'] ?? '') == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="confirmada" <?= ($data['estado'] ?? '') == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                    <option value="cancelada" <?= ($data['estado'] ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    <option value="realizada" <?= ($data['estado'] ?? '') == 'realizada' ? 'selected' : '' ?>>Realizada</option>
                </select>
            <?php endif; ?>

            <div id="llegada_section">
                <h4>Datos de Llegada (Aeropuerto → Hotel)</h4>
                <label for="fecha_entrada">Día de Llegada:</label>
                <input type="date" name="fecha_entrada" value="<?= htmlspecialchars($data['fecha_entrada'] ?? '') ?>">
                
                <label for="hora_entrada">Hora de Llegada:</label>
                <input type="time" name="hora_entrada" value="<?= htmlspecialchars($data['hora_entrada'] ?? '') ?>">

                <label for="numero_vuelo_entrada">Número de Vuelo:</label>
                <input type="text" name="numero_vuelo_entrada" value="<?= htmlspecialchars($data['numero_vuelo_entrada'] ?? '') ?>">

                <label for="origen_vuelo_entrada">Aeropuerto de Origen:</label>
                <input type="text" name="origen_vuelo_entrada" value="<?= htmlspecialchars($data['origen_vuelo_entrada'] ?? '') ?>">
            </div>

            <div id="salida_section">
                <h4>Datos de Salida (Hotel → Aeropuerto)</h4>
                <label for="fecha_vuelo_salida">Día de Salida:</label>
                <input type="date" name="fecha_vuelo_salida" value="<?= htmlspecialchars($data['fecha_vuelo_salida'] ?? '') ?>">

                <label for="hora_vuelo_salida">Hora de Vuelo:</label>
                <input type="time" name="hora_vuelo_salida" value="<?= htmlspecialchars($data['hora_vuelo_salida'] ?? '') ?>">

                <label for="hora_partida">Hora de Recogida en el Hotel:</label>
                <input type="time" name="hora_partida" value="<?= htmlspecialchars($data['hora_partida'] ?? '') ?>">

                <label for="numero_vuelo_salida">Número de Vuelo:</label>
                <input type="text" name="numero_vuelo_salida" value="<?= htmlspecialchars($data['numero_vuelo_salida'] ?? '') ?>">
            </div>

            <button type="submit"><?= $isEdit ? 'Guardar Modificación' : 'Confirmar Reserva' ?></button>
        </form>
    </div>

    <script>
        function toggleFields(type) {
            const llegada = document.getElementById('llegada_section');
            const salida = document.getElementById('salida_section');
            type = parseInt(type);
            llegada.style.display = (type === 1 || type === 3) ? 'block' : 'none';
            salida.style.display = (type === 2 || type === 3) ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const tipo = document.getElementById('id_tipo_reserva').value;
            toggleFields(tipo);
        });
    </script>
</body>
</html>
