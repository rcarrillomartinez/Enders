<?php

// Reuse centralized Database singleton
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
  id_viajero INT NULL,
  id_transfer INT NULL,
  fecha_reserva DATE NULL,
  fecha_partida DATE NULL,
  hora_partida TIME NULL,
  num_pasajeros INT NULL,
  estado VARCHAR(50) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

$pdo->exec($createSql);

// Ensure expected columns exist on the table; if not, add them.
$expectedCols = [
    'id_reserva' => "INT AUTO_INCREMENT",
    'id_viajero' => "INT NULL",
    'id_transfer' => "INT NULL",
    'fecha_reserva' => "DATE NULL",
    'fecha_partida' => "DATE NULL",
    'hora_partida' => "TIME NULL",
    'num_pasajeros' => "INT NULL",
    'estado' => "VARCHAR(50) NULL",
    'created_at' => "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP",
];

$colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = :db AND table_name = 'transfer_reservas'");
$colStmt->execute([':db' => DB_NAME]);
$existing = $colStmt->fetchAll(PDO::FETCH_COLUMN);
$existing = array_map('strtolower', $existing);

foreach ($expectedCols as $col => $definition) {
    if (!in_array(strtolower($col), $existing)) {
        // Add missing column
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

// Ensure marker table exists before checking to avoid "table not found" errors
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
        'id_viajero' => 1,
        'id_transfer' => 10,
        'fecha_reserva' => date('Y-m-d'),
        'fecha_partida' => date('Y-m-d', strtotime('+7 days')),
        'hora_partida' => '09:00:00',
        'num_pasajeros' => 2,
        'estado' => 'confirmada'
    ],
    [
        'id_viajero' => 2,
        'id_transfer' => 12,
        'fecha_reserva' => date('Y-m-d', strtotime('-1 days')),
        'fecha_partida' => date('Y-m-d', strtotime('+3 days')),
        'hora_partida' => '14:30:00',
        'num_pasajeros' => 4,
        'estado' => 'pendiente'
    ],
    [
        'id_viajero' => 3,
        'id_transfer' => 15,
        'fecha_reserva' => date('Y-m-d', strtotime('-5 days')),
        'fecha_partida' => date('Y-m-d', strtotime('+1 days')),
        'hora_partida' => '06:45:00',
        'num_pasajeros' => 1,
        'estado' => 'cancelada'
    ],
];

$insertSql = "INSERT INTO transfer_reservas (id_viajero,id_transfer,fecha_reserva,fecha_partida,hora_partida,num_pasajeros,estado) VALUES (:id_viajero,:id_transfer,:fecha_reserva,:fecha_partida,:hora_partida,:num_pasajeros,:estado)";
$insertStmt = $pdo->prepare($insertSql);

$inserted = 0;

// Disable foreign key checks so sample inserts don't fail on constrained schemas
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
try {
    foreach ($sample as $row) {
        $insertStmt->execute([
            ':id_viajero' => $row['id_viajero'],
            ':id_transfer' => $row['id_transfer'],
            ':fecha_reserva' => $row['fecha_reserva'],
            ':fecha_partida' => $row['fecha_partida'],
            ':hora_partida' => $row['hora_partida'],
            ':num_pasajeros' => $row['num_pasajeros'],
            ':estado' => $row['estado'],
        ]);
        $inserted++;
    }
} finally {
    // Re-enable foreign key checks
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

// Record that seeder ran
$pdo->exec("CREATE TABLE IF NOT EXISTS {$markerTable} (marker VARCHAR(255) PRIMARY KEY, applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
$pdo->prepare("INSERT IGNORE INTO {$markerTable} (marker) VALUES (:marker)")->execute([':marker' => $markerKey]);

echo "Inserted {$inserted} sample rows into transfer_reservas." . PHP_EOL;
exit(0);
