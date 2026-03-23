<?php get_header(); while (have_posts()) : the_post();
$content = get_the_content();
$has_own_header = has_shortcode($content, 'scout_code_page') || has_shortcode($content, 'scout_codes_hub') || has_shortcode($content, 'scout_encodeur') || has_shortcode($content, 'scout_famille');
if ($has_own_header): ?>
<?php the_content(); ?>
<?php else: ?>
<div class="page-hero"><h1><?php the_title(); ?></h1></div>
<section><div class="container"><?php the_content(); ?></div></section>
<?php endif; ?>
<?php endwhile; get_footer(); ?>
