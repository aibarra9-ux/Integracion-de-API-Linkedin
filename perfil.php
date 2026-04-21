<?php
// 1. Abrimos la mochila de la sesión
session_start();

// 2. Verificamos si la llave maestra (Access Token) existe
// Si alguien intenta entrar a perfil.php sin haberse logueado, lo regresamos al index
if (!isset($_SESSION['access_token'])) {
    header("Location: index.html");
    exit();
}

$access_token = $_SESSION['access_token'];

// 3. Preparar la petición a la API de LinkedIn (Punto final de UserInfo)
$url = "https://api.linkedin.com/v2/userinfo";

// 4. Usar cURL para ir a pedir los datos a la API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// ¡AQUÍ ESTÁ LA MAGIA! Le mostramos el token a LinkedIn en el encabezado (Header)
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $access_token
));

// 5. Ejecutar y guardar la respuesta
$respuesta = curl_exec($ch);
curl_close($ch);

// Traducir el JSON a un arreglo de PHP
$datos_usuario = json_decode($respuesta, true);

// 6. Extraer los datos a variables fáciles de usar (usamos un avatar por defecto si no tiene foto)
$nombre = isset($datos_usuario['name']) ? $datos_usuario['name'] : 'Usuario Misterioso';
$email = isset($datos_usuario['email']) ? $datos_usuario['email'] : 'Sin correo';
$foto = isset($datos_usuario['picture']) ? $datos_usuario['picture'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - APInkedin</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f3f2ef; margin: 0;}
        .profile-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; width: 300px; }
        .profile-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #0a66c2; margin-bottom: 20px; }
        h2 { margin: 0; color: #333; }
        p { color: #666; margin-bottom: 30px; }
        .btn-logout { background-color: #e02424; color: white; padding: 10px 20px; text-decoration: none; border-radius: 20px; font-weight: bold; }
        .btn-logout:hover { background-color: #c81e1e; }
    </style>
</head>
<body>

    <div class="profile-card">
        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" class="profile-pic">
        <h2><?php echo htmlspecialchars($nombre); ?></h2>
        <p><?php echo htmlspecialchars($email); ?></p>
        
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>

</body>
</html>