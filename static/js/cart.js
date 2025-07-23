document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.form-compra');
    const cartCountBadge = document.querySelector('.cart-count');

    // Crear dinámicamente el mensaje si no existe
    let mensaje = document.querySelector('.mensaje-carrito');
    if (!mensaje) {
        mensaje = document.createElement('div');
        mensaje.className = 'mensaje-carrito';
        mensaje.style.textAlign = 'center';
        mensaje.style.marginTop = '10px';
        form.insertAdjacentElement('afterend', mensaje);
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch('cart.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text();

            if (response.ok) {
                mensaje.textContent = '¡Producto añadido al carrito!';
                mensaje.style.color = 'green';

                // Actualizar contador del carrito
                let currentCount = parseInt(cartCountBadge.textContent) || 0;
                cartCountBadge.textContent = currentCount + 1;

                // Animación del contador
                cartCountBadge.classList.add('updated');
                setTimeout(() => {
                    cartCountBadge.classList.remove('updated');
                }, 400);
            } else {
                mensaje.textContent = result || 'Error al añadir al carrito.';
                mensaje.style.color = 'red';
            }
        } catch (error) {
            mensaje.textContent = 'Error de conexión. Intenta de nuevo.';
            mensaje.style.color = 'red';
            console.error('Error al enviar el formulario:', error);
        }
    });
});
