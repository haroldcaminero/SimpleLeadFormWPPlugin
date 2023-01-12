<?php
namespace Harold_Leads;

class Customer {

    private $customer_fields = [];
    private $shortcode_name = HLGF_PLUGIN_NAME.'-form';

    public function __construct() {
        add_action( 'init', array( $this, '_init' ) );
        $this->customer_fields = [
            '_phone_number'      => __( 'Phone Number', HLGF_PLUGIN_NAME ),
            '_email_address'     => __( 'Email Address', HLGF_PLUGIN_NAME ),
            '_desired_budget'    => __( 'Desired Budget', HLGF_PLUGIN_NAME ),
            '_message'           => __( 'Message', HLGF_PLUGIN_NAME ),
        ];
    }

    public function _init() {
        // customer post types
        $this->_init_customer_post_type();
        // taxonomy
        $this->_init_customer_taxonomy();

        // additional custom fields 
        add_action( 'add_meta_boxes_customers', array( $this, '_add_customer_fields' ) );
        // saving custom fields
        add_action( 'save_post', array( $this, '_save_customer_meta_box_data') );
        // show custom fields in the list of customers
        add_action( 'manage_customers_posts_columns' , array( $this, '_custom_customers_columns' ), 10, 2 );
        add_filter( 'manage_edit-customers_sortable_columns', array( $this, '_set_custom_sortable_customers_columns' ) );
        add_action( 'manage_customers_posts_custom_column' ,  array( $this, '_custom_customers_column' ), 10, 2 );

        //make title as the name
        add_filter( 'enter_title_here', array( $this, '_change_title_text' ) );

        //shortcodes
        add_submenu_page(
            'edit.php?post_type=customers',
            __( 'Customers', HLGF_PLUGIN_NAME ),
            __( 'Form Shortcode', HLGF_PLUGIN_NAME ),
            'manage_options',
            'hlgf-customer-shortcode',
            array( $this, 'customer_shortcode_callback' ),
            100
        );

        add_shortcode( $this->shortcode_name , array( $this, '_customer_shortcode_callback' ) );

        // custom scripts
        add_action('admin_enqueue_scripts', array( $this, '_admin_scripts' ));
        add_action('wp_enqueue_scripts', array( $this, '_frontend_scripts' ));

        //ajax callback
        add_action( 'wp_ajax_hlgf_ajax', array( $this, 'hlgf_ajax_callback') );
        add_action( 'wp_ajax_nopriv_hlgf_ajax', array( $this, 'hlgf_ajax_callback') );
    }

    public function hlgf_ajax_callback(){
       
        $data = [
            'success' => 'no',
            'message' => 'error'
        ];

        if ( ! isset( $_POST['customer_info_nonce'] ) ) {
            echo json_encode($data); wp_die();
        }
    
        if ( ! wp_verify_nonce( $_POST['customer_info_nonce'], 'customer_info_nonce' ) ) {
            echo json_encode($data); wp_die();
        }

        if ( ! isset( $_POST['_name'] ) ) {
            echo json_encode($data); wp_die();
        }
    
        $post_id = wp_insert_post(array(
            'post_title'    => sanitize_text_field($_POST['_name']), 
            'post_type'     => 'customers', 
            'post_content'  => '',
            'post_status'   => 'publish',
        ));

        $this->_save_customer_meta_box_data( $post_id  );
       
        $data = [
            'success' => 'yes',
            'message' => 'good',
            'post_id' =>  $post_id
        ];

        echo json_encode($data);
        wp_die();
    }

    public function _customer_shortcode_callback ( $attr ) {

        $args = shortcode_atts( array(
            'name_label' => 'Name',
            'phone_number_label' => 'Phone Number',
            'email_address_label' => 'Email Address',
            'message_label' => 'Message',
            'desired_budget_label' => 'Desired Budget'
        ), $attr );
     
        ob_start();
        require_once ( HLGF_PLUGIN_PATH . 'admin/parts/form.php' );
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function _admin_scripts () {
        wp_enqueue_style( HLGF_PLUGIN_NAME.'admin-style', HLGF_PLUGIN_URL.'admin/css/admin.css', [], time() );
        wp_enqueue_script( HLGF_PLUGIN_NAME.'admin-script', HLGF_PLUGIN_URL. 'admin/js/admin.js', array('jquery'), time() );
    }

    public function _frontend_scripts () {
       
        wp_enqueue_style( HLGF_PLUGIN_NAME.'frontend-style', HLGF_PLUGIN_URL.'public/css/public.css', [], time() );
        wp_enqueue_script( HLGF_PLUGIN_NAME.'frontend-script', HLGF_PLUGIN_URL. 'public/js/public.js', array('jquery'), time() );
        wp_localize_script( HLGF_PLUGIN_NAME.'frontend-script', 'hlfa_ajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'security' => wp_create_nonce( 'customer_info_nonce' )
        ));
    }

    public function customer_shortcode_callback () {
        require_once ( HLGF_PLUGIN_PATH . 'admin/parts/shortcode.php' );
    }

