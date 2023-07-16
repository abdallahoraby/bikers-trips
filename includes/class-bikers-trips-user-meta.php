<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function my_enqueue() {

    wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . '/js/bikers-trips-public.js', array('jquery') );

    wp_localize_script( 'ajax-script', 'my_ajax_object',
        array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'my_enqueue' );

// add custom user meta fields

add_action( 'show_user_profile', 'module_user_profile_fields' );
add_action( 'edit_user_profile', 'module_user_profile_fields' );
function module_user_profile_fields( $user )
{ ?>
    <h3>Bikers Trips Info</h3>

    <table class="form-table">
        <tr>
            <th><label for="module_activation">Module Activation</label></th>
            <td>
                <input id="module_activation" name="module_activation" type="checkbox" value="1" <?php if ( get_the_author_meta( 'module_activation', $user->ID ) == 1  ) echo ' checked="checked"'; ?> />
                <span class="description"><?php _e("Please enter your address."); ?></span>
            </td>
        </tr>

        <tr>
            <th><label for="user_full_name">الاسم بالكامل :</label></th>
            <td>
                <input id="user_full_name" name="user_full_name" type="text" value="<?php if ( get_the_author_meta( 'user_full_name', $user->ID ) ) echo get_the_author_meta( 'user_full_name', $user->ID ); ?>"  />
            </td>
        </tr>

        <tr>
            <th><label for="user_id_num">رقم الهوية :</label></th>
            <td>
                <input id="user_id_num" name="user_id_num" type="text" value="<?php if ( get_the_author_meta( 'user_id_num', $user->ID ) ) echo get_the_author_meta( 'user_id_num', $user->ID ); ?>"  />
            </td>
        </tr>

        <tr>
            <th><label for="user_license_id">رقم الرخصة :</label></th>
            <td>
                <input id="user_license_id" name="user_license_id" type="text" value="<?php if ( get_the_author_meta( 'user_license_id', $user->ID ) ) echo get_the_author_meta( 'user_license_id', $user->ID ); ?>"  />
            </td>
        </tr>

        <tr>
            <th><label for="user_license_exp">تاريخ انتهاء الرخصة :</label></th>
            <td>
                <input id="user_license_exp" name="user_license_exp" type="text" value="<?php if ( get_the_author_meta( 'user_license_exp', $user->ID ) ) echo get_the_author_meta( 'user_license_exp', $user->ID ); ?>" class="custom_date" />
            </td>
        </tr>

    </table>
<?php }

add_action( 'personal_options_update', 'save_module_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_module_user_profile_fields' );

function save_module_user_profile_fields( $user_id )
{
    if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }else{
        if(isset($_POST['module_activation']) && $_POST['module_activation'] > 0){
            update_user_meta( $user_id, 'module_activation', $_POST['module_activation'] );
        }else{
            delete_user_meta($user_id, 'module_activation');
        }

        // New Bikers Trips user meta
        if( isset($_POST['user_full_name']) ){
            update_user_meta( $user_id, 'user_full_name', $_POST['user_full_name'] );
        }else{
            delete_user_meta($user_id, 'user_full_name');
        }

        if( isset($_POST['user_id_num']) ){
            update_user_meta( $user_id, 'user_id_num', $_POST['user_id_num'] );
        }else{
            delete_user_meta($user_id, 'user_id_num');
        }

        if( isset($_POST['user_license_id']) ){
            update_user_meta( $user_id, 'user_license_id', $_POST['user_license_id'] );
        }else{
            delete_user_meta($user_id, 'user_license_id');
        }

        if( isset($_POST['user_license_exp']) ){
            update_user_meta( $user_id, 'user_license_exp', $_POST['user_license_exp'] );
        }else{
            delete_user_meta($user_id, 'user_license_exp');
        }

    }
}


/******
 * Ajax action to save user data
******/

 add_action('wp_ajax_update_biker_custom_meta', 'update_biker_custom_meta');
 function update_biker_custom_meta(){

     $user_id = get_current_user_id();

     if(isset($_POST['user_full_name'])){
         update_user_meta( $user_id, 'user_full_name', $_POST['user_full_name'] );
     }

     if(isset($_POST['user_id_num'])){
         update_user_meta( $user_id, 'user_id_num', $_POST['user_id_num'] );
     }

     if(isset($_POST['user_license_id'])){
         update_user_meta( $user_id, 'user_license_id', $_POST['user_license_id'] );
     }

     if(isset($_POST['user_license_exp'])){
         update_user_meta( $user_id, 'user_license_exp', $_POST['user_license_exp'] );
     }

     wp_die();


 }


