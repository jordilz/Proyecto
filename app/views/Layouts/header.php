<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KŌDO NYŪSU</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Goolge Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Public+Sans&display=swap" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="/Proyecto/css/style.css">

</head>

<body>

  <header class="header">
    <div class="contenedor">
      <div class="barra">
        <a class="logo" href="/Proyecto/index.php">
          <h1 class="logo_nombre fw-bold">
            <span><img class="logo_img" src="/Proyecto/public/img/icono_logo.png" alt="logo pagina"></span>KŌDO NYŪSU
          </h1>
        </a>

        <div class="buscador">
          <form class="buscador_formulario" method="get">
            <label for="buscador_input" class="visually-hidden">Buscar</label>
            <input type="search" name="buscador" id="buscador_input" placeholder="Buscar">
            <button type="submit" aria-label="Buscar"></button>
          </form>
        </div>

      <div class="navegacion iconos-header">
        <a href="index.php?url=Tienda/cesta" class="icono-header">
          <img src="public/img/icono_carrito_blanco.png" alt="Carrito" class="icono-img">
          <span id="contador-carrito">0</span>
        </a>
        <a href="#" class="icono-header">
          <img src="/Proyecto/public/img/icono_usuario.png" alt="Usuario" class="icono-img">
        </a>
      </div>

        <!-- Checkbox oculto -->
        <input type="checkbox" class="menu-toggle-checkbox" id="menu-toggle" />

        <!-- Botón hamburguesa -->
        <label for="menu-toggle" class="menu-toggle">&#9776;</label>

        <!-- Menú desplegable -->
        <div id="nav-principal" class="navegacion_principal">
          <nav class="navegacion_menu">
            <ul class="navegacion_lista">
              <li class="navegacion_item fw-bold"><a href="/Proyecto/index.php" class="navegacion_enlace">INICIO</a>
              </li>
              <li class="navegacion_item fw-bold"><a href="index.php?url=tienda/index" class="navegacion_enlace">TIENDA</a>
              </li>
              <li class="navegacion_item fw-bold"><a href="index.php?url=LoMasVisto/index" class="navegacion_enlace">LO MÁS
                  VISTO</a></li>
              <li class="navegacion_item fw-bold"><a href="index.php?url=Actualidad/index"
                  class="navegacion_enlace">ACTUALIDAD</a></li>
            </ul>
          </nav>
        </div>



      </div>

  </header>

<script src="public/js/script.js"></script>



