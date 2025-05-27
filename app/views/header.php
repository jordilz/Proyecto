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
        <h1 class="logo_nombre">
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

      <div class="navegacion">
        <a href="#" class="navegacion_enlace">
          <img class="navegacion_enlace_carrito" src="/Proyecto/public/img/icono_carrito_blanco.png" alt="icono carrito">
        </a>
        <a href="#" class="navegacion_enlace">
          <img class="navegacion_enlace_perfil" src="/Proyecto/public/img/icono_usuario.png" alt="icono perfil">
        </a>
      </div>
    </div>
  </div>
</header>
