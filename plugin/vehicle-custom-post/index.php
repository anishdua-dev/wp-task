<?php
   /*
   Plugin Name: vehicle Custom Post Type
   Plugin URI: http://www.netscriptindia.com
   description: >- This plugin is used to create a custom post type for vehicle.
  a plugin to create awesomeness and spread joy
   Version: 1.2
   Author: Mr. Anish Dua
   Author URI: http://www.netscriptindia.com
   License: GPL2
   */
?>
<?php
/*
* Creating a function to create our vehicle Custom post type
*/
 
class WPVehiclePostType {
    public $singular_name = 'Vehicle';
    public $plural_name = 'Vehicles';
    public $plural_name_small = 'vehicles';
    public $post_type_slug = 'vehicles';
    public $tax_slug = 'vehicle_type';

    public function __construct() {
        register_activation_hook( __FILE__, array($this, 'booking_table_on_activation') );
        add_action( 'init', array($this, 'vehicle_custom_post_type') );
        add_action( 'add_meta_boxes', array($this, 'wpt_add_vehicle_metaboxes') );
        add_action( 'save_post', array($this,'wpt_save_vehicle_meta'), 1, 2 );
        add_shortcode( 'vehicle_booking_form', array($this,'vehicli_booking_func') );
        add_action( 'wp_enqueue_scripts', array($this,'themeslug_enqueue_script') );
        add_action( 'admin_menu', array($this, 'register_booking_menu_page') );
        add_action('admin_enqueue_scripts', array($this,'my_enqueue'));

        add_action("wp_ajax_get_vechile_by_cat", array($this, "get_vechile_by_cat"));
        add_action("wp_ajax_nopriv_get_vechile_by_cat", array($this, "get_vechile_by_cat"));

        add_action("wp_ajax_get_vechile_price", array($this, "get_vechile_price"));
        add_action("wp_ajax_nopriv_get_vechile_price", array($this, "get_vechile_price"));

        add_action("wp_ajax_send_booking_form_action", array($this, "send_booking_form_action"));
        add_action("wp_ajax_nopriv_send_booking_form_action", array($this, "send_booking_form_action"));

        add_action("wp_ajax_change_booking_status", array($this, "change_booking_status"));
        add_action("wp_ajax_nopriv_change_booking_status", array($this, "change_booking_status"));

        add_action( 'phpmailer_init', array($this, 'send_smtp_email' ));
        
        add_action( 'wp_mail_failed', array($this, 'onMailError'), 10, 1 );
        
    }

    public function booking_table_on_activation(){
        // create the custom table
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'save_bookings';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            first_name varchar(50) NOT NULL default '',
            last_name varchar(50) NOT NULL default '',
            email varchar(50) NOT NULL default '',
            phone varchar(50) NOT NULL default '',
            vehicle_type int(11) NOT NULL,
            vehicle_id int(50) NOT NULL,
            price varchar(50) NOT NULL,
            message varchar(350) NOT NULL default '',
            status int(11) NOT NULL default '0') $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function vehicle_custom_post_type() {
        // Set UI labels for Custom Post Type
            $labels = array(
                'name'                => _x( 'Vehicles', 'Post Type General Name'),
                'singular_name'       => _x( $this->singular_name, 'Post Type Singular Name'),
                'menu_name'           => __( $this->plural_name, 'twentytwenty' ),
                'parent_item_colon'   => __( 'Parent '.$this->singular_name),
                'all_items'           => __( 'All '.$this->plural_name),
                'view_item'           => __( 'View ' . $this->singular_name),
                'add_new_item'        => __( 'Add New Vehicle', 'twentytwenty' ),
                'add_new'             => __( 'Add '.$this->singular_name, 'twentytwenty' ),
                'edit_item'           => __( 'Edit '.$this->singular_name),
                'update_item'         => __( 'Update '.$this->singular_name),
                'search_items'        => __( 'Search '.$this->singular_name),
                'not_found'           => __( 'Not Found'),
                'not_found_in_trash'  => __( 'Not found in Trash'),
            );
             
        // Set other options for Custom Post Type
             
            $args = array(
                'label'               => __( $this->plural_name_small, 'twentytwenty' ),
                'description'         => __( $this->singular_name.' news and reviews', 'twentytwenty' ),
                'labels'              => $labels,
                // Features this CPT supports in Post Editor
                'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 5,
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'post',
                'show_in_rest' => true,
         
            );
             
            // Registering your Custom Post Type
            register_post_type( $this->post_type_slug, $args );

            $labels_taxonomy = array(
                'name'              => _x( $this->singular_name.' Types', 'taxonomy general name' ),
                'singular_name'     => _x( $this->singular_name.' Type', 'taxonomy singular name' ),
                'search_items'      => __( 'Search ' . $this->singular_name . ' Types' ),
                'all_items'         => __( 'All '. $this->singular_name .' Types' ),
                'parent_item'       => __( 'Parent '. $this->singular_name .' Type' ),
                'parent_item_colon' => __( 'Parent '. $this->singular_name .' Type:' ),
                'edit_item'         => __( 'Edit '. $this->singular_name .' Type' ), 
                'update_item'       => __( 'Update '. $this->singular_name .' Type' ),
                'add_new_item'      => __( 'Add New '. $this->singular_name .' Type' ),
                'new_item_name'     => __( 'New '. $this->singular_name .' Type' ),
                'menu_name'         => __( ''. $this->singular_name .' Types' ),
              );
              $args = array(
                'labels' => $labels_taxonomy,
                'hierarchical' => true,
              );
              register_taxonomy( $this->tax_slug, $this->post_type_slug, $args );



         
        }
        public function wpt_add_vehicle_metaboxes() {
            add_meta_box(
                'wpt_vehicle_custom_fields',
                'Vehicle price per hour',
                array($this,'wpt_vehicle_custom_callback'),
                'vehicles',
                'normal',
                'default'
            );
        }

