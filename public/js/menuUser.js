function toggleMenu() {
  const menu = document.getElementById("menuUser");
  menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

// Cerrar menú si se hace clic fuera
document.addEventListener("click", function (event) {
  const menu = document.getElementById("menuUser");
  const icono = document.querySelector(".navegacion_enlace_perfil");

  if (!menu.contains(event.target) && !icono.contains(event.target)) {
    menu.style.display = "none";
  }
});
