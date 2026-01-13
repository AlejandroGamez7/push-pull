<?php
include 'conexion.php';

// Obtener los vinilos de la base de datos
$sql = "SELECT * FROM vinilos WHERE VISIBLE = 1 ORDER BY ID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo - Retrogroove</title>
  <link rel="stylesheet" href="../frontend/styles.css">
  <link rel="icon" type="image/png" href="img/favicon_o.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>

<body>
  <!-- Menú lateral -->
  <nav class="sidebar" id="sidebar">
    <h2 id="menuToggle">☰</h2>
    <ul>
      <li><a href="index.html#inicio">Inicio</a></li>
      <li><a href="index.html#destacados">Destacados</a></li>
      <li><a href="index.html#nosotros">Sobre nosotros</a></li>
      <li><a href="index.html#footer">Contacto</a></li>
      <li><a href="login.php">Login</a></li>
    </ul>
  </nav>

  <!-- Contenido principal -->
  <div class="main-content" id="mainContent">
    <main class="hero" id="inicio">
      <img src="img/retrogroovelogo_wo.svg" alt="Logo Retrogroove" class="hero-logo">
      <section class="content fade-in" style="text-align: center;">
        <p class="titulo">CATÁLOGO</p>
        <p class="slogan">Explora nuestra colección de vinilos</p>
      </section>
    </main>

    <section class="featured" style="padding: 80px 20px;">
      <h2 class="fade-in">Catálogo Completo</h2>
      <div class="vinyls">
        <?php 
        if ($result && $result->num_rows > 0) {
          while($vinilo = $result->fetch_assoc()) {
            ?>
        <div class="vinyl slide-up">
          <img src="img/covers/<?php echo htmlspecialchars($vinilo['FOTO']); ?>" alt="<?php echo htmlspecialchars($vinilo['NOMBRE']); ?>">
          <div class="overlay-text">
            <?php echo htmlspecialchars($vinilo['NOMBRE']); ?> de <?php echo htmlspecialchars($vinilo['ARTISTA']); ?><br><br>
            <div class="caracteristicas">
              <?php echo htmlspecialchars($vinilo['DESCRIPCION']); ?><br>
              Año: <?php echo htmlspecialchars($vinilo['AÑO']); ?><br>
            </div>
            <span class="price-tag"><?php echo htmlspecialchars($vinilo['PRECIO']); ?>€</span>
          </div>
        </div>
            <?php
          }
        } else {
          echo '<p style="text-align: center; color: #888; grid-column: 1/-1;">No hay vinilos disponibles en este momento.</p>';
        }
        ?>
      </div>
    </section>

    <!-- Footer -->
    <footer id="footer" class="footer">
      <div class="footer-grid">
        <div>
          <h3>Contact</h3>
          <p>(+34) 961 45 28 35<br> info@retrogroove.com</p>
          <div class="social">
            <i><img src="img/icono_facebook.svg"></i><i><img src="img/icono_instagram.svg"></i>
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

  <script src="../frontend/script.js"></script>
</body>

</html>
