<?php
include 'conexion.php';

// Variables para mensajes
$mensaje = '';
$tipo_mensaje = ''; // 'success' o 'error'

// Si se recibe un ID por GET, lo guardamos para preseleccionar
$id_vinilo_seleccionado = isset($_GET['idvinilo']) ? intval($_GET['idvinilo']) : 0;
$nombre_vinilo_seleccionado = "";

if ($id_vinilo_seleccionado > 0) {
    $sql_v = "SELECT NOMBRE, ARTISTA FROM vinilos WHERE ID = $id_vinilo_seleccionado";
    $res_v = $conn->query($sql_v);
    if ($res_v && $res_v->num_rows > 0) {
        $v = $res_v->fetch_assoc();
        $nombre_vinilo_seleccionado = $v['NOMBRE'] . " - " . $v['ARTISTA'];
    }
}

// Procesa el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $comentario = $conn->real_escape_string($_POST['comentario']);
    $vinilo_id = intval($_POST['vinilo_id']); // Usamos vinilo_id del select

    if ($vinilo_id > 0 && !empty($nombre) && !empty($ciudad) && !empty($comentario)) {
        // Preparar la consulta
        $stmt = $conn->prepare("INSERT INTO opiniones (idVinilo, nombre, ciudad, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $vinilo_id, $nombre, $ciudad, $comentario);
        
        if ($stmt->execute()) {
            $mensaje = "¡Gracias! Tu reseña ha sido guardada correctamente.";
            $tipo_mensaje = "success";
            // Limpiamos los campos para no re-enviar
            $nombre = $ciudad = $comentario = "";
            $id_vinilo_seleccionado = 0; // Opcional: resetear selección
        } else {
            $mensaje = "Error al guardar la reseña: " . $conn->error;
            $tipo_mensaje = "error";
        }
        $stmt->close();
    } else {
        $mensaje = "Por favor, rellena todos los campos obligatorios.";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de vinilos para el selector
$sql = "SELECT ID, NOMBRE, ARTISTA FROM vinilos WHERE VISIBLE = 1 ORDER BY NOMBRE ASC";
$result_vinilos = $conn->query($sql);
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
    /* Estilos específicos para el formulario, manteniendo coherencia con el sitio */
    .form-section {
        padding: 50px 20px;
        display: flex;
        justify-content: center;
    }

    .form-container {
      width: 100%;
      max-width: 700px;
      /* Fondo semi-transparente estilo glassmorphism acorde al sitio */
      background: rgba(26, 26, 26, 0.9);
      border: 1px solid #333;
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
      position: relative;
      overflow: hidden;
    }
    
    /* Pequeño detalle naranja decorativo */
    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #ff761a, #ff4c29);
    }

    .form-container h2 {
      color: #ff761a;
      font-family: "Montserrat", sans-serif;
      font-size: 2rem;
      margin-bottom: 10px;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .form-container .subtitle {
      color: #888;
      text-align: center;
      margin-bottom: 30px;
      font-size: 0.95rem;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      color: #fff;
      font-weight: 500;
      margin-bottom: 8px;
      font-size: 0.95rem;
      font-family: 'Inter', sans-serif;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 14px 16px;
      background: #111;
      border: 1px solid #444;
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
      background: #161616;
      box-shadow: 0 0 0 4px rgba(255, 118, 26, 0.1);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
      line-height: 1.6;
    }

    .form-group select option {
      background: #111;
      color: #fff;
      padding: 10px;
    }

    .button-group {
      display: flex;
      gap: 15px;
      margin-top: 40px;
    }

    .submit-button {
      flex: 2;
      background: #e85d04; 
      color: #fff;
      border: none;
      font-weight: bold; /* Bold */
      border-radius: 4px;
      padding: 10px 20px;
      font-size: 0.9rem; /* Standardized size */
      font-family: 'Segoe UI', sans-serif; /* Standardized font */
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase; /* Uppercase */
      height: auto;
    }

    .submit-button:hover {
      background: #ff6b0a;
      transform: none; /* Removed bounce */
      box-shadow: none;
    }

    .cancel-button {
      flex: 1;
      background: #555;
      color: white;
      border: none;
      font-weight: bold; /* Bold */
      border-radius: 4px;
      padding: 10px 20px;
      font-size: 0.9rem; /* Standardized size */
      font-family: 'Segoe UI', sans-serif; /* Standardized font */
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.2s ease;
      text-transform: uppercase; /* Uppercase */
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      height: auto;
    }

    .cancel-button:hover {
      background: #666;
      color: white;
    }

    .required {
      color: #ff761a;
      margin-left: 3px;
    }

    /* Mensajes de feedback */
    .alert {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 25px;
      text-align: center;
      font-weight: 500;
      animation: fadeIn 0.5s ease;
    }

    .alert-success {
      background: rgba(46, 204, 113, 0.15);
      border: 1px solid #2ecc71;
      color: #2ecc71;
    }

    .alert-error {
      background: rgba(231, 76, 60, 0.15);
      border: 1px solid #e74c3c;
      color: #e74c3c;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
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
    <main class="hero" id="inicio" style="min-height: 40vh; align-items: center; padding-top: 100px;">
      <img src="img/retrogroovelogo_wo.svg" alt="Logo Retrogroove" class="hero-logo">
      <section class="content fade-in" style="text-align: center;">
        <p class="titulo" style="font-size: 3rem; margin-bottom: 0;">TU OPINIÓN</p>
        <p class="slogan" style="color: #888; font-weight: normal;">Ayuda a otros coleccionistas a elegir su próximo vinilo</p>
      </section>
    </main>

    <div class="form-section">
        <div class="form-container">
        
          <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
                <?php if($tipo_mensaje == 'success'): ?>
                    <div style="margin-top:10px;">
                        <a href="ver_catalogo.php" style="color: inherit; text-decoration: underline;">Volver al catálogo</a>
                    </div>
                <?php endif; ?>
            </div>
          <?php endif; ?>
    
          <?php if(empty($mensaje) || $tipo_mensaje == 'error'): ?>
          
          <h2>Nueva Reseña</h2>
          <?php if($nombre_vinilo_seleccionado != ""): ?>
              <p class="subtitle" style="color: #ff761a; font-weight: bold; margin-top: -5px;"><?php echo htmlspecialchars($nombre_vinilo_seleccionado); ?></p>
          <?php else: ?>
              <p class="subtitle">Comparte tu experiencia con nosotros</p>
          <?php endif; ?>
    
          <form action="formulario.php" method="POST" id="reviewForm">
            
            <!-- Selector de vinilo -->
            <div class="form-group">
              <label for="vinilo_id">
                Vinilo que vas a reseñar <span class="required">*</span>
              </label>
              <select name="vinilo_id" id="vinilo_id" required>
                <option value="">Selecciona un álbum...</option>
                <?php 
                if ($result_vinilos && $result_vinilos->num_rows > 0) {
                  while($vinilo = $result_vinilos->fetch_assoc()) {
                    $selected = ($id_vinilo_seleccionado == $vinilo['ID']) ? 'selected' : '';
                    echo '<option value="' . $vinilo['ID'] . '" ' . $selected . '>' . htmlspecialchars($vinilo['NOMBRE']) . ' - ' . htmlspecialchars($vinilo['ARTISTA']) . '</option>';
                  }
                }
                ?>
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
                placeholder="Ej: Alex Turner" 
                required
                maxlength="100"
                value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>"
              >
            </div>
    
            <!-- Ciudad -->
            <div class="form-group">
              <label for="ciudad">
                Ciudad <span class="required">*</span>
              </label>
              <input 
                type="text" 
                name="ciudad" 
                id="ciudad" 
                placeholder="Ej: Madrid" 
                required
                maxlength="100"
                value="<?php echo isset($_POST['ciudad']) ? htmlspecialchars($_POST['ciudad']) : ''; ?>"
              >
            </div>
    
            <!-- Comentario -->
            <div class="form-group">
              <label for="comentario">
                Tu Reseña <span class="required">*</span>
              </label>
              <textarea 
                name="comentario" 
                id="comentario" 
                placeholder="Cuéntanos qué te pareció el disco, el estado del envío, la calidad del sonido..."
                required
                maxlength="1000"
              ><?php echo isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : ''; ?></textarea>
            </div>
    
            <!-- Botones -->
            <div class="button-group">
              <a href="ver_catalogo.php" class="cancel-button">Cancelar</a>
              <button type="submit" class="submit-button">Publicar Reseña</button>
            </div>
    
          </form>
          
          <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footer" class="footer">
      <div class="footer-grid">
        <div>
          <h3>Contact</h3>
          <p>(+34) 961 45 28 35<br> info@retrogroove.com</p>
          <div class="social">
            <i><img src="img/icono_facebook.svg"></i>
            <i><img src="img/icono_instagram.svg"></i>
            <i><img src="img/icono_twitter.svg"></i>
            <i><img src="img/icono_youtube.svg"></i>
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

  <script src="script.js"></script>
</body>
</html>