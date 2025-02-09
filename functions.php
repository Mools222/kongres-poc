<?php
/**
 * Enqueue scripts and styles.
 */
add_action('wp_enqueue_scripts', 'kongres_scripts');
function kongres_scripts() {
    wp_enqueue_style('kongres-style', get_stylesheet_uri());

//    $t =get_post_meta(59);
//    var_dump($t);

    if (is_page_template('page-vue.php')) {
//        enqueue_service_worker_scripts(); // This is imported in index.js
        activate_wp_api_plugin();
        enqueue_vue_scripts();
    }
}

/**
 * Load the service worker script
 */
function enqueue_service_worker_scripts() {
    wp_enqueue_script('swScript', get_template_directory_uri() . '/assets/js/swScript.js', array(), null, false);

//    $base_path = str_replace('index.php', '', $_SERVER['PHP_SELF']);
//    wp_localize_script('swScript', 'vars', array('basePath' => $base_path));
//    wp_add_inline_script('swScript', "let vars2 = {basePath: " . json_encode($base_path) . "};", 'before'); // https://digwp.com/2019/07/better-inline-script/
}

/**
 * Load backbone.js
 */
function activate_wp_api_plugin() {
    wp_enqueue_script('wp-api');
}

/**
 * Load compiled Vue JS (which includes both the code to make Vue work and my own components)
 */
function enqueue_vue_scripts() {
    // [Not necessary, since Vue is compiled with main123.js - See index.js]
//    wp_enqueue_script('vue', get_template_directory_uri() . '/assets/js/vue.js', null, null, true); // change to vue.min.js for production
//    wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main123.js', array('vue'), null, true);

    wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main123.js', array(), null, true);

//    $t = get_permalink();
//    $t2 = get_home_url();
//    $t3 = get_site_url();

    $base_url = get_site_url();
    $base_path = str_replace('index.php', '', $_SERVER['PHP_SELF']);
    $is_user_logged_in = is_user_logged_in();
    wp_localize_script('main', 'customVars', array('baseUrl' => $base_url, 'basePath' => $base_path, 'isUserLoggedIn' => json_encode($is_user_logged_in)));
}

/**
 * Add ACF-data to CPT "arrangement" when loaded via WP's REST API
 */
add_filter('rest_prepare_arrangement', 'addCustomField', 10, 3); // rest_prepare_{post_type}
function addCustomField($response, $post, $request) {
    $arrangement_id = $post->ID;
    $arrangement_name = get_field('arrangement_name', $arrangement_id);
    $arrangement_start_date = get_field('arrangement_start_date', $arrangement_id);
    $response->data['acf'] = ['name' => $arrangement_name, 'start_date' => $arrangement_start_date];
    return $response;
}

//register_meta('arrangement', '', array('show_in_rest' => true));

// https://support.advancedcustomfields.com/forums/topic/insert-post-wp-rest-api-with-field-text-and-image/
// https://developer.wordpress.org/reference/hooks/rest_insert_this-post_type/
add_action('rest_insert_arrangement', 'arrangement_update_acf', 10, 3);
function arrangement_update_acf($post, $request, $true) {
    $arrangement_id = $post->ID;
    $params = $request->get_params();

    if (isset($params['arrangement_name'])) {
        $arrangement_name = $params['arrangement_name'];
        update_field('arrangement_name', $arrangement_name, $arrangement_id);
    }

    if (isset($params['arrangement_start_date'])) {
        $date = $params['arrangement_start_date'];
        $arrangement_date = date("Ymd", $date);
        update_field('arrangement_start_date', $arrangement_date, $arrangement_id);
    }
}

add_action('rest_api_init', 'prepare_api');
function prepare_api($wp_rest_server) {
//    protect_api_from_unauthorized_users();
    register_custom_api_routes();
}

/**
 * Block users who are not logged in from accessing WP's REST API
 */
function protect_api_from_unauthorized_users() {
    $is_login_route = preg_match('/wp-json\/custom\/login$/', $_SERVER['REQUEST_URI']);
    if (!is_user_logged_in() && !$is_login_route) {
        echo 'Du er ikke logget ind.';
        status_header(403);
        die();
    }
}

/**
 * Register custom routes for WP's REST API
 */
function register_custom_api_routes() {
    register_rest_route('custom', '/login', array('methods' => 'POST', 'callback' => 'login_via_api'));
    register_rest_route('custom', '/logout', array('methods' => 'GET', 'callback' => 'logout_via_api'));
    register_rest_route('custom', '/isloggedin', array('methods' => 'GET', 'callback' => 'is_logged_in'));
    register_rest_route('custom', '/getnonce', array('methods' => 'GET', 'callback' => 'get_nonce'));
}

function get_nonce() {
    $t2 = wp_create_nonce('wp_rest');
    echo $t2;
}

function login_via_api($request) {
    $params = $request->get_params();
    $credentials = [
        'user_login' => $params['user_login'],
        'user_password' => $params["user_password"],
        'remember' => false
    ];
    $user = wp_signon($credentials);

    if (is_wp_error($user))
        echo $user->get_error_message();

//    $t = wp_create_nonce();

//    $i = wp_nonce_tick();
//    $action = -1;
//    $uid = (int)$user->ID;
//    $token = wp_get_session_token();
//    $expected = substr(wp_hash($i . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10);
//    $expected2 = substr( wp_hash( ( $i - 1 ) . '|' . $action . '|' . $uid . '|' . $token, 'nonce' ), -12, 10 );

    echo "login success";
//    echo $t;
}

function logout_via_api($request) {
    wp_logout();
    echo "logout success";
}

function is_logged_in($request) {
//    $is_ajax_call = (defined('DOING_AJAX') && DOING_AJAX);
//    $is_doing_ajax = wp_doing_ajax();
//    $t = is_user_logged_in();
//    $params = $request->get_params();
//    $headers = $request->get_headers();
//    $t1 = wp_verify_nonce( $headers['x_wp_nonce'][0] );

    echo json_encode(is_user_logged_in());
}

/**
 * Block access to the back end for non-admins
 */
add_action('admin_init', 'block_wp_admin'); // 'admin_init': Fires as an admin screen or script is being initialized.
function block_wp_admin() {
    $is_admin_page = is_admin(); // Determines whether the current request is for an administrative interface page.
    $is_admin_user = current_user_can('administrator');
    $is_ajax_call = (defined('DOING_AJAX') && DOING_AJAX);

    if ($is_admin_page && !$is_admin_user && !$is_ajax_call) {
        wp_safe_redirect(home_url());
        die();
    }
}

/**
 * Hide admin bar for non-admins
 */
add_filter('show_admin_bar', 'hide_admin_bar');
function hide_admin_bar($show) {
    $is_admin_user = current_user_can('administrator');
    if (!$is_admin_user)
        return false;
    return $show;
}

/**
 * Cron (wp_schedule_single_event)
 */
add_action('my_new_event', 'do_this_cron');
function do_this_cron($text) {
    update_field('arrangement_name', $text, 32);
}


add_filter('rest_prepare_event', 'add_acf_to_event', 10, 3);
function add_acf_to_event($response, $post, $request) {
    $event_id = $post->ID;
    $fields = get_fields($event_id);
    $response->data['acf'] = $fields;
    return $response;
}