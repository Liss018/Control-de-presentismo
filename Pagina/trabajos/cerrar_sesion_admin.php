<?php
session_start(); // Inicia la sesión
session_destroy(); // Destruye la sesión
header("Location: sesion_admi.html"); // Redirigir al inicio de sesión
exit();
?>