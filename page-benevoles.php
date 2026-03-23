<?php
/**
 * Template Name: Notre équipe
 * @package Scout_GM
 */
get_header();
$teams = function_exists('scout_gm_get_teams') ? scout_gm_get_teams() : [];
$total_members = 0;
foreach ($teams as $t) {
    $total_members += count(function_exists('scout_gm_get_team') ? scout_gm_get_team($t['slug']) : []);
}
?>
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
.event-castor { background: #e0f2fe; color: #0369a1; }
.event-louveteau { background: #fef3c7; color: #b45309; }
.event-eclaireur { background: #d1fae5; color: #047857; }
.event-pionnier { background: #fce7f3; color: #be185d; }
.event-special { background: #fff7ed; color: #c2410c; }
.schedule-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-top: 32px; }
.schedule-card { background: white; border-radius: var(--radius); border: 1px solid var(--border); padding: 20px; position: relative; overflow: hidden; }
.schedule-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; }
.schedule-card:nth-child(1)::before { background: var(--scout-blue); }
.schedule-card:nth-child(2)::before { background: var(--scout-gold); }
.schedule-card:nth-child(3)::before { background: var(--scout-green); }
.schedule-card:nth-child(4)::before { background: var(--scout-purple); }
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

/* ═══════════ BÉNÉVOLES PAGE ═══════════ */
.team-intro {
  text-align: center; max-width: 680px; margin: 0 auto 56px;
}
.team-intro h2 {
  font-size: clamp(1.6rem, 3vw, 2.2rem); color: var(--scout-green); font-weight: 700; margin-bottom: 12px;
}
.team-intro p { color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; }

/* Person cards */
.person-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
.person-card {
  background: white; border-radius: var(--radius-lg); border: 1px solid var(--border);
  padding: 28px 24px; text-align: center; position: relative; overflow: hidden;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.person-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
  background: var(--scout-green); transition: height 0.3s;
}
.person-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,51,32,0.12); border-color: transparent; }
.person-card:hover::before { height: 6px; }
.person-avatar {
  width: 72px; height: 72px; border-radius: 50%; margin: 0 auto 16px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.6rem; font-weight: 700; color: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.person-card h3 { font-size: 1rem; color: var(--text); margin-bottom: 4px; font-weight: 600; }
.person-role { font-size: 0.82rem; color: var(--scout-green); font-weight: 600; margin-bottom: 12px; }
.person-contact {
  display: flex; flex-direction: column; gap: 6px; font-size: 0.78rem; color: var(--text-muted);
}
.person-contact a { color: var(--text-soft); text-decoration: none; transition: color 0.2s; }
.person-contact a:hover { color: var(--scout-green); }

/* Unit badge on animation cards */
.person-unit-badge {
  display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px;
  border-radius: 100px; font-size: 0.72rem; font-weight: 600; margin-bottom: 14px;
}

/* Stats banner */
.stats-banner {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px;
  border-radius: var(--radius-lg); overflow: hidden; margin: 56px 0;
  box-shadow: var(--shadow-md);
}
.stat-item {
  background: white; padding: 32px 20px; text-align: center; position: relative;
}
.stat-item::after {
  content: ''; position: absolute; bottom: 0; left: 20%; right: 20%; height: 3px;
  border-radius: 3px; background: var(--scout-green); opacity: 0.15;
}
.stat-number {
  font-size: 2.2rem; font-weight: 700; color: var(--scout-green);
  line-height: 1; margin-bottom: 6px;
}
.stat-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }

/* CTA section */
.volunteer-cta {
  background: linear-gradient(165deg, #001a0f 0%, #003320 50%, #004d30 100%);
  border-radius: var(--radius-lg); padding: 56px 40px; text-align: center;
  color: white; position: relative; overflow: hidden; margin-top: 56px;
}
.volunteer-cta::before {
  content: ''; position: absolute; inset: 0;
  background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="p" width="60" height="60" patternUnits="userSpaceOnUse"><circle cx="30" cy="30" r="1" fill="rgba(255,180,0,0.08)"/></pattern></defs><rect fill="url(%23p)" width="60" height="60"/></svg>');
}
.volunteer-cta > * { position: relative; z-index: 1; }
.volunteer-cta h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 8px; }
.volunteer-cta p { color: rgba(255,255,255,0.7); font-size: 0.95rem; max-width: 520px; margin: 0 auto 24px; line-height: 1.6; }
.volunteer-cta .cta-roles {
  display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 28px;
}
.volunteer-cta .cta-role {
  background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
  padding: 8px 18px; border-radius: 100px; font-size: 0.8rem; color: rgba(255,255,255,0.85);
}

/* Animate on scroll */
.fade-up { opacity: 0; transform: translateY(24px); transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-up.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
  .stats-banner { grid-template-columns: repeat(2, 1fr); }
  .person-grid { grid-template-columns: 1fr 1fr; }
  .volunteer-cta { padding: 40px 24px; }
}
@media (max-width: 480px) {
  .person-grid { grid-template-columns: 1fr; }
}

.person-card[style*="border-left"]::before { display: none; }

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

/* ═══════════ BÉNÉVOLES PAGE ═══════════ */
.team-intro {
  text-align: center; max-width: 680px; margin: 0 auto 56px;
}
.team-intro h2 {
  font-size: clamp(1.6rem, 3vw, 2.2rem); color: var(--scout-green); font-weight: 700; margin-bottom: 12px;
}
.team-intro p { color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; }

/* Person cards */
.person-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
.person-card {
  background: white; border-radius: var(--radius-lg); border: 1px solid var(--border);
  padding: 28px 24px; text-align: center; position: relative; overflow: hidden;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.person-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
  background: var(--scout-green); transition: height 0.3s;
}
.person-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,51,32,0.12); border-color: transparent; }
.person-card:hover::before { height: 6px; }
.person-avatar {
  width: 72px; height: 72px; border-radius: 50%; margin: 0 auto 16px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.6rem; font-weight: 700; color: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.person-card h3 { font-size: 1rem; color: var(--text); margin-bottom: 4px; font-weight: 600; }
.person-role { font-size: 0.82rem; color: var(--scout-green); font-weight: 600; margin-bottom: 12px; }
.person-contact {
  display: flex; flex-direction: column; gap: 6px; font-size: 0.78rem; color: var(--text-muted);
}
.person-contact a { color: var(--text-soft); text-decoration: none; transition: color 0.2s; }
.person-contact a:hover { color: var(--scout-green); }

/* Unit badge on animation cards */
.person-unit-badge {
  display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px;
  border-radius: 100px; font-size: 0.72rem; font-weight: 600; margin-bottom: 14px;
}

/* Stats banner */
.stats-banner {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px;
  border-radius: var(--radius-lg); overflow: hidden; margin: 56px 0;
  box-shadow: var(--shadow-md);
}
.stat-item {
  background: white; padding: 32px 20px; text-align: center; position: relative;
}
.stat-item::after {
  content: ''; position: absolute; bottom: 0; left: 20%; right: 20%; height: 3px;
  border-radius: 3px; background: var(--scout-green); opacity: 0.15;
}
.stat-number {
  font-size: 2.2rem; font-weight: 700; color: var(--scout-green);
  line-height: 1; margin-bottom: 6px;
}
.stat-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }

