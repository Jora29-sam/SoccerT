document.addEventListener("DOMContentLoaded", () => {
  const leadModal = document.getElementById("leadModal");
  const closeLead = document.getElementById("closeLead");
  const leadForm = document.getElementById("leadForm");
  const leadMsg = document.getElementById("leadMsg");

  // Mostrar el modal al cargar
  if (leadModal) {
    leadModal.style.display = "block";
  }

  // Cerrar modal lead
  if (closeLead) {
    closeLead.onclick = () => {
      leadModal.style.display = "none";
    };
  }

  // Enviar formulario
  if (leadForm) {
    leadForm.onsubmit = (e) => {
      e.preventDefault();

      const nombre = leadForm.nombre.value;
      const correo = leadForm.correo.value;
      const telefono = leadForm.telefono.value;

      const params = new URLSearchParams();
      params.append("nombre", nombre);
      params.append("correo", correo);
      params.append("telefono", telefono);

      fetch('enviar_catalogo.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
      })
      .then(res => res.text())
      .then(msg => {
        leadMsg.textContent = msg;

        setTimeout(() => {
          if (msg.toLowerCase().includes('gracias')) {
            setTimeout(() => {
              leadModal.style.display = "none";
            }, 8000); 
          } else {
            leadMsg.style.color = "red";
          }
        }, 500);
      });
    };
  }

  // Modal de error de login
    const cartIcon = document.getElementById("cartIcon");
    const loginModal = document.getElementById("loginErrorModal");
    const closeBtn = loginModal ? loginModal.querySelector(".close") : null;

    if (cartIcon && loginModal) {
        cartIcon.addEventListener("click", (e) => {
            if (!isLoggedIn) {
                e.preventDefault();  // evita ir a resumen.php
                loginModal.style.display = "block";
            }
            // si está logueado, deja ir a resumen.php normalmente
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            loginModal.style.display = "none";
        });
    }
});
