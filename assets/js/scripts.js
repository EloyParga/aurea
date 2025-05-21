
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-agregar').forEach(boton => {
        boton.addEventListener('click', function () {
            const productoId = this.getAttribute('data-id');
            const cantidad = 1; // puedes hacer esto dinámico con un input si quieres

            fetch('/aurea/functions/agregar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `producto_id=${productoId}&cantidad=${cantidad}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Producto agregado al carrito");
                    // opcional: actualizar contador
                } else {
                    alert(data.error || "Error al agregar producto");
                }
            })
            .catch(err => console.error("Error en la solicitud AJAX", err));
        });
    });
});