/* CTA section */
.volunteer-cta {
  background: linear-gradient(165deg, #001a0f 0%, #003320 50%, #004d30 100%);
  border-radius: var(--radius-lg); padding: 56px 40px; text-align: center;
  color: white; position: relative; overflow: hidden; margin-top: 56px;
}
.volunteer-cta::before {
  content: ''; position: absolute; inset: 0;
  background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="p" width="60" height="60" patternUnits="userSpaceOnUse"><circle cx="30" cy="30" r="1" fill="rgba(255,180,0,0.08)"/></pattern></defs><rect fill="url(%23p)" width="60" height="60"/></svg>');
}
.volunteer-cta > * { position: relative; z-index: 1; }
.volunteer-cta h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 8px; }
.volunteer-cta p { color: rgba(255,255,255,0.7); font-size: 0.95rem; max-width: 520px; margin: 0 auto 24px; line-height: 1.6; }
.volunteer-cta .cta-roles {
  display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 28px;
}
.volunteer-cta .cta-role {
  background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
  padding: 8px 18px; border-radius: 100px; font-size: 0.8rem; color: rgba(255,255,255,0.85);
}

/* Animate on scroll */
.fade-up { opacity: 0; transform: translateY(24px); transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-up.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
  .stats-banner { grid-template-columns: repeat(2, 1fr); }
  .person-grid { grid-template-columns: 1fr 1fr; }
  .volunteer-cta { padding: 40px 24px; }
}
@media (max-width: 480px) {
  .person-grid { grid-template-columns: 1fr; }
}

