<h2>Panel Administración</h2>
<p><a href="?action=adminCreateReserva">Crear reserva</a> | <a href="?action=adminCalendar">Ver calendario</a></p>
<table border="1" cellpadding="6">
<thead>
<tr>
    <th>ID</th>
    <th>Localizador</th>
    <th>Tipo</th>
    <th>Fechas</th>
    <th>Hotel</th>
    <th>Usuario</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach($reservas as $r): ?>
<tr>
    <td><?php echo $r['id']; ?></td>
    <td><?php echo $r['locator']; ?></td>
    <td><?php echo $r['type']; ?></td>
    <td><?php echo $r['arrival_date'].' '.$r['arrival_time'].' / '.$r['departure_date'].' '.$r['departure_time']; ?></td>
    <td><?php echo $r['hotel_name'] ?? ''; ?></td>
    <td><?php echo $r['user_email'] ?? ''; ?></td>
    <td><a href="?action=showTransfer&id=<?php echo $r['id']; ?>">Ver</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
