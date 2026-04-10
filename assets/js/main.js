function toggleMenu() {
  const nav = document.getElementById('main-nav');
  const btn = document.getElementById('hamburger');
  nav.classList.toggle('open');
  btn.classList.toggle('open');
}

// Chiudi menu cliccando fuori
document.addEventListener('click', function(e) {
  const nav = document.getElementById('main-nav');
  const btn = document.getElementById('hamburger');
  if (nav && btn && !nav.contains(e.target) && !btn.contains(e.target)) {
    nav.classList.remove('open');
    btn.classList.remove('open');
  }
});