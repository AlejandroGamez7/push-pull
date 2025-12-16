// Botón de catálogo (simulación)
const catalogoBtn = document.getElementById("catalogoBtn");
if (catalogoBtn) {
  catalogoBtn.addEventListener("click", () => {
    window.location.href = "ver_catalogo.php";
  });
}

// Sidebar toggle
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const menuToggle = document.getElementById("menuToggle");

if (menuToggle) {
  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("closed");
    mainContent.classList.toggle("expanded");
  });
}

// Animaciones al hacer scroll
const elements = document.querySelectorAll('.fade-in, .slide-up, .slide-in-right, .about, .vinyl');

const showOnScroll = () => {
  const triggerBottom = window.innerHeight * 0.85;
  const triggerTop = window.innerHeight * 0.1; // Umbral superior para detectar salida de vista

  elements.forEach(el => {
    const boxTop = el.getBoundingClientRect().top;
    const boxBottom = el.getBoundingClientRect().bottom;
    if (boxTop < triggerBottom && boxBottom > triggerTop) {
      el.classList.add('show');
    } else {
      el.classList.remove('show');
    }
  });
};

// Throttle para mejorar rendimiento en scroll intensivo (evita llamadas excesivas)
let ticking = false;
const updateScroll = () => {
  if (!ticking) {
    requestAnimationFrame(() => {
      showOnScroll();
      ticking = false;
    });
    ticking = true;
  }
};

window.addEventListener('scroll', showOnScroll);
window.addEventListener('load', showOnScroll);
