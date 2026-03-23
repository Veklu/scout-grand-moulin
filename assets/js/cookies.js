/**
 * Cookie Consent — Loi 25 du Québec
 */
(function() {
  var banner = document.getElementById('cookieBanner');
  if (!banner) return;
  if (!localStorage.getItem('scout_gm_cookies')) {
    banner.style.display = 'block';
  }
})();

function acceptCookies() {
  localStorage.setItem('scout_gm_cookies', 'all');
  document.getElementById('cookieBanner').style.display = 'none';
}
function necessaryCookies() {
  localStorage.setItem('scout_gm_cookies', 'necessary');
  document.getElementById('cookieBanner').style.display = 'none';
}
function customizeCookies() { necessaryCookies(); }
