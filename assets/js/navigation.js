/**
 * Mobile Navigation
 */
document.addEventListener('DOMContentLoaded', function() {
  var toggle = document.querySelector('.nav-toggle');
  var links = document.querySelector('.nav-links');
  if (!toggle || !links) return;
  toggle.addEventListener('click', function() {
    toggle.classList.toggle('active');
    links.classList.toggle('active');
  });
  links.querySelectorAll('a').forEach(function(link) {
    link.addEventListener('click', function() {
      toggle.classList.remove('active');
      links.classList.remove('active');
    });
  });
});