.person-card[style*="border-left"]::before { display: none; }


/* Unit sub-group card bar */
.person-card:hover .person-card-bar { height: 6px; }
</style>

<div class="page-hero"><h1><?php esc_html_e('Notre équipe', 'scout-gm'); ?></h1><p><?php esc_html_e('Des bénévoles passionnés qui donnent de leur temps pour faire grandir les jeunes de notre communauté.', 'scout-gm'); ?></p></div>

<!-- STATS BANNER -->
<section style="padding-top:0;margin-top:-30px;position:relative;z-index:2"><div class="container">
<div class="stats-banner fade-up">
  <div class="stat-item"><div class="stat-number"><?php echo $total_members; ?>+</div><div class="stat-label"><?php esc_html_e('Bénévoles actifs', 'scout-gm'); ?></div></div>
  <div class="stat-item"><div class="stat-number">4</div><div class="stat-label"><?php esc_html_e('Unités scoutes', 'scout-gm'); ?></div></div>
  <div class="stat-item"><div class="stat-number"><?php echo count($teams); ?></div><div class="stat-label"><?php esc_html_e('Équipes', 'scout-gm'); ?></div></div>
  <div class="stat-item"><div class="stat-number">60+</div><div class="stat-label"><?php esc_html_e('Jeunes encadrés', 'scout-gm'); ?></div></div>
</div>
</div></section>

<!-- DYNAMIC TEAM SECTIONS -->
<?php
$bg_toggle = false;
foreach ($teams as $team_info):
    $members = function_exists('scout_gm_get_team') ? scout_gm_get_team($team_info['slug']) : [];
    $bg_style = $bg_toggle ? ' style="background:var(--cream, #f9f8f5)"' : '';
    $bg_toggle = !$bg_toggle;
?>
<section<?php echo $bg_style; ?>><div class="container">
<div class="team-intro fade-up">
  <h2><?php echo esc_html($team_info['name']); ?></h2>
  <?php if (!empty($team_info['description'])): ?>
    <p><?php echo esc_html($team_info['description']); ?></p>
  <?php endif; ?>
</div>

<?php if (!empty($members)): ?>
<?php
// For non-admin sections, group members by their primary unit
$is_admin_section = ($team_info['slug'] === 'conseil' || $team_info['slug'] === 'administration');

if ($is_admin_section):
    // Admin sections: flat grid, no sub-groups
?>
<div class="person-grid">
<?php foreach ($members as $i => $user):
    $title = function_exists('scout_gm_get_title') ? scout_gm_get_title($user->ID) : '';
    $phone = get_user_meta($user->ID, 'scout_phone', true);
    $user_units = function_exists('scout_gm_get_user_units') ? scout_gm_get_user_units($user->ID) : [];
    $accent = '#0e7490';
    $bar_style = 'background:#0e7490';
    $initials = mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1));
    if (!trim($initials)) $initials = mb_strtoupper(mb_substr($user->display_name, 0, 2));
?>
<div class="person-card fade-up" style="animation-delay:<?php echo $i * 0.05; ?>s">
  <div class="person-card-bar" style="position:absolute;top:0;left:0;right:0;height:4px;<?php echo $bar_style; ?>;transition:height 0.3s;border-radius:20px 20px 0 0"></div>
  <div class="person-avatar" style="background:linear-gradient(135deg, <?php echo esc_attr($accent); ?>, <?php echo esc_attr($accent); ?>cc)"><?php echo esc_html($initials); ?></div>
  <h3><?php echo esc_html($user->display_name); ?></h3>
  <?php if ($title): ?><div class="person-role"><?php echo esc_html($title); ?></div><?php endif; ?>
  <?php foreach ($user_units as $ud): ?>
    <div class="person-unit-badge" style="background:<?php echo esc_attr($ud['bg_color']); ?>;color:<?php echo esc_attr($ud['text_color']); ?>">
      <?php if (!empty($ud['badge_url'])): ?><img src="<?php echo esc_url($ud['badge_url']); ?>" alt="" style="width:16px;height:16px;object-fit:contain;vertical-align:middle;margin-right:4px"><?php endif; ?>
      <?php echo esc_html($ud['name']); ?>
    </div>
  <?php endforeach; ?>
  <div class="person-contact">
    <?php if ($phone): ?><span>📞 <?php echo esc_html($phone); ?></span><?php endif; ?>
    <?php
      $mask_key = get_user_meta($user->ID, 'scout_mask_email_key', true);
      $display_email = $user->user_email;
      if ($mask_key !== '' && $mask_key !== false) {
          $all_emails = get_option('scout_gm_emails', []);
          $idx = intval($mask_key);
          $display_email = isset($all_emails[$idx]) ? $all_emails[$idx]['email'] : (get_option('scout_gm_generic_email', '') ?: $display_email);
      }
      if ($display_email): ?><a href="mailto:<?php echo esc_attr($display_email); ?>">✉️ <?php echo esc_html($display_email); ?></a><?php endif; ?>
  </div>
