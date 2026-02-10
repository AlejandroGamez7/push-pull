<?php
// include 'conexion.php';

// Aquí irá la lógica para procesar el formulario cuando se envíe
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $nombre = $_POST['nombre'];
//     $ciudad = $_POST['ciudad'];
//     $comentario = $_POST['comentario'];
//     $vinilo_id = $_POST['vinilo_id'];
//     
//     // Insertar en base de datos
//     // $stmt = $conn->prepare("INSERT INTO opiniones (idVinilo, nombre, ciudad, comentario) VALUES (?, ?, ?, ?)");
//     // $stmt->bind_param("sssi", $vinilo_id, $nombre, $ciudad, $comentario);
//     // $stmt->execute();
//     
//     // Redireccionar o mostrar mensaje de éxito
// }

// Obtener lista de vinilos para el select
// $sql = "SELECT ID, NOMBRE, ARTISTA FROM vinilos WHERE VISIBLE = 1 ORDER BY NOMBRE ASC";
// $vinilos_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escribir Reseña - Retrogroove</title>
  <link rel="stylesheet" href="./styles.css">
  <link rel="icon" type="./image/png" href="./img/favicon_o.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <style>
    /* Estilos específicos para el formulario */
    .form-container {
      max-width: 700px;
      margin: 80px auto;
      padding: 40px;
      background: rgba(26, 26, 26, 0.8);
      border-radius: 15px;
      box-shadow: 0 0 30px rgba(255, 118, 26, 0.2);
    }

    .form-container h2 {
      color: #ff761a;
      font-family: "Montserrat", sans-serif;
      font-size: 2.5rem;
      margin-bottom: 10px;
      text-align: center;
    }

    .form-container .subtitle {
      color: #d9d9d9;
      text-align: center;
      margin-bottom: 40px;
      font-size: 1rem;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      color: #d9d9d9;
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 15px;
      background: rgba(13, 13, 13, 0.6);
      border: 1px solid #333;
      border-radius: 8px;
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #ff761a;
      box-shadow: 0 0 10px rgba(255, 118, 26, 0.3);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 150px;
      line-height: 1.6;
    }

    .form-group select {
      cursor: pointer;
      color: #1a1a1a;
    }

    .form-group select option {
      background: #2a2a2a;
      color: #d9d9d9;
    }

    .button-group {
      display: flex;
      gap: 15px;
      margin-top: 35px;
    }

    .submit-button {
      flex: 1;
      background: rgba(255, 118, 26, 0.22);
      color: #ff761a;
      border: 1px solid #ff761a;
      font-weight: bold;
      border-radius: 31px;
      padding: 12px 30px;
      font-size: 1rem;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .submit-button:hover {
      background: rgba(255, 118, 26, 0.35);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 118, 26, 0.3);
    }

    .cancel-button {
      flex: 1;
      background: rgba(217, 217, 217, 0.15);
      color: #d9d9d9;
      border: 1px solid #555;
      font-weight: bold;
      border-radius: 31px;
      padding: 12px 30px;
      font-size: 1rem;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 50px;
    }

    .cancel-button:hover {
      background: rgba(217, 217, 217, 0.25);
      
      border-color: #777;
    }

    .required {
      color: #ff761a;
    }

    /* Mensaje de éxito (opcional para cuando implementes el backend) */
    .success-message {
      background: rgba(46, 204, 113, 0.2);
      border: 1px solid #2ecc71;
      color: #2ecc71;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 30px;
      text-align: center;
      display: none;
    }

    .success-message.show {
      display: block;
    }
  </style>
</head>

