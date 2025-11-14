<?php

// Base de datos y tabla transfer_reservas
require_once __DIR__ . '/../app/core/Database.php';
try {
    $pdo = Database::getInstance()->getConnection();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$createSql = <<<SQL
CREATE TABLE IF NOT EXISTS transfer_reservas (
  id_reserva INT AUTO_INCREMENT PRIMARY KEY,
  localizador VARCHAR(100) NOT NULL,
  id_hotel INT NULL,
  id_tipo_reserva INT NOT NULL,
  email_cliente VARCHAR(100) NOT NULL,
  fecha_reserva DATETIME NOT NULL,
  fecha_modificacion DATETIME NOT NULL,
  id_destino INT NOT NULL,
  fecha_entrada DATE NOT NULL,
  hora_entrada TIME NOT NULL,
  numero_vuelo_entrada VARCHAR(50) NOT NULL,
  origen_vuelo_entrada VARCHAR(50) NOT NULL,
  hora_vuelo_salida TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  fecha_vuelo_salida DATE NOT NULL,
  num_viajeros INT NOT NULL,
  id_vehiculo INT NOT NULL,
  id_viajero INT NULL,
  id_transfer INT NULL,
  fecha_partida DATE NULL,
  hora_partida TIME NULL,
  num_pasajeros INT NULL,
  estado VARCHAR(50) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

$pdo->exec($createSql);

$expectedCols = [
    'id_reserva' => "INT AUTO_INCREMENT PRIMARY KEY",
    'localizador' => "VARCHAR(100) NOT NULL",
    'id_hotel' => "INT NULL",
    'id_tipo_reserva' => "INT NOT NULL",
    'email_cliente' => "VARCHAR(100) NOT NULL",
    'fecha_reserva' => "DATETIME NOT NULL",
    'fecha_modificacion' => "DATETIME NOT NULL",
    'id_destino' => "INT NOT NULL",
    'fecha_entrada' => "DATE NOT NULL",
    'hora_entrada' => "TIME NOT NULL",
    'numero_vuelo_entrada' => "VARCHAR(50) NOT NULL",
    'origen_vuelo_entrada' => "VARCHAR(50) NOT NULL",
    'hora_vuelo_salida' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
    'fecha_vuelo_salida' => "DATE NOT NULL",
    'num_viajeros' => "INT NOT NULL",
    'id_vehiculo' => "INT NOT NULL",
    'id_viajero' => "INT NULL",
    'id_transfer' => "INT NULL",
    'fecha_partida' => "DATE NULL",
    'hora_partida' => "TIME NULL",
    'num_pasajeros' => "INT NULL",
    'estado' => "VARCHAR(50) NULL",
    'created_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
];

$colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = :db AND table_name = 'transfer_reservas'");
$colStmt->execute([':db' => DB_NAME]);
$existing = $colStmt->fetchAll(PDO::FETCH_COLUMN);
$existing = array_map('strtolower', $existing);

foreach ($expectedCols as $col => $definition) {
    if (!in_array(strtolower($col), $existing)) {
        $sql = "ALTER TABLE transfer_reservas ADD COLUMN {$col} {$definition}";
        try {
            $pdo->exec($sql);
            echo "Added missing column {$col}." . PHP_EOL;
        } catch (Exception $e) {
            echo "Warning: could not add column {$col}: " . $e->getMessage() . PHP_EOL;
        }
    }
}

$markerKey = 'seed_transfer_reservas_v1';
$markerTable = 'seeder_marker';

$pdo->exec("CREATE TABLE IF NOT EXISTS {$markerTable} (
    marker VARCHAR(255) PRIMARY KEY,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$checkStmt = $pdo->prepare("SELECT 1 FROM {$markerTable} WHERE marker = :marker LIMIT 1");
$checkStmt->execute([':marker' => $markerKey]);
$markerExists = (bool) $checkStmt->fetchColumn();

if ($markerExists) {
    echo "Seeder already applied (marker found). Exiting." . PHP_EOL;
    exit(0);
}


$sample = [
    [
        'localizador' => 'LOC001',
        'id_hotel' => 1,
        'id_tipo_reserva' => 1,
        'email_cliente' => 'cliente1@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-10 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => 5,
        'fecha_entrada' => date('Y-m-d', strtotime('+5 days')),
        'hora_entrada' => '15:00:00',
        'numero_vuelo_entrada' => 'IB123',
        'origen_vuelo_entrada' => 'MAD',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('+6 days')),
        'num_viajeros' => 2,
        'id_vehiculo' => 1,
        'id_viajero' => 1,
        'id_transfer' => 10,
        'fecha_partida' => date('Y-m-d', strtotime('+5 days')),
        'hora_partida' => '08:30:00',
        'num_pasajeros' => 2,
        'estado' => 'confirmada'
    ],
    [
        'localizador' => 'LOC002',
        'id_hotel' => 2,
        'id_tipo_reserva' => 1,
        'email_cliente' => 'cliente2@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-3 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => 7,
        'fecha_entrada' => date('Y-m-d', strtotime('+10 days')),
        'hora_entrada' => '12:00:00',
        'numero_vuelo_entrada' => 'UX456',
        'origen_vuelo_entrada' => 'BCN',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('+11 days')),
        'num_viajeros' => 4,
        'id_vehiculo' => 2,
        'id_viajero' => 2,
        'id_transfer' => 12,
        'fecha_partida' => date('Y-m-d', strtotime('+10 days')),
        'hora_partida' => '14:15:00',
        'num_pasajeros' => 4,
        'estado' => 'confirmada'
    ],
    [
        'localizador' => 'LOC003',
        'id_hotel' => 3,
        'id_tipo_reserva' => 2,
        'email_cliente' => 'cliente3@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-7 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s', strtotime('-1 days')),
        'id_destino' => 8,
        'fecha_entrada' => date('Y-m-d', strtotime('+2 days')),
        'hora_entrada' => '10:00:00',
        'numero_vuelo_entrada' => 'VB789',
        'origen_vuelo_entrada' => 'AGP',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('+3 days')),
        'num_viajeros' => 1,
        'id_vehiculo' => 1,
        'id_viajero' => 3,
        'id_transfer' => 15,
        'fecha_partida' => date('Y-m-d', strtotime('+2 days')),
        'hora_partida' => '06:45:00',
        'num_pasajeros' => 1,
        'estado' => 'completada'
    ],
    [
        'localizador' => 'LOC004',
        'id_hotel' => 1,
        'id_tipo_reserva' => 1,
        'email_cliente' => 'cliente4@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-1 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => 6,
        'fecha_entrada' => date('Y-m-d', strtotime('+14 days')),
        'hora_entrada' => '14:00:00',
        'numero_vuelo_entrada' => 'AF101',
        'origen_vuelo_entrada' => 'TFS',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('+15 days')),
        'num_viajeros' => 3,
        'id_vehiculo' => 3,
        'id_viajero' => 4,
        'id_transfer' => 18,
        'fecha_partida' => date('Y-m-d', strtotime('+14 days')),
        'hora_partida' => '11:00:00',
        'num_pasajeros' => 3,
        'estado' => 'pendiente'
    ],
    [
        'localizador' => 'LOC005',
        'id_hotel' => 2,
        'id_tipo_reserva' => 2,
        'email_cliente' => 'cliente5@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-15 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s', strtotime('-3 days')),
        'id_destino' => 9,
        'fecha_entrada' => date('Y-m-d', strtotime('-2 days')),
        'hora_entrada' => '16:00:00',
        'numero_vuelo_entrada' => 'BA202',
        'origen_vuelo_entrada' => 'IBZ',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('-1 days')),
        'num_viajeros' => 5,
        'id_vehiculo' => 2,
        'id_viajero' => 5,
        'id_transfer' => 20,
        'fecha_partida' => date('Y-m-d', strtotime('-2 days')),
        'hora_partida' => '09:30:00',
        'num_pasajeros' => 5,
        'estado' => 'completada'
    ],
    [
        'localizador' => 'LOC006',
        'id_hotel' => 4,
        'id_tipo_reserva' => 1,
        'email_cliente' => 'cliente6@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-5 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'id_destino' => 10,
        'fecha_entrada' => date('Y-m-d', strtotime('-1 days')),
        'hora_entrada' => '18:00:00',
        'numero_vuelo_entrada' => 'LH303',
        'origen_vuelo_entrada' => 'PMI',
        'fecha_vuelo_salida' => date('Y-m-d'),
        'num_viajeros' => 2,
        'id_vehiculo' => 4,
        'id_viajero' => 6,
        'id_transfer' => 22,
        'fecha_partida' => date('Y-m-d', strtotime('-1 days')),
        'hora_partida' => '16:45:00',
        'num_pasajeros' => 2,
        'estado' => 'cancelada'
    ],
    [
        'localizador' => 'LOC007',
        'id_hotel' => 3,
        'id_tipo_reserva' => 2,
        'email_cliente' => 'cliente7@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s'),
        'fecha_modificacion' => date('Y-m-d H:i:s'),
        'id_destino' => 5,
        'fecha_entrada' => date('Y-m-d', strtotime('+21 days')),
        'hora_entrada' => '11:00:00',
        'numero_vuelo_entrada' => 'KL404',
        'origen_vuelo_entrada' => 'MAD',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('+22 days')),
        'num_viajeros' => 6,
        'id_vehiculo' => 3,
        'id_viajero' => 7,
        'id_transfer' => 25,
        'fecha_partida' => date('Y-m-d', strtotime('+21 days')),
        'hora_partida' => '07:00:00',
        'num_pasajeros' => 6,
        'estado' => 'confirmada'
    ],
    [
        'localizador' => 'LOC008',
        'id_hotel' => 1,
        'id_tipo_reserva' => 1,
        'email_cliente' => 'cliente8@example.com',
        'fecha_reserva' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'fecha_modificacion' => date('Y-m-d H:i:s', strtotime('-1 days')),
        'id_destino' => 7,
        'fecha_entrada' => date('Y-m-d', strtotime('+8 days')),
        'hora_entrada' => '13:00:00',
        'numero_vuelo_entrada' => 'SV505',
        'origen_vuelo_entrada' => 'SVQ',
        'fecha_vuelo_salida' => date('Y-m-d', strtotime('+9 days')),
        'num_viajeros' => 1,
        'id_vehiculo' => 1,
        'id_viajero' => 8,
        'id_transfer' => 30,
        'fecha_partida' => date('Y-m-d', strtotime('+8 days')),
        'hora_partida' => '13:30:00',
        'num_pasajeros' => 1,
        'estado' => 'pendiente'
    ],
];

