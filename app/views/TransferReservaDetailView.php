<?php
// This view expects the controller to provide the variable:
// - $reserva : associative array for a single reservation
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Reserva Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
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
            word-break: break-word;
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
            echo '<a href="index.php" class="btn">Back to List</a>';
        } else {
            echo '<div class="error"><strong>Reserva no encontrada.</strong></div>';
        }
        ?>
    </div>
</body>
</html>