</div>
<?php endforeach; ?>
</div>

<?php else:
    // Animation/other sections: group by unit
    $units_config = function_exists('scout_gm_get_units') ? scout_gm_get_units() : [];
    $grouped = [];
    $no_unit = [];

    foreach ($members as $user) {
        $user_units = function_exists('scout_gm_get_user_units') ? scout_gm_get_user_units($user->ID) : [];
        if (empty($user_units)) {
            $no_unit[] = $user;
        } else {
            // Place in first unit group
            $primary_slug = $user_units[0]['slug'];
            $grouped[$primary_slug][] = $user;
        }
    }

    // Render each unit sub-group in the order units are configured
    foreach ($units_config as $uc):
        if (empty($grouped[$uc['slug']])) continue;
        $unit_accent = !empty($uc['accent_color']) ? $uc['accent_color'] : ($uc['text_color'] ?? '#007748');
?>
<div class="unit-subgroup fade-up" style="margin-bottom:32px">
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
    <?php if (!empty($uc['badge_url'])): ?>
      <img src="<?php echo esc_url($uc['badge_url']); ?>" alt="" style="width:36px;height:36px;object-fit:contain">
    <?php endif; ?>
    <h3 style="font-size:1.15rem;color:<?php echo esc_attr($unit_accent); ?>;margin:0;font-weight:700"><?php echo esc_html($uc['name']); ?></h3>
    <?php if (!empty($uc['age'])): ?><span style="font-size:0.8rem;color:#6a6a62;font-weight:400"><?php echo esc_html($uc['age']); ?></span><?php endif; ?>
  </div>
  <div class="person-grid">
  <?php foreach ($grouped[$uc['slug']] as $i => $user):
      $title = function_exists('scout_gm_get_title') ? scout_gm_get_title($user->ID) : '';
      $phone = get_user_meta($user->ID, 'scout_phone', true);
      $user_units = function_exists('scout_gm_get_user_units') ? scout_gm_get_user_units($user->ID) : [];

      $accent = $unit_accent;
      $bar_style = 'background:' . $accent;
      if (count($user_units) > 1) {
          $colors = [];
          foreach ($user_units as $ud) { $colors[] = !empty($ud['accent_color']) ? $ud['accent_color'] : '#007748'; }
          $stops = []; $pct = 100 / count($colors);
          foreach ($colors as $ci => $c) { $stops[] = $c . ' ' . round($ci * $pct, 1) . '%'; $stops[] = $c . ' ' . round(($ci + 1) * $pct, 1) . '%'; }
          $bar_style = 'background:linear-gradient(90deg,' . implode(',', $stops) . ')';
      }

      $initials = mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1));
      if (!trim($initials)) $initials = mb_strtoupper(mb_substr($user->display_name, 0, 2));
  ?>
  <div class="person-card fade-up" style="animation-delay:<?php echo $i * 0.05; ?>s">
    <div class="person-card-bar" style="position:absolute;top:0;left:0;right:0;height:4px;<?php echo $bar_style; ?>;transition:height 0.3s;border-radius:20px 20px 0 0"></div>
    <div class="person-avatar" style="background:linear-gradient(135deg, <?php echo esc_attr($accent); ?>, <?php echo esc_attr($accent); ?>cc)"><?php echo esc_html($initials); ?></div>
    <h3><?php echo esc_html($user->display_name); ?></h3>
    <?php if ($title): ?><div class="person-role"><?php echo esc_html($title); ?></div><?php endif; ?>
    <?php foreach ($user_units as $ud): ?>
      <div class="person-unit-badge" style="background:<?php echo esc_attr($ud['bg_color']); ?>;color:<?php echo esc_attr($ud['text_color']); ?>">
        <?php if (!empty($ud['badge_url'])): ?><img src="<?php echo esc_url($ud['badge_url']); ?>" alt="" style="width:16px;height:16px;object-fit:contain;vertical-align:middle;margin-right:4px"><?php endif; ?>
        <?php echo esc_html($ud['name']); ?>
      </div>
    <?php endforeach; ?>
    <div class="person-contact">
      <?php if ($phone): ?><span>📞 <?php echo esc_html($phone); ?></span><?php endif; ?>
      <?php
      $mask_key = get_user_meta($user->ID, 'scout_mask_email_key', true);
      $display_email = $user->user_email;
      if ($mask_key !== '' && $mask_key !== false) {
          $all_emails = get_option('scout_gm_emails', []);
          $idx = intval($mask_key);
          $display_email = isset($all_emails[$idx]) ? $all_emails[$idx]['email'] : (get_option('scout_gm_generic_email', '') ?: $display_email);
      }
      if ($display_email): ?><a href="mailto:<?php echo esc_attr($display_email); ?>">✉️ <?php echo esc_html($display_email); ?></a><?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  </div>
