<?php
// Incluye el modelo de autenticación para verificar el usuario actual.
require_once __DIR__ . '/../models/Auth.php';

// Determina si el formulario es para editar una reserva existente o crear una nueva.
$isEdit = isset($data['id_reserva']);
$title = $isEdit ? 'Modificar Reserva ' . htmlspecialchars($data['localizador']) : 'Crear Nueva Reserva';
$data = $data ?? [];
$tipo_reserva_actual = $data['id_tipo_reserva'] ?? ''; 
$vehiculos = $data['vehiculos'] ?? []; // Inicializa la lista de vehículos.
$vehiculo_actual_id = $data['id_vehiculo'] ?? '';
$hoteles = $data['hoteles'] ?? [];
$hotel_actual_id = $data['id_hotel'] ?? ''; 
$errors = $errors ?? [];
$currentUser = Auth::getCurrentUser();
?>
<!-- Vista para el formulario de creación/edición de reservas de transfer. -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        /* Estilos generales para todos los elementos */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Fondo degradado para la página */
            min-height: 100vh;
            padding: 20px;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 30px;
            max-width: 800px; /* Reducido para el formulario */
            margin-left: auto;
            margin-right: auto;
        }
        /* Estilos para el título de la barra de navegación */
        .navbar h2 { color: #333; font-size: 1.5em; }
        /* Estilos para los enlaces de la barra de navegación */
        .navbar-links a { color: #667eea; text-decoration: none; font-weight: 600; }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px; /* Reducido para el formulario */
            padding: 40px;
            margin: 0 auto;
        }
        /* Estilos para el título principal del formulario */
        h1 { color: #333; margin-bottom: 30px; text-align: center; font-size: 2em; }
        /* Estilos para los grupos de formulario */
        .form-group { margin-bottom: 20px; }
        label {
            /* Estilos para las etiquetas de los campos */
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        /* Estilos para los campos de entrada y select */
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            /* Transición para el color del borde */
            transition: border-color 0.3s;
        }
        /* Estilos al enfocar campos de entrada y select */
        input:focus, select:focus { outline: none; border-color: #667eea; }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            /* Efectos de transición para el botón de envío */
            margin-top: 10px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            /* Efecto de elevación al pasar el ratón */
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }
        .btn-back {
            background-color: #f1f3f5;
            color: #333;
            border: 1px solid #ccc;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s;
            /* Transición para el color de fondo */
        }
        .btn-back:hover {
            background-color: #e0e0e0;
        }
        /* Estilos para mensajes de error */
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        h3 {
            /* Estilos para los subtítulos de sección */
            color: #667eea;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <div class="navbar">
        <h2>🏝️ Transfer Reservas</h2>
        <div class="navbar-links">
            <a href="?action=gestion_reservas">← Volver a la gestión</a>
        </div>
    </div>

    <div class="container">
        <!-- Título del formulario (Crear o Modificar Reserva) -->
        <h1><?= $title ?></h1>

        <a href="?action=gestion_reservas" class="btn-back">← Volver</a>

        <?php if (!empty($errors)): ?>
            <!-- Muestra mensajes de error si existen -->
            <div class="alert-error">
                **Error:** <?= htmlspecialchars(implode(', ', $errors)) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario principal para la reserva -->
        <form id="reservaForm" action="<?= $formAction ?>" method="POST">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_reserva" value="<?= htmlspecialchars($data['id_reserva']) ?>">
                <!-- Campo oculto para el ID de la reserva si se está editando -->
            <?php endif; ?>
            
            <div class="form-group">
                <label for="tipoReservaId" class="block text-sm font-medium text-gray-700">Tipo de Reserva</label>
                <select id="tipoReservaId" name="tipo_reserva" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="" disabled <?= $tipo_reserva_actual === '' ? 'selected' : '' ?>>-- Elige una opción --</option>
                    <option value="1" <?= $tipo_reserva_actual == '1' ? 'selected' : '' ?>>AEROPUERTO -> HOTEL</option>
                    <option value="2" <?= $tipo_reserva_actual == '2' ? 'selected' : '' ?>>HOTEL -> AEROPUERTO</option>
                    <option value="3" <?= $tipo_reserva_actual == '3' ? 'selected' : '' ?>>IDA Y VUELTA</option>
                </select>
            </div>

            <!-- Contenedor para campos generales y de envío, inicialmente oculto -->
            <div id="campos_generales_y_envio" style="display:none;">

                <!-- Campos específicos para la llegada (Aeropuerto -> Hotel), inicialmente ocultos -->
                <div id="aero_hotel_fields" style="display:none;">
                    <h3>Detalles de Llegada (Aeropuerto → Hotel)</h3>
                        <div class="form-group">
                            <label>Día de llegada:</label>
                            <input type="date" name="fecha_llegada" value="<?= htmlspecialchars($data['fecha_entrada'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Hora de llegada:</label>
                            <input type="time" name="hora_llegada" value="<?= htmlspecialchars($data['hora_entrada'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Número de vuelo:</label>
                            <input type="text" name="vuelo_llegada" value="<?= htmlspecialchars($data['numero_vuelo_entrada'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Aeropuerto de origen:</label>
                            <input type="text" name="origen_llegada" value="<?= htmlspecialchars($data['origen_vuelo_entrada'] ?? '') ?>">
                        </div>
                </div>

                <!-- Campos específicos para la salida (Hotel -> Aeropuerto), inicialmente ocultos -->
                <div id="hotel_aero_fields" style="display:none;">
                    <h3>Detalles de Salida (Hotel → Aeropuerto)</h3>
                        <div class="form-group">
                            <label>Día del vuelo:</label>
                            <input type="date" name="fecha_salida" value="<?= htmlspecialchars($data['fecha_vuelo_salida'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Hora del vuelo:</label>
                            <input type="time" name="hora_salida" value="<?= htmlspecialchars($data['hora_partida'] ?? '') ?>">
                        </div>
                </div>
                
                <!-- Sección de información general de la reserva -->
                <h3>Información General</h3>

                <div class="form-group">
                    <label>Número de viajeros:</label>
                    <input type="number" name="num_viajeros" min="1" required value="<?= htmlspecialchars($data['num_viajeros'] ?? 1) ?>">
                </div>
                <!-- Selector de hotel -->

                <div class="form-group">
                    <label for="hotelId" class="block text-sm font-medium text-gray-700">Hotel</label>
                    <select id="hotelId" name="id_hotel" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="" disabled <?= $hotel_actual_id === '' ? 'selected' : '' ?>>-- Selecciona un Hotel --</option>
                        <?php foreach ($hoteles as $hotel): ?>
                            <option 
                                value="<?= htmlspecialchars($hotel['id_hotel']) ?>" 
                                <?= ($hotel_actual_id == $hotel['id_hotel']) ? 'selected' : '' ?> 
                            >
                                <?= htmlspecialchars($hotel['nombre_hotel']) ?> <!-- Muestra el nombre del hotel -->
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Selector de vehículo, con filtrado dinámico por capacidad -->

                <div class="form-group">
                    <label for="vehiculoId" class="block text-sm font-medium text-gray-700">Vehículo</label>
                    <select id="vehiculoId" name="id_vehiculo" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="" disabled <?= $vehiculo_actual_id === '' ? 'selected' : '' ?>>-- Selecciona un Vehículo --</option>
                        <?php foreach ($vehiculos as $vehiculo): ?>
                            <option 
                                value="<?= htmlspecialchars($vehiculo['id_vehiculo']) ?>" 
                                <?= ($vehiculo_actual_id == $vehiculo['id_vehiculo']) ? 'selected' : '' ?> 
                                data-capacidad="<?= htmlspecialchars($vehiculo['capacidad'] ?? 0) ?>"
                            >
                                <!-- Muestra la descripción y capacidad del vehículo -->
                                <?= htmlspecialchars($vehiculo['descripcion'] ?? 'Vehículo sin descripción') ?> 
                                (Capacidad: <?= htmlspecialchars($vehiculo['capacidad'] ?? 0) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Sección de datos del cliente -->

                <h3>Datos del cliente</h3>
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($data['nombre_cliente'] ?? ($currentUser['user_type'] === 'viajero' ? ($data['nombre'] ?? '') : '')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellido 1:</label>
                    <input type="text" name="apellido1_cliente" value="<?= htmlspecialchars($data['apellido1_cliente'] ?? ($currentUser['user_type'] === 'viajero' ? ($data['apellido1'] ?? '') : '')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellido 2:</label>
                    <input type="text" name="apellido2_cliente" value="<?= htmlspecialchars($data['apellido2_cliente'] ?? ($currentUser['user_type'] === 'viajero' ? ($data['apellido2'] ?? '') : '')) ?>">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email_cliente" value="<?= htmlspecialchars($data['email_cliente'] ?? $currentUser['user_email'] ?? '') ?>" required <?= ($currentUser['user_type'] ?? '') !== 'admin' ? 'readonly' : '' ?>>
                    <!-- El campo de email es de solo lectura si el usuario no es admin -->
                </div>
                
                <!-- Campo para el estado de la reserva, solo visible para administradores -->
                <?php if (($currentUser['user_type'] ?? '') === 'admin'): ?>
                    <div class="form-group">
                        <label for="estado">Estado de la Reserva:</label>
                        <select name="estado">
                            <option value="pendiente" <?= ($data['estado'] ?? '') == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="confirmada" <?= ($data['estado'] ?? '') == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                            <option value="cancelada" <?= ($data['estado'] ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                            <option value="completada" <?= ($data['estado'] ?? '') == 'completada' ? 'selected' : '' ?>>Completada</option>
                        </select>
                    </div>
                <?php endif; ?>
                <!-- Botón de envío del formulario -->
                <button type="submit" class="btn-submit"><?= $isEdit ? 'Guardar Cambios' : 'Crear Reserva' ?></button>
            </div>
        </form>
    </div>

<script>
    // Espera a que el DOM esté completamente cargado antes de ejecutar el script.
    document.addEventListener('DOMContentLoaded', function() {
        const tipoReservaSelect = document.getElementById('tipoReservaId');
        const camposGenerales = document.getElementById('campos_generales_y_envio');
        const aeroHotel = document.getElementById('aero_hotel_fields');
        const hotelAero = document.getElementById('hotel_aero_fields');
        const numViajerosInput = document.querySelector('input[name="num_viajeros"]');
        const vehiculoSelect = document.getElementById('vehiculoId');

        // Función para mostrar/ocultar campos del formulario según el tipo de reserva seleccionado.
        function toggleFields(tipo) {
            // 1. Ocultar todos los campos condicionales al inicio
            aeroHotel.style.display = 'none';
            hotelAero.style.display = 'none';
            camposGenerales.style.display = 'none';

            if(tipo === '1') { // Aeropuerto -> Hotel
                camposGenerales.style.display = 'block';
                aeroHotel.style.display = 'block';
            } else if(tipo === '2') { // Hotel -> Aeropuerto
                camposGenerales.style.display = 'block';
                hotelAero.style.display = 'block';
            } else if(tipo === '3') { // Ida y vuelta
                camposGenerales.style.display = 'block';
                aeroHotel.style.display = 'block';
                hotelAero.style.display = 'block';
            }
        }
        
        // Ejecuta la función al cargar la página para establecer el estado inicial de los campos
        // si se está editando una reserva existente o si hay un tipo de reserva preseleccionado.
        const initialType = tipoReservaSelect.value;
        if (initialType) {
            toggleFields(initialType);
        } else {
            // Si no hay valor inicial, asegura que todo esté oculto si la opción es "disabled selected".
            camposGenerales.style.display = 'none';
        }


        // Escucha el evento 'change' en el selector de tipo de reserva para actualizar los campos.
        tipoReservaSelect.addEventListener('change', function() {
            toggleFields(this.value);
        });

        // Función para filtrar los vehículos disponibles según el número de viajeros.
        function filterVehiculos() {
            const numViajeros = parseInt(numViajerosInput.value, 10) || 0;
            let firstVisibleOptionValue = null;

            for (const option of vehiculoSelect.options) {
                if (option.value === "") continue; // Skip the placeholder

                // Obtiene la capacidad del vehículo del atributo 'data-capacidad'.
                const capacidad = parseInt(option.dataset.capacidad, 10);
                if (capacidad >= numViajeros) {
                    option.style.display = '';
                    if (firstVisibleOptionValue === null) {
                        firstVisibleOptionValue = option.value;
                    }
                } else {
                    option.style.display = 'none';
                }
            }
        }
        // Escucha el evento 'input' en el campo de número de viajeros para filtrar los vehículos dinámicamente.
        numViajerosInput.addEventListener('input', filterVehiculos);
        // Ejecuta la función de filtrado al cargar la página para aplicar el filtro inicial.
        filterVehiculos(); // Run on page load as well
    });
</script>
</body>
</html>