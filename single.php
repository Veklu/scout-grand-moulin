<?php get_header(); while (have_posts()) : the_post(); ?>
<div class="page-hero"><h1><?php the_title(); ?></h1><p><?php the_date(); ?></p></div>
<section><div class="container post-content" style="max-width:800px"><?php the_content(); ?></div></section>
<?php endwhile; get_footer(); ?>
