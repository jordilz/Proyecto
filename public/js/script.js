function añadirACarrito(nombre, precio, imagen) {
    const juego = { nombre, precio, imagen };

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.push(juego);
    localStorage.setItem("carrito", JSON.stringify(carrito));

    actualizarContadorCarrito();
    mostrarToast("Se ha añadido el juego a la cesta");

    if (document.getElementById("lista-cesta")) {
        mostrarCesta(); // Si estás en la vista de la cesta
    }
}

function actualizarContadorCarrito() {
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    const contador = document.getElementById("contador-carrito");
    if (contador) {
        contador.textContent = carrito.length;
    }
}

function mostrarCesta() {
  const lista = document.getElementById("lista-cesta");
  const total = document.getElementById("total-cesta");
  const carrito = JSON.parse(localStorage.getItem("carrito")) || [];

  lista.innerHTML = "";
  let suma = 0;

  if (carrito.length === 0) {
    lista.innerHTML = `
      <p class="mensaje-cesta-vacia">
        Tu cesta está vacía.<br>
        Puedes ver los juegos disponibles en nuestra
        <a href="/Proyecto/index.php?url=tienda/index" class="link-tienda">tienda</a>.
      </p>
    `;
    total.textContent = "Total: 0 €";
    return;
  }

  carrito.forEach((producto, index) => {
    const div = document.createElement("div");
    div.classList.add("cesta-item");

    div.innerHTML = `
      <img src="${producto.imagen}" alt="${producto.nombre}">
      <div class="cesta-item-info">
        <h3>${producto.nombre}</h3>
        <p>${producto.precio.toFixed(2)} €</p>
      </div>
      <button onclick="eliminarDelCarrito(${index})">Eliminar</button>
    `;

    lista.appendChild(div);
    suma += producto.precio;
  });

  total.textContent = "Total: " + suma.toFixed(2) + " €";
}



function eliminarDelCarrito(index) {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.splice(index, 1);
    localStorage.setItem("carrito", JSON.stringify(carrito));
    mostrarCesta();
    actualizarContadorCarrito();
}

function mostrarToast(mensaje) {
    let toast = document.getElementById("toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "toast";
        toast.className = "toast-msg";
        document.body.appendChild(toast);
    }

    toast.textContent = mensaje;
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

function vaciarCesta() {
  localStorage.removeItem("carrito");
  mostrarCesta();
  actualizarContadorCarrito();
  mostrarToast("Has vaciado la cesta.");
}

document.addEventListener("DOMContentLoaded", () => {
    actualizarContadorCarrito();
    if (document.getElementById("lista-cesta")) {
        mostrarCesta();
    }
});
