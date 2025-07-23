<!-- Modal -->
<div class="modal" id="loginModal">
  <div class="modal-content">
    <span class="close" id="closeLogin">&times;</span>

<?php if (empty($usuario)): ?>
    <!-- Login Form -->
    <h2>Iniciar sesión</h2>
    <form method="post">
      <input type="hidden" name="accion" value="login">
      <input type="email" name="correo" placeholder="Correo" required><br>
      <input type="password" name="contrasena" placeholder="Contraseña" required><br>
      <button type="submit">Entrar</button>
      <div class="form-link">
        ¿No tienes cuenta? <a href="#" id="openRegisterFromLogin">Regístrate aquí</a>
      </div>
      <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    </form>
    <!-- Registro Form -->
    <div id="registerForm" style="display: none;">
      <h2>Registrarse</h2>
      <form method="post">
        <input type="hidden" name="accion" value="registro">
        <input type="text" name="nombre" placeholder="Nombre completo" required><br>
        <input type="email" name="correo" placeholder="Correo" required><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br>
        <button type="submit">Registrarse</button>
        <div class="form-link">
          ¿Ya tienes cuenta? <a href="#" id="openLoginFromRegister">Inicia sesión aquí</a>
        </div>
      </form>
    </div>
<?php else: ?>
    <!-- Perfil -->
    <h2>¡Hola, <?php echo htmlspecialchars($usuario["nombre"]); ?>!</h2>

    <div class="perfil-info">
        <img src="static/images/usuario.jpg" alt="Foto de perfil">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario["nombre"]); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario["correo"]); ?></p>
        <p><strong>Miembro desde:</strong> <?php echo date("F Y", strtotime($usuario["fecha_registro"])); ?></p>

        <?php if ($usuario["rol"] === "admin"): ?>
        <p><strong>Rol:</strong> Admin</p>
        <a href="dashboard.php" class="btn-admin">Ir al Dashboard</a>
        <?php endif; ?>

        <form action="logout.php" method="post" class="logout-form">
        <button class="logout-btn" type="submit">Cerrar Sesión</button>
        </form>
    </div>
<?php endif; ?>

  </div>
</div>


<script src="static/js/modal.js"></script>
<script src="static/js/auth.js"></script>
