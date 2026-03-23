<?php
/**
 * Front page template
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

/* Dropdown sub-menu */
.nav-links > li { position: relative; }
.nav-links .sub-menu {
  display: none; position: absolute; top: 100%; left: 0;
  background: #fff; border: 1px solid #e0ddd4; border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.10); padding: 6px 0;
  min-width: 200px; z-index: 9999; list-style: none;
  margin-top: 4px; animation: dropIn 0.15s ease-out;
}
@keyframes dropIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
.nav-links > li:hover > .sub-menu { display: block; }
.nav-links .sub-menu li { padding: 0; }
.nav-links .sub-menu a {
  display: block; padding: 8px 16px !important; font-size: 0.82rem !important;
  color: var(--text-soft) !important; border-radius: 0 !important;
  white-space: nowrap; transition: all 0.15s;
}
.nav-links .sub-menu a:hover { background: rgba(0,119,72,0.06) !important; color: var(--scout-green) !important; padding-left: 20px !important; }
.nav-arrow { font-size: 0.7em; opacity: 0.5; margin-left: 2px; }

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
  background: linear-gradient(165deg, var(--scout-green-deep) 0%, var(--scout-green) 50%, #00a86b 100%);
  padding: 100px 24px 60px; text-align: center; color: white; position: relative;
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

/* Town rotator */
#townName {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.town-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: rgba(255,255,255,0.2);
  transition: all 0.3s;
}
.town-dot.active {
  background: var(--scout-gold);
  width: 24px; border-radius: 4px;
}

.unit-card img { transition: transform 0.3s ease; }
.unit-card:hover img { transform: scale(1.1); }

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

/* Town rotator */
#townName {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.town-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: rgba(255,255,255,0.2);
  transition: all 0.3s;
}
.town-dot.active {
  background: var(--scout-gold);
  width: 24px; border-radius: 4px;
}

.unit-card img { transition: transform 0.3s ease; }
.unit-card:hover img { transform: scale(1.1); }

</style>

