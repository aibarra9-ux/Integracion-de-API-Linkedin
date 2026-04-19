<?php
// Iniciar sesión en PHP para guardar el token temporalmente y pasárselo a tu compañera
session_start();

// 1. TUS CREDENCIALES
$client_id = '78ohjqhtudbxc8';
$client_secret = 'SECRETO_OCULTO_PARA_GITHUB'; // Reemplaza esto con el secreto del portal
$redirect_uri = 'http://localhost/apinkedin/callback.php';

// 2. RECIBIR EL CÓDIGO DE LINKEDIN
if (isset($_GET['code'])) {
    $auth_code = $_GET['code'];

    // 3. PREPARAR LA PETICIÓN AL SERVIDOR DE LINKEDIN PARA PEDIR EL TOKEN
    $url = "https://www.linkedin.com/oauth/v2/accessToken";
    $datos_post = array(
        'grant_type' => 'authorization_code',
        'code' => $auth_code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    );

    // Usamos cURL para hacer una petición POST oculta desde PHP
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos_post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

    // 4. EJECUTAR Y OBTENER RESPUESTA
    $respuesta = curl_exec($ch);
    curl_close($ch);

    // Convertir el JSON de respuesta a un arreglo de PHP
    $datos_token = json_decode($respuesta, true);

    // 5. VERIFICAR SI OBTUVIMOS EL TOKEN
    if (isset($datos_token['access_token'])) {
        // ¡ÉXITO! Guardamos el token en la sesión.
        $_SESSION['access_token'] = $datos_token['access_token'];
        
        // Redirigimos a la página de éxito (que hará tu compañera)
        header("Location: perfil.php");
        exit();
    } else {
        echo "Error al obtener el token: ";
        print_r($datos_token);
    }

} elseif (isset($_GET['error'])) {
    echo "El usuario canceló el inicio de sesión: " . htmlspecialchars($_GET['error_description']);
} else {
    echo "No se recibió ningún código de autorización.";
}
?>