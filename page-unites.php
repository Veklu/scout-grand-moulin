<?php
/**
 * Template Name: Nos unités
 * @package Scout_GM
 */
get_header(); ?>

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

:root {
  /* Scouts du Canada official brand palette */
  --scout-green: #007748;      /* Primary — Pantone 3415 C */
  --scout-green-dark: #005a36;
  --scout-green-deep: #003d24;
  --scout-blue: #0065cc;        /* Secondary - Accent */
  --scout-blue-light: #e8f4ff;
  --scout-blue-dark: #004da0;
  --scout-gold: #ffb400;        /* Secondary — dark bg only for text */
  --scout-gold-dark: #b37e00;   /* Gold text on light backgrounds (AA compliant) */
  --scout-navy: #002d5a;        /* Secondary */
  --scout-pink: #ff73be;        /* Accent */
  --scout-purple: #8237ff;      /* Accent */
  --scout-orange: #ff4637;      /* Accent */
  --scout-gray: #b2b2b2;
  
  /* Functional */
  --white: #ffffff;
  --cream: #f9f8f5;
  --warm-white: #fffdf8;
  --text: #1a1a16;
  --text-soft: #3a3a36;
  --text-muted: #6a6a62;
  --border: #e0ddd4;
  --danger: #c0392b;
  
  --shadow-sm: 0 1px 3px rgba(0,119,72,0.06);
  --shadow-md: 0 4px 16px rgba(0,119,72,0.08);
  --shadow-lg: 0 12px 40px rgba(0,119,72,0.1);
  --radius: 12px;
  --radius-lg: 20px;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Poppins', system-ui, -apple-system, sans-serif;
  color: var(--text);
  background: var(--warm-white);
  line-height: 1.65;
  -webkit-font-smoothing: antialiased;
}

a { color: var(--scout-blue); text-decoration: none; transition: color 0.2s; }
a:hover { color: var(--scout-blue-dark); }
img { max-width: 100%; height: auto; }