</div>
<?php endforeach;

    // Members with no unit
    if (!empty($no_unit)):
?>
<div class="unit-subgroup fade-up" style="margin-bottom:32px">
  <h3 style="font-size:1.05rem;color:#6a6a62;margin:0 0 14px"><?php esc_html_e('Autres', 'scout-gm'); ?></h3>
  <div class="person-grid">
  <?php foreach ($no_unit as $i => $user):
      $title = function_exists('scout_gm_get_title') ? scout_gm_get_title($user->ID) : '';
      $phone = get_user_meta($user->ID, 'scout_phone', true);
      $user_units = function_exists('scout_gm_get_user_units') ? scout_gm_get_user_units($user->ID) : [];
      $accent = '#007748'; $bar_style = 'background:#007748';
      $initials = mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1));
      if (!trim($initials)) $initials = mb_strtoupper(mb_substr($user->display_name, 0, 2));
  ?>
  <div class="person-card fade-up" style="animation-delay:<?php echo $i * 0.05; ?>s">
    <div class="person-card-bar" style="position:absolute;top:0;left:0;right:0;height:4px;<?php echo $bar_style; ?>;transition:height 0.3s;border-radius:20px 20px 0 0"></div>
    <div class="person-avatar" style="background:linear-gradient(135deg, <?php echo esc_attr($accent); ?>, <?php echo esc_attr($accent); ?>cc)"><?php echo esc_html($initials); ?></div>
    <h3><?php echo esc_html($user->display_name); ?></h3>
    <?php if ($title): ?><div class="person-role"><?php echo esc_html($title); ?></div><?php endif; ?>
    <div class="person-contact">
      <?php if ($phone): ?><span>📞 <?php echo esc_html($phone); ?></span><?php endif; ?>
      <?php
      $mask_key = get_user_meta($user->ID, 'scout_mask_email_key', true);
      $display_email = $user->user_email;
      if ($mask_key !== '' && $mask_key !== false) {
          $all_emails = get_option('scout_gm_emails', []);
          $idx = intval($mask_key);
          $display_email = isset($all_emails[$idx]) ? $all_emails[$idx]['email'] : (get_option('scout_gm_generic_email', '') ?: $display_email);
      }
      if ($display_email): ?><a href="mailto:<?php echo esc_attr($display_email); ?>">✉️ <?php echo esc_html($display_email); ?></a><?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  </div>
</div>
<?php endif; endif; ?>
<?php else: ?>
<div style="text-align:center;padding:40px;color:#6a6a62">
  <p><?php esc_html_e('Aucun membre dans cette section pour le moment.', 'scout-gm'); ?></p>
</div>
<?php endif; ?>
</div></section>
<?php endforeach; ?>

