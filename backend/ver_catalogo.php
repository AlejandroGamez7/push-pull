<?php
include 'conexion.php';

// Obtener los vinilos de la base de datos
$sql = "SELECT * FROM vinilos WHERE VISIBLE = 1 ORDER BY ID DESC";
$result = $conn->query($sql);

// Obtener las opiniones de la base de datos
$sql_opiniones = "SELECT opiniones.*, vinilos.NOMBRE as nombre_vinilo 
                  FROM opiniones 
                  LEFT JOIN vinilos ON opiniones.idVinilo = vinilos.ID 
                  ORDER BY opiniones.id DESC";
$result_opiniones = $conn->query($sql_opiniones);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo - Retrogroove</title>
  <link rel="stylesheet" href="/styles.css">
  <link rel="icon" type="/image/png" href="/img/favicon_o.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <style>
    /* --- ESTILOS CARRUSEL DE OPINIONES --- */
    .reviews-carousel-section {
      position: relative;
    }

    .carousel-container {
      position: relative;
      max-width: 1000px;
      margin: 0 auto;
    }

    .carousel-wrapper {
      overflow: hidden;
      border-radius: 12px;
    }

    .carousel-track {
      display: flex;
      transition: transform 0.5s ease-in-out;
      gap: 20px;
    }

    .review-card {
      flex: 0 0 100%;
      background: linear-gradient(135deg, #161616 0%, #1a1a1a 100%);
      border: 1px solid #333;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 8px 32px rgba(232, 93, 4, 0.1);
      display: flex;
      flex-direction: column;
      gap: 25px;
      animation: slideIn 0.5s ease-in-out;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      padding-bottom: 20px;
      border-bottom: 1px solid #333;
    }

    .review-user-info {
      flex: 1;
    }

    .review-name {
      font-size: 1.3rem;
      font-weight: 700;
      color: #ffffff;
      margin: 0;
      margin-bottom: 8px;
      font-family: 'Montserrat', sans-serif;
    }

    .review-city {
      font-size: 0.95rem;
      color: #888;
      margin: 0;
      font-family: 'Inter', sans-serif;
    }

    .review-vinyl-ref {
      text-align: right;
    }

    .vinyl-badge {
      display: inline-block;
      background: rgba(217, 217, 217, 0.22);
      color: #d9d9d9;
      border: 1px solid #D9D9D9;
      font-weight: bold;
      border-radius: 31px;
      padding: 6px 18px;
      font-size: 0.9rem;
      letter-spacing: 1px;
      user-select: none;
    }

    .review-body {
      flex: 1;
    }

    .review-text {
      font-size: 1.05rem;
      color: #ddd;
      line-height: 1.8;
      margin: 0;
      font-style: italic;
      color: #b0b0b0;
      font-family: 'Inter', sans-serif;
    }

    /* Botones de navegación */
    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      border: 2px solid #e85d04;
      background-color: transparent;
      color: #e85d04;
      font-size: 24px;
      cursor: pointer;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      z-index: 10;
    }

    .carousel-btn:hover {
      background-color: #e85d04;
      color: white;
      transform: translateY(-50%) scale(1.1);
    }

    .carousel-btn-prev {
      left: -70px;
    }

    .carousel-btn-next {
      right: -70px;
    }

    /* Indicadores (dots) */
    .carousel-dots {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-top: 30px;
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #444;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .dot.active {
      background-color: #e85d04;
      transform: scale(1.2);
    }

    .dot:hover {
      border-color: #e85d04;
    }

    /* Responsivo */
    @media (max-width: 768px) {
      .carousel-btn-prev {
        left: 10px;
      }

      .carousel-btn-next {
        right: 10px;
      }

      .review-card {
        padding: 25px;
      }

      .review-name {
        font-size: 1.1rem;
      }

      .review-text {
        font-size: 0.95rem;
      }

      .review-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .review-vinyl-ref {
        text-align: left;
        width: 100%;
      }

      .vinyl-badge {
        display: block;
        width: fit-content;
      }
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
    <main class="hero" id="inicio">
      <img src="/img/retrogroovelogo_wo.svg" alt="Logo Retrogroove" class="hero-logo">
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
          <img src="/img/covers/<?php echo htmlspecialchars($vinilo['FOTO']); ?>" alt="<?php echo htmlspecialchars($vinilo['NOMBRE']); ?>">
          <div class="overlay-text">
            <?php echo htmlspecialchars($vinilo['NOMBRE']); ?> de <?php echo htmlspecialchars($vinilo['ARTISTA']); ?><br><br>
            <div class="caracteristicas">
              <?php echo htmlspecialchars($vinilo['DESCRIPCION']); ?><br>
              Año: <?php echo htmlspecialchars($vinilo['AÑO']); ?><br>
            <div class="price-and-review">
              <span class="price-tag"><?php echo htmlspecialchars($vinilo['PRECIO']); ?>€</span>
              <a href="formulario.php?idvinilo=<?php echo $vinilo['ID']; ?>" class="review-button">Reseña</a>
            </div>
        </div> <!-- Cierre caracteristicas -->
      </div> <!-- Cierre overlay-text -->
    </div> <!-- Cierre vinyl -->
            <?php
          }
        } else {
          echo '<p style="text-align: center; color: #888; grid-column: 1/-1;">No hay vinilos disponibles en este momento.</p>';
        }
        ?>
      </div>
    </section>

    <!-- SECCIÓN CARRUSEL DE OPINIONES -->
    <section class="reviews-carousel-section" style="padding: 80px 20px; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);">
      <h2 class="fade-in" style="text-align: center; margin-bottom: 50px;">Opiniones de Nuestros Clientes</h2>
      
      <?php if ($result_opiniones && $result_opiniones->num_rows > 0): ?>
        <div class="carousel-container">
          <div class="carousel-wrapper">
            <div class="carousel-track" id="carouselTrack">
              <?php 
              $reviews = [];
              while($opinion = $result_opiniones->fetch_assoc()) {
                $reviews[] = $opinion;
              }
              foreach($reviews as $opinion): 
              ?>
              <div class="review-card">
                <div class="review-header">
                  <div class="review-user-info">
                    <h3 class="review-name"><?php echo htmlspecialchars($opinion['nombre']); ?></h3>
                    <p class="review-city"> <?php echo htmlspecialchars($opinion['ciudad']); ?></p>
                  </div>
                  <div class="review-vinyl-ref">
                    <span class="vinyl-badge">
                      <?php 
                      if(!empty($opinion['nombre_vinilo'])) {
                        echo htmlspecialchars($opinion['nombre_vinilo']);
                      } else {
                        echo "Vinilo #" . $opinion['idVinilo'];
                      }
                      ?>
                    </span>
                  </div>
                </div>
                <div class="review-body">
                  <p class="review-text">"<?php echo htmlspecialchars($opinion['comentario']); ?>"</p>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <button class="carousel-btn carousel-btn-prev" onclick="moveCarousel(-1)">❮</button>
          <button class="carousel-btn carousel-btn-next" onclick="moveCarousel(1)">❯</button>
          
          <div class="carousel-dots" id="carouselDots">
            <?php for($i = 0; $i < count($reviews); $i++): ?>
              <span class="dot" onclick="currentCarouselItem(<?php echo $i; ?>)"></span>
            <?php endfor; ?>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align: center; color: #888; font-size: 1.1rem;">No hay opiniones registradas aún. ¡Sé el primero en dejar una reseña!</p>
      <?php endif; ?>
    </section>

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
    // --- CARRUSEL DE OPINIONES ---
    let currentIndex = 0;

    function updateCarousel() {
      const track = document.getElementById('carouselTrack');
      const dots = document.querySelectorAll('.dot');
      
      // Calcular el desplazamiento (cada tarjeta ocupa 100% + 20px de gap)
      const offset = -currentIndex * (100 + 2); // 2% por el gap relativizado
      track.style.transform = `translateX(calc(${-currentIndex * 100}% - ${currentIndex * 20}px))`;
      
      // Actualizar punto activo
      dots.forEach((dot, index) => {
        if (index === currentIndex) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
    }

    function moveCarousel(direction) {
      const totalCards = document.querySelectorAll('.review-card').length;
      currentIndex += direction;
      
      // Envolver al inicio o final
      if (currentIndex < 0) {
        currentIndex = totalCards - 1;
      } else if (currentIndex >= totalCards) {
        currentIndex = 0;
      }
      
      updateCarousel();
    }

    function currentCarouselItem(index) {
      currentIndex = index;
      updateCarousel();
    }

    // Inicializar el carrusel
    document.addEventListener('DOMContentLoaded', function() {
      updateCarousel();
      
      // Opcional: Auto-avance cada 6 segundos
      // setInterval(() => moveCarousel(1), 6000);
    });

    // Soporte para teclado
    document.addEventListener('keydown', function(event) {
      if (event.key === 'ArrowLeft') {
        moveCarousel(-1);
      } else if (event.key === 'ArrowRight') {
        moveCarousel(1);
      }
    });
  </script>

</body>

</html>