<body>
  <!-- Menú lateral -->
  <nav class="sidebar" id="sidebar">
    <h2 id="menuToggle">☰</h2>
    <ul>
      <li><a href="https://push-pull-six.vercel.app/#inicio">Inicio</a></li>
      <li><a href="https://push-pull-six.vercel.app/#destacados">Destacados</a></li>
      <li><a href="https://push-pull-six.vercel.app/#nosotros">Sobre nosotros</a></li>
      <li><a href="https://push-pull-six.vercel.app/#footer">Contacto</a></li>
      <li><a href="login.php">Login</a></li>
    </ul>
  </nav>

  <!-- Contenido principal -->
  <div class="main-content" id="mainContent">
    <main class="hero" id="inicio" style="min-height: 40vh;">
      <img src="../img/retrogroovelogo_wo.svg" alt="Logo Retrogroove" class="hero-logo">
      <section class="content fade-in" style="text-align: center;">
        <p class="titulo" style="font-size: 3.5rem;">RESEÑA</p>
        <p class="slogan">Comparte tu opinión sobre nuestros vinilos</p>
      </section>
    </main>

    <!-- Mensaje de éxito (se mostrará cuando el formulario se envíe correctamente) -->
    <div class="success-message" id="successMessage">
      ¡Gracias por tu reseña! Tu opinión ha sido enviada correctamente.
    </div>

    <!-- Formulario -->
    <div class="form-container">
      <h2>Escribe tu reseña</h2>
      <p class="subtitle">Todos los campos son obligatorios</p>

      <form action="formulario.php" method="POST" id="reviewForm">
        
        <!-- Selector de vinilo -->
        <div class="form-group">
          <label for="vinilo_id">
            Selecciona el vinilo <span class="required">*</span>
          </label>
          <select name="vinilo_id" id="vinilo_id" required>
            <option value="">-- Elige un vinilo --</option>
            <!-- Aquí irá el bucle PHP para mostrar los vinilos -->
            <!-- <?php 
            // if ($vinilos_result && $vinilos_result->num_rows > 0) {
            //   while($vinilo = $vinilos_result->fetch_assoc()) {
            //     echo '<option value="' . $vinilo['ID'] . '">' . htmlspecialchars($vinilo['NOMBRE']) . ' - ' . htmlspecialchars($vinilo['ARTISTA']) . '</option>';
            //   }
            // }
            ?> -->
            
            <!-- Ejemplos de opciones (borrar cuando conectes con la BD) -->
            <option value="1">The Dark Side of the Moon - Pink Floyd</option>
            <option value="2">Abbey Road - The Beatles</option>
            <option value="3">Thriller - Michael Jackson</option>
          </select>
        </div>

        <!-- Nombre -->
        <div class="form-group">
          <label for="nombre">
            Tu nombre <span class="required">*</span>
          </label>
          <input 
            type="text" 
            name="nombre" 
            id="nombre" 
            placeholder="Ej: Juan Pérez" 
            required
            maxlength="100"
          >
        </div>

        <!-- Ciudad -->
        <div class="form-group">
          <label for="ciudad">
            Tu ciudad <span class="required">*</span>
          </label>
          <input 
            type="text" 
            name="ciudad" 
            id="ciudad" 
            placeholder="Ej: Valencia" 
            required
            maxlength="100"
          >
        </div>

        <!-- Comentario -->
        <div class="form-group">
          <label for="comentario">
            Tu opinión <span class="required">*</span>
          </label>
          <textarea 
            name="comentario" 
            id="comentario" 
            placeholder="Escribe aquí tu opinión sobre este vinilo..."
            required
            maxlength="1000"
          ></textarea>
        </div>

        <!-- Botones -->
        <div class="button-group">
          <a href="ver_catalogo.php" class="cancel-button">Cancelar</a>
          <button type="submit" class="submit-button">Enviar reseña</button>
        </div>

      </form>
    </div>

    <!-- Footer -->
    <footer id="footer" class="footer">
      <div class="footer-grid">
        <div>
          <h3>Contact</h3>
          <p>(+34) 961 45 28 35<br> info@retrogroove.com</p>
          <div class="social">
            <i><img src="/img/icono_facebook.svg"></i>
            <i><img src="/img/icono_instagram.svg"></i>
            <i><img src="/img/icono_twitter.svg"></i>
            <i><img src="/img/icono_youtube.svg"></i>
          </div>
        </div>
        <div>
          <h3>Horario</h3>
          <p>Lunes–Viernes: 09:30–14:00 &nbsp; 17:00–21:00<br> Sábados: 10:30–14:00</p>
        </div>
        <div>
          <h3>Ubicación</h3>
          <p>Carrer del Mar 12, 46001 València, España</p>
        </div>
      </div>
      <div class="copy"> © 2025 Retrogroove. Todos los derechos reservados. </div>
    </footer>
  </div>

  <script src="/script.js"></script>
  
  <script>
    // Script opcional para mostrar el mensaje de éxito
    // Descomenta cuando tengas el backend funcionando
    /*
    const form = document.getElementById('reviewForm');
    form.addEventListener('submit', function(e) {
      // El formulario se enviará normalmente, pero puedes agregar validaciones aquí
    });
    */
  </script>
</body>

</html>