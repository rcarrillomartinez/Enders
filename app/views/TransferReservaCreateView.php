<h2>Crear Nueva Reserva</h2>

<a href="?action=dashboard"><button type="button">Volver</button></a>

<form id="reservaForm" method="post" action="?action=storeReserva" style="margin-top:20px;">

    <label for="tipo_reserva">Selecciona el tipo de reserva:</label>
    <select id="tipo_reserva" name="tipo_reserva" required>
        <option value="" disabled selected>-- Elige una opción --</option>
        <option value="1">Aeropuerto → Hotel</option>
        <option value="2">Hotel → Aeropuerto</option>
        <option value="3">Ida y vuelta</option>
    </select>

    <div id="campos_generales_y_envio" style="display:none; margin-top:20px;">

        <div id="aero_hotel_fields" style="display:none; margin-top:10px;">
            <h3>Aeropuerto → Hotel</h3>
            <label>Día de llegada:</label>
            <input type="date" name="fecha_llegada">
            <label>Hora de llegada:</label>
            <input type="time" name="hora_llegada">
            <label>Número de vuelo:</label>
            <input type="text" name="vuelo_llegada">
            <label>Aeropuerto de origen:</label>
            <input type="text" name="origen_llegada">
        </div>

        <div id="hotel_aero_fields" style="display:none; margin-top:10px;">
            <h3>Hotel → Aeropuerto</h3>
            <label>Día del vuelo:</label>
            <input type="date" name="fecha_salida">
            <label>Hora del vuelo:</label>
            <input type="time" name="hora_salida">
            <label>Número de vuelo:</label>
            <input type="text" name="vuelo_salida">
            <label>Hora de recogida en hotel:</label>
            <input type="time" name="hora_recogida">
        </div>

        <label>Número de viajeros:</label>
        <input type="number" name="num_viajeros" min="1" required>

        <label for="hotel_select">Hotel destino / recogida:</label>
        <select id="hotel_select" name="nombre_hotel" required>
            <option value="" disabled selected>-- Selecciona hotel --</option>
            <?php foreach($hoteles as $hotel): ?>
                <option value="<?= htmlspecialchars($hotel['nombre_hotel'] ?? '') ?>">
                    <?= htmlspecialchars($hotel['nombre_hotel'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="vehiculo_select">Vehículo:</label>
        <select id="vehiculo_select" name="vehiculo_descripcion" required>
            <option value="" disabled selected>-- Selecciona vehículo --</option>
            <?php foreach($vehiculos as $v): ?>
                <option value="<?= htmlspecialchars($v['descripcion'] ?? '') ?>">
                    <?= htmlspecialchars($v['descripcion'] ?? '') ?> (Capacidad: <?= $v['capacidad'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <h3>Datos del cliente</h3>
        <label>Nombre:</label>
        <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" required>
        <label>Apellido 1:</label>
        <input type="text" name="apellido1_cliente" value="<?= htmlspecialchars($user['apellido1'] ?? '') ?>" required>
        <label>Apellido 2:</label>
        <input type="text" name="apellido2_cliente" value="<?= htmlspecialchars($user['apellido2'] ?? '') ?>">
        <label>Email:</label>
        <input type="email" name="email_cliente" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <button type="submit" style="margin-top:10px;">Crear Reserva</button>
    </div>
</form>

<script>
document.getElementById('tipo_reserva').addEventListener('change', function() {
    var tipo = this.value;
    const camposGenerales = document.getElementById('campos_generales_y_envio');
    const aeroHotel = document.getElementById('aero_hotel_fields');
    const hotelAero = document.getElementById('hotel_aero_fields');

    // 1. Ocultar todos los campos condicionales y generales al inicio del cambio
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
});
</script>