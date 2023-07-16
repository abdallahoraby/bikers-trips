<?php get_header(); ?>

<?php

    $trip_id = $_GET['trip_id'];

    $author_id = get_post_field( 'post_author', $trip_id );
    $user_full_name = get_the_author_meta('user_full_name', $author_id ) ;

    $trip_members_count = get_post_meta($trip_id, 'trip_members_count')[0];

    $trip_date = get_post_meta($trip_id, 'trip_date')[0];
    $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');

    $trip_dest = get_post_meta($trip_id, 'trip_dest')[0];

    $trip_distance = get_post_meta($trip_id, 'trip_distance')[0];

    $trip_status_meta = get_post_meta($trip_id, 'trip_status');
    if( !empty($trip_status_meta)){
        $trip_status = $trip_status_meta[0];
    }

    ?>


                    <div class="container mt-5">
                        <div class="row tour-wapper-datails">
                            <div class="col-md-4 info-wappeer text-center"
                                style="border: 1px solid #ddd; padding: 10px 15px; border-radius: 10px; box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.10) !important; height: 100%;">
                                <h4>متابعة الرحلة</h4>
                                <div class="tour-details trip_status_<?php echo $trip_status ; ?>">
                                    <h3>
                                        تفاصيل الرحلة
                                        <span class="trip-status">
                                            <strong> live </strong>
                                            <div class="trip-status-dot"></div>
                                        </span>
                                    </h3>
                                    <div class="info d-flex mb-3">
                                        <i class="fas fa-info-circle"></i>
                                        <h5> المجموعة :</h5>
                                        <span>الغربية</span>
                                    </div>
                                    <hr>
                                    <div class="info d-flex mb-3">
                                        <i class="fas fa-user"></i>
                                        <h5> قائد الرحلة :</h5>
                                        <span> <?php echo $user_full_name ; ?> </span>
                                    </div>
                                    <hr>
                                    <div class="info d-flex mb-3">
                                        <i class="fas fa-user-friends"></i>
                                        <h5> عدد الاعضاء :</h5>
                                        <span> <?php echo $trip_members_count ; ?> </span>
                                    </div>
                                    <hr>
                                    <div class="info d-flex mb-3">
                                        <i class="fas fa-calendar-alt"></i>
                                        <h5> التاريخ :</h5>
                                        <span> <?php echo $trip_date ; ?> </span>
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
                                        <span> <?php echo $trip_dest ; ?> </span>
                                    </div>
                                    <hr>
                                    <div class="info d-flex mb-3">
                                        <i class="far fa-map"></i>
                                        <h5> المسافة :</h5>
                                        <span> <?php echo $trip_distance ; ?> KM</span>
                                    </div>
                                    <!-- <hr> -->
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="title-tour-details d-flex justify-content-center ">
                                    <?php
                                        $trip_status_meta = get_post_meta($trip_id, 'trip_status');
                                        $trip_status = $trip_status_meta[0];

                                        $trip_is_publish = get_post_status($trip_id);

                                        if( $trip_status === 'live' || $trip_is_publish !== 'publish' || $trip_status === 'finished' ){
                                            $start_enabled = 'disabled';
                                        } else {
                                            $start_enabled = '';
                                        }

                                        if( $trip_status === 'finished' || $trip_status === 'ready' || $trip_is_publish !== 'publish' ){
                                            $finish_disabled = 'disabled';
                                        } else {
                                            $finish_disabled = '';
                                        }
                                    ?>
                                    <button class="btn btn-success trip_actions start_trip" data-trip_id="<?php echo $trip_id;?>" <?php echo $start_enabled; ?> > بدء الرحلة </button>
                                    <h4>الخريطه المباشرة</h4>
                                    <button class="btn btn-danger trip_actions finish_trip" data-trip_id="<?php echo $trip_id;?>" <?php echo $finish_disabled; ?> > انهاء الرحلة </button>
                                </div>
                                <div class="mapouter">
                                    <div class="gmap_canvas">
<!--                                        <iframe width="100%" height="300px" id="gmap_canvas" src="https://maps.google.com/maps?q=%D8%A7%D9%84%D8%B1%D9%8A%D8%A7%D8%B6%20-%20%D8%A7%D9%84%D8%B3%D8%B9%D9%88%D8%AF%D9%8A%D8%A9&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>-->

                                        <?php

                                            if( $trip_status === 'live' ){
                                                global $wpdb;

                                                $table_name = $wpdb->prefix . "bikers_tracking";

                                                $trip_live_info_query = $wpdb->get_results( "SELECT * FROM $table_name WHERE `trip_id` = $trip_id" );

                                            }

                                        ?>

                                        <input type="hidden" value="<?php echo $trip_status ;?>" class="trip_status_refresh"></input>

                                        <input type="hidden" value="" id="current_longitude">
                                        <input type="hidden" value="" id="current_latitude">
                                        <button onclick="showPosition();" id="click_me" data-trip_id="<?php echo $trip_id;?>"  hidden>Show Position</button>

                                        <?php

                                            $google_maps_key_option = get_option('bikers_trips_app_options');
                                            if( !empty($google_maps_key_option) ){
                                                $google_maps_key = $google_maps_key_option['google_map_key'];
                                            }

                                            $default_latitude_option = get_option('bikers_trips_app_options');
                                            if( !empty($default_latitude_option) ){
                                                $default_latitude = $default_latitude_option['default_latitude'];
                                            }

                                            $default_longitude_option = get_option('bikers_trips_app_options');
                                            if( !empty($default_longitude_option) ){
                                                $default_longitude = $default_longitude_option['default_longitude'];
                                            }

                                        ?>

                                        <input type="hidden" name="google_map_key" id="google_map_key" value="<?php echo $google_maps_key; ?>">
                                        <input type="hidden" name="default_latitude" id="default_latitude" value="<?php echo $default_latitude; ?>">
                                        <input type="hidden" name="default_longitude" id="default_longitude" value="<?php echo $default_longitude; ?>">

                                        <iframe
                                                width="100%"
                                                height="350"
                                                frameborder="0" style="border:0"
                                                id="current_map"
                                                src="https://www.google.com/maps/embed/v1/place?key=<?php echo $google_maps_key; ?>&q=<?php echo $default_latitude ;?>,<?php echo $default_longitude ;?>&zoom=14&maptype=roadmap" allowfullscreen>
                                        </iframe>

                                        <style>
                                            .mapouter {
                                                position: relative;
                                                text-align: right;
                                                height: auto;
                                                width: 100%;
                                                /* border: 1px solid #ddd; */
                                                /* padding: 10px; */
                                            }
                                        </style>
                                        <style>
                                            .gmap_canvas {
                                                overflow: hidden;
                                                background: none !important;
                                                height: auto;
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
                                            <img src=" <?php echo plugin_dir_url( dirname( __FILE__ ) ) .'/images/distance.png' ; ?>" alt="">
                                            <div class="text-center">
                                                <h5>المسافة المقطوعه</h5>
                                                <span>5.2 KM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <img src=" <?php echo plugin_dir_url( dirname( __FILE__ ) ) .'/images/clock.png' ; ?>" alt="">
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
                                                    <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'/images/startup.png' ; ?>" alt="Picture">
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
                                                    <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'/public/images/motorcycles.png' ; ?>" class="img-fluid">
                                                </div>
                                                <div class="header-timeLine2 header-timeLine-border">
                                                    <h5>54 KM</h5>
                                                    <span style="font-size: 12px;">اليوم 12:15 صباحا</span>
                                                </div>
                                            </div>
                
                
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>




<?php get_footer();?>