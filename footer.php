</main>

<footer class="site-footer" role="contentinfo">
  <div class="footer-grid">
    <div class="footer-col">
      <?php if (has_custom_logo()) the_custom_logo(); ?>
      <p><?php esc_html_e('Groupe scout desservant Deux-Montagnes, Sainte-Marthe-sur-le-Lac, Oka, Pointe-Calumet et Saint-Joseph-du-Lac.', 'scout-gm'); ?></p>
      <p style="margin-top:12px"><strong style="color:var(--scout-gold)"><?php echo esc_html(scout_gm_email()); ?></strong></p>
    </div>
    <div class="footer-col">
      <h4><?php esc_html_e('Navigation', 'scout-gm'); ?></h4>
      <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'fallback_cb'=>false]); ?>
    </div>
    <div class="footer-col">
      <h4><?php esc_html_e('Ressources', 'scout-gm'); ?></h4>
      <ul>
        <li><a href="https://scoutsducanada.ca" target="_blank" rel="noopener"><?php esc_html_e('Scouts du Canada', 'scout-gm'); ?></a></li>
        <li><a href="https://scoutsducanada.ca/district/ailes-du-nord/" target="_blank" rel="noopener"><?php esc_html_e('District Les Ailes du Nord', 'scout-gm'); ?></a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4><?php esc_html_e('Légal', 'scout-gm'); ?></h4>
      <ul>
        <?php if (get_privacy_policy_url()) : ?>
          <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Politique de confidentialité', 'scout-gm'); ?></a></li>
        <?php endif; ?>
        <li><a href="<?php echo esc_url(home_url('/conditions/')); ?>"><?php esc_html_e('Conditions d\'utilisation', 'scout-gm'); ?></a></li>
        <li><?php /* translators: %s: name of the privacy officer */ printf(esc_html__('Responsable : %s', 'scout-gm'), esc_html(scout_gm_privacy())); ?></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> &middot; Deux-Montagnes &middot; <a href="https://scoutsducanada.ca" style="color:rgba(255,255,255,0.5)"><?php esc_html_e('Association des Scouts du Canada', 'scout-gm'); ?></a></span>
    <span><?php esc_html_e('Conforme à la Loi 25 du Québec', 'scout-gm'); ?></span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
