<?php
// /SisCap/auth/logout.php
session_start();
session_destroy();
header('Location: ../cursos/index.php');
exit;
