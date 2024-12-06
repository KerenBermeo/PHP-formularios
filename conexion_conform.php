<?php 
// Inicialización de las variables con los datos recibidos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : ""; 
$edad = isset($_POST['edad']) ? (int)$_POST['edad'] : 0;
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";

// Validaciones 
$errores = [];
if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio.";
}
if ($edad <= 0) {
    $errores[] = "La edad debe ser mayor a 0.";
}
if (strlen($descripcion) == 0) {
    $errores[] = "La descripción no puede ir vacia.";
}

// Si hay errores, mostrar y detener ejecución
if (!empty($errores)) {
    foreach ($errores as $error) {
        echo $error . "<br>";
    }
    exit;
}

// Datos de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yosoy";

try {
    // Conexión al servidor para crear la base de datos
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    echo "Base de datos '$dbname' creada o ya existía.<br>";

    // Conectarse a la base de datos recién creada
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla si no existe
    $sql = "CREATE TABLE IF NOT EXISTS persona (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(30) NOT NULL,
        edad INT NOT NULL,
        descripcion TEXT NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Tabla 'persona' creada o ya existía.<br>";

    // Insertar datos
    $sql = "INSERT INTO persona (nombre, edad, descripcion) VALUES (:nombre, :edad, :descripcion)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':edad', $edad);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->execute();

    echo "Datos insertados exitosamente en la tabla 'persona'.<br>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn = null; // Cerrar conexión
}
?>