        public function wpt_vehicle_custom_callback() {
            global $post;
        
            // Nonce field to validate form request came from current site
            wp_nonce_field( basename( __FILE__ ), 'vehicle_fields' );
        
            // Get the location data if it's already been entered
            $start_price = get_post_meta( $post->ID, 'start_price', true );
        
            // Output the field
            echo '<input type="text" name="start_price" value="' . esc_textarea( $start_price )  . '" class="widefat">';
        
        }

        public function wpt_save_vehicle_meta($post_id, $post){
            // Return if the user doesn't have edit permissions.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        
            // Now that we're authenticated, time to save the data.
            // This sanitizes the data from the field and saves it into an array $events_meta.
            $start_price = esc_textarea( $_POST['start_price'] );
        
            update_post_meta($post_id, 'start_price', $start_price);
        
        }

        public function vehicli_booking_func() {

            $vehicle_taxonomies = get_terms( array(
                'taxonomy' => 'vehicle_type',
                'hide_empty' => false
            ) );
            $args = array(
                'numberposts' => -1,
                'post_type'   => 'vehicles'
              );
               
              $all_vehicles = get_posts( $args );

            ob_start(); 
            include_once('forms/booking-form.php');
            ?>
            
        <?php
            $output_string = ob_get_contents();
            ob_end_clean();
        
            return $output_string;
        }

