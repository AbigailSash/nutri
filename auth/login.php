<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Panel</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../css/global.css" />
  <link rel="stylesheet" href="../css/login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
  <div class="main-wrapper">
    <div class="logo-area">
      <img src="../img/logo5.png" alt="Leila Olmedo Logo" />
    </div>

    <div class="login-wrapper">
      <div class="login-panel">
        <h2>Bienvenido</h2>
        <img src="../img/logo0.png" alt="Nutrición Icon" class="login-icon" />

        <!-- FORMULARIO corregido -->
        <form action="validar_login.php" method="post">
          <label for="dni">DNI</label>
          <div class="input-group">
            <img src="../img/input.png" class="icon-img" alt="User Icon" />
            <input type="text" id="dni" name="dni" placeholder="Ingresa tu DNI" required>
          </div>

          <label for="contrasena">Contraseña</label>
          <div class="input-group">
            <img src="../img/input.png" class="icon-img" alt="Password Icon" />
            <input type="password" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" required>
            <span class="toggle-icon" onclick="togglePassword()">👁️</span>
          </div>

          <div class="options">
            <label><input type="checkbox"> Recordar</label>
            <a href="recuperar_usuario.php">¿Olvidó su contraseña?</a>
          </div>

          <button type="submit" class="login-btn">Ingresar</button>
        </form>

        <!-- Mensaje de error -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
          <div class="error-msg">
            <p style="color: crimson; text-align:center; margin-top: 1rem;">DNI o contraseña incorrectos.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById("contrasena");
      const icon = document.querySelector(".toggle-icon");
      const type = input.getAttribute("type") === "password" ? "text" : "password";
      input.setAttribute("type", type);
      icon.textContent = type === "text" ? "🙈" : "👁️";
    }
  </script>
</body>
</html>
