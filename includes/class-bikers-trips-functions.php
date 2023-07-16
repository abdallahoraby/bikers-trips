<?php




    function pre_dump($arr){

        echo '<pre style="direction:ltr !important;">';
        var_dump($arr);
        echo '</pre>';

    }

    add_filter( 'page_template', 'wp_page_template' );
    function wp_page_template( $page_template )
    {
        if ( is_page( 'bikers-trips-dashboard' ) ) {
            $page_template = ABSPATH . 'wp-content/plugins/bikers-trips/public/templates/page-bikers-trips-dashboard.php';
        }

        if ( is_page( 'trip' ) ) {
            $page_template = ABSPATH . 'wp-content/plugins/bikers-trips/public/templates/page-bikers-trips-trip.php';
        }

        if ( is_page( 'join-trip' ) ) {
            $page_template = ABSPATH . 'wp-content/plugins/bikers-trips/public/templates/page-bikers-trips-join-trip.php';
        }


        return $page_template;
    }


/******
 * Ajax action to start trip
 ******/

add_action('wp_ajax_start_trip', 'start_trip');
function start_trip(){
    global $wpdb;

    $trip_id = $_POST['trip_id'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    // add record in tracking table
    $table_name = $wpdb->prefix . "bikers_tracking";
    $insertTrack = $wpdb->insert( $table_name, array(
        'trip_id' => $trip_id,
        'start_longitude' => $longitude,
        'start_latitude' => $latitude,
        'status' => 'started',
    ) );

    $insertTrackLive = $wpdb->insert( $table_name, array(
        'trip_id' => $trip_id,
        'start_longitude' => $longitude,
        'start_latitude' => $latitude,
        'status' => 'live',
    ) );

    if( $insertTrack && $insertTrackLive ){
        // add post meta to trip with status ( live )
        update_post_meta($trip_id, 'trip_status', 'live');

        echo 'success';
    }


    wp_die();

}


/******
 * Ajax action to track live trip
 ******/

add_action('wp_ajax_track_trip', 'track_trip');
function track_trip(){
    global $wpdb;

    $trip_id = $_POST['trip_id'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $updated_at = date('Y-m-d H:i:s');

    // update live record in tracking table
    $table_name = $wpdb->prefix . "bikers_tracking";
    $trackLive = $wpdb->query($wpdb->prepare("UPDATE $table_name SET start_longitude='$longitude', start_latitude='$latitude', started_at='$updated_at' WHERE trip_id= %d AND status LIKE %s", array( $trip_id, 'live') ) );


    if( $trackLive ){
        echo 'success';
    }


    wp_die();

}

/******
 * Ajax action to finish trip
 ******/

add_action('wp_ajax_finish_trip', 'finish_trip');
function finish_trip(){

    global $wpdb;

    $trip_id = $_POST['trip_id'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $updated_at = date('Y-m-d H:i:s');

    // add record in tracking table
    $table_name = $wpdb->prefix . "bikers_tracking";

    $finishTrip = $wpdb->insert( $table_name, array(
        'trip_id' => $trip_id,
        'start_longitude' => $longitude,
        'start_latitude' => $latitude,
        'status' => 'finished',
    ) );

    if( $finishTrip ){
        // add post meta to trip with status ( live )
        update_post_meta($trip_id, 'trip_status', 'finished');

        echo 'success';
    }


    wp_die();

}




/******
 * Ajax action to save new trip data
 ******/

add_action('wp_ajax_add_new_trip', 'add_new_trip');
function add_new_trip(){

    $user_id = get_current_user_id();

    $user_full_name = get_the_author_meta('user_full_name', $user_id ) ;

    $trip_dest = $_POST['trip_dest'];
    $trip_members_count = $_POST['trip_members_count'];
    $trip_date = $_POST['trip_date'];
    $trip_duration = $_POST['trip_duration'];
    $trip_distance = $_POST['trip_distance'];

    // Create post object
    $my_post = array(
        'post_type'     => 'bikers-trips',
        'post_title'    => $trip_dest ,
        'post_content'  => 'Content',
        'post_status'   => 'pending',
        'post_author'   => $user_id,
        'meta_input' => array(
            'trip_dest' => $trip_dest,
            'trip_members_count' => $trip_members_count,
            'trip_date' => $trip_date,
            'trip_duration' => $trip_duration,
            'trip_distance' => $trip_distance,
            'trip_status'   => 'ready'
        )
    );

    // Insert the post into the database
    if( wp_insert_post( $my_post ) ){
        return array(
            'message' => 'success'
        );
    }


    wp_die();


}


/******
 * Ajax action to add members data to a trip
 ******/

add_action('wp_ajax_add_new_member', 'add_new_member');
function add_new_member(){

    global $wpdb;

    $member_name = $_POST['member_name'];
    $member_id_num = $_POST['member_id_num'];
    $member_license_id = $_POST['member_license_id'];
    $member_license_exp = $_POST['member_license_exp'];
    $trip_remaining_members = $_POST['trip_remaining_members'];

    if( $trip_remaining_members < 1 ){
        wp_die();
    } else {
        $trip_id = $_POST['trip_id'];
        $created_at = date('Y-m-d H:i:s');

        $table_name = $wpdb->prefix . "bikers_members";
        $wpdb->insert( $table_name, array(
            'name' => $member_name,
            'id_num' => $member_id_num,
            'license_id' => $member_license_id,
            'license_exp' => $member_license_exp,
            'trip_id' => $trip_id,
        ) );
        wp_die();
    }



}


/******
 * Ajax action to filter trips based on date (upcoming)
 ******/

add_action('wp_ajax_filter_trips_by_date', 'filter_trips_by_date');
function filter_trips_by_date(){

    $trip_date_filter = $_POST['trip_date_filter'];


    // get all trips for current user
    $args = array(
        'author_not_in' => array( get_current_user_id() ),
        'post_type' => 'bikers-trips',
        'post_status' => array( 'pending', 'publish' ),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'trip_date_only',
                'value' => $trip_date_filter
            ),
            array(
                'key' => 'trip_status',
                'value' => 'ready'
            )
        ),

    );

    $bikers_trips_list = new WP_Query( $args );

    $filtered_section = '';

    if( $bikers_trips_list->have_posts() ):

        foreach ( $bikers_trips_list->posts as $bikers_trip_post ):

            $trip_id = $bikers_trip_post->ID;
            $trip_title = get_the_title( $trip_id );
            $trip_distance = get_post_meta($trip_id, 'trip_distance')[0];
            $trip_date = get_post_meta($trip_id, 'trip_date')[0];
            $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');

            $posted_date = get_the_date('U', $trip_id);
            $posted_since = human_time_diff($posted_date,current_time( 'U' ));

            // check remaining members
            // get members added in wp_bikers_members
            global $wpdb;
            $current_user = wp_get_current_user();
            $studentTable = $wpdb->prefix.'bikers_members';


            $result = $wpdb->get_results ( "SELECT * FROM $studentTable WHERE `trip_id` = $trip_id  ");
            $registered_members_count = count($result);

            $trip_members_count = get_post_meta($trip_id, 'trip_members_count')[0];

            $available_members = (int) $trip_members_count - $registered_members_count;

            $trip_is_publish = get_post_status( $trip_id );

            if( $trip_is_publish !== 'publish' || $available_members < 1){
                $isPublish = 'hidden';
                $isAllowed = 'hidden';
            } else {
                $isPublish = '';
                $isAllowed = '';
            }





            $filtered_section .= '
                        <div class="col-md-12 mb-3">
                                                    <div class="background-test">
                                                        <div class="tour-wapper">
                                                            <div class="col-md-8">
                                                                <span class="distance">
                                                                    '. $trip_distance .' KM
                                                                </span>
                                                                <h6 class="mt-2"> '. $trip_date .' </h6>
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                
                                                                <h6>'. $trip_title .'</h6>
                                                                <div class="time-tour">
                                                                    <h6 class="ml-1">المدة</h6>
                                                                    <span> '. $posted_since .' </span>
                                                                </div>
                                                                <div class="text-center">
                                                                    <a href="'. home_url() .'/join-trip?trip_id='. $trip_id .'" class="btn btn-fill-out" '. $isPublish . ' '. $isAllowed .' >
                                                                        <span>
                                                                            انضم
                                                                            للرحلة
                                                                        </span>
                                                                    </a>
                                                                </div>
                
                                                            </div>
                
                                                        </div>
                                                    </div>
                                                </div>
                    ';


        endforeach;
    else:
        $filtered_section = '<div class="alert alert-danger" role="alert">
                              لاتوجد رحلات حالية
                            </div>';
    endif;

    echo $filtered_section;

    wp_die();


}



/******
 * Ajax action to get Trip Info
 ******/

add_action('wp_ajax_get_single_trip', 'get_single_trip');
function get_single_trip(){

    $trip_id = $_POST['trip_id'];

    $author_id = get_post_field( 'post_author', $trip_id );
    $user_full_name = get_the_author_meta('user_full_name', $author_id ) ;

    $trip_members_count = get_post_meta($trip_id, 'trip_members_count')[0];

    $trip_date = get_post_meta($trip_id, 'trip_date')[0];
    $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');

    $trip_dest = get_post_meta($trip_id, 'trip_dest')[0];

    $trip_distance = get_post_meta($trip_id, 'trip_distance')[0];


    $tripInfo = '
                <div class="container mt-5">
                    <div class="row tour-wapper-datails">
                        <div class="col-md-4 info-wappeer text-center"
                            style="border: 1px solid #ddd; padding: 10px 15px; border-radius: 10px; box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.10) !important; height: 100%;">
                            <h4>متابعة الرحلة</h4>
                            <div class="tour-details">
                                <h3>تفاصيل الرحلة</h3>
                                <div class="info d-flex mb-3">
                                    <i class="fas fa-info-circle"></i>
                                    <h5> المجموعة :</h5>
                                    <span>الغربية</span>
                                </div>
                                <hr>
                                <div class="info d-flex mb-3">
                                    <i class="fas fa-user"></i>
                                    <h5> قائد الرحلة :</h5>
                                    <span> '. $user_full_name .' </span>
                                </div>
                                <hr>
                                <div class="info d-flex mb-3">
                                    <i class="fas fa-user-friends"></i>
                                    <h5> عدد الاعضاء :</h5>
                                    <span> '. $trip_members_count .' </span>
                                </div>
                                <hr>
                                <div class="info d-flex mb-3">
                                    <i class="fas fa-calendar-alt"></i>
                                    <h5> التاريخ :</h5>
                                    <span> '. $trip_date .' </span>
                                </div>
                                <hr>
                                <div class="info d-flex mb-3">
                                    <i class="fas fa-clock"></i>
                                    <h5> وقت الانطلاق :</h5>
                                    <span>9:15 صباحا</span>
                                </div>
                                <hr>
                                <div class="info d-flex mb-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <h5> الوجهه :</h5>
                                    <span> '. $trip_dest .' </span>
                                </div>
                                <hr>
                                <div class="info d-flex mb-3">
                                    <i class="far fa-map"></i>
                                    <h5> المسافة :</h5>
                                    <span> '. $trip_distance .' KM</span>
                                </div>
                                <!-- <hr> -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="title-tour-details d-flex justify-content-center ">
                                <button class="btn btn-success"> بدء الرحلة </button>
                                <h4>الخريطه المباشرة</h4>
                                <button class="btn btn-danger"> انهاء الرحلة </button>
                            </div>
                            <div class="mapouter">
                                <div class="gmap_canvas"><iframe width="100%" height="300px" id="gmap_canvas"
                                        src="https://maps.google.com/maps?q=%D8%A7%D9%84%D8%B1%D9%8A%D8%A7%D8%B6%20-%20%D8%A7%D9%84%D8%B3%D8%B9%D9%88%D8%AF%D9%8A%D8%A9&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                    <a href="https://soap2day-to.com"></a>
                                    <br>
                                    <style>
                                        .mapouter {
                                            position: relative;
                                            text-align: right;
                                            height: 300px;
                                            width: 100%;
                                            /* border: 1px solid #ddd; */
                                            /* padding: 10px; */
                                        }
                                    </style>
                                    <a href="https://www.embedgooglemap.net">how to add google map to website</a>
                                    <style>
                                        .gmap_canvas {
                                            overflow: hidden;
                                            background: none !important;
                                            height: 300px;
                                            width: 100%;
                                        }
                                    </style>
                                </div>
                            </div>
                            <div class="title-tour-details mt-4 mb-3">
                                <div class="d-flex justify-content-center">
                                    <h4>تحديثات الرحلة</h4>
                                </div>
                                <div class="d-flex justify-content-between mt-4 mb-5">
                                    <div class="d-flex">
                                        <img src="'. plugin_dir_url( dirname( __FILE__ ) ) .'/public/images/distance.png" alt="">
                                        <div class="text-center">
                                            <h5>المسافة المقطوعه</h5>
                                            <span>5.2 KM</span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <img src="'. plugin_dir_url( dirname( __FILE__ ) ) .'/public/images/clock.png" alt="">
                                        <div class="text-center">
                                            <h5>المدة الحالية</h5>
                                            <span>00.29.13</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="line-section-vertical">
                                        <div class="d-flex">
                                            <div class="img-timeline-rokect">
                                                <img src="'. plugin_dir_url( dirname( __FILE__ ) ) .'/public/images/startup.png" alt="Picture">
                                            </div>
                                            <div class="header-timeLine">
                                                <h5 style="color:black">أنطلقت الرحلة الساعه 9:14 صباحا</h5>
                                            </div>
                                        </div>
            
                                        <div class="timeline-wappers">
                                            <div class=" mb-3 p-2 timeline-wapper__desc">
                                                <div class="img-client-timeLine">
                                                    <img src="client.jpg" alt="" class="img-fluid">
                                                </div>
                                                <div class="mr-3">
                                                    <h4 class="m-0">صالح دماس</h4>
                                                    <span style="font-size: 12px;">اليوم 12:15 صباحا</span>
                                                </div>
                                            </div>
            
                                            <img src="bikes.jpg" alt="" class="img-fluid">
            
                                            <div class="flex items-center icons-timeline">
            
            
                                                <i class="fas fa-heart"></i>
                                                <i class="far fa-heart"></i>
            
            
            
                                                <!-- <div class="placement">
                                                    <div class="heart"></div>
                                                </div> -->
            
                                                <!-- <i class="far fa-heart mr-3"></i> -->
                                                <i class="far fa-comment-alt"></i>
                                                <i class="fas fa-comment-alt"></i>
                                                <div id="comments-container"></div>
            
                                            </div>
                                        </div>
                                        <div class="cd-timeline__blocks">
                                            <div class="img-timeline-bike">
                                                <img src="'. plugin_dir_url( dirname( __FILE__ ) ) .'/public/images/motorcycles.png" class="img-fluid">
                                            </div>
                                            <div class="header-timeLine2 header-timeLine-border">
                                                <h5>54 KM</h5>
                                                <span style="font-size: 12px;">اليوم 12:15 صباحا</span>
                                            </div>
                                        </div>
            
            
                                    </div>
                                </div>
                            </div>
            
                            <!-- <section class="cd-timeline js-cd-timeline col-md-12">
            
                                <div class="container max-container-timeline row">
                                    <div class="col-md-12">
                                        <div class="cd-timeline__block">
                                            <div class="cd-timeline__img cd-timeline__img--picture">
                                                <img src="startup.png" alt="Picture">
                                            </div>
                                            <div class="header-timeLine">
                                                <h5>أنطلقت الرحلة الساعه 9:14 صباحا</h5>
                                            </div>
                                            <div class="cd-timeline__content text-component">
                                                <div class="d-flex align-items-center mb-3 p-2">
                                                    <div class="img-client-timeLine">
                                                        <img src="client.jpg" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="mr-3">
                                                        <h4 class="m-0">صالح دماس</h4>
                                                        <span style="font-size: 12px;">اليوم 12:15 صباحا</span>
                                                    </div>
                                                </div>
                                             
                                                <img src="bikes.jpg" alt="" class="img-fluid">
            
                                                <div class="flex items-center icons-timeline">
                                                    
                                                    <i class="far fa-heart mr-3"></i>
                                                    <i class="far fa-comment-alt"></i>
                                                </div>
                                            </div>
                                        </div>
            
                                        <div class="cd-timeline__block">
                                            <div class="cd-timeline__img cd-timeline__img--picture">
                                                <img src="motorcycles.png" alt="Movie">
                                            </div>
                                            <div class="header-timeLine header-timeLine-border">
                                                <h5>54 KM</h5>
                                                <span style="font-size: 12px;">اليوم 12:15 صباحا</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section> -->
                        </div>
                    </div>
                </div>
            ';

    echo $tripInfo;

    wp_die();


}



/****************************************
 * add settings page for this plugin
 ****************************************/


class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;


    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Bikers Trips App Settings',
            'Bikers Trips App Settings',
            'manage_options',
            'bikers_settings',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'bikers_trips_app_options' );
        ?>
        <div class="wrap">
            <h1>Bikers Trips App Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'bikers_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'bikers_trips_app_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Bikers Trips Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'bikers_settings' // Page
        );


        add_settings_field(
            'google_map_key',
            'Google Maps API Key',
            array( $this, 'google_map_key_callback' ),
            'bikers_settings',
            'setting_section_id'
        );

        add_settings_field(
            'default_latitude',
            'Default Latitude',
            array( $this, 'default_latitude_callback' ),
            'bikers_settings',
            'setting_section_id'
        );

        add_settings_field(
            'default_longitude',
            'Default Longitude',
            array( $this, 'default_longitude_callback' ),
            'bikers_settings',
            'setting_section_id'
        );


    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['google_map_key'] ) )
            $new_input['google_map_key'] = sanitize_text_field( $input['google_map_key'] );

        if( isset( $input['default_latitude'] ) )
            $new_input['default_latitude'] = sanitize_text_field( $input['default_latitude'] );

        if( isset( $input['default_longitude'] ) )
            $new_input['default_longitude'] = sanitize_text_field( $input['default_longitude'] );



        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print '';
    }


    /**
     * Get the settings option array and print one of its values
     */
    public function google_map_key_callback()
    {
        printf(
            '<input type="text" id="google_map_key" name="bikers_trips_app_options[google_map_key]" value="%s" />',
            isset( $this->options['google_map_key'] ) ? esc_attr( $this->options['google_map_key']) : ''
        );

    }

    /**
     * Get the settings option array and print one of its values
     */
    public function default_latitude_callback()
    {
        printf(
            '<input type="text" id="default_latitude" name="bikers_trips_app_options[default_latitude]" value="%s" />',
            isset( $this->options['default_latitude'] ) ? esc_attr( $this->options['default_latitude']) : ''
        );

    }

    /**
     * Get the settings option array and print one of its values
     */
    public function default_longitude_callback()
    {
        printf(
            '<input type="text" id="default_longitude" name="bikers_trips_app_options[default_longitude]" value="%s" />',
            isset( $this->options['default_longitude'] ) ? esc_attr( $this->options['default_longitude']) : ''
        );

    }



}

if( is_admin() )
    $my_settings_page = new MySettingsPage();

