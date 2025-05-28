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

