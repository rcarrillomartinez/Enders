<h2>Dashboard Usuario</h2>
<p>Bienvenido: <?php echo htmlspecialchars($user['nombre'] ?? $user['user_email']); ?></p>

<h3>Mis reservas</h3>
<?php if(empty($reservas)): ?>
    <p>No tienes reservas.</p>
<?php else: ?>
    <ul>
    <?php foreach($reservas as $r): ?>
        <li>
            <strong>Localizador:</strong> <?= htmlspecialchars($r['localizador']); ?>  
            — <?= htmlspecialchars($r['fecha_reserva']); ?>  
            <a href="?action=showReserva&id=<?= $r['id_reserva']; ?>">Ver detalles</a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Botón para crear nueva reserva -->
<p><a href="?action=createReserva">+ Crear nueva reserva</a></p>
