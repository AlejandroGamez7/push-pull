// Botón de catálogo (simulación)
document.getElementById("catalogoBtn").addEventListener("click", () => {
  alert("El catálogo estará disponible próximamente 🎶");
});

// Sidebar toggle
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const menuToggle = document.getElementById("menuToggle");

menuToggle.addEventListener("click", () => {
  sidebar.classList.toggle("closed");
  mainContent.classList.toggle("expanded");
});

// Animaciones al hacer scroll
const elements = document.querySelectorAll('.fade-in, .slide-up, .slide-in-right, .about, .vinyl');

const showOnScroll = () => {
  const triggerBottom = window.innerHeight * 0.85;
  elements.forEach(el => {
    const boxTop = el.getBoundingClientRect().top;
    if (boxTop < triggerBottom) el.classList.add('show');
  });
};

window.addEventListener('scroll', showOnScroll);
window.addEventListener('load', showOnScroll);
