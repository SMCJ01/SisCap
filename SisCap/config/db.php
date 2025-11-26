<?php
// /SisCap/config/db.php
$host = 'localhost';
$dbname = 'SistemaCapacitaciones';
$user = 'root';
$pass = ''; // pon tu contraseña si tienes

if (!isset($pdo)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