    public function _save_customer_meta_box_data( $post_id ) {

        if ( ! isset( $_POST['customer_info_nonce'] ) ) {
            return;
        }
    
        if ( ! wp_verify_nonce( $_POST['customer_info_nonce'], 'customer_info_nonce' ) ) {
            return;
        }
    
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
    
        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
    
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
    
        }
        else {
    
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
    
        foreach ( $this->customer_fields as $name => $label ){
            if ( isset( $_POST[$name] ) ) {
                update_post_meta( $post_id, $name, sanitize_text_field( $_POST[$name] ) );
            }
            
        }

    }

    public function _add_customer_fields() {
        add_meta_box( 'customer_info', __( 'Customer Information', HLGF_PLUGIN_NAME ), array( $this, '_customer_box_callback' ), null, 'normal' );
    }

    public function _customer_box_callback( $post ) {
        wp_nonce_field( 'customer_info_nonce', 'customer_info_nonce' );

        foreach ( $this->customer_fields as $name => $label ) {
            $value = get_post_meta( $post->ID, $name, true );
            echo '<div style="margin-bottom: 20px;">';
            echo '<label for="' . $name . '">' . $label . '</label>';
            if( $name == '_message' ){
                echo '<textarea style="width:100%" id="hlgf_' . $name . '" name="' . $name . '">' .  esc_attr( $value ) . '</textarea>';
            }
            else{
                echo '<input type="text" style="width:100%" id="hlgf_' . $name . '" value="' . esc_attr( $value ) . '" name="' . $name . '" />';
            }
            echo '</div>';
        }

    }

    public function _init_customer_post_type() {
        $labels = array( 
            'name' => __( 'Customers' , HLGF_PLUGIN_NAME ),
            'singular_name' => __( 'Customer' , HLGF_PLUGIN_NAME ),
            'add_new' => __( 'New Customer' , HLGF_PLUGIN_NAME ),
            'add_new_item' => __( 'Add New Customer' , HLGF_PLUGIN_NAME ),
            'edit_item' => __( 'Edit Customer' , HLGF_PLUGIN_NAME ),
            'new_item' => __( 'New Customer' , HLGF_PLUGIN_NAME ),
            'view_item' => __( 'View Customer' , HLGF_PLUGIN_NAME ),
            'search_items' => __( 'Search Customers' , HLGF_PLUGIN_NAME ),
            'not_found' =>  __( 'No Customers Found' , HLGF_PLUGIN_NAME ),
            'not_found_in_trash' => __( 'No Customers found in Trash' , HLGF_PLUGIN_NAME ),
        );

        $args = array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'hierarchical' => false,
            'supports' => array(
                'title', 
                'custom-fields', 
                'thumbnail',
                'page-attributes'
            ),
            'rewrite'   => array( 'slug' => 'customers' ),
            'show_in_rest' => true
        );

        register_post_type( 'customers', $args );
    }

    public function _init_customer_taxonomy(){
        $labels = array(
            'name' => __( 'Customer Categories', HLGF_PLUGIN_NAME ),
            'singular_name' => __( 'Customer Category', HLGF_PLUGIN_NAME ),
            'search_items' =>  __( 'Search Customer Categories', HLGF_PLUGIN_NAME ),
            'all_items' => __( 'All Customer Categories', HLGF_PLUGIN_NAME ),
            'parent_item' => __( 'Parent Customer Category', HLGF_PLUGIN_NAME ),
            'parent_item_colon' => __( 'Parent Customer Category:', HLGF_PLUGIN_NAME ),
            'edit_item' => __( 'Edit Customer Category', HLGF_PLUGIN_NAME ), 
            'update_item' => __( 'Update Customer Category', HLGF_PLUGIN_NAME ),
            'add_new_item' => __( 'Add New Customer Category', HLGF_PLUGIN_NAME ),
            'new_item_name' => __( 'New Customer Category Name', HLGF_PLUGIN_NAME ),
            'menu_name' => __( 'Customer Categories', HLGF_PLUGIN_NAME ),
          );    
          
    
        register_taxonomy('customer_categories',array('customers'), array(
            'hierarchical' => true,
            'public' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'customer_category' ),
        ));
    }

    public function _custom_customers_columns( $columns ) {
        $columns['title'] = __( 'Name', HLGF_PLUGIN_NAME );
        foreach ( $this->customer_fields as $name => $label ) {
            $columns[$name] = $label;
        }
        return $columns;
    }

    public function _set_custom_sortable_customers_columns($columns) {
        $columns['_customer_name'] = '_customer_name';
        return $columns;
    }

    function _custom_customers_column( $column, $post_id ) {
        if ( isset( $this->customer_fields[$column] ) ) {
            echo get_post_meta( $post_id , $column , true ); 
        }
    }

    function _change_title_text( $title ){
        $screen = get_current_screen();
      
        if ( 'customers' == $screen->post_type ) {
            $title = 'Enter name here';
        }
        return $title;
    }

}

