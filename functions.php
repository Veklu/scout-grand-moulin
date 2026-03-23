<?php
/**
 * 5e Scout Grand-Moulin — Theme Functions
 * @package Scout_GM
 */
defined('ABSPATH') || exit;

require_once get_template_directory() . '/blocks/scout-bubble-block/register.php';

// ── SETUP ──
function scout_gm_setup() {
    load_theme_textdomain('scout-gm', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', ['height'=>80,'width'=>200,'flex-height'=>true,'flex-width'=>true]);
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');

    register_nav_menus([
        'primary' => __('Navigation principale','scout-gm'),
        'footer'  => __('Pied de page','scout-gm'),
    ]);

    if (!isset($content_width)) $content_width = 1200;
}
add_action('after_setup_theme','scout_gm_setup');

// ── ENQUEUE ──
function scout_gm_enqueue() {
    wp_enqueue_style('scout-gm-fonts','https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap',[],null);
    wp_enqueue_style('scout-gm-main',get_template_directory_uri().'/assets/css/main.css',['scout-gm-fonts'],'1.0.0');

    // Page-specific CSS is now inline in each template for reliability

    wp_enqueue_script('scout-gm-cookies',get_template_directory_uri().'/assets/js/cookies.js',[],'1.0.0',true);
    wp_enqueue_script('scout-gm-nav',get_template_directory_uri().'/assets/js/navigation.js',[],'1.0.0',true);
}
add_action('wp_enqueue_scripts','scout_gm_enqueue');

// ── WIDGETS ──
function scout_gm_widgets() {
    register_sidebar(['name'=>__('Pied de page','scout-gm'),'id'=>'footer-1','before_widget'=>'<div class="footer-widget">','after_widget'=>'</div>','before_title'=>'<h4>','after_title'=>'</h4>']);
}
add_action('widgets_init','scout_gm_widgets');

// ── NAV WALKER ──
class Scout_GM_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu">';
    }
    function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = $item->classes ? implode(' ', $item->classes) : '';
        $is_cta = in_array('menu-item-cta', $item->classes ?: []);
        $has_children = in_array('menu-item-has-children', $item->classes ?: []);
        $output .= '<li class="' . esc_attr($classes) . '">';
        $cls = $is_cta ? 'nav-cta' : '';
        if ($item->current) $cls .= ' active';
        $output .= '<a href="' . esc_url($item->url) . '"';
        if (trim($cls)) $output .= ' class="' . esc_attr(trim($cls)) . '"';
        if ($item->target) $output .= ' target="' . esc_attr($item->target) . '"';
        $output .= '>' . esc_html($item->title);
        if ($has_children && $depth === 0) $output .= ' <span class="nav-arrow">▾</span>';
        $output .= '</a>';
    }
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

// ── CUSTOMIZER ──
function scout_gm_customize($wp_customize) {
    $wp_customize->add_section('scout_gm_contact',['title'=>__('Coordonnées du groupe','scout-gm'),'priority'=>30]);

    $fields = [
        'scout_gm_email'   => [__('Courriel','scout-gm'),'info@5escoutgrandmoulin.org','sanitize_email'],
        'scout_gm_phone'   => [__('Téléphone','scout-gm'),'514-730-3398','sanitize_text_field'],
        'scout_gm_privacy' => [__('Responsable vie privée (Loi 25)','scout-gm'),'Jean Côté','sanitize_text_field'],
    ];
    foreach ($fields as $key => [$label,$default,$sanitize]) {
        $wp_customize->add_setting($key,['default'=>$default,'sanitize_callback'=>$sanitize]);
        $wp_customize->add_control($key,['label'=>$label,'section'=>'scout_gm_contact','type'=>'text']);
    }
}
add_action('customize_register','scout_gm_customize');

// ── HELPERS ──
function scout_gm_email()   { return get_theme_mod('scout_gm_email','info@5escoutgrandmoulin.org'); }
function scout_gm_phone()   { return get_theme_mod('scout_gm_phone','514-730-3398'); }
function scout_gm_privacy() { return get_theme_mod('scout_gm_privacy','Jean Côté'); }

// ── CLEANUP ──
remove_action('wp_head','print_emoji_detection_script',7);
remove_action('wp_print_styles','print_emoji_styles');

// ── GALERIE: Photo Albums ──
function scout_gm_galerie_setup() {
    // Register "Album" taxonomy
    register_taxonomy('scout_album', 'attachment', [
        'labels' => [
            'name' => __('Albums photo', 'scout-gm'),
            'singular_name' => __('Album', 'scout-gm'),
            'add_new_item' => __('Ajouter un album', 'scout-gm'),
            'edit_item' => __('Modifier l\'album', 'scout-gm'),
            'search_items' => __('Chercher un album', 'scout-gm'),
            'all_items' => __('Tous les albums', 'scout-gm'),
        ],
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'album'],
    ]);

    // Create default albums on theme activation
    $defaults = ['Camps', 'Réunions', 'Cérémonies', 'Sorties', 'Communautaire'];
    foreach ($defaults as $album) {
        if (!term_exists($album, 'scout_album')) {
            wp_insert_term($album, 'scout_album');
        }
    }
}
add_action('init', 'scout_gm_galerie_setup');

// Add album dropdown to Media Library upload screen
function scout_gm_attachment_fields($form_fields, $post) {
    $terms = get_terms(['taxonomy' => 'scout_album', 'hide_empty' => false]);
    $current = wp_get_object_terms($post->ID, 'scout_album', ['fields' => 'ids']);
    
    $options = '<option value="">' . esc_html__('— Aucun album —', 'scout-gm') . '</option>';
    foreach ($terms as $term) {
        $sel = in_array($term->term_id, $current) ? ' selected' : '';
        $options .= '<option value="' . $term->term_id . '"' . $sel . '>' . esc_html($term->name) . '</option>';
    }
    
    $form_fields['scout_album'] = [
        'label' => __('Album photo', 'scout-gm'),
        'input' => 'html',
        'html'  => '<select name="attachments[' . $post->ID . '][scout_album]">' . $options . '</select>',
    ];
    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'scout_gm_attachment_fields', 10, 2);

function scout_gm_attachment_fields_save($post, $attachment) {
    if (isset($attachment['scout_album'])) {
        $album_id = intval($attachment['scout_album']);
        if ($album_id > 0) {
            wp_set_object_terms($post['ID'], [$album_id], 'scout_album');
        } else {
            wp_set_object_terms($post['ID'], [], 'scout_album');
        }
    }
    return $post;
}
add_filter('attachment_fields_to_save', 'scout_gm_attachment_fields_save', 10, 2);