/* ═══════════ COOKIE BANNER (Loi 25) ═══════════ */
.cookie-banner {
  position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999;
  background: rgba(0, 61, 36, 0.97);
  backdrop-filter: blur(20px);
  padding: 24px;
  border-top: 3px solid var(--scout-gold);
  animation: slideUp 0.5s ease-out;
}
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
.cookie-inner { max-width: 1100px; margin: 0 auto; display: flex; align-items: flex-start; gap: 24px; }
.cookie-text { flex: 1; color: #e0e0d8; font-size: 0.85rem; line-height: 1.65; }
.cookie-text strong { color: var(--scout-gold); display: block; font-size: 0.95rem; margin-bottom: 6px; }
.cookie-text a { color: var(--scout-gold); text-decoration: underline; text-underline-offset: 2px; }
.cookie-actions { display: flex; flex-direction: column; gap: 8px; flex-shrink: 0; }
.cookie-actions button {
  padding: 10px 22px; border-radius: 8px; font-family: 'Poppins', sans-serif;
  font-weight: 600; font-size: 0.82rem; cursor: pointer; border: none; transition: all 0.2s; white-space: nowrap;
}
.btn-accept { background: var(--scout-gold); color: var(--scout-green-deep); }
.btn-accept:hover { background: #ffc233; }
.btn-necessary { background: transparent; color: #e0e0d8; border: 1.5px solid rgba(255,255,255,0.25) !important; }
.btn-necessary:hover { border-color: rgba(255,255,255,0.5) !important; }
.btn-customize { background: transparent; color: #8cc4ff; text-decoration: underline; font-size: 0.78rem; padding: 4px 0; }

/* ═══════════ NAVIGATION ═══════════ */
nav {
  position: sticky; top: 0; z-index: 1000;
  background: rgba(255,253,248,0.92);
  backdrop-filter: blur(16px);
  border-bottom: 2px solid var(--scout-blue);
  padding: 0 24px;
}
.nav-inner {
  max-width: 1200px; margin: 0 auto; display: flex;
  align-items: center; justify-content: space-between; height: 72px;
}
.nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; color: var(--scout-green); }
.nav-logo img { height: 50px; width: auto; }
.nav-logo-text { font-weight: 600; font-size: 0.95rem; line-height: 1.2; }
.nav-logo-text span { font-weight: 400; font-size: 0.75rem; color: var(--text-muted); display: block; }
.nav-links { display: flex; gap: 4px; list-style: none; }
.nav-links a {
  text-decoration: none; color: var(--text-soft); font-size: 0.85rem; font-weight: 500;
  padding: 8px 14px; border-radius: 8px; transition: all 0.2s;
}
.nav-links a:hover { background: rgba(0,119,72,0.06); color: var(--scout-green); }
.nav-links a.active { background: rgba(0,119,72,0.08); color: var(--scout-green); font-weight: 600; }
.nav-cta { background: var(--scout-blue-dark) !important; color: white !important; font-weight: 600 !important; }
.nav-cta:hover { background: var(--scout-blue) !important; }
.nav-mobile { display: none; background: none; border: none; cursor: pointer; font-size: 1.5rem; color: var(--scout-green); padding: 8px; }

/* ═══════════ BUTTONS ═══════════ */
.btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 14px 28px; border-radius: 10px; font-family: 'Poppins', sans-serif;
  font-weight: 600; font-size: 0.92rem; text-decoration: none;
  transition: all 0.25s; border: none; cursor: pointer;
}
.btn-primary { background: var(--scout-gold); color: var(--scout-green-deep); }
.btn-primary:hover { background: #ffc233; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(255,180,0,0.3); }
.btn-green { background: var(--scout-green); color: white; }
.btn-green:hover { background: var(--scout-green-dark); }
.btn-outline-white { background: transparent; color: white; border: 1.5px solid rgba(255,255,255,0.3); }
.btn-outline-white:hover { border-color: rgba(255,255,255,0.6); background: rgba(255,255,255,0.05); }
.btn-outline { background: transparent; color: var(--scout-blue); border: 1.5px solid var(--scout-blue); }
.btn-outline:hover { border-color: var(--scout-blue-dark); background: rgba(0,120,255,0.04); color: var(--scout-blue-dark); }

/* ═══════════ SECTIONS ═══════════ */
section { padding: 80px 24px; }
.container { max-width: 1100px; margin: 0 auto; }
.section-header { text-align: center; max-width: 640px; margin: 0 auto 56px; }
.section-header h2 { font-size: clamp(1.8rem, 3.5vw, 2.5rem); color: var(--scout-green); margin-bottom: 12px; font-weight: 700; }
.section-header p { color: var(--text-muted); font-size: 1rem; }

/* ═══════════ PAGE HERO ═══════════ */
.page-hero {
  background: linear-gradient(165deg, #001a0f 0%, #003320 35%, #004d30 70%, #005a36 100%);
  padding: 100px 24px 60px; text-align: center; color: white; position: relative;
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}
.page-hero::before {
  content: ''; position: absolute; inset: 0;
  background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="p" width="60" height="60" patternUnits="userSpaceOnUse"><circle cx="30" cy="30" r="1" fill="rgba(255,180,0,0.06)"/></pattern></defs><rect fill="url(%23p)" width="60" height="60"/></svg>');
}
.page-hero * { position: relative; z-index: 1; }
.page-hero h1 { font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 8px; font-weight: 700; }
.page-hero p { color: rgba(255,255,255,0.7); font-size: 1.05rem; max-width: 520px; margin: 0 auto; }

/* ═══════════ CARDS ═══════════ */
.card {
  background: white; border: 1px solid var(--border); border-radius: var(--radius-lg);
  padding: 32px 24px; transition: all 0.3s;
}
.card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: transparent; }

/* ═══════════ TABLES ═══════════ */
.styled-table { width: 100%; border-collapse: collapse; background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); }
.styled-table thead { background: var(--scout-green); color: white; }
.styled-table th { padding: 14px 20px; text-align: left; font-size: 0.85rem; font-weight: 600; }
.styled-table td { padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: 0.88rem; }
.styled-table tbody tr:last-child td { border: none; }
.styled-table tbody tr:hover { background: rgba(0,119,72,0.02); }

/* ═══════════ FORMS ═══════════ */
.form-group { margin-bottom: 20px; }
.form-label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; color: var(--text); }
.form-label .required { color: var(--danger); }
.form-hint { font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; }
.form-input, .form-select, .form-textarea {
  width: 100%; padding: 12px 16px; border: 1.5px solid var(--border); border-radius: 8px;
  font-family: 'Poppins', sans-serif; font-size: 0.9rem; color: var(--text);
  background: white; transition: border-color 0.2s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--scout-blue); box-shadow: 0 0 0 3px rgba(0,120,255,0.12); }
