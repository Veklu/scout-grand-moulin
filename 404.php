<?php get_header(); ?>

<div style="background:linear-gradient(160deg,#003320,#005a36,#007748);padding:60px 24px;text-align:center;color:#fff;position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;opacity:0.04;font-size:20rem;display:flex;align-items:center;justify-content:center;font-weight:900;letter-spacing:-20px;pointer-events:none">404</div>
  <div style="position:relative;z-index:1">
    <div style="font-size:4rem;margin-bottom:12px">🧭</div>
    <h1 style="font-size:2.2rem;margin-bottom:8px;font-weight:700">Oups! Sentier introuvable</h1>
    <p style="opacity:0.7;max-width:500px;margin:0 auto;font-size:0.95rem">On dirait que cette piste ne mène nulle part. Même les meilleurs éclaireurs se perdent parfois!</p>
  </div>
</div>

<section style="max-width:700px;margin:0 auto;padding:48px 24px;text-align:center">

  <p style="font-size:1.05rem;color:#3a3a36;margin-bottom:32px">Voici quelques pistes pour retrouver votre chemin :</p>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:40px">
    <a href="<?php echo esc_url(home_url('/')); ?>" style="background:#fff;border:1px solid #e0ddd4;border-radius:12px;padding:24px 16px;text-decoration:none;color:inherit;transition:all 0.2s;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
      <div style="font-size:2rem;margin-bottom:8px">🏠</div>
      <div style="font-weight:700;color:#007748;margin-bottom:4px">Accueil</div>
      <div style="font-size:0.78rem;color:#6a6a62">Retour à la page principale</div>
    </a>
    <a href="<?php echo esc_url(home_url('/inscription/')); ?>" style="background:#f0faf4;border:2px solid #007748;border-radius:12px;padding:24px 16px;text-decoration:none;color:inherit;transition:all 0.2s;box-shadow:0 2px 8px rgba(0,119,72,0.08)">
      <div style="font-size:2rem;margin-bottom:8px">📋</div>
      <div style="font-weight:700;color:#007748;margin-bottom:4px">S'inscrire</div>
      <div style="font-size:0.78rem;color:#6a6a62">Inscription pour la prochaine année scoute</div>
    </a>
    <a href="<?php echo esc_url(home_url('/unites/')); ?>" style="background:#fff;border:1px solid #e0ddd4;border-radius:12px;padding:24px 16px;text-decoration:none;color:inherit;transition:all 0.2s;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
      <div style="font-size:2rem;margin-bottom:8px">⚜️</div>
      <div style="font-weight:700;color:#007748;margin-bottom:4px">Nos unités</div>
      <div style="font-size:0.78rem;color:#6a6a62">Castors, Louveteaux, Éclaireurs, Pionniers</div>
    </a>
  </div>

  <div style="background:linear-gradient(135deg,#003320,#007748);border-radius:16px;padding:32px;color:#fff;margin-bottom:32px">
    <h2 style="font-size:1.3rem;margin-bottom:8px">Vous cherchez à inscrire votre enfant?</h2>
    <p style="opacity:0.7;font-size:0.9rem;margin-bottom:16px">Les inscriptions pour l'année scoute sont ouvertes! Rejoignez la grande famille du 5e Groupe scout Grand-Moulin.</p>
    <a href="<?php echo esc_url(home_url('/inscription/')); ?>" style="display:inline-block;background:#fff;color:#007748;padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;font-size:0.95rem">S'inscrire maintenant →</a>
  </div>

  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
    <a href="<?php echo esc_url(home_url('/agenda/')); ?>" style="padding:8px 16px;border:1.5px solid #e0ddd4;border-radius:8px;text-decoration:none;color:#3a3a36;font-size:0.85rem;font-weight:500">📅 Agenda</a>
    <a href="<?php echo esc_url(home_url('/benevoles/')); ?>" style="padding:8px 16px;border:1.5px solid #e0ddd4;border-radius:8px;text-decoration:none;color:#3a3a36;font-size:0.85rem;font-weight:500">👥 Bénévoles</a>
    <a href="<?php echo esc_url(home_url('/galerie/')); ?>" style="padding:8px 16px;border:1.5px solid #e0ddd4;border-radius:8px;text-decoration:none;color:#3a3a36;font-size:0.85rem;font-weight:500">📸 Galerie</a>
    <a href="mailto:<?php echo esc_attr(function_exists('scout_gm_email') ? scout_gm_email() : 'info@5escoutgrandmoulin.org'); ?>" style="padding:8px 16px;border:1.5px solid #e0ddd4;border-radius:8px;text-decoration:none;color:#3a3a36;font-size:0.85rem;font-weight:500">✉️ Nous contacter</a>
  </div>

</section>

<?php get_footer(); ?>