<section class="hero" style="position:relative;display:flex;align-items:center;overflow:hidden;background:linear-gradient(165deg, #001a0f 0%, #003320 35%, #004d30 70%, #005a36 100%);box-shadow:0 4px 12px rgba(0,0,0,0.12)">
  
  
  <div style="position:relative;z-index:1;max-width:1200px;margin:0 auto;padding:80px 24px;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center">
    <div style="color:white">
      <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(0,120,255,0.18);border:1px solid rgba(0,120,255,0.35);padding:6px 16px;border-radius:100px;font-size:0.8rem;color:var(--scout-gold);margin-bottom:24px"><?php esc_html_e('Scouts du Canada — District Les Ailes du Nord', 'scout-gm'); ?></div>
      <h1 style="font-size:clamp(2.6rem,5vw,3.8rem);font-weight:700;line-height:1.1;margin-bottom:16px"><?php
        /* translators: %s: the word "ici" wrapped in styled em tag */
        printf(__('L\'aventure<br>commence %s', 'scout-gm'), '<em style="font-style:italic;color:var(--scout-gold)">' . esc_html__('ici', 'scout-gm') . '</em>');
      ?></h1>
      <p style="font-size:1.1rem;color:rgba(255,255,255,0.88);margin-bottom:12px;font-weight:300"><?php esc_html_e('Le 5e Groupe scout Grand-Moulin offre aux jeunes de 7 à 17 ans un environnement de croissance, d\'entraide et de découverte en plein air.', 'scout-gm'); ?></p>
      <div class="town-rotator" style="margin-bottom:36px" aria-live="polite" aria-label="<?php esc_attr_e('Villes desservies', 'scout-gm'); ?>">
        <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:2px;color:rgba(255,255,255,0.45);margin-bottom:12px;font-weight:600"><?php esc_html_e('Nous desservons votre communauté', 'scout-gm'); ?></p>
        <div style="display:flex;align-items:center;gap:12px;height:48px">
          <span style="font-size:1.4rem">📍</span>
          <span class="town-name" id="townName" style="font-size:clamp(1.4rem,3vw,1.8rem);font-weight:700;color:white;display:inline-block"></span>
        </div>
        <div class="town-dots" style="display:flex;gap:6px;margin-top:10px">
          <span class="town-dot active" data-index="0"></span>
          <span class="town-dot" data-index="1"></span>
          <span class="town-dot" data-index="2"></span>
          <span class="town-dot" data-index="3"></span>
          <span class="town-dot" data-index="4"></span>
        </div>
      </div>
      <script>
      (function(){
        const towns = ["Deux-Montagnes","Sainte-Marthe-sur-le-Lac","Oka","Pointe-Calumet","Saint-Joseph-du-Lac"];
        const el = document.getElementById("townName");
        const dots = document.querySelectorAll(".town-dot");
        let current = 0;
        function show(i) {
          el.style.opacity = "0";
          el.style.transform = "translateY(12px)";
          setTimeout(function(){
            el.textContent = towns[i];
            el.style.opacity = "1";
            el.style.transform = "translateY(0)";
            dots.forEach(function(d,j){ d.classList.toggle("active", j===i); });
          }, 300);
        }
        show(0);
        setInterval(function(){ current = (current+1)%towns.length; show(current); }, 2800);
      })();
      </script>
      <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="<?php echo esc_url(home_url('/inscription/')); ?>" class="btn btn-primary"><?php esc_html_e('Inscrire mon enfant', 'scout-gm'); ?> →</a>
        <a href="<?php echo esc_url(home_url('/unites/')); ?>" class="btn btn-outline-white"><?php esc_html_e('Découvrir nos unités', 'scout-gm'); ?></a>
      </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
      <div style="background:rgba(255,255,255,0.12);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.18);border-radius:20px;padding:28px;color:white">
        <h3 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:1.5px;color:var(--scout-gold);margin-bottom:16px">📅 <?php esc_html_e('Prochaines réunions', 'scout-gm'); ?></h3>
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.12);font-size:0.88rem"><div><strong><?php esc_html_e('Castors', 'scout-gm'); ?></strong> <span style="color:rgba(255,255,255,0.6);font-size:0.78rem"><?php esc_html_e('· 7-8 ans', 'scout-gm'); ?></span></div><span style="color:var(--scout-gold);font-weight:500;font-size:0.82rem"><?php esc_html_e('Ven 18h45', 'scout-gm'); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.12);font-size:0.88rem"><div><strong><?php esc_html_e('Louveteaux', 'scout-gm'); ?></strong> <span style="color:rgba(255,255,255,0.6);font-size:0.78rem"><?php esc_html_e('· 9-11 ans', 'scout-gm'); ?></span></div><span style="color:var(--scout-gold);font-weight:500;font-size:0.82rem"><?php esc_html_e('Mar 18h45', 'scout-gm'); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.12);font-size:0.88rem"><div><strong><?php esc_html_e('Éclaireurs', 'scout-gm'); ?></strong> <span style="color:rgba(255,255,255,0.6);font-size:0.78rem"><?php esc_html_e('· 12-14 ans', 'scout-gm'); ?></span></div><span style="color:var(--scout-gold);font-weight:500;font-size:0.82rem"><?php esc_html_e('Dim 18h00', 'scout-gm'); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:0.88rem"><div><strong><?php esc_html_e('Pionniers', 'scout-gm'); ?></strong> <span style="color:rgba(255,255,255,0.6);font-size:0.78rem"><?php /* translators: age range */ esc_html_e('· 14-17 ans', 'scout-gm'); ?></span></div><span style="color:var(--scout-gold);font-weight:500;font-size:0.82rem"><?php esc_html_e('À déterminer', 'scout-gm'); ?></span></div>
      </div>
      <div style="background:rgba(255,255,255,0.12);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.18);border-radius:20px;padding:20px 28px;text-align:center;color:white">
        <p style="font-size:0.88rem;color:rgba(255,255,255,0.8)"><?php esc_html_e('Contactez-nous', 'scout-gm'); ?></p>
        <p style="font-size:1.05rem;font-weight:600;color:var(--scout-gold)"><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="9ef7f0f8f1deabfbedfdf1ebeaf9ecfff0faf3f1ebf2f7f0b0f1ecf9">[email&#160;protected]</a></p>
      </div>
    </div>
  </div>
</section>

<?php if (shortcode_exists('scout_code_du_jour')): ?>
<section style="padding:40px 24px 0">
  <?php echo do_shortcode('[scout_code_du_jour]'); ?>
</section>
<?php endif; ?>

<section id="unites">
  <div class="section-header">
    <h2><?php esc_html_e('Nos unités', 'scout-gm'); ?></h2>
    <p><?php esc_html_e('Quatre branches adaptées à chaque groupe d\'âge, du castor au pionnier.', 'scout-gm'); ?></p>
  </div>
  <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px">
    <div class="card" style="text-align:center;position:relative;overflow:hidden"><div style="position:absolute;top:0;left:0;right:0;height:4px;background:#d4a017"></div><img src="https://scoutsducanada.ca/content/uploads/2023/12/20230109_-_Badge_castor_final.png" alt="<?php esc_attr_e('Badge Castors — Scouts du Canada', 'scout-gm'); ?>" style="width:72px;height:72px;margin-bottom:12px"><h3 style="font-weight:700;font-size:1.1rem;color:var(--scout-green)"><?php esc_html_e('Castors', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-muted);font-weight:500;margin-bottom:12px"><?php esc_html_e('7 — 8 ans', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-soft)"><?php esc_html_e('Découverte du scoutisme par le jeu, la nature et l\'apprentissage en petit groupe.', 'scout-gm'); ?></p></div>
    <div class="card" style="text-align:center;position:relative;overflow:hidden"><div style="position:absolute;top:0;left:0;right:0;height:4px;background:#007748"></div><img src="https://scoutsducanada.ca/content/uploads/2023/06/badge_louveteau.png" alt="<?php esc_attr_e('Badge Louveteaux — Scouts du Canada', 'scout-gm'); ?>" style="width:72px;height:72px;margin-bottom:12px"><h3 style="font-weight:700;font-size:1.1rem;color:var(--scout-green)"><?php esc_html_e('Louveteaux', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-muted);font-weight:500;margin-bottom:12px"><?php esc_html_e('9 — 11 ans', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-soft)"><?php esc_html_e('Aventures en meute, développement de l\'autonomie et du sens de la communauté.', 'scout-gm'); ?></p></div>
    <div class="card" style="text-align:center;position:relative;overflow:hidden"><div style="position:absolute;top:0;left:0;right:0;height:4px;background:#0065cc"></div><img src="https://scoutsducanada.ca/content/uploads/2023/08/badge_eclaireur.png" alt="<?php esc_attr_e('Badge Éclaireurs — Scouts du Canada', 'scout-gm'); ?>" style="width:72px;height:72px;margin-bottom:12px"><h3 style="font-weight:700;font-size:1.1rem;color:var(--scout-green)"><?php esc_html_e('Éclaireurs', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-muted);font-weight:500;margin-bottom:12px"><?php esc_html_e('12 — 14 ans', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-soft)"><?php esc_html_e('Camps, projets d\'équipe, leadership et défis en plein air.', 'scout-gm'); ?></p></div>
    <div class="card" style="text-align:center;position:relative;overflow:hidden"><div style="position:absolute;top:0;left:0;right:0;height:4px;background:#c0392b"></div><img src="https://scoutsducanada.ca/content/uploads/2023/08/badge_pionnier.png" alt="<?php esc_attr_e('Badge Pionniers — Scouts du Canada', 'scout-gm'); ?>" style="width:72px;height:72px;margin-bottom:12px"><h3 style="font-weight:700;font-size:1.1rem;color:var(--scout-green)"><?php esc_html_e('Pionniers', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-muted);font-weight:500;margin-bottom:12px"><?php esc_html_e('14 — 17 ans', 'scout-gm'); ?></p><p style="font-size:0.82rem;color:var(--text-soft)"><?php esc_html_e('Engagement communautaire, autonomie complète et préparation à la vie adulte.', 'scout-gm'); ?></p></div>
  </div>
</section>

<section style="background:var(--cream)">
  <div class="section-header">
    <h2><?php esc_html_e('Pourquoi le scoutisme?', 'scout-gm'); ?></h2>
    <p><?php esc_html_e('Le scoutisme développe des compétences pour la vie.', 'scout-gm'); ?></p>
  </div>
  <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px">
    <div style="text-align:center;padding:24px"><span style="font-size:2rem;display:block;margin-bottom:12px">🌲</span><h3 style="font-size:1rem;font-weight:700;color:var(--scout-green);margin-bottom:8px"><?php esc_html_e('Plein air', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-soft)"><?php esc_html_e('Camps, randonnées, canot — une connexion directe avec la nature québécoise.', 'scout-gm'); ?></p></div>
    <div style="text-align:center;padding:24px"><span style="font-size:2rem;display:block;margin-bottom:12px">🤝</span><h3 style="font-size:1rem;font-weight:700;color:var(--scout-green);margin-bottom:8px"><?php esc_html_e('Communauté', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-soft)"><?php esc_html_e('Des amitiés durables, l\'entraide et le sens de l\'appartenance.', 'scout-gm'); ?></p></div>
    <div style="text-align:center;padding:24px"><span style="font-size:2rem;display:block;margin-bottom:12px">⭐</span><h3 style="font-size:1rem;font-weight:700;color:var(--scout-green);margin-bottom:8px"><?php esc_html_e('Leadership', 'scout-gm'); ?></h3><p style="font-size:0.85rem;color:var(--text-soft)"><?php esc_html_e('Prise d\'initiative, travail d\'équipe et développement du caractère.', 'scout-gm'); ?></p></div>
  </div>
  <div style="text-align:center;margin-top:24px"><a href="<?php echo esc_url(home_url('/inscription/')); ?>" class="btn btn-green"><?php esc_html_e('Inscrire mon enfant', 'scout-gm'); ?> →</a></div>
</section>

<script>
      (function(){
        const towns = ["Deux-Montagnes","Sainte-Marthe-sur-le-Lac","Oka","Pointe-Calumet","Saint-Joseph-du-Lac"];
        const el = document.getElementById("townName");
        const dots = document.querySelectorAll(".town-dot");
        let current = 0;
        function show(i) {
          el.style.opacity = "0";
          el.style.transform = "translateY(12px)";
          setTimeout(function(){
            el.textContent = towns[i];
            el.style.opacity = "1";
            el.style.transform = "translateY(0)";
            dots.forEach(function(d,j){ d.classList.toggle("active", j===i); });
          }, 300);
        }
        show(0);
        setInterval(function(){ current = (current+1)%towns.length; show(current); }, 2800);
      })();
      </script>

<?php get_footer(); ?>