.form-textarea { min-height: 100px; resize: vertical; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-section { border-top: 2px solid var(--border); padding-top: 28px; margin-top: 32px; }
.form-section h3 { font-size: 1.05rem; color: var(--scout-green); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
.checkbox-group { display: flex; flex-direction: column; gap: 12px; }
.checkbox-item {
  display: flex; gap: 12px; align-items: flex-start; padding: 16px;
  background: var(--cream); border-radius: var(--radius); border: 1px solid var(--border);
}
.checkbox-item input[type="checkbox"] { width: 20px; height: 20px; margin-top: 2px; flex-shrink: 0; accent-color: var(--scout-green); }
.checkbox-item label { font-size: 0.85rem; color: var(--text-soft); line-height: 1.5; cursor: pointer; }
.checkbox-item.consent-critical { background: #fffbeb; border-color: var(--scout-gold); }
.checkbox-item.consent-critical::before { content: ''; width: 3px; background: var(--scout-gold); border-radius: 2px; align-self: stretch; flex-shrink: 0; }
.consent-note {
  background: rgba(0,119,72,0.04); border-left: 3px solid var(--scout-green);
  padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 24px 0; font-size: 0.85rem; color: var(--text-soft);
}
.consent-note strong { color: var(--scout-green); }

/* ═══════════ GALLERY ═══════════ */
.gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
.gallery-item {
  aspect-ratio: 4/3; border-radius: var(--radius); overflow: hidden;
  background: var(--cream); position: relative; cursor: pointer;
}
.gallery-item .placeholder {
  width: 100%; height: 100%; display: flex; flex-direction: column;
  align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;
}
.gallery-item .placeholder span { font-size: 2.5rem; margin-bottom: 8px; }
.gallery-item:hover { box-shadow: var(--shadow-md); }
.gallery-albums { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 32px; }
.gallery-albums button {
  padding: 8px 20px; border-radius: 100px; border: 1.5px solid var(--border);
  background: white; font-family: 'Poppins', sans-serif; font-size: 0.82rem;
  font-weight: 500; cursor: pointer; transition: all 0.2s; color: var(--text-soft);
}
.gallery-albums button.active, .gallery-albums button:hover {
  background: var(--scout-blue); color: white; border-color: var(--scout-blue);
}

/* ═══════════ CALENDAR ═══════════ */
.calendar-wrapper { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); overflow: hidden; }
.calendar-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; background: var(--scout-green); color: white; }
.calendar-header h3 { font-size: 1.05rem; }
.calendar-nav { display: flex; gap: 8px; }
.calendar-nav button { padding: 6px 12px; border-radius: 6px; border: 1px solid rgba(0,120,255,0.4); background: rgba(0,120,255,0.15); color: white; cursor: pointer; font-family: 'Poppins'; font-weight: 600; }
.calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
.calendar-day-header { padding: 12px 8px; text-align: center; font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
.calendar-day { padding: 8px; min-height: 80px; border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); font-size: 0.8rem; }
.calendar-day .day-num { font-weight: 600; color: var(--text); margin-bottom: 4px; }
.calendar-day.other-month .day-num { color: var(--border); }
.calendar-event { padding: 2px 6px; border-radius: 4px; font-size: 0.68rem; font-weight: 500; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.event-castor { background: #fef3c7; color: #92400e; }
.event-louveteau { background: #d1fae5; color: #047857; }
.event-eclaireur { background: #dbeafe; color: #1d4ed8; }
.event-pionnier { background: #fee2e2; color: #b91c1c; }
.event-special { background: #fff7ed; color: #c2410c; }
.schedule-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-top: 32px; }
.schedule-card { background: white; border-radius: var(--radius); border: 1px solid var(--border); padding: 20px; position: relative; overflow: hidden; }
.schedule-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; }
.schedule-card:nth-child(1)::before { background: #d4a017; }
.schedule-card:nth-child(2)::before { background: #007748; }
.schedule-card:nth-child(3)::before { background: #0065cc; }
.schedule-card:nth-child(4)::before { background: #c0392b; }
.schedule-card h4 { font-size: 0.95rem; color: var(--scout-green); margin-bottom: 8px; }
.schedule-card .detail { font-size: 0.82rem; color: var(--text-soft); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }

/* ═══════════ LEGAL ═══════════ */
.legal-content { max-width: 780px; margin: 0 auto; }
.legal-content h2 { font-size: 1.3rem; color: var(--scout-green); margin: 40px 0 16px; padding-top: 20px; border-top: 1px solid var(--border); }
.legal-content h2:first-of-type { border: none; margin-top: 0; padding-top: 0; }
.legal-content h3 { font-size: 1.05rem; color: var(--scout-green); margin: 24px 0 12px; }
.legal-content p, .legal-content li { font-size: 0.9rem; color: var(--text-soft); line-height: 1.7; margin-bottom: 12px; }
.legal-content ul { padding-left: 20px; }
.legal-callout { background: #fffbeb; border-left: 4px solid var(--scout-gold); padding: 20px; border-radius: 0 var(--radius) var(--radius) 0; margin: 24px 0; }
.legal-callout strong { color: var(--scout-green); }
.legal-content .officer-box { background: var(--cream); border-radius: var(--radius); padding: 24px; margin: 24px 0; }

/* ═══════════ FOOTER ═══════════ */
.site-footer { background: var(--scout-green-deep); color: rgba(255,255,255,0.5); padding: 48px 24px 24px; }
.footer-grid { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
.footer-col h4 { color: var(--scout-gold); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 16px; font-weight: 600; }
.footer-col p, .footer-col a { font-size: 0.82rem; color: rgba(255,255,255,0.5); line-height: 1.8; }
.footer-col a:hover { color: var(--scout-gold); }
.footer-col ul { list-style: none; }
.footer-col li { margin-bottom: 4px; }
.footer-logo { height: 60px; margin-bottom: 16px; }
.footer-bottom { max-width: 1100px; margin: 0 auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; font-size: 0.75rem; flex-wrap: wrap; gap: 8px; }

/* ═══════════ RESPONSIVE ═══════════ */
@media (max-width: 768px) {
  .nav-links { display: none; }
  .nav-mobile { display: block; }
  .form-row { grid-template-columns: 1fr; }
  .footer-grid { grid-template-columns: 1fr 1fr; }
  .calendar-day { min-height: 60px; }
  .cookie-inner { flex-direction: column; }
  .cookie-actions { flex-direction: row; flex-wrap: wrap; }
  section { padding: 60px 24px; }
}
@media (max-width: 480px) {
  .footer-grid { grid-template-columns: 1fr; }
  .gallery-grid { grid-template-columns: 1fr; }
}

/* Keyboard focus indicators */
a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
  outline: 3px solid var(--scout-blue);
  outline-offset: 2px;
}
.btn:focus-visible { outline: 3px solid var(--scout-blue); outline-offset: 2px; }
.skip-link:focus { left: 0 !important; }

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
  .cookie-banner { animation: none; }
}

/* High contrast mode support */
@media (forced-colors: active) {
  .btn, .nav-cta { border: 2px solid ButtonText; }
  .card { border: 1px solid CanvasText; }
}

.skip-link:focus{left:0;}
/* Keyboard focus indicators */
a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
  outline: 3px solid var(--scout-blue);
  outline-offset: 2px;
}
.btn:focus-visible { outline: 3px solid var(--scout-blue); outline-offset: 2px; }
.skip-link:focus { left: 0 !important; }

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
  .cookie-banner { animation: none; }
}

/* High contrast mode support */
@media (forced-colors: active) {
  .btn, .nav-cta { border: 2px solid ButtonText; }
  .card { border: 1px solid CanvasText; }
}

</style>

<div class="page-hero"><h1><?php esc_html_e('Nos unités', 'scout-gm'); ?></h1><p><?php esc_html_e('Quatre branches adaptées à chaque étape du développement des jeunes.', 'scout-gm'); ?></p></div>
<section><div class="container"><div style="display:grid;gap:32px">
<div class="card" style="display:grid;grid-template-columns:120px 1fr;gap:32px;align-items:center;border-left:5px solid #d4a017"><div style="text-align:center"><img src="https://scoutsducanada.ca/content/uploads/2023/12/20230109_-_Badge_castor_final.png" alt="<?php esc_attr_e('Badge Castors — Scouts du Canada', 'scout-gm'); ?>" style="width:90px;height:90px"></div><div><h2 style="font-size:1.3rem;color:var(--scout-green);margin-bottom:4px"><?php esc_html_e('Castors', 'scout-gm'); ?></h2><p style="color:#d4a017;font-weight:600;font-size:0.88rem;margin-bottom:12px"><?php esc_html_e('7 — 8 ans · Vendredi 18h45 à 20h45', 'scout-gm'); ?></p><p style="font-size:0.9rem;color:var(--text-soft);margin-bottom:12px"><?php esc_html_e('Les Castors découvrent le scoutisme par le jeu, les activités en nature et les travaux manuels. Ils apprennent à vivre en petit groupe, à développer leur curiosité et à respecter leur environnement.', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-muted)">📍 <a href="https://maps.app.goo.gl/j4ui35T8yks326Ka6" target="_blank">École Les Mésanges</a> · Animatrice : Mélanie Bizier</p></div></div>
<div class="card" style="display:grid;grid-template-columns:120px 1fr;gap:32px;align-items:center;border-left:5px solid #007748"><div style="text-align:center"><img src="https://scoutsducanada.ca/content/uploads/2023/06/badge_louveteau.png" alt="<?php esc_attr_e('Badge Louveteaux — Scouts du Canada', 'scout-gm'); ?>" style="width:90px;height:90px"></div><div><h2 style="font-size:1.3rem;color:var(--scout-green);margin-bottom:4px"><?php esc_html_e('Louveteaux', 'scout-gm'); ?></h2><p style="color:#007748;font-weight:600;font-size:0.88rem;margin-bottom:12px"><?php esc_html_e('9 — 11 ans · Mardi 18h45 à 20h45', 'scout-gm'); ?></p><p style="font-size:0.9rem;color:var(--text-soft);margin-bottom:12px"><?php esc_html_e('Les Louveteaux vivent des aventures en « meute ». Inspirés du Livre de la Jungle, ils développent leur autonomie, leurs habiletés et leur sens de la communauté.', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-muted)">📍 <a href="https://maps.app.goo.gl/j4ui35T8yks326Ka6" target="_blank">École Les Mésanges</a> · Animateur : Steve Kimmell</p></div></div>
<div class="card" style="display:grid;grid-template-columns:120px 1fr;gap:32px;align-items:center;border-left:5px solid #0065cc"><div style="text-align:center"><img src="https://scoutsducanada.ca/content/uploads/2023/08/badge_eclaireur.png" alt="<?php esc_attr_e('Badge Éclaireurs — Scouts du Canada', 'scout-gm'); ?>" style="width:90px;height:90px"></div><div><h2 style="font-size:1.3rem;color:var(--scout-green);margin-bottom:4px"><?php esc_html_e('Éclaireurs', 'scout-gm'); ?></h2><p style="color:#0065cc;font-weight:600;font-size:0.88rem;margin-bottom:12px"><?php esc_html_e('12 — 14 ans · Dimanche 18h00 à 21h00', 'scout-gm'); ?></p><p style="font-size:0.9rem;color:var(--text-soft);margin-bottom:12px"><?php esc_html_e('Les Éclaireurs travaillent en patrouilles autonomes. Ils planifient et réalisent des projets, développent leur leadership et vivent des défis de plein air plus ambitieux.', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-muted)">📍 <a href="https://maps.app.goo.gl/t1GRBt7Q4ZBuLd5x8" target="_blank">Local des Lions de Deux-Montagnes</a> · Animateur : Jonathan Perreault · 438-888-5822</p></div></div>
<div class="card" style="display:grid;grid-template-columns:120px 1fr;gap:32px;align-items:center;border-left:5px solid #c0392b"><div style="text-align:center"><img src="https://scoutsducanada.ca/content/uploads/2023/08/badge_pionnier.png" alt="<?php esc_attr_e('Badge Pionniers — Scouts du Canada', 'scout-gm'); ?>" style="width:90px;height:90px"></div><div><h2 style="font-size:1.3rem;color:var(--scout-green);margin-bottom:4px"><?php esc_html_e('Pionniers', 'scout-gm'); ?></h2><p style="color:#c0392b;font-weight:600;font-size:0.88rem;margin-bottom:12px"><?php esc_html_e('14 — 17 ans · Horaire à déterminer', 'scout-gm'); ?></p><p style="font-size:0.9rem;color:var(--text-soft);margin-bottom:12px"><?php esc_html_e('Les Pionniers sont les aînés du groupe. Ils vivent le scoutisme avec une grande autonomie : engagement communautaire, expéditions et projets de service.', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-muted)">Animatrice : Jennyfer Savard · ✉️ <a href="/cdn-cgi/l/email-protection#d1b8bfb7be91e4b4a2b2bea4a5b6a3b0bfb5bcbea4bdb8bfffbea3b6"><span class="__cf_email__" data-cfemail="fa93949c95bacf9f8999958f8e9d889b949e97958f969394d495889d">[email&#160;protected]</span></a></p></div></div>
</div></div></section>



<?php get_footer(); ?>
