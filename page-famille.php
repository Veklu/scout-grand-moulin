<?php
/**
 * Template Name: Famille
 * @package Scout_GM
 */
get_header(); ?>

<section class="page-hero" style="background:linear-gradient(160deg,#003320,#005a36,#007748);padding:56px 24px;text-align:center;color:#fff">
  <h1 style="font-size:2.4rem;margin-bottom:8px">👨‍👩‍👧‍👦 <?php esc_html_e('Espace famille', 'scout-gm'); ?></h1>
  <p style="opacity:0.7;max-width:600px;margin:0 auto"><?php esc_html_e('Gérez les inscriptions de vos enfants, renouvelez ou inscrivez un nouveau membre.', 'scout-gm'); ?></p>
</section>

<section style="max-width:900px;margin:0 auto;padding:40px 20px">
  <?php
  if (shortcode_exists('scout_famille')) {
      echo do_shortcode('[scout_famille]');
  } else {
      echo '<p>' . esc_html__('Le plugin d\'inscription doit être activé.', 'scout-gm') . '</p>';
  }
  ?>
</section>

<?php get_footer(); ?>
