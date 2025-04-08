function validarNombre(nombre) {
    return nombre.length >= 3;
}

function validarCorreo(correo) {
    let correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return correoRegex.test(correo);
}

function validarMensaje(mensaje) {
    return mensaje.length >= 10;
}

document.getElementById("contactoForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

    let isValid = true;

    let nombre = document.getElementById("nombre");
    let errorNombre = document.getElementById("errorNombre");
    if (!validarNombre(nombre.value)) {
        errorNombre.textContent = "El nombre debe tener al menos 3 caracteres.";
        isValid = false;
    } else {
        errorNombre.textContent = "";
    }

    let correo = document.getElementById("correo");
    let errorCorreo = document.getElementById("errorCorreo");
    if (!validarCorreo(correo.value)) {
        errorCorreo.textContent = "Ingrese un correo electrónico válido.";
        isValid = false;
    } else {
        errorCorreo.textContent = "";
    }

    let mensaje = document.getElementById("mensaje");
    let errorMensaje = document.getElementById("errorMensaje");
    if (!validarMensaje(mensaje.value)) {
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