        public function themeslug_enqueue_script() {
            wp_enqueue_script( 'jquery-v-2', 'https://code.jquery.com/jquery-2.1.3.min.js', false );
            wp_enqueue_script( 'mycustomJs', plugin_dir_url( __FILE__ ).'js/custom-script.js', array('jquery'),'1.5.0');
            wp_localize_script( 'mycustomJs', 'frontend_ajax_object',
                    array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'data_var_1' => 'value 1',
                    'data_var_2' => 'value 2',
                )
            );
        }

        public function my_enqueue() {
            wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery'), '1.2.5');
            wp_localize_script( 'my_custom_script', 'backend_ajax_object',
                    array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'data_var_1' => 'value 1',
                    'data_var_2' => 'value 2',
                )
            );
        }

        public function register_booking_menu_page() {
            add_menu_page( 'Booking', 'Bookings', 'manage_options', 'manage_bookings', array($this, 'callback_booking'), 'dashicons-welcome-widgets-menus', 90 );
        }

        public function callback_booking(){
            global $wpdb;
            $tablename = $wpdb->prefix.'save_bookings';
            $tablename1 = $wpdb->prefix.'posts';
            $resData = $wpdb->get_results("SELECT * FROM $tablename sb LEFT JOIN $tablename1 p ON sb.vehicle_id = p.ID", ARRAY_A);
           include_once('admin/booking.php');
        }

        public function get_vechile_by_cat(){
            global $post;
            $json = array();
            if(!empty($_POST['cat_id'])){
                $cat_id = $_POST['cat_id'];
                $args = array(
                    'numberposts' => -1,
                    'tax_query' => array(
                        array(
                        'taxonomy' => 'vehicle_type',
                        'field' => 'term_id',
                        'terms' => $cat_id
                         )
                        ),
                    'post_type'   => 'vehicles'
                  );
                   
                  $vehicles_by_cat = get_posts( $args );
                  if(!empty($vehicles_by_cat)){
                    $json['success'] = true;
                    $json['data'] = $vehicles_by_cat;
                  }else{
                    $json['success'] = false;
                    $json['data'] = array();
                  }
                 
            }else{
                $json['success'] = false;
                $json['data'] = array();
            }
            echo json_encode($json);
            die();
        }

        public function get_vechile_price(){
            $json = array();
            if(!empty($_POST['vehicle_id'])){
                $vehicle_price = get_post_meta( $_POST['vehicle_id'], 'start_price', true ); 
                if(!empty($vehicle_price)){
                    $json['success'] = true;
                    $json['data'] = $vehicle_price;
                }else{
                    $json['success'] = false;
                    $json['data'] = array();
                }
        
            }else{
                $json['success'] = false;
                $json['data'] = array();
            }
            echo json_encode($json);
            die();
        }

        public function send_booking_form_action(){
            // echo '<pre>'; print_r($_POST); die();
            global $wpdb;
            $json = array();
            $tablename = $wpdb->prefix.'save_bookings';
            if(isset($_POST['action']) && $_POST['action'] == 'send_booking_form_action'){
                $save_booking = $wpdb->insert( $tablename, array(
                    'first_name' => $_POST['first_name'], 
                    'last_name' => $_POST['last_name'],
                    'email' => $_POST['email'], 
                    'phone' => $_POST['phone'],
                    'vehicle_type' => $_POST['vehicle_type'], 
                    'vehicle_id' => $_POST['vehicle_name'], 
                    'price' => $_POST['vehicle_price'],
                    'message' => $_POST['message']
                ),
                    array( '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s')
                );
        
                if($save_booking){
                    $message = '';
                    $message .= '<html>';
                    $message .= '<head>';   
                    $message .= '<title>New Booking</title>';
                    $message .= '</head>';
                    $message .= '<body>';
                    $message .= '<p>New Booking is made</p>';
                    $message .= '<table>';
                        $message .= '<tr>';
                            $message .= '<th>Booking ID</th>';
                            $message .= '<th>Booking Status</th>';
                        $message .= '</tr>';
                        $message .= '<tr>';
                            $message .= '<td>'.$save_booking.'</td>';
                            $message .= '<td>Pending</td>';
        
                        $message .= '</tr>';
                    $message .= '</table>';
                    $message .= '</body>';
                    $message .= '<html>';
                    $headers = array('Content-Type: text/html; charset=UTF-8');
        
                    // Admin Email
                    $to = get_option( 'admin_email' );
                    $subject = 'New Booking';           
                
                    wp_mail( $to, $subject, $message, $headers );
        
                    // Customer Email
                    $to = $_POST['email'];
                    wp_mail( $to, $subject, $message, $headers );
        
                    $json['success'] = true;
                    $json['data'] = $save_booking;
                }else{
                    $json['success'] = false;
                    $json['data'] = array();
                }
            }
            echo json_encode($json);
            die();
        }

        public function change_booking_status(){
            global $wpdb;
            $json = array();
            $table_name = $wpdb->prefix.'save_bookings';
        
            $update_req = $wpdb->update( 
                $table_name,
                array( 'status' =>$_POST['status']),
                array( 'id' => $_POST['booking_id'] ) 
            );
            if($update_req){
                if($_POST['status'] == 0){
                    $status = 'Pending';
                }elseif($_POST['status'] == 1){
                    $status = 'Approved';
                }elseif($_POST['status'] == 2){
                    $status = 'Reject';
                }else{
                    $status = 'Completed';
                }
                $message = '';
                    $message .= '<html>';
                    $message .= '<head>';   
                    $message .= '<title>Booking Status update</title>';
                    $message .= '</head>';
                    $message .= '<body>';
                    $message .= '<p>Your booking status is updated.</p>';
                    $message .= '<table>';
                        $message .= '<tr>';
                            $message .= '<th>Booking ID</th>';
                            $message .= '<th>Booking Status</th>';
                        $message .= '</tr>';
                        $message .= '<tr>';
                            $message .= '<td>'.$_POST['booking_id'].'</td>';
                            $message .= '<td>'. $status .'</td>';
        
                        $message .= '</tr>';
                    $message .= '</table>';
                    $message .= '</body>';
                    $message .= '<html>';
                    $headers = array('Content-Type: text/html; charset=UTF-8');
        
                    // Admin Email
                    $to = get_option( 'admin_email' );
                    $subject = 'Booking status updated';           
                
                    wp_mail( $to, $subject, $message, $headers );
                $json['success'] = true;
                $json['data'] = $update_req;
            }else{
                $json['success'] = false;
                $json['data'] = array();
            }
            echo json_encode($json);
            die();  
        }

        public function send_smtp_email( $phpmailer ) {
            $phpmailer->isSMTP();
            $phpmailer->Host       = 'smtp.gmail.com';
            $phpmailer->Port       = '587';
            $phpmailer->SMTPSecure = 'tls';
            $phpmailer->SMTPAuth   = true;
            $phpmailer->Username   = 'moudgil.developer@gmail.com';
            $phpmailer->Password   = 'radhey@92';
            $phpmailer->From       = 'flyhighdua@gmail.com';
            $phpmailer->FromName   = 'Anish';
        }
        public function onMailError( $wp_error ) {
            echo "<pre>";
            print_r($wp_error);
            echo "</pre>";
        }
}

$vehiclepost = new WPVehiclePostType();
?>