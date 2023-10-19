<?php
session_start(); // Iniciar la sesión

$videojuego=$_SESSION['videojuego'];
$plataforma=$_SESSION['plataforma'];

echo '<div style="float: left; width: 50%;">';
echo '<h2>Resultados de ' . $videojuego . ' ' . $plataforma . ' en Google Shopping:</h2>';

if (isset($_SESSION['resultados_google'])) {
    $resultados = $_SESSION['resultados_google'];

    foreach ($resultados as $resultado) {
        echo '<div style="background-color: #f5f5f5; padding: 10px; margin-bottom: 10px;">';
        echo '<h3>Precio: ' . $resultado['precio'] . '</h3>';
        echo '<p>Tienda: ' . $resultado['tienda'] . '</p>';
        echo '</div>';
    }
} else {
    echo 'No se encontraron resultados de Google Shopping.';
}

echo '</div>';

echo '<div style="float: left; width: 50%;">';
echo '<h2>Resultados de ' . $videojuego . ' ' . $plataforma . ' en eBay:</h2>';

if (isset($_SESSION['resultados_ebay'])) {
    $resultados_ebay = $_SESSION['resultados_ebay'];

    if (is_array($resultados_ebay)) {
        foreach ($resultados_ebay as $resultado) {
            $precio = preg_replace('/[^0-9.,]/', '', $resultado);
            if (!empty($precio)) {
                echo '<div style="background-color: #f5f5f5; padding: 10px; margin-bottom: 10px;">';
                echo '<h3>Precio: ' . $precio . '$ </h3>';
                echo '</div>';
            }
        }
    } else {
        echo 'No se encontraron resultados de eBay.';
    }
} else {
    echo 'No se encontraron resultados de eBay.';
}

echo '</div>';

if (isset($_SESSION['fecha_lanzamiento'])) {
    $fechaLanzamiento = $_SESSION['fecha_lanzamiento'];
    echo '<div style="clear: both; margin-top: 20px;">';
    echo '<h2>Fecha de lanzamiento de ' . $videojuego . ' :</h2>'; 
    echo '<div style="background-color: #f5f5f5; padding: 10px; margin-bottom: 10px;">';
    echo '<p style="font-weight: bold; font-size: 16px;">' . $fechaLanzamiento . '</p>';
    echo '</div>';
    echo '</div>';
}

if (isset($_SESSION['desarrollador'])) {
    $desarrollador = $_SESSION['desarrollador'];
    echo '<div style="clear: both; margin-top: 20px;">';
    echo '<h2>Desarrollador de ' . $videojuego . ':</h2>';
    echo '<div style="background-color: #f5f5f5; padding: 10px; margin-bottom: 10px;">';
    echo '<p style="font-weight: bold; font-size: 16px;">' . $desarrollador . '</p>';
    echo '</div>';
    echo '</div>';
}

echo '<div style="clear: both; margin-top: 20px;">';
echo '<a href="index.php" style="text-decoration: none; background-color: #f5f5f5; padding: 10px 20px; font-weight: bold;">Buscar otro videojuego</a>';
echo '</div>';

?>