// REST API endpoint for galerie photos
function scout_gm_galerie_api() {
    register_rest_route('scout-gm/v1', '/galerie', [
        'methods' => 'GET',
        'callback' => function($request) {
            $album = $request->get_param('album');
            $args = [
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_status' => 'inherit',
                'posts_per_page' => 50,
                'orderby' => 'date',
                'order' => 'DESC',
            ];
            if ($album && $album !== 'all') {
                $args['tax_query'] = [[
                    'taxonomy' => 'scout_album',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($album),
                ]];
            } else {
                // Only show images that have an album assigned
                $args['tax_query'] = [[
                    'taxonomy' => 'scout_album',
                    'operator' => 'EXISTS',
                ]];
            }
            $query = new WP_Query($args);
            $photos = [];
            foreach ($query->posts as $p) {
                $photos[] = [
                    'id' => $p->ID,
                    'src' => wp_get_attachment_image_url($p->ID, 'large'),
                    'thumb' => wp_get_attachment_image_url($p->ID, 'medium'),
                    'alt' => get_post_meta($p->ID, '_wp_attachment_image_alt', true) ?: $p->post_title,
                    'caption' => $p->post_excerpt,
                    'album' => wp_get_object_terms($p->ID, 'scout_album', ['fields' => 'names']),
                ];
            }
            return $photos;
        },
        'permission_callback' => '__return_true',
    ]);
    
    register_rest_route('scout-gm/v1', '/albums', [
        'methods' => 'GET',
        'callback' => function() {
            $terms = get_terms(['taxonomy' => 'scout_album', 'hide_empty' => true]);
            $albums = [];
            foreach ($terms as $t) {
                $albums[] = ['slug' => $t->slug, 'name' => $t->name, 'count' => $t->count];
            }
            return $albums;
        },
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'scout_gm_galerie_api');

// ── AGENDA: Events ──
function scout_gm_events_setup() {
    register_post_type('scout_event', [
        'labels' => [
            'name' => __('Événements', 'scout-gm'),
            'singular_name' => __('Événement', 'scout-gm'),
            'add_new' => __('Ajouter un événement', 'scout-gm'),
            'add_new_item' => __('Ajouter un événement', 'scout-gm'),
            'edit_item' => __('Modifier l\'événement', 'scout-gm'),
            'all_items' => __('Tous les événements', 'scout-gm'),
            'search_items' => __('Chercher un événement', 'scout-gm'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'custom-fields'],
        'has_archive' => false,
        'rewrite' => ['slug' => 'evenement'],
    ]);
}
add_action('init', 'scout_gm_events_setup');

// Meta boxes for event fields
function scout_gm_event_meta_boxes() {
    add_meta_box('scout_event_details', __('Détails de l\'événement', 'scout-gm'), 'scout_gm_event_meta_render', 'scout_event', 'normal', 'high');
}
add_action('add_meta_boxes', 'scout_gm_event_meta_boxes');

function scout_gm_event_meta_render($post) {
    wp_nonce_field('scout_event_meta', 'scout_event_nonce');
    $date = get_post_meta($post->ID, '_event_date', true);
    $date_end = get_post_meta($post->ID, '_event_date_end', true);
    $time_start = get_post_meta($post->ID, '_event_time_start', true);
    $time_end = get_post_meta($post->ID, '_event_time_end', true);
    $location = get_post_meta($post->ID, '_event_location', true);
    $unit = get_post_meta($post->ID, '_event_unit', true);
    $recurrence = get_post_meta($post->ID, '_event_recurrence', true) ?: 'none';
    $recurrence_end = get_post_meta($post->ID, '_event_recurrence_end', true);
    $recurrence_exceptions = get_post_meta($post->ID, '_event_recurrence_exceptions', true);
    ?>
    <style>.scout-evt-table td{padding:6px 0}.scout-evt-table input,.scout-evt-table select{padding:6px}</style>
    <table class="scout-evt-table" style="width:100%">
    <tr><td style="width:160px"><strong><?php echo '📅 ' . esc_html__('Date de début *', 'scout-gm'); ?></strong></td><td><input type="date" name="event_date" value="<?php echo esc_attr($date); ?>" required style="width:200px"></td></tr>
    <tr><td><strong><?php echo '📅 ' . esc_html__('Date de fin', 'scout-gm'); ?></strong></td><td><input type="date" name="event_date_end" value="<?php echo esc_attr($date_end); ?>" style="width:200px"> <span class="description"><?php esc_html_e('Laisser vide pour un événement d\'une journée', 'scout-gm'); ?></span></td></tr>
    <tr><td><strong><?php echo '🕐 ' . esc_html__('Heure début', 'scout-gm'); ?></strong></td><td><input type="time" name="event_time_start" value="<?php echo esc_attr($time_start); ?>" style="width:140px"></td></tr>
    <tr><td><strong><?php echo '🕐 ' . esc_html__('Heure fin', 'scout-gm'); ?></strong></td><td><input type="time" name="event_time_end" value="<?php echo esc_attr($time_end); ?>" style="width:140px"></td></tr>
    <tr><td><strong><?php echo '📍 ' . esc_html__('Lieu', 'scout-gm'); ?></strong></td><td><input type="text" name="event_location" value="<?php echo esc_attr($location); ?>" style="width:100%" placeholder="<?php esc_attr_e('Ex: École Les Mésanges', 'scout-gm'); ?>"></td></tr>
    <tr><td><strong><?php echo '🏷️ ' . esc_html__('Unité / Type', 'scout-gm'); ?></strong></td><td>
        <select name="event_unit" style="width:200px">
            <option value="special" <?php selected($unit, 'special'); ?>><?php echo '🌟 ' . esc_html__('Événement spécial', 'scout-gm'); ?></option>
            <option value="castor" <?php selected($unit, 'castor'); ?>><?php echo '🦫 ' . esc_html__('Castors', 'scout-gm'); ?></option>
            <option value="louveteau" <?php selected($unit, 'louveteau'); ?>><?php echo '🐺 ' . esc_html__('Louveteaux', 'scout-gm'); ?></option>
            <option value="eclaireur" <?php selected($unit, 'eclaireur'); ?>><?php echo '🧭 ' . esc_html__('Éclaireurs', 'scout-gm'); ?></option>
            <option value="pionnier" <?php selected($unit, 'pionnier'); ?>><?php echo '⚜ ' . esc_html__('Pionniers', 'scout-gm'); ?></option>
            <option value="groupe" <?php selected($unit, 'groupe'); ?>><?php echo '👥 ' . esc_html__('Tout le groupe', 'scout-gm'); ?></option>
        </select>
    </td></tr>
    </table>

    <hr style="margin:16px 0">
    <h3 style="margin:0 0 8px"><?php echo '🔁 ' . esc_html__('Récurrence', 'scout-gm'); ?></h3>
    <table class="scout-evt-table" style="width:100%">
    <tr><td style="width:160px"><strong><?php esc_html_e('Répéter', 'scout-gm'); ?></strong></td><td>
        <select name="event_recurrence" id="eventRecurrence" style="width:200px" onchange="document.getElementById('recurrenceOpts').style.display=this.value==='none'?'none':'table-row-group'">
            <option value="none" <?php selected($recurrence, 'none'); ?>><?php esc_html_e('Ne pas répéter', 'scout-gm'); ?></option>
            <option value="weekly" <?php selected($recurrence, 'weekly'); ?>><?php esc_html_e('Chaque semaine', 'scout-gm'); ?></option>
            <option value="biweekly" <?php selected($recurrence, 'biweekly'); ?>><?php esc_html_e('Aux deux semaines', 'scout-gm'); ?></option>
            <option value="monthly" <?php selected($recurrence, 'monthly'); ?>><?php esc_html_e('Chaque mois (même jour du mois)', 'scout-gm'); ?></option>
        </select>
    </td></tr>
    <tbody id="recurrenceOpts" style="<?php echo $recurrence === 'none' ? 'display:none' : ''; ?>">
    <tr><td><strong><?php esc_html_e('Répéter jusqu\'au', 'scout-gm'); ?></strong></td><td><input type="date" name="event_recurrence_end" value="<?php echo esc_attr($recurrence_end); ?>" style="width:200px"> <span class="description"><?php esc_html_e('Date de fin de la récurrence', 'scout-gm'); ?></span></td></tr>
    <tr><td><strong><?php esc_html_e('Exceptions', 'scout-gm'); ?></strong></td><td><input type="text" name="event_recurrence_exceptions" value="<?php echo esc_attr($recurrence_exceptions); ?>" style="width:100%" placeholder="2026-03-14, 2026-04-18"> <span class="description"><?php esc_html_e('Dates à exclure (séparées par des virgules, format AAAA-MM-JJ)', 'scout-gm'); ?></span></td></tr>
    </tbody>
    </table>
    <p class="description" style="margin-top:8px"><?php printf(
        /* translators: %1$s and %2$s are bold labels */
        '💡 <strong>%1$s</strong> %2$s<br>💡 <strong>%3$s</strong> %4$s',
        esc_html__('Multi-jours :', 'scout-gm'),
        esc_html__('Remplissez la date de fin pour les camps et sorties. L\'événement apparaîtra sur chaque jour entre les deux dates.', 'scout-gm'),
        esc_html__('Récurrence :', 'scout-gm'),
        esc_html__('Utilisez la récurrence pour des activités régulières (ex: entraînement chaque lundi). Les exceptions permettent de sauter des dates spécifiques (vacances, congés).', 'scout-gm')
    ); ?></p>
    <?php
}

function scout_gm_event_meta_save($post_id) {
    if (!isset($_POST['scout_event_nonce']) || !wp_verify_nonce($_POST['scout_event_nonce'], 'scout_event_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    $fields = [
        'event_date' => '_event_date',
        'event_date_end' => '_event_date_end',
        'event_time_start' => '_event_time_start',
        'event_time_end' => '_event_time_end',
        'event_location' => '_event_location',
        'event_unit' => '_event_unit',
        'event_recurrence' => '_event_recurrence',
        'event_recurrence_end' => '_event_recurrence_end',
        'event_recurrence_exceptions' => '_event_recurrence_exceptions',
    ];
    foreach ($fields as $input => $meta) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta, sanitize_text_field($_POST[$input]));
        }
    }
}
add_action('save_post_scout_event', 'scout_gm_event_meta_save');

// ── VOLUNTEER OPPORTUNITIES ──
function scout_gm_volunteer_setup() {
    register_post_type('scout_volunteer', [
        'labels' => [
            'name' => __('Appels aux bénévoles', 'scout-gm'),
            'singular_name' => __('Appel', 'scout-gm'),
            'add_new' => __('Ajouter un appel', 'scout-gm'),
            'add_new_item' => __('Nouvel appel aux bénévoles', 'scout-gm'),
            'edit_item' => __('Modifier l\'appel', 'scout-gm'),
            'all_items' => __('Tous les appels', 'scout-gm'),
            'search_items' => __('Chercher un appel', 'scout-gm'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => ['title', 'editor'],
        'has_archive' => false,
        'rewrite' => ['slug' => 'benevoles/appel'],
    ]);
}
add_action('init', 'scout_gm_volunteer_setup');

function scout_gm_volunteer_meta_boxes() {
    add_meta_box('scout_volunteer_details', __('Détails de l\'appel', 'scout-gm'), 'scout_gm_volunteer_meta_render', 'scout_volunteer', 'normal', 'high');
}
add_action('add_meta_boxes', 'scout_gm_volunteer_meta_boxes');

function scout_gm_volunteer_meta_render($post) {
    wp_nonce_field('scout_volunteer_meta', 'scout_volunteer_nonce');
    $category = get_post_meta($post->ID, '_vol_category', true) ?: 'general';
    $spots = get_post_meta($post->ID, '_vol_spots', true) ?: '';
    $filled = get_post_meta($post->ID, '_vol_filled', true) ?: 0;
    $date_event = get_post_meta($post->ID, '_vol_date', true);
    $date_end = get_post_meta($post->ID, '_vol_date_end', true);
    $location = get_post_meta($post->ID, '_vol_location', true);
    $urgency = get_post_meta($post->ID, '_vol_urgency', true) ?: 'normal';
    $contact_email = get_post_meta($post->ID, '_vol_contact', true);
    ?>
    <table style="width:100%">
    <tr><td style="width:160px"><strong><?php echo '📁 ' . esc_html__('Catégorie', 'scout-gm'); ?></strong></td><td>
        <select name="vol_category" style="width:200px">
            <option value="cuisine" <?php selected($category, 'cuisine'); ?>><?php echo '🍳 ' . esc_html__('Cuisine / Camp', 'scout-gm'); ?></option>
            <option value="aide_camp" <?php selected($category, 'aide_camp'); ?>><?php echo '⛺ ' . esc_html__('Aide au camp', 'scout-gm'); ?></option>
            <option value="transport" <?php selected($category, 'transport'); ?>><?php echo '🚗 ' . esc_html__('Transport', 'scout-gm'); ?></option>
            <option value="animation" <?php selected($category, 'animation'); ?>><?php echo '🎪 ' . esc_html__('Animation', 'scout-gm'); ?></option>
            <option value="logistique" <?php selected($category, 'logistique'); ?>><?php echo '📦 ' . esc_html__('Logistique', 'scout-gm'); ?></option>
            <option value="evenement" <?php selected($category, 'evenement'); ?>><?php echo '🎉 ' . esc_html__('Événement spécial', 'scout-gm'); ?></option>
            <option value="formation" <?php selected($category, 'formation'); ?>><?php echo '📚 ' . esc_html__('Formation', 'scout-gm'); ?></option>
            <option value="general" <?php selected($category, 'general'); ?>><?php echo '🤝 ' . esc_html__('Aide générale', 'scout-gm'); ?></option>
        </select>
    </td></tr>
    <tr><td><strong><?php echo '🔥 ' . esc_html__('Urgence', 'scout-gm'); ?></strong></td><td>
        <select name="vol_urgency" style="width:200px">
            <option value="low" <?php selected($urgency, 'low'); ?>><?php echo '🟢 ' . esc_html__('Faible — on a le temps', 'scout-gm'); ?></option>
            <option value="normal" <?php selected($urgency, 'normal'); ?>><?php echo '🟡 ' . esc_html__('Normal', 'scout-gm'); ?></option>
            <option value="high" <?php selected($urgency, 'high'); ?>><?php echo '🟠 ' . esc_html__('Urgent', 'scout-gm'); ?></option>
            <option value="critical" <?php selected($urgency, 'critical'); ?>><?php echo '🔴 ' . esc_html__('Critique — besoin immédiat!', 'scout-gm'); ?></option>
        </select>
    </td></tr>
    <tr><td><strong><?php echo '👥 ' . esc_html__('Places disponibles', 'scout-gm'); ?></strong></td><td>
        <input type="number" name="vol_spots" value="<?php echo esc_attr($spots); ?>" min="0" style="width:80px" placeholder="∞">
        <span class="description"><?php esc_html_e('Laisser vide = illimité', 'scout-gm'); ?></span>
    </td></tr>
    <tr><td><strong><?php echo '✅ ' . esc_html__('Places comblées', 'scout-gm'); ?></strong></td><td>
        <input type="number" name="vol_filled" value="<?php echo esc_attr($filled); ?>" min="0" style="width:80px">
    </td></tr>
    <tr><td><strong><?php echo '📅 ' . esc_html__('Date de l\'activité', 'scout-gm'); ?></strong></td><td>
        <input type="date" name="vol_date" value="<?php echo esc_attr($date_event); ?>" style="width:200px">
        <input type="date" name="vol_date_end" value="<?php echo esc_attr($date_end); ?>" style="width:200px">
        <span class="description"><?php esc_html_e('Début — Fin (optionnel)', 'scout-gm'); ?></span>
    </td></tr>
    <tr><td><strong><?php echo '📍 ' . esc_html__('Lieu', 'scout-gm'); ?></strong></td><td>
        <input type="text" name="vol_location" value="<?php echo esc_attr($location); ?>" style="width:100%" placeholder="<?php esc_attr_e('Ex: Camp Tamaracouta, École Les Mésanges', 'scout-gm'); ?>">
    </td></tr>
    <tr><td><strong><?php echo '✉️ ' . esc_html__('Courriel de contact', 'scout-gm'); ?></strong></td><td>
        <input type="email" name="vol_contact" value="<?php echo esc_attr($contact_email); ?>" style="width:100%" placeholder="<?php esc_attr_e('Laisser vide = courriel du groupe', 'scout-gm'); ?>">
    </td></tr>
    </table>
    <?php
}

function scout_gm_volunteer_meta_save($post_id) {
    if (!isset($_POST['scout_volunteer_nonce']) || !wp_verify_nonce($_POST['scout_volunteer_nonce'], 'scout_volunteer_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    $fields = [
        'vol_category' => '_vol_category', 'vol_urgency' => '_vol_urgency',
        'vol_spots' => '_vol_spots', 'vol_filled' => '_vol_filled',
        'vol_date' => '_vol_date', 'vol_date_end' => '_vol_date_end',
        'vol_location' => '_vol_location', 'vol_contact' => '_vol_contact',
    ];
    foreach ($fields as $input => $meta) {
        if (isset($_POST[$input])) update_post_meta($post_id, $meta, sanitize_text_field($_POST[$input]));
    }
}
add_action('save_post_scout_volunteer', 'scout_gm_volunteer_meta_save');

// Helper: get active volunteer opportunities
function scout_gm_get_volunteer_opportunities() {
    return get_posts([
        'post_type' => 'scout_volunteer',
        'post_status' => 'publish',
        'posts_per_page' => 50,
        'orderby' => 'meta_value',
        'meta_key' => '_vol_date',
        'order' => 'ASC',
    ]);
}

// ── RECURRING SCHEDULE (Customizer) ──
function scout_gm_schedule_customize($wp_customize) {
    $wp_customize->add_section('scout_gm_schedule', [
        'title' => __('Horaires récurrents', 'scout-gm'),
        'description' => __('Définir les réunions récurrentes par unité. Format: jour de la semaine (1=lundi, 5=vendredi, 7=dimanche).', 'scout-gm'),
        'priority' => 35,
    ]);
    $units = [
        'castor' => ['label' => 'Castors', 'day' => 5, 'start' => '18:45', 'end' => '20:45', 'location' => 'École Les Mésanges'],
        'louveteau' => ['label' => 'Louveteaux', 'day' => 2, 'start' => '18:45', 'end' => '20:45', 'location' => 'École Les Mésanges'],
        'eclaireur' => ['label' => 'Éclaireurs', 'day' => 7, 'start' => '18:00', 'end' => '21:00', 'location' => 'Local des Lions'],
        'pionnier' => ['label' => 'Pionniers', 'day' => 0, 'start' => '', 'end' => '', 'location' => ''],
    ];
    foreach ($units as $key => $defaults) {
        $wp_customize->add_setting("scout_schedule_{$key}_day", ['default' => $defaults['day'], 'sanitize_callback' => 'absint']);
        $wp_customize->add_control("scout_schedule_{$key}_day", ['label' => $defaults['label'] . ' — Jour (0=off, 1=lun, 7=dim)', 'section' => 'scout_gm_schedule', 'type' => 'number', 'input_attrs' => ['min' => 0, 'max' => 7]]);
        $wp_customize->add_setting("scout_schedule_{$key}_start", ['default' => $defaults['start'], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("scout_schedule_{$key}_start", ['label' => $defaults['label'] . ' — Début', 'section' => 'scout_gm_schedule', 'type' => 'time']);
        $wp_customize->add_setting("scout_schedule_{$key}_end", ['default' => $defaults['end'], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("scout_schedule_{$key}_end", ['label' => $defaults['label'] . ' — Fin', 'section' => 'scout_gm_schedule', 'type' => 'time']);
        $wp_customize->add_setting("scout_schedule_{$key}_location", ['default' => $defaults['location'], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("scout_schedule_{$key}_location", ['label' => $defaults['label'] . ' — Lieu', 'section' => 'scout_gm_schedule', 'type' => 'text']);
    }
}
add_action('customize_register', 'scout_gm_schedule_customize');

// Helper: get events for a given month
function scout_gm_get_month_events($year, $month) {
    $events = [];
    $month_start = sprintf('%04d-%02d-01', $year, $month);
    $month_end = date('Y-m-t', strtotime($month_start));
    $days_in_month = intval(date('t', strtotime($month_start)));

    // 1) Custom events from DB — fetch all that could overlap this month
    $posts = get_posts([
        'post_type' => 'scout_event',
        'posts_per_page' => 200,
        'post_status' => 'publish',
        'meta_query' => [
            'relation' => 'OR',
            // Single/start date in this month
            ['key' => '_event_date', 'value' => [$month_start, $month_end], 'compare' => 'BETWEEN', 'type' => 'DATE'],
            // Multi-day ending in this month
            ['key' => '_event_date_end', 'value' => [$month_start, $month_end], 'compare' => 'BETWEEN', 'type' => 'DATE'],
            // Multi-day spanning this entire month
            [
                'relation' => 'AND',
                ['key' => '_event_date', 'value' => $month_start, 'compare' => '<', 'type' => 'DATE'],
                ['key' => '_event_date_end', 'value' => $month_end, 'compare' => '>', 'type' => 'DATE'],
            ],
        ],
    ]);

    foreach ($posts as $p) {
        $start = get_post_meta($p->ID, '_event_date', true);
        $end = get_post_meta($p->ID, '_event_date_end', true);
        $recurrence = get_post_meta($p->ID, '_event_recurrence', true) ?: 'none';
        $rec_end = get_post_meta($p->ID, '_event_recurrence_end', true);
        $exceptions_raw = get_post_meta($p->ID, '_event_recurrence_exceptions', true);
        $exceptions = $exceptions_raw ? array_map('trim', explode(',', $exceptions_raw)) : [];

        $event_data = [
            'title' => $p->post_title,
            'unit' => get_post_meta($p->ID, '_event_unit', true) ?: 'special',
            'time_start' => get_post_meta($p->ID, '_event_time_start', true),
            'time_end' => get_post_meta($p->ID, '_event_time_end', true),
            'location' => get_post_meta($p->ID, '_event_location', true),
            'type' => 'custom',
        ];

        // Collect all dates this event falls on
        $event_dates = [];

        if ($recurrence !== 'none' && $start) {
            // Recurring event — generate occurrences
            $current = strtotime($start);
            $limit = $rec_end ? strtotime($rec_end) : strtotime($month_end);
            $limit = min($limit, strtotime($month_end));

            while ($current <= $limit) {
                $date_str = date('Y-m-d', $current);
                if (!in_array($date_str, $exceptions)) {
                    // If multi-day, expand each occurrence
                    if ($end && $end > $start) {
                        $duration_days = (strtotime($end) - strtotime($start)) / 86400;
                        for ($dd = 0; $dd <= $duration_days; $dd++) {
                            $occ = date('Y-m-d', $current + $dd * 86400);
                            if ($occ >= $month_start && $occ <= $month_end) $event_dates[] = $occ;
                        }
                    } else {
                        if ($date_str >= $month_start && $date_str <= $month_end) $event_dates[] = $date_str;
                    }
                }

                // Advance to next occurrence
                if ($recurrence === 'weekly') $current = strtotime('+1 week', $current);
                elseif ($recurrence === 'biweekly') $current = strtotime('+2 weeks', $current);
                elseif ($recurrence === 'monthly') $current = strtotime('+1 month', $current);
                else break;
            }
        } elseif ($end && $end > $start) {
            // Multi-day (no recurrence) — fill every day in range
            $cs = max(strtotime($start), strtotime($month_start));
            $ce = min(strtotime($end), strtotime($month_end));
            for ($t = $cs; $t <= $ce; $t += 86400) {
                $event_dates[] = date('Y-m-d', $t);
            }
            // Add multi-day indicator
            $event_data['multi_day'] = true;
            $event_data['day_start'] = $start;
            $event_data['day_end'] = $end;
        } else {
            // Simple single-day event
            if ($start >= $month_start && $start <= $month_end) {
                $event_dates[] = $start;
            }
        }

        // Add to events array
        foreach ($event_dates as $date_str) {
            $day = intval(date('j', strtotime($date_str)));
            $evt = $event_data;
            // Mark first/last day for multi-day events
            if (!empty($evt['multi_day'])) {
                if ($date_str === $evt['day_start']) $evt['title'] .= ' (début)';
                elseif ($date_str === $evt['day_end']) $evt['title'] .= ' (fin)';
                else $evt['title'] .= ' (suite)';
            }
            $events[$day][] = $evt;
        }
    }

    // 2) Auto-fill recurring unit meetings (from Customizer schedule)
    $units = ['castor' => 'Castors', 'louveteau' => 'Louveteaux', 'eclaireur' => 'Éclaireurs', 'pionnier' => 'Pionniers'];
    $php_days = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 0];

    foreach ($units as $key => $label) {
        $day_num = intval(get_theme_mod("scout_schedule_{$key}_day", 0));
        if ($day_num < 1 || $day_num > 7) continue;
        $php_dow = $php_days[$day_num];
        $start_time = get_theme_mod("scout_schedule_{$key}_start", '');

        for ($d = 1; $d <= $days_in_month; $d++) {
            $date_str = sprintf('%04d-%02d-%02d', $year, $month, $d);
            if (intval(date('w', strtotime($date_str))) === $php_dow) {
                $dominated = false;
                if (isset($events[$d])) {
                    foreach ($events[$d] as $existing) {
                        if ($existing['unit'] === $key && $existing['type'] === 'custom') {
                            $dominated = true;
                            break;
                        }
                    }
                }
                if (!$dominated) {
                    $events[$d][] = [
                        'title' => $label,
                        'unit' => $key,
                        'time_start' => $start_time,
                        'type' => 'recurring',
                    ];
                }
            }
        }
    }

    return $events;
}


// ── BÉNÉVOLES: Configurable teams + gendered titles + admin page ──

function scout_gm_get_teams() {
    $default = [
        ['slug' => 'conseil', 'name' => 'Conseil d\'administration', 'description' => 'Le conseil assure la gestion, la planification et la pérennité du groupe scout.'],
        ['slug' => 'animation', 'name' => 'Équipe d\'animation', 'description' => 'Les animateurs et animatrices qui vivent les aventures au quotidien avec vos jeunes.'],
    ];
    return get_option('scout_gm_teams', $default);
}

// Configurable units
function scout_gm_get_units() {
    $default = [
        ['slug' => 'castors', 'name' => 'Castors', 'age' => '7-8 ans', 'badge_url' => 'https://scoutsducanada.ca/content/uploads/2023/12/20230109_-_Badge_castor_final.png', 'bg_color' => '#fef3c7', 'text_color' => '#92400e', 'accent_color' => '#d4a017'],
        ['slug' => 'louveteaux', 'name' => 'Louveteaux', 'age' => '9-11 ans', 'badge_url' => 'https://scoutsducanada.ca/content/uploads/2023/06/badge_louveteau.png', 'bg_color' => '#d1fae5', 'text_color' => '#047857', 'accent_color' => '#007748'],
        ['slug' => 'eclaireurs', 'name' => 'Éclaireurs', 'age' => '12-14 ans', 'badge_url' => 'https://scoutsducanada.ca/content/uploads/2023/08/badge_eclaireur.png', 'bg_color' => '#dbeafe', 'text_color' => '#1d4ed8', 'accent_color' => '#0065cc'],
        ['slug' => 'pionniers', 'name' => 'Pionniers', 'age' => '14-17 ans', 'badge_url' => 'https://scoutsducanada.ca/content/uploads/2023/08/badge_pionnier.png', 'bg_color' => '#fee2e2', 'text_color' => '#b91c1c', 'accent_color' => '#c0392b'],
        ['slug' => 'administration', 'name' => 'Administration', 'age' => '', 'badge_url' => '', 'bg_color' => '#e0f2fe', 'text_color' => '#0e7490', 'accent_color' => '#0e7490'],
    ];
    return get_option('scout_gm_units', $default);
}

// Helper: get unit data by slug
function scout_gm_get_unit($slug) {
    $units = scout_gm_get_units();
    // Default accent colors for known units (fallback if not in saved data)
    $default_accents = [
        'castors' => '#d4a017', 'louveteaux' => '#007748',
        'eclaireurs' => '#0065cc', 'pionniers' => '#c0392b',
        'administration' => '#0e7490',
    ];
    foreach ($units as $u) {
        if ($u['slug'] === $slug) {
            // Ensure accent_color exists
            if (empty($u['accent_color'])) {
                $u['accent_color'] = $default_accents[$slug] ?? $u['text_color'] ?? '#007748';
            }
            return $u;
        }
    }
    return null;
}

// Helper: get unit dropdown with checkboxes (multi-select)
function scout_gm_unit_checkboxes($name, $selected_slugs = []) {
    static $dropdown_id = 0;
    $dropdown_id++;
    $units = scout_gm_get_units();
    if (is_string($selected_slugs)) $selected_slugs = array_filter(explode(',', $selected_slugs));
    
    // Build selected label
    $selected_names = [];
    foreach ($units as $u) {
        if (in_array($u['slug'], $selected_slugs)) $selected_names[] = $u['name'];
    }
    $label = !empty($selected_names) ? implode(', ', $selected_names) : '— Aucune —';
    
    $html = '<div class="scout-unit-dropdown" style="position:relative;display:inline-block;width:100%">';
    $html .= '<div onclick="toggleUnitDrop(' . $dropdown_id . ')" style="padding:4px 8px;border:1px solid #8c8f94;border-radius:4px;background:#fff;cursor:pointer;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-height:24px;line-height:16px;display:flex;align-items:center;justify-content:space-between">';
    $html .= '<span id="unitLabel' . $dropdown_id . '">' . esc_html($label) . '</span>';
    $html .= '<span style="font-size:9px;margin-left:4px">▼</span></div>';
    $html .= '<div id="unitDrop' . $dropdown_id . '" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #8c8f94;border-radius:4px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:9999;max-height:200px;overflow-y:auto;margin-top:2px">';
    
    foreach ($units as $u) {
        $checked = in_array($u['slug'], $selected_slugs) ? ' checked' : '';
        $bg = esc_attr($u['accent_color'] ?? $u['text_color'] ?? '#007748');
        $html .= '<label style="display:flex;align-items:center;gap:6px;padding:5px 8px;cursor:pointer;font-size:11px;border-bottom:1px solid #f0f0f0;transition:background 0.1s" onmouseover="this.style.background=\'#f5f5f5\'" onmouseout="this.style.background=\'#fff\'">';
        $html .= '<input type="checkbox" name="' . esc_attr($name) . '[]" value="' . esc_attr($u['slug']) . '"' . $checked . ' onchange="updateUnitLabel(' . $dropdown_id . ')" style="accent-color:' . $bg . '">';
        if (!empty($u['badge_url'])) {
            $html .= '<img src="' . esc_url($u['badge_url']) . '" style="width:16px;height:16px;object-fit:contain">';
        } else {
            $html .= '<span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:' . $bg . '"></span>';
        }
        $html .= '<span>' . esc_html($u['name']) . '</span>';
        $html .= '</label>';
    }
    
    $html .= '</div></div>';
    return $html;
}

// Helper: get team/section dropdown with checkboxes (multi-select)
function scout_gm_team_checkboxes($name, $selected_slugs = []) {
    static $team_dropdown_id = 0;
    $team_dropdown_id++;
    $did = 't' . $team_dropdown_id;
    $teams = scout_gm_get_teams();
    if (is_string($selected_slugs)) $selected_slugs = array_filter(explode(',', $selected_slugs));

    $selected_names = [];
    foreach ($teams as $t) {
        if (in_array($t['slug'], $selected_slugs)) $selected_names[] = $t['name'];
    }
    $label = !empty($selected_names) ? implode(', ', $selected_names) : '— Aucune —';

    $html = '<div class="scout-unit-dropdown" style="position:relative;display:inline-block;width:100%">';
    $html .= '<div onclick="toggleUnitDrop(\'' . $did . '\')" style="padding:4px 8px;border:1px solid #8c8f94;border-radius:4px;background:#fff;cursor:pointer;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-height:24px;line-height:16px;display:flex;align-items:center;justify-content:space-between">';
    $html .= '<span id="unitLabel' . $did . '">' . esc_html($label) . '</span>';
    $html .= '<span style="font-size:9px;margin-left:4px">▼</span></div>';
    $html .= '<div id="unitDrop' . $did . '" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #8c8f94;border-radius:4px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:9999;max-height:200px;overflow-y:auto;margin-top:2px">';

    foreach ($teams as $t) {
        $checked = in_array($t['slug'], $selected_slugs) ? ' checked' : '';
        $html .= '<label style="display:flex;align-items:center;gap:6px;padding:5px 8px;cursor:pointer;font-size:11px;border-bottom:1px solid #f0f0f0;transition:background 0.1s" onmouseover="this.style.background=\'#f5f5f5\'" onmouseout="this.style.background=\'#fff\'">';
        $html .= '<input type="checkbox" name="' . esc_attr($name) . '[]" value="' . esc_attr($t['slug']) . '"' . $checked . ' onchange="updateUnitLabel(\'' . $did . '\')" style="accent-color:#007748">';
        $html .= '<span>' . esc_html($t['name']) . '</span>';
        $html .= '</label>';
    }

    $html .= '</div></div>';
    return $html;
}

// Helper: get all team slugs for a user
function scout_gm_get_user_teams($user_id) {
    $teams_str = get_user_meta($user_id, 'scout_teams', true);
    if ($teams_str) return array_filter(explode(',', $teams_str));
    // Migration: old single scout_team field
    $old = get_user_meta($user_id, 'scout_team', true);
    if ($old && $old !== 'none') return [$old];
    return [];
}
function scout_gm_get_user_units($user_id) {
    $slugs_str = get_user_meta($user_id, 'scout_units', true);
    if (!$slugs_str) {
        // Migration: check old single scout_unit field
        $old = get_user_meta($user_id, 'scout_unit', true);
        if ($old) return [scout_gm_get_unit($old)];
        return [];
    }
    $slugs = array_filter(explode(',', $slugs_str));
    $result = [];
    foreach ($slugs as $s) {
        $ud = scout_gm_get_unit(trim($s));
        if ($ud) $result[] = $ud;
    }
    return $result;
}

function scout_gm_benevoles_admin_menu() {
    add_menu_page('Bénévoles', 'Bénévoles', 'manage_options', 'scout-benevoles', 'scout_gm_benevoles_admin_page', 'dashicons-groups', 26);
}
add_action('admin_menu', 'scout_gm_benevoles_admin_menu');

function scout_gm_benevoles_admin_page() {
    // Save generic email
    if (isset($_POST['scout_save_settings_ben']) && wp_verify_nonce($_POST['_scout_ben_settings_nonce'], 'scout_save_settings_ben')) {
        $emails = [];
        $e_labels = $_POST['email_label'] ?? [];
        $e_addrs = $_POST['email_addr'] ?? [];
        for ($i = 0; $i < count($e_addrs); $i++) {
            $addr = sanitize_email($e_addrs[$i]);
            $label = sanitize_text_field($e_labels[$i] ?? '');
            if ($addr) $emails[] = ['label' => $label ?: $addr, 'email' => $addr];
        }
        update_option('scout_gm_emails', $emails);
        // Keep backwards compat
        if (!empty($emails)) update_option('scout_gm_generic_email', $emails[0]['email']);
        echo '<div class="notice notice-success"><p>Courriels sauvegardés!</p></div>';
    }
    // Save teams
    if (isset($_POST['scout_save_teams']) && wp_verify_nonce($_POST['_scout_teams_nonce'], 'scout_save_teams')) {
        $teams = [];
        $slugs = $_POST['team_slug'] ?? []; $names = $_POST['team_name'] ?? []; $descs = $_POST['team_desc'] ?? [];
        for ($i = 0; $i < count($slugs); $i++) {
            $slug = sanitize_key($slugs[$i]); $name = sanitize_text_field($names[$i] ?? '');
            if ($slug && $name) $teams[] = ['slug' => $slug, 'name' => $name, 'description' => sanitize_text_field($descs[$i] ?? '')];
        }
        if (!empty($teams)) update_option('scout_gm_teams', $teams);
        echo '<div class="notice notice-success"><p>Sections sauvegardées!</p></div>';
    }
    // Save titles
    if (isset($_POST['scout_save_titles']) && wp_verify_nonce($_POST['_scout_titles_nonce'], 'scout_save_titles')) {
        $titles = []; $tm = $_POST['title_m'] ?? []; $tf = $_POST['title_f'] ?? [];
        for ($i = 0; $i < count($tm); $i++) {
            $m = sanitize_text_field($tm[$i]); $f = sanitize_text_field($tf[$i] ?? '');
            if ($m) $titles[] = ['m' => $m, 'f' => $f ?: $m];
        }
        update_option('scout_gm_titles', $titles);
        echo '<div class="notice notice-success"><p>Titres sauvegardés!</p></div>';
    }
    // Save units
    if (isset($_POST['scout_save_units']) && wp_verify_nonce($_POST['_scout_units_nonce'], 'scout_save_units')) {
        $units = [];
        $uslugs = $_POST['unit_slug'] ?? [];
        $unames = $_POST['unit_name'] ?? [];
        $uages = $_POST['unit_age'] ?? [];
        $ubadges = $_POST['unit_badge'] ?? [];
        $ubgs = $_POST['unit_bg'] ?? [];
        $utxts = $_POST['unit_txt'] ?? [];
        $uaccents = $_POST['unit_accent'] ?? [];
        for ($i = 0; $i < count($uslugs); $i++) {
            $slug = sanitize_key($uslugs[$i]);
            $name = sanitize_text_field($unames[$i] ?? '');
            if ($slug && $name) {
                $units[] = [
                    'slug' => $slug, 'name' => $name,
                    'age' => sanitize_text_field($uages[$i] ?? ''),
                    'badge_url' => esc_url_raw($ubadges[$i] ?? ''),
                    'bg_color' => sanitize_hex_color($ubgs[$i] ?? '#e0f2fe'),
                    'text_color' => sanitize_hex_color($utxts[$i] ?? '#0369a1'),
                    'accent_color' => sanitize_hex_color($uaccents[$i] ?? '#007748'),
                ];
            }
        }
        if (!empty($units)) update_option('scout_gm_units', $units);
        echo '<div class="notice notice-success"><p>Unités sauvegardées!</p></div>';
    }
    // Save members
    if (isset($_POST['scout_save_members']) && wp_verify_nonce($_POST['_scout_members_nonce'], 'scout_save_members')) {
        $uids = $_POST['member_user_id'] ?? [];
        $mteams = $_POST['member_teams'] ?? [];
        $mtitle = $_POST['member_title_idx'] ?? [];
        $mgen = $_POST['member_gender'] ?? [];
        $munit = $_POST['member_units'] ?? [];
        $mphone = $_POST['member_phone'] ?? [];
        $mvis = $_POST['member_visible'] ?? [];
        $mmask_keys = $_POST['member_mask_email_key'] ?? [];
        $titles = get_option('scout_gm_titles', []);
        foreach ($uids as $i => $uid) {
            $uid = intval($uid);
            if (!$uid) continue;
            $tidx = intval($mtitle[$i] ?? -1);
            $title_m = ($tidx >= 0 && isset($titles[$tidx])) ? $titles[$tidx]['m'] : '';
            $title_f = ($tidx >= 0 && isset($titles[$tidx])) ? $titles[$tidx]['f'] : '';
            update_user_meta($uid, 'scout_visible', in_array($uid, $mvis) ? '1' : '0');
            update_user_meta($uid, 'scout_teams', sanitize_text_field(implode(',', array_map('sanitize_key', $mteams[$uid] ?? []))));
            update_user_meta($uid, 'scout_title_m', $title_m);
            update_user_meta($uid, 'scout_title_f', $title_f);
            update_user_meta($uid, 'scout_title_idx', $tidx);
            update_user_meta($uid, 'scout_gender', sanitize_text_field($mgen[$i] ?? ''));
            update_user_meta($uid, 'scout_units', sanitize_text_field(implode(',', array_map('sanitize_key', $munit[$uid] ?? []))));
            update_user_meta($uid, 'scout_phone', sanitize_text_field($mphone[$i] ?? ''));
            update_user_meta($uid, 'scout_sort_order', intval($i + 1));
            // Email masking: empty = show personal, number = index in emails list
            $mask_key = isset($mmask_keys[$uid]) ? sanitize_text_field($mmask_keys[$uid]) : '';
            update_user_meta($uid, 'scout_mask_email_key', $mask_key);
            update_user_meta($uid, 'scout_mask_email', $mask_key !== '' ? '1' : '0');
        }
        echo '<div class="notice notice-success"><p>Membres sauvegardés!</p></div>';
    }

    $teams = scout_gm_get_teams();
    $titles = get_option('scout_gm_titles', [
        ['m'=>'Président','f'=>'Présidente'],['m'=>'Vice-Président','f'=>'Vice-Présidente'],
        ['m'=>'Secrétaire','f'=>'Secrétaire'],['m'=>'Trésorier','f'=>'Trésorière'],
        ['m'=>'Animateur','f'=>'Animatrice'],['m'=>'Aide-animateur','f'=>'Aide-animatrice'],
        ['m'=>'Responsable logistique','f'=>'Responsable logistique'],['m'=>'VP Communications','f'=>'VP Communications'],
    ]);
    $generic_email = get_option('scout_gm_generic_email', '');
    $emails_list = get_option('scout_gm_emails', []);
    if (empty($emails_list) && $generic_email) {
        $emails_list = [['label' => 'Général', 'email' => $generic_email]];
    }
    // Get users sorted by their current order
    // Only show users who are NOT plain subscribers
    $all_users_raw = get_users(['orderby' => 'display_name', 'order' => 'ASC', 'number' => 200]);
    $all_users = array_filter($all_users_raw, function($u) {
        return !in_array('subscriber', $u->roles) || count($u->roles) > 1;
    });
    // Sort by scout_sort_order (users without it go to the end)
    usort($all_users, function($a, $b) {
        $oa = intval(get_user_meta($a->ID, 'scout_sort_order', true) ?: 999);
        $ob = intval(get_user_meta($b->ID, 'scout_sort_order', true) ?: 999);
        return $oa - $ob;
    });
    ?>
    <style>
    #membersBody tr{cursor:grab}#membersBody tr.dragging{opacity:0.4;background:#e8f4ff}
    #membersBody tr.drag-over{border-top:3px solid #007748}
    .mask-col{text-align:center}
    </style>

    <div class="wrap"><h1>⚜️ Gestion des bénévoles</h1>

    <!-- EMAILS -->
    <div class="postbox" style="padding:20px;margin-top:20px">
        <h2 style="margin-top:0">✉️ Courriels du groupe</h2>
        <p class="description">Gérez les adresses courriel du groupe. Vous pourrez ensuite choisir laquelle afficher pour chaque bénévole dont le courriel est masqué.</p>
        <form method="post"><?php wp_nonce_field('scout_save_settings_ben', '_scout_ben_settings_nonce'); ?>
        <table class="widefat" style="max-width:600px">
            <thead><tr><th>Étiquette</th><th>Adresse courriel</th><th style="width:40px"></th></tr></thead>
            <tbody id="emailsBody">
            <?php if (!empty($emails_list)): foreach ($emails_list as $ei => $em): ?>
            <tr>
                <td><input type="text" name="email_label[]" value="<?php echo esc_attr($em['label']); ?>" style="width:100%" placeholder="Ex: Castors, Administration"></td>
                <td><input type="email" name="email_addr[]" value="<?php echo esc_attr($em['email']); ?>" style="width:100%" placeholder="courriel@5escoutgrandmoulin.org"></td>
                <td><button type="button" onclick="this.closest('tr').remove()" class="button" style="color:#c0392b">✕</button></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td><input type="text" name="email_label[]" value="Général" style="width:100%"></td>
                <td><input type="email" name="email_addr[]" value="<?php echo esc_attr($generic_email); ?>" style="width:100%"></td>
                <td><button type="button" onclick="this.closest('tr').remove()" class="button" style="color:#c0392b">✕</button></td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top:8px">
            <button type="button" id="addEmailBtn" class="button">+ Ajouter un courriel</button>
            <button type="submit" name="scout_save_settings_ben" class="button button-primary" style="margin-left:8px">💾 Sauvegarder</button>
        </div>
        <script>
        document.getElementById('addEmailBtn').addEventListener('click', function(){
            var tb = document.getElementById('emailsBody');
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            var inp1 = document.createElement('input');
            inp1.type='text'; inp1.name='email_label[]'; inp1.style.width='100%'; inp1.placeholder='Étiquette';
            td1.appendChild(inp1);
            var inp2 = document.createElement('input');
            inp2.type='email'; inp2.name='email_addr[]'; inp2.style.width='100%'; inp2.placeholder='courriel@5escoutgrandmoulin.org';
            td2.appendChild(inp2);
            var btn = document.createElement('button');
            btn.type='button'; btn.className='button'; btn.style.color='#c0392b'; btn.textContent='✕';
            btn.onclick=function(){ this.closest('tr').remove(); };
            td3.appendChild(btn);
            tr.appendChild(td1); tr.appendChild(td2); tr.appendChild(td3);
            tb.appendChild(tr);
        });
        </script>
        </form>
    </div>

    <!-- SECTIONS -->
    <div class="postbox" style="padding:20px;margin-top:20px">
        <h2 style="margin-top:0">📋 Sections</h2>
        <form method="post"><?php wp_nonce_field('scout_save_teams', '_scout_teams_nonce'); ?>
        <table class="wp-list-table widefat fixed striped" id="teamsTable">
            <thead><tr><th style="width:150px">Identifiant</th><th>Nom</th><th>Description</th><th style="width:50px"></th></tr></thead>
            <tbody><?php foreach ($teams as $t): ?>
            <tr><td><input type="text" name="team_slug[]" value="<?php echo esc_attr($t['slug']); ?>" style="width:100%"></td><td><input type="text" name="team_name[]" value="<?php echo esc_attr($t['name']); ?>" style="width:100%"></td><td><input type="text" name="team_desc[]" value="<?php echo esc_attr($t['description']); ?>" style="width:100%"></td><td><button type="button" onclick="this.closest('tr').remove()" class="button" style="color:#c0392b">✕</button></td></tr>
            <?php endforeach; ?></tbody>
        </table>
        <div style="margin-top:8px;display:flex;gap:8px"><button type="button" class="button" onclick="addRow('teamsTable','<td><input type=text name=team_slug[] style=width:100%></td><td><input type=text name=team_name[] style=width:100%></td><td><input type=text name=team_desc[] style=width:100%></td>')">+ Ajouter</button><button type="submit" name="scout_save_teams" class="button button-primary">Sauvegarder</button></div>
        </form>
    </div>

    <!-- TITLES -->
    <div class="postbox" style="padding:20px;margin-top:20px">
        <h2 style="margin-top:0">🏷️ Titres / Rôles</h2>
        <form method="post"><?php wp_nonce_field('scout_save_titles', '_scout_titles_nonce'); ?>
        <table class="wp-list-table widefat fixed striped" id="titlesTable">
            <thead><tr><th>Titre masculin</th><th>Titre féminin</th><th style="width:50px"></th></tr></thead>
            <tbody><?php foreach ($titles as $t): ?>
            <tr><td><input type="text" name="title_m[]" value="<?php echo esc_attr($t['m']); ?>" style="width:100%"></td><td><input type="text" name="title_f[]" value="<?php echo esc_attr($t['f']); ?>" style="width:100%"></td><td><button type="button" onclick="this.closest('tr').remove()" class="button" style="color:#c0392b">✕</button></td></tr>
            <?php endforeach; ?></tbody>
        </table>
        <div style="margin-top:8px;display:flex;gap:8px"><button type="button" class="button" onclick="addRow('titlesTable','<td><input type=text name=title_m[] style=width:100% placeholder=Animateur></td><td><input type=text name=title_f[] style=width:100% placeholder=Animatrice></td>')">+ Ajouter</button><button type="submit" name="scout_save_titles" class="button button-primary">Sauvegarder</button></div>
        </form>
    </div>

    <!-- UNITS -->
    <div class="postbox" style="padding:20px;margin-top:20px">
        <h2 style="margin-top:0">⚜️ Unités scoutes</h2>
        <p class="description">Gérez les unités avec leur badge officiel et couleurs. Ces unités apparaissent dans les menus déroulants des membres et sur le calendrier.</p>
        <form method="post"><?php wp_nonce_field('scout_save_units', '_scout_units_nonce'); ?>
        <?php $units_list = scout_gm_get_units(); ?>
        <table class="wp-list-table widefat fixed striped" id="unitsTable">
            <thead><tr><th style="width:110px">Identifiant</th><th style="width:120px">Nom</th><th style="width:70px">Âge</th><th>URL du badge</th><th style="width:40px">Fond</th><th style="width:40px">Texte</th><th style="width:40px">Accent</th><th style="width:50px">Aperçu</th><th style="width:40px"></th></tr></thead>
            <tbody><?php foreach ($units_list as $ui => $uu): ?>
            <tr>
                <td><input type="text" name="unit_slug[]" value="<?php echo esc_attr($uu['slug']); ?>" style="width:100%"></td>
                <td><input type="text" name="unit_name[]" value="<?php echo esc_attr($uu['name']); ?>" style="width:100%"></td>
                <td><input type="text" name="unit_age[]" value="<?php echo esc_attr($uu['age'] ?? ''); ?>" style="width:100%" placeholder="7-8 ans"></td>
                <td><input type="url" name="unit_badge[]" value="<?php echo esc_attr($uu['badge_url'] ?? ''); ?>" style="width:100%" placeholder="https://..."></td>
                <td><input type="color" name="unit_bg[]" value="<?php echo esc_attr($uu['bg_color'] ?? '#e0f2fe'); ?>" style="width:30px;height:24px;padding:0;border:none"></td>
                <td><input type="color" name="unit_txt[]" value="<?php echo esc_attr($uu['text_color'] ?? '#0369a1'); ?>" style="width:30px;height:24px;padding:0;border:none"></td>
                <td><input type="color" name="unit_accent[]" value="<?php echo esc_attr($uu['accent_color'] ?? $uu['text_color'] ?? '#007748'); ?>" style="width:30px;height:24px;padding:0;border:none"></td>
                <td><?php if (!empty($uu['badge_url'])): ?><img src="<?php echo esc_url($uu['badge_url']); ?>" style="width:28px;height:28px;object-fit:contain"><?php else: ?><span style="display:inline-block;width:28px;height:28px;border-radius:50%;background:<?php echo esc_attr($uu['bg_color'] ?? '#e0f2fe'); ?>;border:2px solid <?php echo esc_attr($uu['accent_color'] ?? $uu['text_color'] ?? '#007748'); ?>"></span><?php endif; ?></td>
                <td><button type="button" onclick="this.closest('tr').remove()" class="button" style="color:#c0392b">✕</button></td>
            </tr>
            <?php endforeach; ?></tbody>
        </table>
        <div style="margin-top:8px;display:flex;gap:8px"><button type="button" class="button" onclick="addRow('unitsTable','<td><input type=text name=unit_slug[] style=width:100%></td><td><input type=text name=unit_name[] style=width:100%></td><td><input type=text name=unit_age[] style=width:100%></td><td><input type=url name=unit_badge[] style=width:100%></td><td><input type=color name=unit_bg[] value=#e0f2fe style=width:30px;height:24px;padding:0;border:none></td><td><input type=color name=unit_txt[] value=#0369a1 style=width:30px;height:24px;padding:0;border:none></td><td><input type=color name=unit_accent[] value=#007748 style=width:30px;height:24px;padding:0;border:none></td><td></td>')">+ Ajouter</button><button type="submit" name="scout_save_units" class="button button-primary">Sauvegarder</button></div>
        </form>
    </div>

    <!-- MEMBERS (drag-drop sortable) -->
    <div class="postbox" style="padding:20px;margin-top:20px">
        <h2 style="margin-top:0">👥 Membres <span style="font-weight:400;font-size:13px;color:#6a6a62">— glissez les rangées pour réordonner</span></h2>
        <form method="post"><?php wp_nonce_field('scout_save_members', '_scout_members_nonce'); ?>
        <table class="wp-list-table widefat fixed" style="font-size:12px" id="membersTable">
            <thead><tr>
                <th style="width:28px">👁️</th>
                <th style="width:28px">📧</th>
                <th style="width:140px">Utilisateur</th>
                <th style="width:110px">Section</th>
                <th style="width:50px">Genre</th>
                <th style="width:160px">Titre</th>
                <th style="width:120px">Unités</th>
                <th style="width:90px">Tél.</th>
                
                <th style="width:28px">⋮⋮</th>
            </tr></thead>
            <tbody id="membersBody"><?php foreach ($all_users as $u):
                $vis = get_user_meta($u->ID, 'scout_visible', true);
                $mask = get_user_meta($u->ID, 'scout_mask_email', true);
                $team_slugs = scout_gm_get_user_teams($u->ID);
                $tidx = get_user_meta($u->ID, 'scout_title_idx', true);
                if ($tidx === '' || $tidx === false) { $old_m = get_user_meta($u->ID, 'scout_title_m', true) ?: get_user_meta($u->ID, 'scout_title', true); $tidx = -1; foreach ($titles as $ti => $tv) { if ($tv['m'] === $old_m) { $tidx = $ti; break; } } }
                $gen = get_user_meta($u->ID, 'scout_gender', true);
                $unit_slugs = get_user_meta($u->ID, 'scout_units', true);
                if (!$unit_slugs) $unit_slugs = get_user_meta($u->ID, 'scout_unit', true); // migration
                $phone = get_user_meta($u->ID, 'scout_phone', true);
            ?>
            <tr draggable="true" style="<?php echo $vis === '1' ? 'background:#f0faf4' : ''; ?>">
                <td><input type="hidden" name="member_user_id[]" value="<?php echo $u->ID; ?>"><input type="checkbox" name="member_visible[]" value="<?php echo $u->ID; ?>" <?php checked($vis, '1'); ?>></td>
                <td class="mask-col" title="Courriel affiché">
                    <?php $mask_email_key = get_user_meta($u->ID, 'scout_mask_email_key', true); $mask = get_user_meta($u->ID, 'scout_mask_email', true); ?>
                    <select name="member_mask_email_key[<?php echo $u->ID; ?>]" style="width:100%;font-size:11px">
                        <option value="" <?php selected($mask !== '1' && !$mask_email_key, true); ?>>📧 Personnel</option>
                        <?php foreach ($emails_list as $eidx => $em): ?>
                        <option value="<?php echo esc_attr($eidx); ?>" <?php selected($mask_email_key, strval($eidx)); ?> <?php if ($mask === '1' && !$mask_email_key && $eidx === 0) echo 'selected'; ?>><?php echo esc_html($em['label'] . ' — ' . $em['email']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><strong><?php echo esc_html($u->display_name); ?></strong><br><span style="color:#999;font-size:10px"><?php echo esc_html($u->user_email); ?></span></td>
                <td><?php echo scout_gm_team_checkboxes('member_teams[' . $u->ID . ']', $team_slugs); ?></td>
                <td><select name="member_gender[]" style="width:100%"><option value="">—</option><option value="M" <?php selected($gen, 'M'); ?>>M</option><option value="F" <?php selected($gen, 'F'); ?>>F</option></select></td>
                <td><select name="member_title_idx[]" style="width:100%"><option value="-1">—</option><?php foreach ($titles as $ti => $tv): ?><option value="<?php echo $ti; ?>" <?php selected(intval($tidx), $ti); ?>><?php echo esc_html($tv['m'] . ' / ' . $tv['f']); ?></option><?php endforeach; ?></select></td>
                <td style="font-size:11px"><?php echo scout_gm_unit_checkboxes('member_units[' . $u->ID . ']', $unit_slugs); ?></td>
                <td><input type="text" name="member_phone[]" value="<?php echo esc_attr($phone); ?>" style="width:100%"></td>
                <td style="cursor:grab;text-align:center;color:#999;font-size:16px" title="Glisser pour réordonner">⋮⋮</td>
            </tr>
            <?php endforeach; ?></tbody>
        </table>
        <div style="margin-top:12px"><button type="submit" name="scout_save_members" class="button button-primary">💾 Sauvegarder</button></div>
        </form>
    </div></div>

    <script>
    function addRow(id,h){var t=document.querySelector('#'+id+' tbody'),r=document.createElement('tr');r.innerHTML=h+'<td><button type=button onclick=this.closest("tr").remove() class=button style=color:#c0392b>✕</button></td>';t.appendChild(r);}

    // Dropdown with checkboxes (shared by units and sections)
    function toggleUnitDrop(id){
        var el=document.getElementById('unitDrop'+id);
        // Close all other dropdowns first
        document.querySelectorAll('[id^="unitDrop"]').forEach(function(d){if(d.id!=='unitDrop'+id)d.style.display='none';});
        el.style.display=el.style.display==='none'?'block':'none';
    }
    function updateUnitLabel(id){
        var drop=document.getElementById('unitDrop'+id);
        var checked=drop.querySelectorAll('input:checked');
        var names=[];
        checked.forEach(function(cb){names.push(cb.closest('label').querySelector('span:last-child').textContent);});
        document.getElementById('unitLabel'+id).textContent=names.length?names.join(', '):'— Aucune —';
    }
    // Close dropdowns when clicking outside
    document.addEventListener('click',function(e){
        if(!e.target.closest('.scout-unit-dropdown')){
            document.querySelectorAll('[id^="unitDrop"]').forEach(function(d){d.style.display='none';});
        }
    });

    // Drag & drop sorting for members
    (function(){
        var tbody=document.getElementById('membersBody');
        var dragRow=null;
        tbody.addEventListener('dragstart',function(e){
            dragRow=e.target.closest('tr');
            if(dragRow)dragRow.classList.add('dragging');
        });
        tbody.addEventListener('dragend',function(e){
            var rows=tbody.querySelectorAll('tr');
            rows.forEach(function(r){r.classList.remove('dragging','drag-over');});
        });
        tbody.addEventListener('dragover',function(e){
            e.preventDefault();
            var target=e.target.closest('tr');
            if(!target||target===dragRow)return;
            tbody.querySelectorAll('tr').forEach(function(r){r.classList.remove('drag-over');});
            target.classList.add('drag-over');
        });
        tbody.addEventListener('drop',function(e){
            e.preventDefault();
            var target=e.target.closest('tr');
            if(!target||target===dragRow)return;
            var rows=Array.from(tbody.querySelectorAll('tr'));
            var dragIdx=rows.indexOf(dragRow);
            var targetIdx=rows.indexOf(target);
            if(dragIdx<targetIdx)target.after(dragRow);
            else target.before(dragRow);
        });
    })();
    </script>
    <?php
}

function scout_gm_user_fields($user) {
    $teams = scout_gm_get_teams();
    $team_slugs = scout_gm_get_user_teams($user->ID);
    $gen = get_user_meta($user->ID, 'scout_gender', true);
    $tm = get_user_meta($user->ID, 'scout_title_m', true) ?: get_user_meta($user->ID, 'scout_title', true);
    $tf = get_user_meta($user->ID, 'scout_title_f', true);
    $unit_slugs = get_user_meta($user->ID, 'scout_units', true);
    if (!$unit_slugs) $unit_slugs = get_user_meta($user->ID, 'scout_unit', true);
    $phone = get_user_meta($user->ID, 'scout_phone', true);
    $vis = get_user_meta($user->ID, 'scout_visible', true);
    $ord = get_user_meta($user->ID, 'scout_sort_order', true) ?: 10;
    ?>
    <h2>⚜️ Profil scout — Page Bénévoles</h2>
    <table class="form-table">
        <tr><th>Afficher</th><td><label><input type="checkbox" name="scout_visible" value="1" <?php checked($vis, '1'); ?>> Oui</label></td></tr>
        <tr><th>Sections</th><td><?php echo scout_gm_team_checkboxes('scout_teams', $team_slugs); ?></td></tr>
        <tr><th>Genre</th><td><select name="scout_gender"><option value="">—</option><option value="M" <?php selected($gen, 'M'); ?>>Masculin</option><option value="F" <?php selected($gen, 'F'); ?>>Féminin</option></select></td></tr>
        <tr><th>Titre (masculin)</th><td><input type="text" name="scout_title_m" value="<?php echo esc_attr($tm); ?>" class="regular-text" placeholder="Animateur"></td></tr>
        <tr><th>Titre (féminin)</th><td><input type="text" name="scout_title_f" value="<?php echo esc_attr($tf); ?>" class="regular-text" placeholder="Animatrice"></td></tr>
        <tr><th>Unités</th><td><?php echo scout_gm_unit_checkboxes('scout_units', $unit_slugs); ?></td></tr>
        <tr><th>Téléphone</th><td><input type="text" name="scout_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td></tr>
        <tr><th>Ordre</th><td><input type="number" name="scout_sort_order" value="<?php echo esc_attr($ord); ?>" class="small-text" min="1" max="99"></td></tr>
    </table>
    <?php
}
add_action('show_user_profile', 'scout_gm_user_fields');
add_action('edit_user_profile', 'scout_gm_user_fields');

function scout_gm_user_fields_save($user_id) {
    if (!current_user_can('edit_user', $user_id)) return;
    update_user_meta($user_id, 'scout_visible', isset($_POST['scout_visible']) ? '1' : '0');
    update_user_meta($user_id, 'scout_teams', sanitize_text_field(implode(',', array_map('sanitize_key', $_POST['scout_teams'] ?? []))));
    update_user_meta($user_id, 'scout_gender', sanitize_text_field($_POST['scout_gender'] ?? ''));
    update_user_meta($user_id, 'scout_title_m', sanitize_text_field($_POST['scout_title_m'] ?? ''));
    update_user_meta($user_id, 'scout_title_f', sanitize_text_field($_POST['scout_title_f'] ?? ''));
    update_user_meta($user_id, 'scout_units', sanitize_text_field(implode(',', array_map('sanitize_key', $_POST['scout_units'] ?? []))));
    update_user_meta($user_id, 'scout_phone', sanitize_text_field($_POST['scout_phone'] ?? ''));
    update_user_meta($user_id, 'scout_sort_order', intval($_POST['scout_sort_order'] ?? 10));
}
add_action('personal_options_update', 'scout_gm_user_fields_save');
add_action('edit_user_profile_update', 'scout_gm_user_fields_save');

function scout_gm_get_team($team_slug) {
    // Get all visible users, then filter by team (supports comma-separated scout_teams)
    $all_visible = get_users([
        'meta_query' => [
            ['key' => 'scout_visible', 'value' => '1'],
        ],
    ]);
    $members = [];
    foreach ($all_visible as $u) {
        $user_teams = scout_gm_get_user_teams($u->ID);
        if (in_array($team_slug, $user_teams)) {
            $u->_sort = intval(get_user_meta($u->ID, 'scout_sort_order', true) ?: 999);
            $members[] = $u;
        }
    }
    usort($members, function($a, $b) { return $a->_sort - $b->_sort; });
    return $members;
}

function scout_gm_get_title($user_id) {
    $gender = get_user_meta($user_id, 'scout_gender', true);
    if ($gender === 'F') {
        $t = get_user_meta($user_id, 'scout_title_f', true);
        if ($t) return $t;
    }
    $t = get_user_meta($user_id, 'scout_title_m', true);
    if ($t) return $t;
    return get_user_meta($user_id, 'scout_title', true);
}

// Admin sidebar customization removed — use WordPress default

// ══════════════════════════════════
// BLOCK TRACKING ON SENSITIVE PAGES
// ══════════════════════════════════

/**
 * Loi 25 / GDPR — Disable ALL tracking, analytics, and data collection
 * on pages that contain sensitive personal or medical data.
 *
 * This blocks: Google Analytics (gtag, analytics.js, GA4), Google Tag Manager,
 * Facebook Pixel, MonsterInsights, Site Kit, Hotjar, Clarity, and any other
 * third-party tracking scripts.
 */
function scout_gm_is_sensitive_page(): bool {
    if (!is_page()) return false;

    // Page slugs that contain sensitive data
    $sensitive_slugs = [
        'inscription',       // Registration form (personal + medical data)
        'verification',      // QR verification (medical data)
        'famille',           // Family portal (personal data)
    ];

    // Check current page and its parent
    $current = get_post();
    if (!$current) return false;

    if (in_array($current->post_name, $sensitive_slugs, true)) return true;

    // Also check parent slug (e.g. /inscription/verification/)
    if ($current->post_parent) {
        $parent = get_post($current->post_parent);
        if ($parent && in_array($parent->post_name, $sensitive_slugs, true)) return true;
    }

    // Check if page uses sensitive shortcodes
    if (has_shortcode($current->post_content, 'scout_inscription')) return true;
    if (has_shortcode($current->post_content, 'scout_verification')) return true;
    if (has_shortcode($current->post_content, 'scout_famille')) return true;

    return false;
}

/**
 * Remove tracking scripts from wp_head and wp_footer on sensitive pages.
 * Runs early (priority 1) to catch everything.
 */
function scout_gm_block_tracking() {
    if (!scout_gm_is_sensitive_page()) return;

    // ── Remove Google Analytics / GA4 / gtag.js ──
    // Common handles used by various GA plugins
    $ga_handles = [
        'google-analytics', 'google_gtagjs', 'gtag', 'gtag-js',
        'ga-script', 'analytics', 'google-analytics-4',
        // MonsterInsights
        'monsterinsights-tracking-script', 'monsterinsights-frontend-script',
        'monsterinsights-lite-tracking-script',
        // Site Kit
        'google-site-kit-analytics', 'google-site-kit',
        'google_gtagjs-js', 'google_gtagjs-js-extra',
        // Rank Math
        'rank-math-analytics',
        // Others
        'woocommerce-google-analytics-integration',
    ];
    foreach ($ga_handles as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }

    // ── Remove Google Tag Manager ──
    $gtm_handles = ['google-tag-manager', 'gtm', 'gtm-script', 'google_gtm'];
    foreach ($gtm_handles as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }

    // ── Remove Facebook Pixel ──
    $fb_handles = ['facebook-pixel', 'fb-pixel', 'facebook-tracking', 'meta-pixel'];
    foreach ($fb_handles as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }

    // ── Remove Hotjar, Clarity, and other behavior trackers ──
    $tracker_handles = ['hotjar', 'clarity', 'microsoft-clarity', 'hj-script', 'fullstory'];
    foreach ($tracker_handles as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }
}
add_action('wp_enqueue_scripts', 'scout_gm_block_tracking', 1);

/**
 * Inline script blocker — remove known tracking from wp_head and wp_footer
 * using WordPress action removal (no output buffering needed).
 */
function scout_gm_cleanup_tracking_actions() {
    if (!scout_gm_is_sensitive_page()) return;

    // Remove all known analytics wp_head/wp_footer hooks at various priorities
    $hooks_to_clean = ['wp_head', 'wp_footer', 'wp_body_open'];
    foreach ($hooks_to_clean as $hook) {
        global $wp_filter;
        if (!isset($wp_filter[$hook])) continue;
        foreach ($wp_filter[$hook]->callbacks as $priority => $callbacks) {
            foreach ($callbacks as $key => $cb) {
                $func_name = '';
                if (is_string($cb['function'])) {
                    $func_name = $cb['function'];
                } elseif (is_array($cb['function']) && isset($cb['function'][1])) {
                    $func_name = is_string($cb['function'][1]) ? $cb['function'][1] : '';
                }
                $func_lower = strtolower($func_name);
                // Match any function containing these keywords
                if (preg_match('/(analytics|gtag|tracking|pixel|hotjar|clarity|facebook|fbq|monsterinsights|google_tag)/i', $func_lower)) {
                    remove_action($hook, $cb['function'], $priority);
                }
            }
        }
    }
}
add_action('template_redirect', 'scout_gm_cleanup_tracking_actions', 999);

/**
 * Filter script tags to block tracking src URLs on sensitive pages.
 */
function scout_gm_filter_script_tags($tag, $handle, $src) {
    if (!scout_gm_is_sensitive_page()) return $tag;

    $blocked_domains = [
        'google-analytics.com',
        'googletagmanager.com',
        'gtag/js',
        'connect.facebook.net',
        'hotjar.com',
        'clarity.ms',
        'fullstory.com',
    ];

    foreach ($blocked_domains as $domain) {
        if (stripos($src, $domain) !== false) {
            return '<!-- ' . esc_html($handle) . ' blocked (Loi 25) -->' . "\n";
        }
    }

    return $tag;
}
add_filter('script_loader_tag', 'scout_gm_filter_script_tags', 999, 3);

/**
 * Add meta tag to tell crawlers not to track this page.
 */
function scout_gm_notrack_meta() {
    if (!scout_gm_is_sensitive_page()) return;
    echo '<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">' . "\n";
    echo '<!-- 🔒 Loi 25: Tracking/analytics disabled on this page — sensitive data -->' . "\n";
}
add_action('wp_head', 'scout_gm_notrack_meta', 1);

/**
 * Block MonsterInsights specifically (it uses its own output method).
 */
function scout_gm_block_monsterinsights() {
    if (!scout_gm_is_sensitive_page()) return;
    // MonsterInsights checks this global
    if (!defined('MONSTERINSIGHTS_DISABLE_TRACKING')) {
        define('MONSTERINSIGHTS_DISABLE_TRACKING', true);
    }
    // Also remove its inline output
    remove_action('wp_head', 'monsterinsights_tracking_script', 6);
    remove_action('wp_head', 'monsterinsights_tracking_script', 9);
    remove_action('wp_head', 'monsterinsights_tracking_script', 10);
}
add_action('template_redirect', 'scout_gm_block_monsterinsights', 0);

/**
 * Block Google Site Kit — ALL modules — on sensitive pages.
 * Uses the official googlesitekit_*_tag_blocked filters.
 */
function scout_gm_block_sitekit() {
    if (!scout_gm_is_sensitive_page()) return;

    // Block Analytics (GA4)
    add_filter('googlesitekit_analytics-4_tag_blocked', '__return_true');

    // Block Tag Manager
    add_filter('googlesitekit_tagmanager_tag_blocked', '__return_true');

    // Block AdSense
    add_filter('googlesitekit_adsense_tag_blocked', '__return_true');

    // Block legacy Analytics (UA) if still active
    add_filter('googlesitekit_analytics_tag_blocked', '__return_true');

    // Also disable tracking via the opt-out method
    add_filter('googlesitekit_allow_tracking_disabled', '__return_true');

    // Remove Site Kit's wp_head and wp_footer output
    // Site Kit registers its scripts via wp_enqueue_scripts — dequeue them all
    add_action('wp_enqueue_scripts', function() {
        global $wp_scripts;
        if (!isset($wp_scripts) || !is_object($wp_scripts)) return;
        foreach ($wp_scripts->registered as $handle => $script) {
            if (stripos($handle, 'googlesitekit') !== false ||
                (isset($script->src) && stripos($script->src, 'googlesitekit') !== false) ||
                (isset($script->src) && stripos($script->src, 'googletagmanager') !== false) ||
                (isset($script->src) && stripos($script->src, 'google-analytics') !== false) ||
                (isset($script->src) && stripos($script->src, 'gtag') !== false)) {
                wp_dequeue_script($handle);
                wp_deregister_script($handle);
            }
        }
    }, 9999);

    // Also catch inline scripts Site Kit prints directly
    add_action('wp_print_scripts', function() {
        global $wp_scripts;
        if (!isset($wp_scripts) || !is_object($wp_scripts)) return;
        foreach ($wp_scripts->registered as $handle => $script) {
            if (stripos($handle, 'googlesitekit') !== false ||
                stripos($handle, 'google_gtagjs') !== false) {
                wp_dequeue_script($handle);
                wp_deregister_script($handle);
            }
        }
    }, 1);
}
add_action('template_redirect', 'scout_gm_block_sitekit', 0);

/**
 * Last resort — filter every script tag on sensitive pages.
 * Catches anything that slipped through the dequeue.
 */
add_filter('script_loader_tag', function($tag, $handle, $src) {
    if (!function_exists('scout_gm_is_sensitive_page') || !scout_gm_is_sensitive_page()) return $tag;

    $blocked = ['googlesitekit', 'googletagmanager', 'google-analytics', 'gtag/js', 'gtag.js'];
    foreach ($blocked as $pattern) {
        if (stripos($src, $pattern) !== false || stripos($handle, $pattern) !== false) {
            return '<!-- ' . esc_html($handle) . ' blocked on sensitive page (Loi 25) -->' . "\n";
        }
    }
    return $tag;
}, 9999, 3);


// ══════════════════════════════════
// CUSTOM LOGIN PAGE
// ══════════════════════════════════

/**
 * Custom login page CSS — matches the scout theme branding
 */
function scout_gm_login_styles() {
    $logo_url = '';
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'medium');
    }
    ?>
    <style>
        /* Background */
        body.login {
            background: linear-gradient(160deg, #003d24 0%, #005a36 30%, #007748 60%, #009960 100%) !important;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            position: relative;
            overflow: hidden;
        }

        /* Subtle pattern overlay */
        body.login::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(255,180,0,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(0,101,204,0.05) 0%, transparent 40%),
                radial-gradient(ellipse at 50% 80%, rgba(255,255,255,0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Fleur-de-lys watermark */
        body.login::after {
            content: '⚜';
            position: fixed;
            bottom: -60px;
            right: -40px;
            font-size: 320px;
            opacity: 0.04;
            color: #fff;
            pointer-events: none;
            z-index: 0;
            transform: rotate(-15deg);
        }

        /* Login container */
        #login {
            position: relative;
            z-index: 1;
            padding: 6% 0 0 !important;
        }

        /* Logo area */
        .login h1 a {
            <?php if ($logo_url): ?>
            background-image: url('<?php echo esc_url($logo_url); ?>') !important;
            background-size: contain !important;
            background-position: center !important;
            width: 200px !important;
            height: 100px !important;
            <?php else: ?>
            background-image: none !important;
            width: 0 !important;
            height: 0 !important;
            <?php endif; ?>
            display: block;
            margin: 0 auto 0;
        }

        /* Group name header */
        .login h1::after {
            content: '5e Groupe scout Grand-Moulin';
            display: block;
            text-align: center;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 600;
            margin-top: 12px;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .login h1::before {
            content: '⚜️';
            display: block;
            text-align: center;
            font-size: 42px;
            margin-bottom: 8px;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.15));
        }

        /* Login form box */
        .login form {
            background: rgba(255,255,255,0.97) !important;
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 12px 48px rgba(0,61,36,0.25), 0 2px 8px rgba(0,0,0,0.1) !important;
            padding: 28px 24px 24px !important;
            margin-top: 20px !important;
            backdrop-filter: blur(8px);
        }

        /* Labels */
        .login form .forgetmenot,
        .login label {
            color: #1a1a16 !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            font-weight: 500 !important;
        }

        /* Input fields */
        .login form input[type="text"],
        .login form input[type="password"] {
            background: #f9f8f5 !important;
            border: 2px solid #e0ddd4 !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
            font-family: 'Poppins', sans-serif !important;
            color: #1a1a16 !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
            box-shadow: none !important;
        }

        .login form input[type="text"]:focus,
        .login form input[type="password"]:focus {
            border-color: #007748 !important;
            box-shadow: 0 0 0 3px rgba(0,119,72,0.15) !important;
            outline: none !important;
            background: #fff !important;
        }

        /* Submit button */
        .login .button-primary,
        #wp-submit {
            background: linear-gradient(135deg, #007748, #009960) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 10px 24px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            font-family: 'Poppins', sans-serif !important;
            text-shadow: none !important;
            box-shadow: 0 4px 12px rgba(0,119,72,0.3) !important;
            transition: all 0.2s !important;
            width: 100% !important;
            height: auto !important;
            margin-top: 8px !important;
            cursor: pointer;
        }

        .login .button-primary:hover,
        #wp-submit:hover {
            background: linear-gradient(135deg, #005a36, #007748) !important;
            box-shadow: 0 6px 20px rgba(0,119,72,0.4) !important;
            transform: translateY(-1px);
        }

        .login .button-primary:active,
        #wp-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0,119,72,0.3) !important;
        }

        /* Remember me checkbox */
        .login .forgetmenot label {
            font-size: 12px !important;
        }

        .login input[type="checkbox"] {
            border-radius: 4px !important;
            border: 2px solid #e0ddd4 !important;
        }

        .login input[type="checkbox"]:checked {
            background: #007748 !important;
            border-color: #007748 !important;
        }

        /* Links below form */
        .login #nav,
        .login #backtoblog {
            text-align: center !important;
        }

        .login #nav a,
        .login #backtoblog a {
            color: rgba(255,255,255,0.8) !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 12px !important;
            text-decoration: none !important;
            transition: color 0.2s !important;
        }

        .login #nav a:hover,
        .login #backtoblog a:hover {
            color: #ffb400 !important;
            text-decoration: underline !important;
        }

        /* Error / success messages */
        .login .message,
        .login .success {
            border-left: 4px solid #007748 !important;
            border-radius: 8px !important;
            background: rgba(255,255,255,0.95) !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        }

        .login #login_error {
            border-left: 4px solid #c0392b !important;
            border-radius: 8px !important;
            background: rgba(255,255,255,0.95) !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        }

        /* Privacy policy link */
        .login .privacy-policy-page-link {
            text-align: center !important;
        }

        .login .privacy-policy-page-link a {
            color: rgba(255,255,255,0.6) !important;
            font-size: 11px !important;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Language switcher */
        .language-switcher,
        .wp-login-lang-switcher {
            font-family: 'Poppins', sans-serif !important;
            text-align: center !important;
            margin-top: 16px !important;
        }

        .language-switcher select,
        .wp-login-lang-switcher select {
            border-radius: 8px !important;
            border: 2px solid rgba(255,255,255,0.25) !important;
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
            padding: 6px 12px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 12px !important;
            appearance: auto !important;
            -webkit-appearance: menulist !important;
            cursor: pointer;
        }

        .language-switcher select option,
        .wp-login-lang-switcher select option {
            background: #003d24 !important;
            color: #fff !important;
        }

        .language-switcher .button,
        .wp-login-lang-switcher .button {
            background: rgba(255,255,255,0.15) !important;
            border: 2px solid rgba(255,255,255,0.25) !important;
            border-radius: 8px !important;
            color: #fff !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 12px !important;
            padding: 4px 14px !important;
            cursor: pointer;
            transition: all 0.2s !important;
            text-shadow: none !important;
            box-shadow: none !important;
        }

        .language-switcher .button:hover,
        .wp-login-lang-switcher .button:hover {
            background: rgba(255,255,255,0.25) !important;
            color: #ffb400 !important;
        }

        /* Footer text */
        .login #login::after {
            content: '⚜️ Scouts du Canada — District Les Ailes du Nord';
            display: block;
            text-align: center;
            color: rgba(255,255,255,0.4);
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            margin-top: 24px;
            padding-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login form {
                margin-left: 8px !important;
                margin-right: 8px !important;
                border-radius: 12px !important;
            }
            body.login::after {
                font-size: 200px;
                bottom: -40px;
                right: -30px;
            }
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'scout_gm_login_styles');

/**
 * Change login logo URL to site home
 */
function scout_gm_login_logo_url() {
    return home_url('/');
}
add_filter('login_headerurl', 'scout_gm_login_logo_url');

/**
 * Change login logo title
 */
function scout_gm_login_logo_title() {
    return get_bloginfo('name') . ' — ' . get_bloginfo('description');
}
add_filter('login_headertext', 'scout_gm_login_logo_title');

/**
 * Load Poppins font on login page
 */
function scout_gm_login_fonts() {
    wp_enqueue_style('scout-gm-login-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap', [], null);
}
add_action('login_enqueue_scripts', 'scout_gm_login_fonts');

add_action( 'wp_enqueue_scripts', function() {
    if ( is_single() ) {
        wp_enqueue_style(
            'scout-gm-single',
            get_template_directory_uri() . '/assets/css/single.css',
            [ 'scout-gm-main' ],
            '1.0.0'
        );
    }
} );
