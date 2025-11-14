<?php

?>
        <?php
        if (isset($reserva) && !empty($reserva)) {
            echo '<div class="detail-group">';
            foreach ($reserva as $key => $value) {
                echo '<div class="detail-row">';
                echo '<span class="detail-label">' . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . ':</span>';
                echo '<span class="detail-value">' . htmlspecialchars($value ?? 'N/A') . '</span>';
                echo '</div>';
            }
            echo '</div>';
            echo '<a href="?action=list" class="btn">Back to List</a>';
        } else {
            echo '<div class="error"><strong>Reserva no encontrada.</strong></div>';
        }
        ?>
<html>
<head>
    <title>Transfer Reserva Detail</title>
    <meta charset="utf-8" />
    <style>
        body {
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
        .detail-group {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            margin: 10px 0;
        }
        .detail-label {
            font-weight: bold;
            width: 200px;
            color: #555;
        }
        .detail-value {
            flex: 1;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transfer Reserva Detail</h1>
        
        <?php
        if (isset($reserva) && !empty($reserva)) {
            echo '<div class="detail-group">';
            foreach ($reserva as $key => $value) {
                echo '<div class="detail-row">';
                echo '<span class="detail-label">' . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . ':</span>';
                echo '<span class="detail-value">' . htmlspecialchars($value ?? 'N/A') . '</span>';
                echo '</div>';
            }
            echo '</div>';
            echo '<a href="?action=list" class="btn">Back to List</a>';
        } else {
            echo '<div class="error"><strong>Reservation not found.</strong></div>';
        }
        ?>
    </div>
</body>
</html>
