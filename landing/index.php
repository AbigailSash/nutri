<!DOCTYPE html>
<html lang="es">
<!-- HEAD: Contiene metadatos y recursos necesarios -->
<head>
  <!-- Configuración básica del documento -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriSalud</title>

  <!-- Hojas de estilo -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/landing.css">
 <!-- ✅ Estilos personalizados -->
  <link rel="icon" href="img/logo-def.png" type="image/png"> <!-- Favicon -->
</head>


<body>

<!-- ========== NAVBAR/HEADER ========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <!-- Logo y nombre de la marca -->
    <a class="navbar-brand d-flex align-items-center" href="#">
    <img src="../img/logo-def.png" alt="Logo" width="40" class="me-2">
      <strong>NutriSalud</strong>
    </a>
    
    <!-- Botón para menú en móviles -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Menú principal -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
        <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimonios">Testimonios</a></li>
        <!-- Botón CTA principal -->
        <li class="nav-item"><a class="btn btn-success" href="../auth/login.php">Login</a></li>
        <!-- Toggle modo oscuro -->
        <li class="nav-item ms-3">
          <button id="darkModeToggle" class="btn btn-outline-secondary">
            <img src="img/darkmode.png" alt="Modo oscuro" width="24">
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ========== HERO SECTION (Portada principal) ========== -->
<section class="py-5 text-center bg-light">
  <div class="container">
    <h1 class="display-5 fw-bold">Transforma tu salud con NutriSalud</h1>
    <p class="lead">Planes alimentarios personalizados, seguimiento clínico y cálculos nutricionales en un solo lugar.</p>
    <!-- Botón CTA principal -->
    <!-- <a href="../auth/login.php" class="btn btn-primary btn-lg">Ingreso al Login</a>-->
  </div>
</section>

<!-- ========== SECCIÓN SERVICIOS ========== -->
<section id="servicios" class="py-5">
  <div class="container">
    <h2 class="text-center mb-4">Nuestros Servicios</h2>
    <div class="row text-center">
      <!-- Servicio 1: Evaluación -->
      <div class="col-md-4">
        <img src="img/evaluacion.png" alt="Evaluaciones" width="230">
        <h5 class="mt-3">Evaluación Nutricional</h5>
        <p>Mediciones antropométricas completas y cálculo de indicadores como IMC e ICC.</p>
      </div>
      
      <!-- Servicio 2: Plan alimenticio -->
      <div class="col-md-4">
        <img src="img/plan.png" alt="Plan" width="230">
        <h5 class="mt-3">Plan Alimentario</h5>
        <p>Dietas personalizadas con objetivos claros: salud, rendimiento o bienestar.</p>
      </div>
      
      <!-- Servicio 3: Seguimiento -->
      <div class="col-md-4">
        <img src="img/seguimiento.png" alt="Seguimiento" width="230">
        <h5 class="mt-3">Seguimiento Continuo</h5>
        <p>Control de progreso con gráficas, reportes y sugerencias adaptativas.</p>
      </div>
    </div>
  </div>
</section>

<!-- ========== SECCIÓN BENEFICIOS ========== -->
<section id="beneficios" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4">¿Por qué elegirnos?</h2>
    <!-- Lista de beneficios con iconos -->
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><img src="img/checks.png" width="20" class="me-2">Atención personalizada y profesional</li>
      <li class="list-group-item"><img src="img/checks.png" width="20" class="me-2">Acceso online a tu historial y planes</li>
      <li class="list-group-item"><img src="img/checks.png" width="20" class="me-2">Plataforma segura y fácil de usar</li>
    </ul>
  </div>
</section>

<!-- ========== SECCIÓN NUTRICIONISTA ========== -->
<section class="py-5 bg-white">
  <div class="container d-flex flex-column flex-md-row align-items-center gap-4">
    <!-- Foto de la profesional -->
    <img src="img/image.png" alt="Nutricionista" class="rounded shadow" style="max-width: 200px;">
    <!-- Información profesional -->
    <div>
      <h3 class="mb-2">Lic. Olmedo Leila</h3>
      <p class="mb-1">Licenciada en Nutrición (M.N. 310)</p>
      <p>Especialista en educación alimentaria, obesidad, deporte y nutrición infantil.</p>
    </div>
  </div>
