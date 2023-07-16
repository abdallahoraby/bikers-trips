<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


    function biekrs_trips_metadata(){
        add_meta_box(
            'trip_info',
            __('معلومات الرحلة', 'bikers-trips'),
            'custom_metabox_field',
            'bikers-trips',
            'normal',
            'core'
        );
    }

    add_action('add_meta_boxes', 'biekrs_trips_metadata');

    function custom_metabox_field( $post ){
        wp_nonce_field(basename(__FILE__), 'bikers_trip_nonce');
        $trip_stored_meta = get_post_meta( $post->ID );
        ?>

        <div>
            <div class="meta-row">
                <div class="meta-th">
                    <label for="trip_dest" class="row-title">وجهة الرحلة</label>
                </div>

                <div class="meta-td">
                    <input type="text" name="trip_date" id="trip_dest" value="<?php if( !empty($trip_stored_meta['trip_dest']) ) echo esc_attr( $trip_stored_meta['trip_dest'][0] ); ?>">
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-th">
                    <label for="trip_members_count" class="row-title">عدد الأعضاء</label>
                </div>

                <div class="meta-td">
                    <input type="text" name="trip_members_count" id="trip_members_count" value="<?php if( !empty($trip_stored_meta['trip_members_count']) ) echo esc_attr( $trip_stored_meta['trip_members_count'][0] ); ?>">
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-th">
                    <label for="trip_date" class="row-title">تاريخ الرحلة</label>
                </div>

                <div class="meta-td">
                    <input type="text" name="trip_date" id="trip_date" value="<?php if( !empty($trip_stored_meta['trip_date']) ) echo esc_attr( $trip_stored_meta['trip_date'][0] ); ?>">
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-th">
                    <label for="trip_duration" class="row-title">مدة الرحلة </label>
                </div>

                <div class="meta-td">
                    <input type="text" name="trip_duration" id="trip_duration" value="<?php if( !empty($trip_stored_meta['trip_duration']) ) echo esc_attr( $trip_stored_meta['trip_duration'][0] ); ?>">
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-th">
                    <label for="trip_distance" class="row-title">مسافة الرحلة </label>
                </div>

                <div class="meta-td">
                    <input type="text" name="trip_distance" id="trip_distance" value="<?php if( !empty($trip_stored_meta['trip_distance']) ) echo esc_attr( $trip_stored_meta['trip_distance'][0] ); ?>">
                </div>
            </div>


        </div>



    <?php
    }


function trip_metadata_save( $post_id ){
        // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset($_POST['bikers_trip_nonce']) && wp_verify_nonce( $_POST['bikers_trip_nonce'], basename(__FILE__) ) ) ? 'true' : 'false';

    // Exit script depending on save status
    if( $is_autosave || $is_revision || !$is_valid_nonce ){
        return;
    }

    if( isset( $_POST['trip_dest'] ) ){
        update_post_meta( $post_id, 'trip_dest', sanitize_text_field($_POST['trip_dest']) );
    }

    if( isset( $_POST['trip_members_count'] ) ){
        update_post_meta( $post_id, 'trip_members_count', sanitize_text_field($_POST['trip_members_count']) );
    }

    if( isset( $_POST['trip_date'] ) ){
        update_post_meta( $post_id, 'trip_date', sanitize_text_field($_POST['trip_date']) );
    }

    if( isset( $_POST['trip_duration'] ) ){
        update_post_meta( $post_id, 'trip_duration', sanitize_text_field($_POST['trip_duration']) );
    }

    if( isset( $_POST['trip_distance'] ) ){
        update_post_meta( $post_id, 'trip_distance', sanitize_text_field($_POST['trip_distance']) );
    }

}

add_action( 'save_post', 'trip_metadata_save' );
