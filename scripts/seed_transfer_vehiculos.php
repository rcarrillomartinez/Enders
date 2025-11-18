<?php

require_once __DIR__ . '/../app/core/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    echo "Database connection successful." . PHP_EOL;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$tableName = 'transfer_vehiculo';

$createSql = <<<SQL
CREATE TABLE IF NOT EXISTS {$tableName} (
  id_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
  Descripción VARCHAR(255) NOT NULL,
  email_conductor VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
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

$vehiculos = [
    ['Descripción' => 'Toyota Prius - Standard', 'email_conductor' => 'conductor1@example.com', 'password' => 'pass123'],
    ['Descripción' => 'Mercedes V-Class - Minivan', 'email_conductor' => 'conductor2@example.com', 'password' => 'pass123'],
    ['Descripción' => 'Ford Transit - Shuttle', 'email_conductor' => 'conductor3@example.com', 'password' => 'pass123'],
    ['Descripción' => 'Skoda Octavia - Standard', 'email_conductor' => 'conductor4@example.com', 'password' => 'pass123'],
];

$insertSql = "INSERT INTO {$tableName} (Descripción, email_conductor, password) VALUES (:descripcion, :email_conductor, :password)";
$stmt = $pdo->prepare($insertSql);

$insertedCount = 0;

foreach ($vehiculos as $vehiculo) {

    $checkStmt = $pdo->prepare("SELECT 1 FROM {$tableName} WHERE email_conductor = :email");
    $checkStmt->execute([':email' => $vehiculo['email_conductor']]);
    if ($checkStmt->fetch()) {
        echo "Vehicle for '{$vehiculo['email_conductor']}' already exists. Skipping." . PHP_EOL;
        continue;
    }

    try {
        $stmt->execute([
            ':descripcion' => $vehiculo['Descripción'],
            ':email_conductor' => $vehiculo['email_conductor'],
            ':password' => password_hash($vehiculo['password'], PASSWORD_BCRYPT)
        ]);
        $insertedCount++;
    } catch (Exception $e) {
        echo "Error inserting vehicle for '{$vehiculo['email_conductor']}': " . $e->getMessage() . PHP_EOL;
    }
}

if ($insertedCount > 0) {
    echo "Successfully inserted {$insertedCount} new vehicles." . PHP_EOL;
} else {
    echo "No new vehicles were inserted." . PHP_EOL;
}

exit(0);
