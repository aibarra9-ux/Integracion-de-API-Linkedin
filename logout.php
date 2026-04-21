<?php
// Abrimos la sesión actual
session_start();

// Destruimos todos los datos registrados (borramos la mochila)
session_destroy();

// Redirigimos al usuario de vuelta a la pantalla de inicio
header("Location: index.html");
exit();
?>