<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KŌDO NYŪSU</title>
  <link rel="stylesheet" type="text/css" href="/Proyecto/css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
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

  <div class="contacto_formulario">
    <div class="contacto_titulo">Contacta con nosotros:</div>
    <form class="formulario" id="contactoForm" method="POST" action="/Proyecto/public/index.php">
      <ul>
        <li>
          <label class="campo" for="nombre">Nombre</label>
          <input class="formulario_campo" type="text" name="nombre_usuario" id="nombre" required>
          <span class="error" id="errorNombre"></span>
        </li>
        <li>
          <label class="campo" for="correo">Correo Electrónico</label>
          <input class="formulario_campo" type="email" name="correo_usuario" id="correo" required>
          <span class="error" id="errorCorreo"></span>
        </li>
        <li>
          <label class="campo" for="mensaje">Mensaje</label>
          <textarea name="mensaje_usuario" id="mensaje" required></textarea>
          <span class="error" id="errorMensaje"></span>
        </li>
      </ul>
      <button type="submit" class="btn_enviar">Enviar</button>
      <p id="confirmacion">
        <?php
          if (isset($_GET['message'])) {
              echo htmlspecialchars($_GET['message']);
          }
        ?>
      </p>
    </form>
  </div>

  <script src="/Proyecto/public/js/contacto.js"></script>

  <footer class="footer">
    <div class="footer_terminos">
      <p><a href="/Proyecto/app/views/formulario_contacto.php">CONTACTA CON NOSOTROS</a></p>
      <p><a href="/Proyecto/index.php">TERMINOS Y CONDICIONES</a></p>
    </div>
  </footer>
</body>

</html>