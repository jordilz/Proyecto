document.getElementById("contactoForm").addEventListener("submit", function(event) {
    event.preventDefault(); 
  
    let isValid = true;
  
    let nombre = document.getElementById("nombre");
    let errorNombre = document.getElementById("errorNombre");
    if (nombre.value.length < 3) {
      errorNombre.textContent = "El nombre debe tener al menos 3 caracteres.";
      isValid = false;
    } else {
      errorNombre.textContent = "";
    }
  
    let correo = document.getElementById("correo");
    let errorCorreo = document.getElementById("errorCorreo");
    let correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correoRegex.test(correo.value)) {
      errorCorreo.textContent = "Ingrese un correo electrónico válido.";
      isValid = false;
    } else {
      errorCorreo.textContent = "";
    }
  
    let mensaje = document.getElementById("mensaje");
    let errorMensaje = document.getElementById("errorMensaje");
    if (mensaje.value.length < 10) {
      errorMensaje.textContent = "El mensaje debe tener al menos 10 caracteres.";
      isValid = false;
    } else {
      errorMensaje.textContent = "";
    }
  
    let confirmacion = document.getElementById("confirmacion");
    if (isValid) {
      confirmacion.textContent = "Gracias por contactar con nosotros, recibirás respuesta en breve.";
      confirmacion.style.display = "block"; 
      nombre.value = "";
      correo.value = "";
      mensaje.value = "";
    } else {
      confirmacion.style.display = "none"; 
    }
  });
  