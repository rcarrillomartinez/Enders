<h2>Calendario de Reservas - Panel Admin</h2>

<p>
    <a href="?action=dashboard">Volver al Panel</a>
</p>

<?php
// Parámetros de vista
$viewType = $_GET['view'] ?? 'week'; // day, week, month
$baseDate = $_GET['date'] ?? date('Y-m-d');

// Determinar rango de fechas
switch($viewType){
    case 'day':
        $start = $end = $baseDate;
        break;
    case 'week':
        $start = date('Y-m-d', strtotime($baseDate.' -3 days'));
        $end = date('Y-m-d', strtotime($baseDate.' +3 days'));
        break;
    case 'month':
        $start = date('Y-m-01', strtotime($baseDate));
        $end = date('Y-m-t', strtotime($baseDate));
        break;
    default:
        $start = date('Y-m-d', strtotime($baseDate.' -3 days'));
        $end = date('Y-m-d', strtotime($baseDate.' +3 days'));
        break;
}

// Filtrar reservas dentro del rango
$filtered = [];
foreach($reservas as $r){
    $arrival = $r['arrival_date'] ?? null;
    $departure = $r['departure_date'] ?? null;
    if(($arrival >= $start && $arrival <= $end) || ($departure >= $start && $departure <= $end)){
        $filtered[] = $r;
    }
}

// Agrupar por fecha de llegada
$byDate = [];
foreach($filtered as $r){
    $dateKey = $r['arrival_date'] ?? '-';
    $byDate[$dateKey][] = $r;
}

// Ordenar por fecha
ksort($byDate);
?>

<!-- Navegación de fechas -->
<p>
    <strong>Vista:</strong> 
    <a href="?action=calendar&view=day&date=<?php echo $baseDate; ?>">Día</a> | 
    <a href="?action=calendar&view=week&date=<?php echo $baseDate; ?>">Semana</a> | 
    <a href="?action=calendar&view=month&date=<?php echo $baseDate; ?>">Mes</a>
</p>

<p>
    <strong>Fecha base:</strong> <?php echo $baseDate; ?>
</p>

<table border="1" cellpadding="6">
<thead>
<tr>
    <th>Fecha</th>
    <th>Localizador</th>
    <th>Cliente</th>
    <th>Hotel</th>
    <th>Tipo</th>
    <th>Viajeros</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>
<?php if(empty($byDate)): ?>
<tr><td colspan="7">No hay reservas en este rango de fechas</td></tr>
<?php else: ?>
<?php foreach($byDate as $fecha => $resList): ?>
    <?php foreach($resList as $res): ?>
    <tr>
        <td><?php echo $fecha; ?></td>
        <td><?php echo $res['locator'] ?? $res['id_reserva']; ?></td>
        <td><?php echo $res['user_email'] ?? $res['email_cliente']; ?></td>
        <td><?php echo $res['hotel_name'] ?? $res['id_hotel']; ?></td>
        <td><?php echo $res['type'] ?? '-'; ?></td>
        <td><?php echo $res['travelers'] ?? $res['num_pasajeros'] ?? 1; ?></td>
        <td>
            <a href="?action=showReserva&id=<?php echo $res['id'] ?? $res['id_reserva']; ?>">Ver</a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
