<?php
// Script para insertar zonas, hoteles y vehículos si no existen
require_once __DIR__ . '/../app/core/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();

    // --- INSERTAR ZONAS ---
    $zonas = [
        1 => 'Zona 1', 2 => 'Zona 2', 3 => 'Zona 3', 4 => 'Zona 4',
        5 => 'Zona 5', 6 => 'Zona 6', 7 => 'Zona 7', 8 => 'Zona 8'
    ];

    foreach ($zonas as $id => $descripcion) {
        $stmt = $pdo->prepare("SELECT * FROM transfer_zona WHERE id_zona = :id");
        $stmt->execute([':id' => $id]);
        if (!$stmt->fetch()) {
            $stmtInsert = $pdo->prepare("INSERT INTO transfer_zona (id_zona, descripcion) VALUES (:id, :descripcion)");
            $stmtInsert->execute([':id' => $id, ':descripcion' => $descripcion]);
            echo "✅ Zona $descripcion creada\n";
        } else {
            echo "✅ Zona $descripcion ya existe\n";
        }
    }

    // --- INSERTAR HOTELES ---
    $hoteles = [
        [1,1,'Hotel Sol',10,1,'1234'],
        [2,1,'Hotel Luna',12,2,'1234'],
        [3,2,'Hotel Estrella',8,3,'1234'],
        [4,2,'Hotel Mar',10,4,'1234'],
        [5,3,'Hotel Playa',15,5,'1234'],
        [6,3,'Hotel Cielo',10,6,'1234'],
        [7,4,'Hotel Nube',12,7,'1234'],
        [8,4,'Hotel Río',10,8,'1234'],
        [9,5,'Hotel Montaña',9,9,'1234'],
        [10,5,'Hotel Bosque',14,10,'1234'],
        [11,6,'Hotel Lago',11,11,'1234'],
        [12,6,'Hotel Arena',13,12,'1234'],
        [13,7,'Hotel Valle',10,13,'1234'],
        [14,7,'Hotel Viento',12,14,'1234'],
        [15,8,'Hotel Solaria',10,15,'1234']
    ];

    foreach ($hoteles as $h) {
        list($id, $id_zona, $nombre, $comision, $usuario, $password) = $h;
        $stmt = $pdo->prepare("SELECT * FROM tranfer_hotel WHERE id_hotel = :id");
        $stmt->execute([':id' => $id]);
        if (!$stmt->fetch()) {
            $stmtInsert = $pdo->prepare("INSERT INTO tranfer_hotel (id_hotel, id_zona, nombre_hotel, Comision, usuario, password) VALUES (:id, :id_zona, :nombre, :comision, :usuario, :password)");
            $stmtInsert->execute([
                ':id' => $id,
                ':id_zona' => $id_zona,
                ':nombre' => $nombre,
                ':comision' => $comision,
                ':usuario' => $usuario,
                ':password' => $password
            ]);
            echo "✅ Hotel $nombre creado\n";
        } else {
            echo "✅ Hotel $nombre ya existe\n";
        }
    }

    // --- INSERTAR VEHÍCULOS ---
    $vehiculos = [
        [1,'Toyota Corolla Rojo',4,'conductor1@example.com','1234'],
        [2,'Ford Fiesta Azul',4,'conductor2@example.com','1234'],
        [3,'Renault Clio Blanco',4,'conductor3@example.com','1234'],
        [4,'Volkswagen Golf Negro',5,'conductor4@example.com','1234'],
        [5,'Seat Ibiza Gris',4,'conductor5@example.com','1234'],
        [6,'Opel Astra Azul',5,'conductor6@example.com','1234'],
        [7,'Hyundai i20 Rojo',4,'conductor7@example.com','1234'],
        [8,'Kia Rio Blanco',4,'conductor8@example.com','1234'],
        [9,'Nissan Micra Negro',4,'conductor9@example.com','1234'],
        [10,'Peugeot 208 Gris',4,'conductor10@example.com','1234'],
        [11,'Honda Civic Rojo',5,'conductor11@example.com','1234'],
        [12,'Mazda 3 Azul',5,'conductor12@example.com','1234'],
        [13,'BMW Serie 1 Negro',5,'conductor13@example.com','1234'],
        [14,'Audi A3 Blanco',5,'conductor14@example.com','1234'],
        [15,'Mercedes A180 Gris',5,'conductor15@example.com','1234']
    ];

    foreach ($vehiculos as $v) {
        list($id, $desc, $cap, $email, $pass) = $v;
        $stmt = $pdo->prepare("SELECT * FROM transfer_vehiculo WHERE id_vehiculo = :id");
        $stmt->execute([':id' => $id]);
        if (!$stmt->fetch()) {
            $stmtInsert = $pdo->prepare("INSERT INTO transfer_vehiculo (id_vehiculo, descripcion, capacidad, email_conductor, password) VALUES (:id, :desc, :cap, :email, :pass)");
            $stmtInsert->execute([
                ':id' => $id,
                ':desc' => $desc,
                ':cap' => $cap,
                ':email' => $email,
                ':pass' => $pass
            ]);
            echo "✅ Vehículo $desc creado\n";
        } else {
            echo "✅ Vehículo $desc ya existe\n";
        }
    }

    echo "✅ Todos los registros procesados correctamente.\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
