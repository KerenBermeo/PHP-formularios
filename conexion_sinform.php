<?php
// Datos de conexión
$servidor = "localhost"; // Servidor (usualmente localhost en XAMPP)
$usuario = "root";       // Usuario por defecto en XAMPP
$contrasena = "";        // Contraseña (por defecto está vacía)
$base_datos = "prueba";  // Nombre de la base de datos

try {
    
    $conn = new PDO("mysql:host=$servidor;dbname=$base_datos", $usuario, $contrasena);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos '$base_datos'.<br>";

    // Crear la tabla "usuarios" si no existe
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        contrasena VARCHAR(255) NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Tabla 'usuarios' creada correctamente o ya existe.<br>";

    // Función para insertar un usuario
    function insertarUsuario($conn, $nombre, $email, $contrasena) {
        try {
            // Hash de la contraseña para mayor seguridad
            $hashContrasena = password_hash($contrasena, PASSWORD_BCRYPT);
            // Consulta SQL para insertar datos
            $sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (:nombre, :email, :contrasena)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contrasena', $hashContrasena);
            $stmt->execute();
            echo "Usuario '$nombre' insertado correctamente.<br>";
        } catch (PDOException $e) {
            echo "Error al insertar usuario: " . $e->getMessage() . "<br>";
        }
    }

    // Usar la función para insertar usuarios
    insertarUsuario($conn, "Keren Bermeo", "keren@example.com", "mi_contraseña_segura");
    insertarUsuario($conn, "Juan Perez", "juan.perez@example.com", "123456");

} catch (PDOException $e) {
    // Manejar errores
    echo "Error en la conexión o ejecución: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>

