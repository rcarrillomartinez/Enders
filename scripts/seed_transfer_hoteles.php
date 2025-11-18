<?php

require_once __DIR__ . '/../app/core/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    echo "Database connection successful." . PHP_EOL;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$tableName = 'tranfer_hotel';

// SQL to create table if it doesn't exist
$createSql = <<<SQL
CREATE TABLE IF NOT EXISTS {$tableName} (
  id_hotel INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL,
  Comision INT(11) DEFAULT NULL,
  id_zona INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

try {
    $pdo->exec($createSql);
    echo "Table '{$tableName}' is ready." . PHP_EOL;
} catch (Exception $e) {
    echo "Error creating table '{$tableName}': " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Sample data for hotels
$hoteles = [
    ['usuario' => 'hparadiso', 'password' => 'pass123', 'id_zona' => 1, 'Comision' => 10],
    ['usuario' => 'hgransol', 'password' => 'pass123', 'id_zona' => 2, 'Comision' => 12],
    ['usuario' => 'hvistamar', 'password' => 'pass123', 'id_zona' => 1, 'Comision' => 10],
    ['usuario' => 'hpalmera', 'password' => 'pass123', 'id_zona' => 3, 'Comision' => 15],
];

$insertSql = "INSERT INTO {$tableName} (usuario, password, id_zona, Comision) VALUES (:usuario, :password, :id_zona, :Comision)";
$stmt = $pdo->prepare($insertSql);

$insertedCount = 0;

foreach ($hoteles as $hotel) {
    // Check if hotel with that username already exists
    $checkStmt = $pdo->prepare("SELECT 1 FROM {$tableName} WHERE usuario = :usuario");
    $checkStmt->execute([':usuario' => $hotel['usuario']]);
    if ($checkStmt->fetch()) {
        echo "Hotel with user '{$hotel['usuario']}' already exists. Skipping." . PHP_EOL;
        continue;
    }

    try {
        $stmt->execute([
            ':usuario' => $hotel['usuario'],
            ':password' => password_hash($hotel['password'], PASSWORD_BCRYPT),
            ':id_zona' => $hotel['id_zona'],
            ':Comision' => $hotel['Comision']
        ]);
        $insertedCount++;
    } catch (Exception $e) {
        echo "Error inserting hotel '{$hotel['usuario']}': " . $e->getMessage() . PHP_EOL;
    }
}

if ($insertedCount > 0) {
    echo "Successfully inserted {$insertedCount} new hotels." . PHP_EOL;
} else {
    echo "No new hotels were inserted." . PHP_EOL;
}

exit(0);