</section>

<!-- ========== SECCIÓN TESTIMONIOS ========== -->
<section id="testimonios" class="py-5">
  <div class="container">
    <h2 class="text-center mb-4">Testimonios de Pacientes</h2>
    <div class="row">
      <!-- Testimonio 1 -->
      <div class="col-md-6">
        <blockquote class="blockquote">
          <p>"Gracias a NutriSalud pude mejorar mis hábitos alimenticios..."</p>
          <footer class="blockquote-footer">Juan R., paciente desde 2023</footer>
        </blockquote>
      </div>
      <!-- Testimonio 2 -->
      <div class="col-md-6">
        <blockquote class="blockquote">
          <p>"El seguimiento es excelente. Me siento acompañada..."</p>
          <footer class="blockquote-footer">Lucía M., paciente desde 2024</footer>
        </blockquote>
      </div>
    </div>
  </div>
</section>

<!-- ========== SECCIÓN PREGUNTAS FRECUENTES ========== -->
<section class="py-5 bg-light" id="faq">
  <div class="container">
    <h2 class="text-center mb-4">Preguntas Frecuentes</h2>
    <!-- Acordeón de preguntas -->
    <div class="accordion" id="accordionFAQ">
      <!-- Pregunta 1 -->
      <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">¿Cómo puedo agendar una cita?</button></h2>
        <div id="faq1" class="accordion-collapse collapse show">
          <div class="accordion-body">Simplemente hacé clic en "Agendar Cita"...</div>
        </div>
      </div>
      <!-- Pregunta 2 -->
      <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">¿La primera consulta incluye plan alimentario?</button></h2>
        <div id="faq2" class="accordion-collapse collapse">
          <div class="accordion-body">Sí, una vez evaluado tu perfil...</div>
        </div>
      </div>
      <!-- Pregunta 3 -->
      <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">¿Qué medios de pago aceptan?</button></h2>
        <div id="faq3" class="accordion-collapse collapse">
          <div class="accordion-body">Efectivo, transferencia y también tarjetas...</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="bg-dark text-white pt-4 pb-2 mt-5">
  <div class="container">
    <div class="row">
      <!-- Columna 1: Descripción -->
      <div class="col-md-4 mb-3">
        <h5>NutriSalud</h5>
        <p>Tu bienestar es nuestra prioridad...</p>
      </div>
      
      <!-- Columna 2: Contacto -->
      <div class="col-md-4 mb-3">
        <h6>Contacto</h6>
        <p>lic.olmedoleila@gmail.com</p>
        <p>+54 370 4 511481</p>
        <p>Formosa, Argentina</p>
      </div>
      
      <!-- Columna 3: Enlaces rápidos -->
      <div class="col-md-4 mb-3">
        <h6>Enlaces rápidos</h6>
        <ul class="list-unstyled">
          <li><a href="#servicios" class="text-white">Servicios</a></li>
          <li><a href="#faq" class="text-white">Preguntas frecuentes</a></li>
          <li><a href="../auth/login.php" class="text-white">Iniciar sesión</a></li>
        </ul>
      </div>
    </div>
    
    <!-- Copyright -->
    <div class="text-center border-top pt-3">
      <p class="mb-0">&copy; 2025 NutriSalud. Todos los derechos reservados.</p>
    </div>
  </div>
</footer>

<!-- ========== BOTÓN FLOTANTE WHATSAPP ========== -->
<a href="https://wa.me/543704511481?text=Hola%20Lic.%20Olmedo%2C%20quiero%20saber%20más%20sobre%20las%20consultas%20nutricionales" class="whatsapp-float" target="_blank">
  <img src="img/whatsapp.png" alt="WhatsApp" width="55">
</a>

<!-- ========== SCRIPTS ========== -->
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para modo oscuro -->
<script>
  const toggle = document.getElementById('darkModeToggle');
  toggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
  });
</script>



</body>
</html>