<!-- VOLUNTEER OPPORTUNITIES -->
<?php
$opportunities = function_exists('scout_gm_get_volunteer_opportunities') ? scout_gm_get_volunteer_opportunities() : [];
$active_opps = [];
foreach ($opportunities as $opp) {
    $date = get_post_meta($opp->ID, '_vol_date', true);
    $spots = get_post_meta($opp->ID, '_vol_spots', true);
    $filled = intval(get_post_meta($opp->ID, '_vol_filled', true));
    if ($spots && $filled >= intval($spots)) continue; // full
    if ($date && $date < date('Y-m-d')) continue; // past
    $active_opps[] = $opp;
}
if (!empty($active_opps)):
$cat_icons = ['cuisine'=>'🍳','aide_camp'=>'⛺','transport'=>'🚗','animation'=>'🎪','logistique'=>'📦','evenement'=>'🎉','formation'=>'📚','general'=>'🤝'];
$urgency_colors = ['low'=>'#27ae60','normal'=>'#e67e22','high'=>'#e67e22','critical'=>'#c0392b'];
$urgency_labels = ['low'=>'','normal'=>'','high'=>__('Urgent','scout-gm'),'critical'=>__('Critique!','scout-gm')];
?>
<section style="background:#fff8f0"><div class="container">
<div class="team-intro fade-up">
  <h2>🙋 <?php esc_html_e('On a besoin de vous!', 'scout-gm'); ?></h2>
  <p><?php esc_html_e('Voici les besoins actuels. Chaque coup de main fait une différence!', 'scout-gm'); ?></p>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:24px">
<?php foreach ($active_opps as $i => $opp):
    $cat = get_post_meta($opp->ID, '_vol_category', true) ?: 'general';
    $urgency = get_post_meta($opp->ID, '_vol_urgency', true) ?: 'normal';
    $spots = get_post_meta($opp->ID, '_vol_spots', true);
    $filled = intval(get_post_meta($opp->ID, '_vol_filled', true));
    $date = get_post_meta($opp->ID, '_vol_date', true);
    $date_end = get_post_meta($opp->ID, '_vol_date_end', true);
    $location = get_post_meta($opp->ID, '_vol_location', true);
    $contact = get_post_meta($opp->ID, '_vol_contact', true) ?: (function_exists('scout_gm_email') ? scout_gm_email() : 'info@5escoutgrandmoulin.org');
    $uc = $urgency_colors[$urgency] ?? '#e67e22';
    $remaining = $spots ? (intval($spots) - $filled) : null;
?>
<div class="fade-up" style="background:#fff;border:1px solid #e0ddd4;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.04);animation-delay:<?php echo $i * 0.05; ?>s">
  <div style="height:4px;background:<?php echo $uc; ?>"></div>
  <div style="padding:20px">
    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px">
      <div style="font-size:1.6rem"><?php echo $cat_icons[$cat] ?? '🤝'; ?></div>
      <?php if (!empty($urgency_labels[$urgency])): ?>
        <span style="background:<?php echo $uc; ?>;color:#fff;padding:2px 8px;border-radius:20px;font-size:0.7rem;font-weight:700"><?php echo $urgency_labels[$urgency]; ?></span>
      <?php endif; ?>
    </div>
    <h3 style="font-size:1rem;margin:0 0 6px;color:#1a1a16"><?php echo esc_html($opp->post_title); ?></h3>
    <?php if ($opp->post_content): ?>
      <p style="font-size:0.82rem;color:#6a6a62;margin-bottom:10px;line-height:1.5"><?php echo esc_html(wp_strip_all_tags(wp_trim_words($opp->post_content, 25))); ?></p>
    <?php endif; ?>
    <div style="font-size:0.78rem;color:#6a6a62;display:flex;flex-direction:column;gap:3px;margin-bottom:12px">
      <?php if ($date): ?>
        <span>📅 <?php echo esc_html(date_i18n('j F Y', strtotime($date))); ?><?php if ($date_end): ?> — <?php echo esc_html(date_i18n('j F Y', strtotime($date_end))); ?><?php endif; ?></span>
      <?php endif; ?>
      <?php if ($location): ?><span>📍 <?php echo esc_html($location); ?></span><?php endif; ?>
      <?php if ($remaining !== null): ?>
        <span>👥 <?php printf(
          /* translators: %1$d: number of remaining spots, %2$s: total spots */
          _n('%1$d place restante sur %2$s', '%1$d places restantes sur %2$s', $remaining, 'scout-gm'),
          $remaining, esc_html($spots)
        ); ?></span>
        <div style="background:#e0ddd4;border-radius:10px;height:6px;overflow:hidden;margin-top:2px">
          <div style="background:<?php echo $uc; ?>;height:100%;width:<?php echo round(($filled / intval($spots)) * 100); ?>%;border-radius:10px"></div>
        </div>
      <?php endif; ?>
    </div>
    <a href="mailto:<?php echo esc_attr($contact); ?>?subject=<?php echo rawurlencode(esc_html__('Bénévolat : ', 'scout-gm') . $opp->post_title); ?>" style="display:inline-block;padding:8px 16px;background:#007748;color:#fff;border-radius:6px;font-size:0.82rem;font-weight:600;text-decoration:none"><?php esc_html_e('Je veux aider', 'scout-gm'); ?> →</a>
  </div>
