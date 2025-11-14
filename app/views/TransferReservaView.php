<?php

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Reservas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .stats {
            background-color: #e7f3ff;
            padding: 10px 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .text-muted {
            color: #888;
            font-style: italic;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transfer Reservas</h1>
        
        <?php
        if (!isset($reservas)) {
            echo '<div class="error"><strong>No data provided.</strong> The controller must supply <code>$reservas</code>.</div>';
        } else {
            $total = $total ?? count($reservas);

            if (empty($reservas)) {
                echo '<div class="error"><strong>No se han encontrado reservas.</strong></div>';
            } else {
                echo '<div class="stats"><strong>Reservas totales:</strong> ' . htmlspecialchars($total) . '</div>';
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID Viajero</th>';
                echo '<th>ID Transfer</th>';
                echo '<th>Fecha Reserva</th>';
                echo '<th>Fecha Partida</th>';
                echo '<th>Hora Partida</th>';
                echo '<th>Passajeros</th>';
                echo '<th>Estado</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($reservas as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id_viajero'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['id_transfer'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_reserva'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha_partida'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['hora_partida'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['num_pasajeros'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['estado'] ?? 'N/A') . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }
        }
        ?>
    </div>
</body>
</html>