$insertSql = "INSERT INTO transfer_reservas (localizador,id_hotel,id_tipo_reserva,email_cliente,fecha_reserva,fecha_modificacion,id_destino,fecha_entrada,hora_entrada,numero_vuelo_entrada,origen_vuelo_entrada,fecha_vuelo_salida,num_viajeros,id_vehiculo,id_viajero,id_transfer,fecha_partida,hora_partida,num_pasajeros,estado) VALUES (:localizador,:id_hotel,:id_tipo_reserva,:email_cliente,:fecha_reserva,:fecha_modificacion,:id_destino,:fecha_entrada,:hora_entrada,:numero_vuelo_entrada,:origen_vuelo_entrada,:fecha_vuelo_salida,:num_viajeros,:id_vehiculo,:id_viajero,:id_transfer,:fecha_partida,:hora_partida,:num_pasajeros,:estado)";
$insertStmt = $pdo->prepare($insertSql);

$inserted = 0;

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
try {
    foreach ($sample as $idx => $row) {
        try {
            $insertStmt->execute([
                ':localizador' => $row['localizador'],
                ':id_hotel' => $row['id_hotel'],
                ':id_tipo_reserva' => $row['id_tipo_reserva'],
                ':email_cliente' => $row['email_cliente'],
                ':fecha_reserva' => $row['fecha_reserva'],
                ':fecha_modificacion' => $row['fecha_modificacion'],
                ':id_destino' => $row['id_destino'],
                ':fecha_entrada' => $row['fecha_entrada'],
                ':hora_entrada' => $row['hora_entrada'],
                ':numero_vuelo_entrada' => $row['numero_vuelo_entrada'],
                ':origen_vuelo_entrada' => $row['origen_vuelo_entrada'],
                ':fecha_vuelo_salida' => $row['fecha_vuelo_salida'],
                ':num_viajeros' => $row['num_viajeros'],
                ':id_vehiculo' => $row['id_vehiculo'],
                ':id_viajero' => $row['id_viajero'],
                ':id_transfer' => $row['id_transfer'],
                ':fecha_partida' => $row['fecha_partida'],
                ':hora_partida' => $row['hora_partida'],
                ':num_pasajeros' => $row['num_pasajeros'],
                ':estado' => $row['estado'],
            ]);
            $inserted++;
        } catch (Exception $e) {
            echo "Error inserting row $idx: " . $e->getMessage() . PHP_EOL;
            var_dump($row);
        }
    }
} finally {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

$pdo->exec("CREATE TABLE IF NOT EXISTS {$markerTable} (marker VARCHAR(255) PRIMARY KEY, applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
$pdo->prepare("INSERT IGNORE INTO {$markerTable} (marker) VALUES (:marker)")->execute([':marker' => $markerKey]);

echo "Inserted {$inserted} sample rows into transfer_reservas." . PHP_EOL;
exit(0);
