<?php
/*
Plugin Name: DOCX to HTML Converter
Description: Convert DOCX files to HTML, preview and validate before creating a new post.
Version: 1.1
Author: Bendev
*/

// Sécurité basique
if (!defined('ABSPATH')) {
    exit;
}

// Ajouter une page d'admin pour télécharger le fichier DOCX
add_action('admin_menu', 'docx_to_html_menu');
function docx_to_html_menu() {
    add_menu_page('Docx vers Article', 'Docx vers Article', 'manage_options', 'docx-to-html', 'docx_to_html_page');
}

// Ajoute la view upload-form
function docx_to_html_page() {
   include plugin_dir_path(__FILE__) . 'views/upload-form.php';
}

// Charge les scripts mammoth.js et docx-to-html.js
add_action('admin_enqueue_scripts', 'enqueue_mammoth_js');
function enqueue_mammoth_js($hook) {
    if ($hook !== 'toplevel_page_docx-to-html') {
        return;
    }
    
    wp_enqueue_script('mammoth', plugins_url('/js/mammoth.browser.min.js', __FILE__), array(), '1.4.2', true);
    wp_enqueue_script('docx-to-html-script', plugins_url('/js/docx-to-html.js', __FILE__), array('jquery', 'mammoth'), '1.0', true);
    wp_localize_script('docx-to-html-script', 'docxToHtml', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('docx_to_html_nonce'),
    ));
}

// Fonction pour qui envoie le fichier docx en ajax pour le convertir en html
add_action('wp_ajax_convert_docx_to_html', 'convert_docx_to_html');
function convert_docx_to_html() {
    check_ajax_referer('docx_to_html_nonce', 'nonce');

    if (!isset($_FILES['docx_file']) || $_FILES['docx_file']['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error('File upload error.');
    }

    $file_content = file_get_contents($_FILES['docx_file']['tmp_name']);
    $response = array('file_content' => base64_encode($file_content));
    wp_send_json_success($response);
}



// Fonction qui ajoute un post via HTML recu en ajax
add_action('wp_ajax_create_post_from_html', 'create_post_from_html');
function create_post_from_html() {
    check_ajax_referer('docx_to_html_nonce', 'nonce');

    $content = isset($_POST['content']) ? wp_kses_post($_POST['content'])  : '';
    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : 'Post from DOCX';


    if (empty($content) || empty($title)) {
        wp_send_json_error('Empty content.');
    }

    
    $styled_content = '<div class="docx-to-html-content alignfull">' . $content . '</div>';

       if (substr($title, -5) === '.docx') {
        $title = substr($title, 0, -5);
    }

    $post_data = array(
        'post_title'    => $title,
        'post_content'  => $styled_content,
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        wp_send_json_error($post_id->get_error_message());
    }

    wp_send_json_success('Le post a été crée avec succes.');
}

// Modifie le style du titre
function custom_article_title($title) {
    if (is_single() && in_the_loop() && is_main_query()) {
        return '<span class="custom-article-title alignfull">' . $title . '</span>';
    }
    return $title;
}
add_filter('the_title', 'custom_article_title');

// Charge les fichiers css
add_action('wp_enqueue_scripts', 'enqueue_docx_to_html_styles');
function enqueue_docx_to_html_styles() {
    if (is_single()) {
        wp_enqueue_style('docx-to-html-style', plugins_url('/css/template-post.css', __FILE__));
    }
}
?>
