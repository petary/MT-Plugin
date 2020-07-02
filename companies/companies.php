<?php

/**

 * Plugin Name: Companies

 */



 if(!defined('ABSPATH')){

     define('ABSPATH',dirname(__FILE__) . '/');

 }

 define('API_GET_STATUS','https://api.hubapi.com/contacts/v1/lists/all/contacts/all?hapikey=');

 define('API_PT','https://api.hubapi.com/crm-objects/v1/objects/tickets?hapikey=');

 define('ASSOCIAT','https://api.hubapi.com/crm-associations/v1/associations?hapikey=');

 define('CONTACT','https://api.hubapi.com/contacts/v1/contact/?hapikey=');
 
 define('TICKETS','https://api.hubapi.com/crm-objects/v1/objects/tickets/batch-read?hapikey=');
 
 define('PIPELINES','https://api.hubapi.com/crm-pipelines/v1/pipelines/tickets?hapikey=');

 define('P_PATH', plugin_dir_path( __FILE__ ));
 define('P_URL', plugin_dir_url( __FILE__ ));

 include_once('inc/functions.php');



 register_activation_hook( __FILE__, 'create_tables' );





 add_filter( 'template_include', 'page_all_template');
 add_filter( 'wp_mail_from', 'wpb_sender_email' );
 add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
 add_action('wp_ajax_my_action', 'my_action_callback');
 add_action('wp_ajax_nopriv_my_action', 'my_action_callback');
 

/**

 * Register a custom menu page.

 */

function wpdocs_register_my_custom_menu_page(){

    $main_options = add_menu_page( 

        'Companies Settings',

        'Companies Settings',

        'manage_options',

        'companies_options',

        'my_custom_menu_page',

        '',

        99

    ); 


    $companies_options = add_submenu_page(
            'companies_options',
            'Companies', 
            'Companies',
            'read',
            'companies_tab',
            'companies_tab'
    );

    $companies_users = add_submenu_page(
        'companies_options',
        'Companies users', 
        'Companies users',
        'read',
        'companies_users',
        'companies_users'
    );

    $companies_devices = add_submenu_page(
        'companies_options',
        'Companies devices', 
        'Companies devices',
        'read',
        'companies_devices',
        'companies_devices'
    );

    $companies_tickets = add_submenu_page(
        'companies_options',
        'Companies tickets', 
        'Companies tickets',
        'read',
        'companies_tickets',
        'companies_tickets'
    );

    add_action( 'admin_print_styles-' . $main_options, 'admin_custom_css' );
    add_action( 'admin_print_scripts-' . $main_options, 'admin_custom_js' );

    add_action( 'admin_print_styles-' . $companies_options, 'admin_custom_css' );
    add_action( 'admin_print_scripts-' . $companies_options, 'admin_custom_js' );

    add_action( 'admin_print_styles-' . $companies_users, 'admin_custom_css' );
    add_action( 'admin_print_scripts-' . $companies_users, 'admin_custom_js' );

    add_action( 'admin_print_styles-' . $companies_devices, 'admin_custom_css' );
    add_action( 'admin_print_scripts-' . $companies_devices, 'admin_custom_js' );

    add_action( 'admin_print_styles-' . $companies_tickets, 'admin_custom_css' );
    add_action( 'admin_print_scripts-' . $companies_tickets, 'admin_custom_js' );

}

add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function admin_custom_css(){

    wp_enqueue_style( 'datatables_css', 'https://cdn.datatables.net/v/dt/dt-1.10.20/b-1.6.1/b-flash-1.6.1/b-html5-1.6.1/datatables.min.css');
    wp_enqueue_style( 'companies_main_css', P_URL .'assets/main.css');
    wp_enqueue_style( 'jquery-ui','https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
    
    
}

function admin_custom_js(){
    wp_enqueue_script( 'datatables_js','https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js', ['jquery']);
    wp_enqueue_script( 'buttons','https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js', ['jquery']);
    wp_enqueue_script( 'pdf','https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js', ['jquery']);
    wp_enqueue_script( 'jszip','https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', ['jquery']);
    wp_enqueue_script( 'buttons.html','https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js', ['jquery']);
    
     wp_enqueue_script( 'companies_main_js', P_URL . 'assets/main.js', ['jquery']);
     wp_enqueue_script( 'jquery-ui-datepicker' );
}

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}

add_action( 'wp_head',function(){
    wp_enqueue_style( 'companies_forms', P_URL .'assets/forms.css');
    
    wp_enqueue_script( 'popper','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', ['jquery']);
    wp_enqueue_script( 'bootstrapjs','https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', ['jquery']);
    wp_enqueue_script( 'companies_forms_js', P_URL . 'assets/forms.js', ['jquery']);
    
} );

function is_company_logged_in(){
    if(isset($_SESSION['company'])){
        return $_SESSION['company'];
    }else{
        return false;
    }
}

