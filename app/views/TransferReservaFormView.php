<?php
require_once __DIR__ . '/../models/Auth.php';

$isEdit = isset($data['id_reserva']);
$title = $isEdit ? 'Modificar Reserva ' . htmlspecialchars($data['localizador'] ?? '') : 'Crear Nueva Reserva';
$data = $data ?? [];
$formAction = $data['formAction'] ?? "?action=transfer_reserva_store";
$tipo_reserva_actual = $data['tipo_reserva'] ?? ''; 
$vehiculos = $data['vehiculos'] ?? []; 
$vehiculo_actual_id = $data['id_vehiculo'] ?? '';
$hoteles = $data['hoteles'] ?? [];
$hotel_actual_id = $data['id_hotel'] ?? ''; 
$errors = $errors ?? [];
$currentUser = Auth::getCurrentUser();

/**
 * Función de ayuda para acceder a un valor de un elemento que puede ser un Array o un Objeto.
 */
$get_data_value = function($item, $key) {
    if (is_array($item)) {
        if (isset($item[$key])) return $item[$key];
        if (isset($item[strtoupper($key)])) return $item[strtoupper($key)];
        return null; 
    }
    if (is_object($item)) {
        return $item->$key ?? null;
    }
    return null;
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?></title>
<style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding:20px;}
    .navbar{background:white; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:15px 30px; display:flex; justify-content:space-between; align-items:center; border-radius:8px; margin-bottom:30px;}
    .navbar h2{color:#333;}
    .navbar-links a{color:#667eea;text-decoration:none;font-weight:600;}
    .container{background:white;border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.2); max-width:800px; padding:40px; margin:0 auto;}
    h1{text-align:center;margin-bottom:30px;color:#333;}
    .form-group{margin-bottom:20px;}
    label{display:block;margin-bottom:8px;color:#333;font-weight:600;}
    input, select{width:100%; padding:12px; border:2px solid #e0e0e0;border-radius:6px; font-size:1em; transition:border-color 0.3s;}
    input:focus, select:focus{outline:none;border-color:#667eea;}
    .btn-submit{width:100%; padding:12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border:none; border-radius:6px; font-size:1em; font-weight:600; cursor:pointer; margin-top:10px;}
    .btn-back{background:#f1f3f5;color:#333;border:1px solid #ccc;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:600;display:inline-block;margin-bottom:20px;}
    .alert-error{background:#f8d7da;color:#721c24;padding:15px;border-radius:6px;margin-bottom:20px;}
    h3{color:#667eea;border-bottom:2px solid #e0e0e0;padding-bottom:5px;margin-top:25px;margin-bottom:15px;}
</style>
</head>
<body>
<div class="navbar">
    <h2>🏝️ Transfer Reservas</h2>
    <div class="navbar-links">
        <a href="?action=gestion_reservas">← Volver a la gestión</a>
    </div>
</div>

<div class="container">
    <h1><?= $title ?></h1>
    <a href="?action=gestion_reservas" class="btn-back">← Volver</a>

    <?php if(!empty($errors)): ?>
        <div class="alert-error">
            **Error:** <?= htmlspecialchars(implode(', ', $errors)) ?>
        </div>
    <?php endif; ?>

    <form id="reservaForm" action="<?= $formAction ?>" method="POST">
        <?php if($isEdit): ?>
            <input type="hidden" name="id_reserva" value="<?= htmlspecialchars($data['id_reserva']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Tipo de Reserva</label>
            <select id="tipoReservaId" name="tipo_reserva" required>
                <option value="" disabled <?= $tipo_reserva_actual === '' ? 'selected' : '' ?>>-- Elige una opción --</option>
                <option value="1" <?= $tipo_reserva_actual=='1'?'selected':'' ?>>AEROPUERTO -> HOTEL</option>
                <option value="2" <?= $tipo_reserva_actual=='2'?'selected':'' ?>>HOTEL -> AEROPUERTO</option>
                <option value="3" <?= $tipo_reserva_actual=='3'?'selected':'' ?>>IDA Y VUELTA</option>
            </select>
        </div>

        <div id="campos_generales_y_envio" style="display:none;">

            <!-- Bloques condicionales de llegada y salida -->
            <div id="aero_hotel_fields" style="display:none;">
                <h3>Detalles de Llegada (Aeropuerto → Hotel)</h3>
                <div class="form-group">
                    <label>Día de llegada:</label>
                    <input type="date" name="fecha_llegada" value="<?= htmlspecialchars($data['fecha_llegada'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Hora de llegada:</label>
                    <input type="time" name="hora_llegada" value="<?= htmlspecialchars($data['hora_llegada'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Número de vuelo:</label>
                    <input type="text" name="vuelo_llegada" value="<?= htmlspecialchars($data['vuelo_llegada'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Aeropuerto de origen:</label>
                    <input type="text" name="origen_llegada" value="<?= htmlspecialchars($data['origen_llegada'] ?? '') ?>">
                </div>
            </div>

            <div id="hotel_aero_fields" style="display:none;">
                <h3>Detalles de Salida (Hotel → Aeropuerto)</h3>
                <div class="form-group">
                    <label>Día del vuelo:</label>
                    <input type="date" name="fecha_salida" value="<?= htmlspecialchars($data['fecha_salida'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Hora del vuelo:</label>
                    <input type="time" name="hora_salida" value="<?= htmlspecialchars($data['hora_salida'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Número de vuelo:</label>
                    <input type="text" name="vuelo_salida" value="<?= htmlspecialchars($data['vuelo_salida'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Hora de recogida en hotel:</label>
                    <input type="time" name="hora_recogida" value="<?= htmlspecialchars($data['hora_recogida'] ?? '') ?>">
                </div>
            </div>

            <h3>Información General</h3>
            <div class="form-group">
                <label>Número de viajeros:</label>
                <input type="number" id="viajeros" name="num_viajeros" min="1" required value="<?= htmlspecialchars($data['num_viajeros'] ?? 1) ?>">
            </div>

            <div class="form-group">
                <label for="hotelId">Hotel</label>
                <select id="hotelId" name="id_hotel" required>
                    <option value="" disabled <?= $hotel_actual_id === '' ? 'selected':'' ?>>-- Selecciona un Hotel --</option>
                    <?php foreach($hoteles as $hotel):
                        $hotel_id = $get_data_value($hotel,'id');
                        $hotel_nombre = $get_data_value($hotel,'nombre');
                    ?>
                        <option value="<?= htmlspecialchars($hotel_id) ?>" <?= ($hotel_actual_id==$hotel_id)?'selected':'' ?>>
                            <?= htmlspecialchars($hotel_nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="vehiculoId">Vehículo</label>
                <select id="vehiculoId" name="id_vehiculo" required>
                    <option value="">-- Selecciona un Vehículo --</option>
                </select>
            </div>

            <h3>Datos del cliente</h3>
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($data['nombre_cliente'] ?? $currentUser['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Apellido 1:</label>
                <input type="text" name="apellido1_cliente" value="<?= htmlspecialchars($data['apellido1_cliente'] ?? $currentUser['apellido1'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Apellido 2:</label>
                <input type="text" name="apellido2_cliente" value="<?= htmlspecialchars($data['apellido2_cliente'] ?? $currentUser['apellido2'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email_cliente" value="<?= htmlspecialchars($data['email_cliente'] ?? $currentUser['user_email'] ?? '') ?>" required <?= ($currentUser['user_type'] ?? '')!=='admin'?'readonly':'' ?>>
            </div>

            <?php if(($currentUser['user_type'] ?? '')==='admin'): ?>
                <div class="form-group">
                    <label>Estado de la Reserva:</label>
                    <select name="estado">
                        <option value="pendiente" <?= ($data['estado'] ?? '')=='pendiente'?'selected':'' ?>>Pendiente</option>
                        <option value="confirmada" <?= ($data['estado'] ?? '')=='confirmada'?'selected':'' ?>>Confirmada</option>
                        <option value="cancelada" <?= ($data['estado'] ?? '')=='cancelada'?'selected':'' ?>>Cancelada</option>
                        <option value="completada" <?= ($data['estado'] ?? '')=='completada'?'selected':'' ?>>Completada</option>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit"><?= $isEdit?'Guardar Cambios':'Crear Reserva' ?></button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const tipoReservaSelect = document.getElementById('tipoReservaId');
    const camposGenerales = document.getElementById('campos_generales_y_envio');
    const aeroHotel = document.getElementById('aero_hotel_fields');
    const hotelAero = document.getElementById('hotel_aero_fields');
    const viajerosInput = document.getElementById('viajeros');
    const vehiculoSelect = document.getElementById('vehiculoId');

    function toggleFields(tipo){
        camposGenerales.style.display='none';
        aeroHotel.style.display='none';
        hotelAero.style.display='none';
        if(!tipo) return;
        camposGenerales.style.display='block';
        if(tipo==='1') aeroHotel.style.display='block';
        else if(tipo==='2') hotelAero.style.display='block';
        else if(tipo==='3'){ aeroHotel.style.display='block'; hotelAero.style.display='block'; }
    }

    toggleFields(tipoReservaSelect.value);
    tipoReservaSelect.addEventListener('change', ()=> toggleFields(tipoReservaSelect.value));

    function cargarVehiculos(){
        const num = viajerosInput.value;
        fetch("?action=vehiculos_filter&viajeros="+num)
        .then(res=>res.json())
        .then(data=>{
            vehiculoSelect.innerHTML="<option value=''>-- Selecciona un Vehículo --</option>";
            if(data.length===0){
                let opt = document.createElement("option");
                opt.textContent="No hay vehículos disponibles";
                opt.value="";
                vehiculoSelect.appendChild(opt);
            } else {
                data.forEach(v=>{
                    let opt = document.createElement("option");
                    opt.value=v.id_vehiculo ?? v.id;
                    let descripcion = v.descripcion ?? ''; // Si no hay descripción, vacío
                    opt.textContent = `${descripcion} (Capacidad: ${v.capacidad})`;
                    vehiculoSelect.appendChild(opt);
                });
            }
        });
    }

    viajerosInput.addEventListener('input', cargarVehiculos);
    if(viajerosInput.value) cargarVehiculos();
});
</script>
</body>
</html>
