<?php
/**
 * Template Name: Inscription
 * 
 * This page renders the registration form from the scout-inscription plugin.
 * The plugin must be installed and activated for the form to appear.
 * 
 * @package Scout_GM
 */
get_header(); ?>

<section class="page-hero" style="background:linear-gradient(160deg,#003320,#005a36,#007748);padding:56px 24px;text-align:center;color:#fff">
  <h1 style="font-size:2.4rem;margin-bottom:8px">📝 Inscription 2025-2026</h1>
  <p style="opacity:0.7;max-width:600px;margin:0 auto">Remplissez le formulaire ci-dessous pour inscrire votre enfant au 5e Groupe scout Grand-Moulin.</p>
</section>

<section style="max-width:900px;margin:0 auto;padding:40px 20px">
  <?php
  if ( shortcode_exists( 'scout_inscription' ) ) {
      // Plugin is active — render the dynamic form
      echo do_shortcode( '[scout_inscription]' );
  } else {
      // Plugin not active — show friendly message
      echo '<div style="background:#fff3e0;border:2px solid #f0c080;border-radius:12px;padding:24px;text-align:center">';
      echo '<h2 style="color:#e67e22;margin-bottom:8px">⚠️ Plugin d\'inscription requis</h2>';
      echo '<p>Le plugin <strong>Scout Inscription</strong> doit être installé et activé pour afficher le formulaire.</p>';
      echo '<p>Allez dans <strong>Extensions → Ajouter</strong> et téléversez le fichier <code>scout-inscription.zip</code>.</p>';
      echo '</div>';
  }
  ?>
</section>

<?php get_footer(); ?>
