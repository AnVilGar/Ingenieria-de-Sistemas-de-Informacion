<?php
require 'PHP Simple HTML DOM Parser/simple_html_dom.php';
session_start(); // Iniciar la sesión
if (isset($_POST['nombre-videojuego'])) {
    $nombreVideojuego = $_POST['nombre-videojuego'];
    $nombreVideojuegoCodificado = urlencode($nombreVideojuego);
	$plataforma = urlencode($_POST['plataforma']);
    $url = "https://www.google.com/search?q=$nombreVideojuegoCodificado+$plataforma&hl=es&source=lnms&tbm=shop&sa=X&ved=2ahUKEwjw382glMf-AhUFY8AKHXDeB6UQ_AUoAXoECAMQAw&gl=es&biw=1536&bih=703&dpr=1.25";
	$url1="https://www.ebay.com/sch/i.html?_from=R40&_trksid=p2380057.m570.l1313&_nkw=$nombreVideojuegoCodificado+$plataforma&_sacat=0";
	$api_key = 'f7109addcce544f38e9c911c96ec54fe';
	//Primer web scraping
	$html = file_get_html($url);

	// Verificar si se pudo obtener el contenido HTML
	if ($html) {
		// Crear un array para almacenar los resultados
		$resultados = array();

		// Buscar todos los elementos que contienen el precio y la tienda
		$elements = $html->find('div.dD8iuc');

		// Verificar si se encontraron elementos
		if ($elements) {
			$contador1=0;
			// Recorrer todos los elementos encontrados
			foreach ($elements as $element) {
				// Obtener el precio y la tienda de cada elemento
				$price_element = $element->find('span.HRLxBb', 0);
				$store_element = $element->plaintext;

				// Verificar si se encontró el precio
				if ($price_element) {
					$price = $price_element->plaintext;

					// Extraer la tienda del texto completo
					$store = str_replace($price, '', $store_element);

					// Agregar el resultado al array
					$resultados[] = array('precio' => $price, 'tienda' => $store);
					$contador1=$contador1+1;
					if ($contador1 == 10) {
						break; // Detener el bucle después de 10 resultados
					}
				}
			}
		}
	} else {
		echo "No se pudo obtener el contenido HTML de la URL.\n";
	}

	//Segundo web scraping
	$curl = curl_init($url1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($curl);
	curl_close($curl);

	if ($result) {
		$html1 = new simple_html_dom();
		$html1->load($result);
	
		$price_elements1 = $html1->find('span.ITALIC');
	
		if ($price_elements1) {
			$resultados_ebay = array();
	
			$contador=0;
			foreach ($price_elements1 as $price_element1) {
				$price1 = $price_element1->plaintext;
				$resultados_ebay[] = $price1;
				$contador=$contador+1;
				if ($contador == 20) {
					break; // Detener el bucle después de 10 resultados
				}
			}
			$_SESSION['resultados_ebay'] = $resultados_ebay;
	
		} else {
			echo "No se encontró el precio del videojuego en Ebay.<br>";
		}
	} else {
		echo "No se pudo obtener el contenido HTML de la URL.<br>";
	}


	//API RAWG Video Game Database
	$url = "https://api.rawg.io/api/games?key=$api_key&search=$nombreVideojuegoCodificado";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    // Verificar si se encontraron resultados
    if (isset($data['results']) && !empty($data['results'])) {
        // Obtener el primer resultado de la lista
        $juego = $data['results'][0];
        $juegoId = $juego['id'];
		$metascore = isset($data['metacritic']) ? $data['metacritic'] : 'No disponible';

        // Realizar la solicitud a la API RAWG para obtener los detalles del juego por ID
        $detalleUrl = "https://api.rawg.io/api/games/$juegoId?key=$api_key";
        $detalleCh = curl_init($detalleUrl);
        curl_setopt($detalleCh, CURLOPT_RETURNTRANSFER, true);
        $detalleResponse = curl_exec($detalleCh);
        curl_close($detalleCh);

        // Decodificar la respuesta JSON
        $detalleData = json_decode($detalleResponse, true);

        // Verificar si se encontraron resultados
        if (isset($detalleData)) {
            echo 'Fecha de lanzamiento: ' . $detalleData['released'] . '<br>';
        } else {
            echo 'No se encontraron resultados para el videojuego ingresado.';
        }
    } else {
        echo 'No se encontraron resultados para el videojuego ingresado.';
    }

	// Almacenar los resultados en variables de sesión
	$_SESSION['resultados_google'] = $resultados;
    $_SESSION['resultados_ebay'] = $resultados_ebay;
	$_SESSION['fecha_lanzamiento'] = $detalleData['released'];
	$_SESSION['desarrollador']= $detalleData['developers'][0]['name'];
	$_SESSION['videojuego']= $nombreVideojuego;
	$_SESSION['plataforma']=$plataforma;
	header('Location: mostrar_resultados.php');


}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>VGDIFF</title>
	<link rel="stylesheet" href="index.css">
</head>
<body>
	<h1>VGDIFF</h1>
	<h2>Comparador de precios de videojuegos</h2>
	
	<form method="POST" action="index.php">
		<label>Buscar videojuego:</label>
		<input type="text" name="nombre-videojuego" placeholder="Introduzca el nombre del videojuego">
		<input type="text" name="plataforma" placeholder="Introduzca la plataforma">
		<input type="submit" value="Buscar">
	</form>
</body>
</html>