</div>
<?php endforeach; ?>
</div>
</div></section>
<?php endif; ?>

<!-- CTA + SHARE -->
<section><div class="container">
<div class="volunteer-cta fade-up">
  <h2><?php esc_html_e('Rejoignez notre équipe!', 'scout-gm'); ?></h2>
  <p><?php esc_html_e('Nous recherchons toujours des bénévoles pour encadrer les activités de nos jeunes scouts.', 'scout-gm'); ?></p>
  <div class="cta-roles">
    <span class="cta-role"><?php esc_html_e('Animateur·rice', 'scout-gm'); ?></span>
    <span class="cta-role"><?php esc_html_e('Aide-animateur·rice', 'scout-gm'); ?></span>
    <span class="cta-role"><?php esc_html_e('Membre du CA', 'scout-gm'); ?></span>
    <span class="cta-role"><?php esc_html_e('Soutien logistique', 'scout-gm'); ?></span>
    <span class="cta-role"><?php esc_html_e('Cuisinier·ère de camp', 'scout-gm'); ?></span>
    <span class="cta-role"><?php esc_html_e('Aide au camp', 'scout-gm'); ?></span>
  </div>
  <a href="mailto:<?php echo esc_attr(function_exists('scout_gm_email') ? scout_gm_email() : 'info@5escoutgrandmoulin.org'); ?>" style="display:inline-block;background:white;color:#007748;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;font-size:0.95rem;margin-bottom:20px"><?php esc_html_e('Nous contacter', 'scout-gm'); ?> ✉️</a>
  
  <!-- Share -->
  <div style="margin-top:12px">
    <p style="font-size:0.82rem;opacity:0.7;margin-bottom:10px"><?php esc_html_e('Partagez cette page et aidez-nous à recruter!', 'scout-gm'); ?></p>
    <?php
    $share_url = urlencode(get_permalink());
    $share_title = urlencode(__('Bénévoles recherchés', 'scout-gm') . ' — ' . get_bloginfo('name'));
    /* translators: %s: site name */
    $share_text = urlencode(sprintf(__('Le %s recherche des bénévoles! Joignez-vous à notre équipe scoute.', 'scout-gm'), get_bloginfo('name')));
    ?>
    <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#1877F2;color:#fff;border-radius:8px;text-decoration:none;font-size:0.82rem;font-weight:600">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        Facebook
      </a>
      <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_text; ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#000;color:#fff;border-radius:8px;text-decoration:none;font-size:0.82rem;font-weight:600">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        X
      </a>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#0A66C2;color:#fff;border-radius:8px;text-decoration:none;font-size:0.82rem;font-weight:600">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        LinkedIn
      </a>
      <a href="https://wa.me/?text=<?php echo $share_text . '%20' . $share_url; ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#25D366;color:#fff;border-radius:8px;text-decoration:none;font-size:0.82rem;font-weight:600">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        WhatsApp
      </a>
      <button onclick="navigator.clipboard.writeText(window.location.href);this.textContent=<?php echo wp_json_encode('✅ ' . __('Copié!', 'scout-gm')); ?>;var b=this;setTimeout(function(){b.innerHTML=<?php echo wp_json_encode('🔗 ' . __('Copier le lien', 'scout-gm')); ?>},1500)" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.3);border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer">🔗 <?php esc_html_e('Copier le lien', 'scout-gm'); ?></button>
    </div>
  </div>
</div>
</div></section>

<script>
(function() {
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.fade-up').forEach(function(el) { observer.observe(el); });
})();
</script>

<?php get_footer(); ?>
