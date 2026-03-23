<?php get_header(); ?>
<?php if (have_posts()) : ?>
<section><div class="container">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('card'); ?> style="margin-bottom:24px">
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:12px"><?php the_date(); ?></div>
      <?php the_excerpt(); ?>
      <a href="<?php the_permalink(); ?>" class="btn btn-outline">Lire la suite →</a>
    </article>
  <?php endwhile; ?>
</div></section>
<?php endif; get_footer(); ?>
