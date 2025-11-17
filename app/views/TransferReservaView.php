<h2>Reservas de Transfer</h2>
<p>Total: <?php echo $total; ?></p>
<table border="1" cellpadding="6">
<thead>
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Hotel</th>
    <th>Fecha Reserva</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach($reservas as $r): ?>
<tr>
    <td><?php echo $r['id_reserva']; ?></td>
    <td><?php echo $r['email_cliente']; ?></td>
    <td><?php echo $r['id_hotel']; ?></td>
    <td><?php echo $r['fecha_reserva']; ?></td>
    <td><a href="?action=showTransfer&id=<?php echo $r['id_reserva']; ?>">Ver</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
