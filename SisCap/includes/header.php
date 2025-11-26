<?php
// /SisCap/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/SisCap');
}

$usuarioSesion = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SisCap - Sistema de Capacitaciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>
<div class="fondo-dinamico"></div>

<header class="main-header">
    <div class="logo">
        <a href="<?= BASE_URL ?>/cursos/index.php">SisCap</a>
    </div>
    <nav class="main-nav">
        <a href="<?= BASE_URL ?>/cursos/index.php">Cursos</a>
        <a href="<?= BASE_URL ?>/profesores/index.php">Profesores</a>
        <a href="<?= BASE_URL ?>/paquetes/index.php">Paquetes</a>

        <?php if ($usuarioSesion): ?>
            <?php if ($usuarioSesion['rol'] === 'profesor'): ?>
                <a href="<?= BASE_URL ?>/panel/profesor.php">Mi panel</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/panel/estudiante.php">Mi panel</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/auth/logout.php" class="btn-link">Salir</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/auth/login.php" class="btn-link">Ingresar</a>
            <a href="<?= BASE_URL ?>/usuarios/registro.php" class="btn-primary">Registrarme</a>
        <?php endif; ?>
    </nav>
</header>

<main class="page-content">
