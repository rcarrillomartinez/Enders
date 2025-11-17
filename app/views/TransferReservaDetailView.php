<p><strong>Localizador:</strong> <?= htmlspecialchars($reserva['localizador']); ?></p>
<h2>Detalle Reserva Transfer: <?php echo $reserva['id_reserva']; ?></h2>
<ul>
    <li>Cliente: <?= htmlspecialchars($cliente_email) ?></li>    
    <li>Hotel: <?= htmlspecialchars($reserva['hotel_nombre']) ?></li>
    <li>Fecha Reserva: <?php echo $reserva['fecha_reserva']; ?></li>
    <li>Hora Entrada: <?php echo $reserva['hora_entrada']; ?></li>
    <li>Vehículo: <?= htmlspecialchars($reserva['vehiculo_descripcion']) ?></li>
    <li>Tipo de Reserva: <?= htmlspecialchars($reserva['tipo_reserva_descripcion']) ?></li>
    <li>Num Viajeros: <?php echo $reserva['num_viajeros']; ?></li>
</ul>
<!-- Botón para volver -->
<a href="?action=dashboard"><button type="button">Volver</button></a>
