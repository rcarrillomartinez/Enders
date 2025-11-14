
<?php
//Creación de la tabla admin y usuario por defecto
require_once __DIR__ . '/../app/core/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    
    $sql = "CREATE TABLE IF NOT EXISTS transfer_admin (
        id_admin INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) UNIQUE NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ transfer_admin table created successfully\n";
    
    // Mira si el admin existe
    $stmt = $pdo->prepare("SELECT * FROM transfer_admin WHERE email = :email");
    $stmt->execute([':email' => 'admin@example.com']);
    
    if ($stmt->fetch()) {
        echo "✅ Admin user already exists\n";
    } else {
        // Crear admin por defecto
        $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO transfer_admin (email, nombre, password) VALUES (:email, :nombre, :password)");
        $stmt->execute([
            ':email' => 'admin@example.com',
            ':nombre' => 'Administrator',
            ':password' => $hashedPassword
        ]);
        
        echo "✅ Admin por defecto creado:\n";
        echo "   Email: admin@example.com\n";
        echo "   Password: admin123\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
