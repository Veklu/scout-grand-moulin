</main>

<footer class="site-footer" role="contentinfo">
  <div class="footer-grid">
    <div class="footer-col">
      <?php if (has_custom_logo()) the_custom_logo(); ?>
      <p>Groupe scout desservant Deux-Montagnes, Sainte-Marthe-sur-le-Lac, Oka, Pointe-Calumet et Saint-Joseph-du-Lac.</p>
      <p style="margin-top:12px"><strong style="color:var(--scout-gold)"><?php echo esc_html(scout_gm_email()); ?></strong></p>
    </div>
    <div class="footer-col">
      <h4>Navigation</h4>
      <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'fallback_cb'=>false]); ?>
    </div>
    <div class="footer-col">
      <h4>Ressources</h4>
      <ul>
        <li><a href="https://scoutsducanada.ca" target="_blank" rel="noopener">Scouts du Canada</a></li>
        <li><a href="https://scoutsducanada.ca/district/ailes-du-nord/" target="_blank" rel="noopener">District Les Ailes du Nord</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Légal</h4>
      <ul>
        <?php if (get_privacy_policy_url()) : ?>
          <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Politique de confidentialité</a></li>
        <?php endif; ?>
        <li><a href="<?php echo esc_url(home_url('/conditions/')); ?>">Conditions d'utilisation</a></li>
        <li>Responsable : <?php echo esc_html(scout_gm_privacy()); ?></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> &middot; Deux-Montagnes &middot; <a href="https://scoutsducanada.ca" style="color:rgba(255,255,255,0.5)">Association des Scouts du Canada</a></span>
    <span>Conforme à la Loi 25 du Québec</span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
