
function añadirACarrito(nombre, precio, imagen) {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.push({ nombre, precio, imagen });
    localStorage.setItem("carrito", JSON.stringify(carrito));
    actualizarContadorCarrito();
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
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    let suma = 0;
    lista.innerHTML = "";

    carrito.forEach((producto, index) => {
        const div = document.createElement("div");
        div.classList.add("game-card");
        div.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}">
            <h3>${producto.nombre}</h3>
            <p>${producto.precio.toFixed(2)} €</p>
            <button class="btn_enviar" onclick="eliminarDelCarrito(${index})">Eliminar</button>
        `;
        lista.appendChild(div);
        suma += producto.precio;
    });

    total.textContent = "Total: " + suma.toFixed(2) + " €";
}

function eliminarDelCarrito(index) {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.splice(index, 1); // elimina el producto en esa posición
    localStorage.setItem("carrito", JSON.stringify(carrito));
    mostrarCesta(); // recarga la vista
    actualizarContadorCarrito(); // actualiza el número del carrito
}



document.addEventListener("DOMContentLoaded", () => {
    actualizarContadorCarrito();
    if (document.getElementById("lista-cesta")) {
        mostrarCesta();
    }
});
