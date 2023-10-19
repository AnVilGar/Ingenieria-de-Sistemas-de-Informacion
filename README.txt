El archivo index.php contiene todas las herramientas para extraer la información (precio, tienda, fecha de lanzamiento y desarrollador) del videojuego a través de la API "RAWG Video Games Database API" y dos web scraping (Google Shopping y eBay) con la librería PHP Simple HTML DOM Parser y curl. Esos datos se pasarán al archivo mostrar_resultados.php mediante $_SESSION. En la página Google Shopping se extraen los 10 primeros resultados y en eBay los 20 primeros resultados.

El archivo mostrar_resultados.php es el que mostrará por pantalla todos los datos obtenidos por la API y web scraping.

El archivo index.css contiene los estilos de los dos archivos anteriores.

La carpeta PHP Simple HTML DOM Parser contiene todos los archivos de la librería PHP Simple HTML DOM Parser.

