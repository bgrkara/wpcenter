<?php
/*
Plugin Name: WPCenter Custom endpoint
Plugin URI: https://bugrakara.com.tr
Description: The plugin offers a special custom endpoint
Version: 1.0
Author: Bugra Kara
Author URI: https://bugrakara.com.tr
*/


/**
Endpoint Callback Fonksiyonu
 **/
function custom_endpoint_callback() {

    /** Önbellek Anahtarı **/
    $transient_key = 'custom_endpoint_data';
    /** Önbellekten Alınan Değer **/
    $cached_data = get_transient($transient_key);

    /** Önbelleğe alınan değer varsa JSON formatında veriyi gönderiyoruz **/
    if ($cached_data) {
        // Önbellekte veri varsa bu veriyi kullanıyor
        wp_send_json(array('status' => 'success', 'data' => $cached_data));
    }else{
        /** Bu kısımda önbellekte veri yoksa Api isteği göderiyoruz **/
        /** Burada Jsonplaceholder adresi üzerinden wp_remote_get ile veriyi alıyoruz **/
        $response = wp_remote_get('https://jsonplaceholder.typicode.com/users');

        /** response'a aktardığımız veride hata kontrolü yapıyoruz. **/
        if (is_wp_error($response)) {
            wp_send_json(array('status' => 'error', 'message' => $response->get_error_message()));
        }

        /** HTTP yanıtının gövde içeriğini alıp dataya aktarıyoruz **/
        $data = json_decode(wp_remote_retrieve_body($response), true);

        $users = array();
        foreach ($data as $user) {
            $users[] = array(
                'id' => $user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'details_url' => rest_url('custom/v1/user-details/' . $user['id']),
            );
        }

        /** Veriyi önbelleğe alıyoruz **/
        set_transient($transient_key, $users, 3600);

        /** Filtreleme */
        $users = apply_filters('custom_endpoint_users', $users);
        wp_send_json(array('status' => 'success', 'data' => $users));

    }

}

add_action('wp_ajax_custom_endpoint_callback', 'custom_endpoint_callback');
add_action('wp_ajax_nopriv_custom_endpoint_callback', 'custom_endpoint_callback');

/** get_user_details fonksiyonunda user detay bilgilerini alıyoruz **/
function get_user_details($data)
{
    /** Data ile aldığımız ID önyüzde çalışmıyordu bunun için
     * sanitize_text_field($_GET['id']) olarak almam gerekti.
     * $data['id'] 'yi api kısmında get ile aldığımı önyüzde kullanıyorum
     * Bu şekilde hatanın önüne geçilmiş oldu.
     */
    $user_id = isset($data['id']) ? absint($data['id']) : intval(sanitize_text_field($_GET['id']));

    if ($user_id) {
        $response = wp_remote_get('https://jsonplaceholder.typicode.com/users/' .$user_id );

        if (is_wp_error($response)) {
            wp_send_json(array('status' => 'error', 'message' => $response->get_error_message()));
        }

        $user_data = json_decode(wp_remote_retrieve_body($response), true);
        wp_send_json(array('status' => 'success', 'data' => $user_data));
    } else {
        wp_send_json(array('status' => 'error', 'message' => 'Geçersiz kullanıcı IDsi.'));
    }
}

add_action('wp_ajax_get_user_details', 'get_user_details');

/** Özel endpoint'e erişim (Action) **/
add_action('rest_api_init', function() {
    register_rest_route('custom/v1', '/endpoint/', array(
        'methods' => 'GET',
        'callback' => 'custom_endpoint_callback',
    ));
    register_rest_route('custom/v1', '/user-details/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_user_details',
    ));
});


/** Jquery, Custom JS Dosyası Eklendi **/
function custom_enqueue_scripts() {
    /** jQuery Eklendi**/
    wp_enqueue_script('jquery');

    /** Özel JavaScript dosyası Eklendi **/
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'custom-script.js', array('jquery'), '1.0', true);

    /** JavaScript dosyasına PHP'den gelen veriyi aktarma **/
    wp_localize_script( 'custom-script', 'ajax', array(
        'url' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');