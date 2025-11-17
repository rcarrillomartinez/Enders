<h2>Mis Reservas</h2>
<?php if(empty($reservas)) echo '<p>No tienes reservas todavía.</p>'; ?>
<ul>
<?php foreach($reservas as $r): ?>
    <li>
        <a href="/reserva/<?php echo $r['id_reserva']; ?>">
            <?php echo $r['localizador']; ?>
        </a> - <?php echo $r['fecha_reserva']; ?>
    </li>
<?php endforeach; ?>
</ul>
