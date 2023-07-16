<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}





// main dashboard shortcode

function bikers_trips_dashboard_shortcode($atts) {


    $notLoggedIn = '
    
                   <div class="row text-center">
                        <div class="container">
                            <h3> You Should Login/Register to Access Trips </h3>
                            <a class="btn btn-default" href="'. get_site_url() .'"> Home </a>
                        </div>
                    </div>
                
                ';


    $user_full_name = get_the_author_meta('user_full_name', get_current_user_id() ) ;
    $user_id_num = get_the_author_meta('user_id_num', get_current_user_id() ) ;
    $user_license_id = get_the_author_meta('user_license_id', get_current_user_id() ) ;
    $user_license_exp = get_the_author_meta('user_license_exp', get_current_user_id() ) ;


    // get all trips for current user
    $args = array(
        'author' => get_current_user_id(),
        'post_type' => 'bikers-trips',
        'post_status' => array( 'pending', 'publish' ),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $bikers_trips_list = new WP_Query( $args );

    if( $bikers_trips_list->have_posts() ) {
        $trips = $bikers_trips_list->posts;
        $trips_section = '';
        foreach ($trips as $trip){

            $trip_title = get_the_title( $trip->ID );
            $trip_distance = get_post_meta($trip->ID, 'trip_distance')[0];
            $trip_date = get_post_meta($trip->ID, 'trip_date')[0];
            $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');


            $posted_date = get_the_date('U', $trip->ID);
            $posted_since = human_time_diff($posted_date,current_time( 'U' ));

            $trip_id = $trip->ID;

            $trip_members_count = get_post_meta($trip->ID, 'trip_members_count')[0];

            // get members added in wp_bikers_members
            global $wpdb;
            $current_user = wp_get_current_user();
            $studentTable = $wpdb->prefix.'bikers_members';


            $result = $wpdb->get_results ( "SELECT * FROM $studentTable WHERE `trip_id` = $trip_id  ");
            $registered_members_count = count($result);


            $trip_status_meta = get_post_meta($trip_id, 'trip_status');
            if( !empty($trip_status_meta)){
                $trip_status = $trip_status_meta[0];
            }


            if( $trip_status === 'finished' ){
                $isFinished = 'hidden';
                $showFinished = '';
            } else {
                $isFinished = '';
                $showFinished = 'hidden';
            }




            $trips_section .= '
                                <div class="col-md-12 mb-3 trip-list trip_status_'. $trip_status .'">
                                                <div class="background-test">
                                                <span class="trip-status">
                                                    <strong> live </strong>
                                                    <div class="trip-status-dot"></div>
                                                </span>
                                                    <div class="tour-wapper">
                                                        <div>
                                                            <span class="distance">
                                                                 '. $trip_distance .' KM
                                                             </span>
                                                            <h6 class="mt-2">'. $trip_date .'</h6>
                                                        </div>
                                                        <div>
            
                                                            <h6>'. $trip_title .'</h6>
                                                            <div class="time-tour">
                                                                <h6 class="ml-1">منذ</h6>
                                                                <span> '. $posted_since .' </span>
                                                            </div>
                                                            
                                                            <div class="text-center tour-current" '. $showFinished .'>
                                                                <a href="'. home_url() .'/trip?trip_id='. $trip_id .'" class="btn btn-fill-out open_trip" data-trip_id="'. $trip_id .'">
                                                                   منتهية
                                                                </a>
                                                            </div>
                                                            
                                                            
                                                            <div class="text-center tour-current" '. $isFinished .'>
                                                                <a href="'. home_url() .'/trip?trip_id='. $trip_id .'" class="btn btn-fill-out open_trip" data-trip_id="'. $trip_id .'">
                                                                    تابع الرحلة مباشرة
                                                                </a>
                                                                
                                                                <a href="" class="add_member btn btn-fill-out" data-toggle="modal" data-target="#add_member" data-trip_id="'. $trip_id .'" data-trip_title="'. $trip_title .'" data-trip_members_count="'. $trip_members_count .'" data-registered_members_count="'. $registered_members_count .'" >
                                                                   اضافة أعضاء
                                                                </a>
            
                                                            </div>
            
                                                        </div>
            
                                                    </div>
                                                </div>
                                            </div>
                                            
                              ';
        }

        wp_reset_postdata();
    } else {
        $trips_section = '<div class="alert alert-danger" role="alert">
                              لاتوجد رحلات حالية
                            </div>';
    }


    // get all live trips ( meta: trip_status: live )
    $live_args = array(
        //'author__not_in' => array( get_current_user_id() ),
        'post_type' => 'bikers-trips',
        'post_status' => array( 'pending', 'publish' ),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'trip_status',
                'value' => 'live'
            )
        ),
    );
    $bikers_trips_list_live = new WP_Query( $live_args );

    if( $bikers_trips_list_live->have_posts() ) {
        $trips = $bikers_trips_list_live->posts;
        $current_trips_section = '';
        foreach ($trips as $trip){

            $trip_title = get_the_title( $trip->ID );
            $trip_distance = get_post_meta($trip->ID, 'trip_distance')[0];
            $trip_date = get_post_meta($trip->ID, 'trip_date')[0];
            $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');


            $posted_date = get_the_date('U', $trip->ID);
            $posted_since = human_time_diff($posted_date,current_time( 'U' ));



            $current_trips_section .= '
                                <div class="col-md-12 mb-3 trip-list trip_status_live">
  
                                                <div class="background-test">
                                                
                                                <span class="trip-status">
                                                    <strong> live </strong>
                                                    <div class="trip-status-dot"></div>
                                                </span>
                                                
                                                    <div class="tour-wapper">
                                                        <div>
                                                            <span class="distance">
                                                                 '. $trip_distance .' KM
                                                             </span>
                                                            <h6 class="mt-2">'. $trip_date .'</h6>
                                                        </div>
                                                        <div>
            
                                                            <h6>'. $trip_title .'</h6>
                                                            <div class="time-tour">
                                                                <h6 class="ml-1">منذ</h6>
                                                                 <span> '. $posted_since .' </span>
                                                            </div>
                                                            <div class="text-center tour-current">
                                                                <a href="'. home_url() .'/trip?trip_id='. $trip->ID .'" class=" btn btn-fill-out">
                                                                    تابع الرحلة مباشرة
                                                                </a>
            
                                                            </div>
            
                                                        </div>
            
                                                    </div>
                                                </div>
                                            </div>
                                            
                              ';
        }

        wp_reset_postdata();
    } else {
        $current_trips_section = '<div class="alert alert-danger" role="alert">
                              لاتوجد رحلات حالية
                            </div>';
    }




    // get all finished trips ( meta: trip_status: finished )
    $finished_args = array(
        //'author__not_in' => array( get_current_user_id() ),
        'post_type' => 'bikers-trips',
        'post_status' => array( 'publish' ),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'trip_status',
                'value' => 'finished'
            )
        ),
    );
    $bikers_trips_list_finished = new WP_Query( $finished_args );

    if( $bikers_trips_list_finished->have_posts() ) {
        $trips = $bikers_trips_list_finished->posts;
        $finished_trips_sections = '';
        foreach ($trips as $trip){

            $trip_title = get_the_title( $trip->ID );
            $trip_distance = get_post_meta($trip->ID, 'trip_distance')[0];
            $trip_date = get_post_meta($trip->ID, 'trip_date')[0];
            $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');


            $posted_date = get_the_date('U', $trip->ID);
            $posted_since = human_time_diff($posted_date,current_time( 'U' ));

            $trip_status_meta = get_post_meta($trip_id, 'trip_status');
            if( !empty($trip_status_meta)){
                $trip_status = $trip_status_meta[0];
            }


            $finished_trips_sections .= '
                                <div class="col-md-12 mb-3 trip-list trip_status_'. $trip_status .'">
  
                                                <div class="background-test">
                                                
                                                <span class="trip-status">
                                                    <strong> live </strong>
                                                    <div class="trip-status-dot"></div>
                                                </span>
                                                
                                                    <div class="tour-wapper">
                                                        <div>
                                                            <span class="distance">
                                                                 '. $trip_distance .' KM
                                                             </span>
                                                            <h6 class="mt-2">'. $trip_date .'</h6>
                                                        </div>
                                                        <div>
            
                                                            <h6>'. $trip_title .'</h6>
                                                            <div class="time-tour">
                                                                <h6 class="ml-1">منذ</h6>
                                                                 <span> '. $posted_since .' </span>
                                                            </div>
                                                            <div class="text-center tour-current">
                                                                <a href="'. home_url() .'/trip?trip_id='. $trip->ID .'" class=" btn btn-fill-out">
                                                                    منتهية
                                                                </a>
            
                                                            </div>
            
                                                        </div>
            
                                                    </div>
                                                </div>
                                            </div>
                                            
                              ';
        }

        wp_reset_postdata();
    } else {
        $finished_trips_sections = '<div class="alert alert-danger" role="alert">
                              لاتوجد رحلات حالية
                            </div>';
    }





    $Content = '
                <div class="naccs container mt-5 mb-5" dir="rtl">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="menu">
                                <div class="active"><span class="light"></span><span>رحلاتى</span></div>
                                <div><span class="light"></span><span>الحالية</span></div>
                                <div><span class="light"></span><span>القادمة</span></div>
                                <div><span class="light"></span><span>المنتهية</span></div>
                                <div><span class="light"></span><span>انشاء رحلة جديدة</span></div>
            
                            </div>
                        </div>
                       
                        <ul class="nacc gc col-md-9 trips_section">
                            <li class="active col-md-12">
                                <div class="row">
            
                                    <div class="col-md-7">
                                        <div class="row">
                                        
                                            '. $trips_section .'
                                            
                                        </div>
            
                                    </div>
                            </li>
                            
                            <li class="col-md-12">
                               
                                <div class="row">
            
                                    <div class="col-md-7">
                                        <div class="row">
                                            
                                            '.  $current_trips_section.'
                                            
                                            
                                        </div>
            
                                    </div>
            
            
                            </li>
            
            
                            <li class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
            
                                        <div class="calendar-wrapper"></div>
                                        
            
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row" id="trip_data_by_date">
                                           
                                        </div>
            
                                    </div>
            
            
                            </li>
            
                            <li class="col-md-12">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="row">
                                            '. $finished_trips_sections .'
                                        </div>
            
                                    </div>
            
            
                            </li>
                            <li class="row">
                                <div class="col-md-6 info-wappeer">
                                    <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px;     height: 330px;">
                                        <h4>المعلومات الشخصية</h4>
                                        
            
                                        <form class="is-readonly">
                                            <div class="form-group info d-flex">
                                                <i class="fas fa-user"></i>
                                                <label for="user_full_name">الاسم</label>
                                                <input type="text" class="form-control is-disabled" id="user_full_name" name="user_full_name"
                                                    placeholder="الاسم" value="'. $user_full_name .'" disabled>
                                            </div>
                                            <div class="form-group info d-flex">
                                                <i class="fas fa-id-card"></i>
                                                <label for="user_id_num">رقم الهوية :</label>
                                                <input type="text" class="form-control is-disabled" id="user_id_num" name="user_id_num"
                                                    placeholder="رقم الهوية " value="'. $user_id_num .'" disabled>
                                            </div>
                                            <div class="form-group info d-flex">
                                                <i class="fas fa-id-card"></i>
                                                <label for="user_license_id">رقم الرخصة :</label>
                                                <input type="text" class="form-control is-disabled" id="user_license_id" name="user_license_id"
                                                    placeholder="رقم الرخصة" value="'. $user_license_id .'" disabled>
                                            </div>
                                            <div class="form-group info d-flex">
                                                <i class="fas fa-id-card"></i>
                                                <label for="datepicker1">تاريخ انتهاء الرخصة :</label>
                                                <input id="datepicker1" width="100%" class="form-control is-disabled user_license_exp" name="user_license_exp"
                                                    placeholder="تاريخ انتهاء الرخصة" value="'. $user_license_exp .'" disabled />
                                            </div>
            
                                            <div class="text-center edit-data mt-3">
                                                <button type="button" class="btn link btn-edit js-edit" data-text="تعديل">
                                                    <span>
                                                        تعديل
                                                    </span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-save js-save link" id="savedata"
                                                    data-text="حفظ">
                                                    <span>
                                                        حفظ
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
            
                                    </div>
                                </div>
                                
                                <form name="add_trip" action="" class="col-md-6" id="add_trip">
                                <div class="col-md-12 info-wappeer">
                                    <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px;     height: 330px;">
                                        <h4>معلومات الرحلة</h4>
                                        <div class="info d-flex mb-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <label>الوجهه :</label>
                                            <input type="text" class="form-control trip_dest" id="inputEmail4" placeholder="الوجهه" required>
                                        </div>
                                        <div class="info d-flex mb-3">
                                            <i class="fas fa-user-friends"></i>
                                            <label>عدد الاعضاء :</label>
                                            <input type="number" class="form-control trip_members_count" id="inputEmail4" placeholder="عدد الاعضاء" required>
                                        </div>
                                        <div class="info d-flex mb-3">
                                            <i class="far fa-calendar-alt"></i>
                                            <label>تاريخ الرحلة :</label>
                                            <input id="datepicker2" width="100%" class="form-control trip_date"
                                                    placeholder="تاريخ الرحلة " value="" required/>
                                        </div>
                                        <div class="info d-flex mb-3">
                                            <i class="far fa-clock"></i>
                                            <label>مدة الرحلة :</label>
                                            <input id="time1" type="text" class="form-control trip_duration" placeholder="مدة الرحلة"
                                                ng-model="to" required>
                                           
            
                                        </div>
                                        
                                        <div class="info d-flex mb-3">
                                            <i class="far fa-map"></i>
                                            <label>المسافة :</label>
                                            <input type="number" class="form-control trip_distance" id="inputEmail4" placeholder="مسافة الرحلة" required>
                                        </div>
            
                                    </div>
                                    
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                        <label class="form-check-label" for="exampleCheck1"
                                            style="margin-right: 18px; color: #2f2f2f;">اتعهد
                                            بأن جميع هذه البيانات صحيحة وانى اوافق على
                                            الشروط والاحكام</label>
                                    </div>
                                    <div class="d-flex justify-content-center data-submit">
                                        <button type="submit" id="add_trip_btn" class="btn link " data-text="تقديم الطلب">
                                            <span>
                                                تقديم الطلب
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                
                               </form> 
                                
                            </li>
                        </ul>
                        
                    </div>
                </div>
                

                                
                                
                        <!-- Modal -->
                        <div class="modal fade" id="add_member" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">اضافة أعضاء</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <!-- add members form -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col-md-8"> <h3 id="trip_title" class="text-center"></h3> </div>
                                    <div class="col-md-4">
                                        <span class="btn btn-primary trip_members_count_span">
                                         الأعضاء المتبقين للتسجيل في الرحلة:  <span class="badge badge-light trip_members_count">0</span>
                                        </span> 
                                    </div>
                                </div>
                               
                               
                                 
                                <div class="col-md-12 info-wappeer calender-active">
                                    <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px; ">
                                        <h4>تسجيل الاعضاء</h4>
                                        <form id="regForm">
                                        <input type="hidden" name="trip_id" class="trip_id" id="trip_id" value=""/>
                                        <input type="hidden" name="trip_remaining_members" class="trip_remaining_members" id="trip_remaining_members" value=""/>
                                        
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <div class="info">
                                                        <i class="fas fa-user"></i>
                                                        <label for="inputEmail4">الاسم</label>
                                                        <input type="text" class="form-control member_name" id="inputEmail4" placeholder="الاسم">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <div class="info">
                                                        <i class="fas fa-id-card"></i>
                                                        <label for="cardId">رقم الهوية</label>
                                                        <input type="text" class="form-control member_id_num" id="cardId" placeholder="رقم الهوية">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="info">
                                                    <i class="fas fa-id-card"></i>
                                                    <label for="inputAddress">رقم الرخصة</label>
                                                    <input type="text" class="form-control member_license_id" id="inputAddress" placeholder="رقم الرخصة">
                                                </div>
                                            </div>
                                            <div class="form-group text-right info">
                                                
                                                <i class="far fa-calendar-alt"></i>
                                                <label for="inputAddress2">تاريخ انتهاء الرخصة</label>
                                                <input id="datepicker" width="100%" class="form-control member_license_exp" placeholder="تاريخ انتهاء الرخصة" value="" />
                                            </div>
            
            
                                            <div class="d-flex justify-content-center data-add mt-3">
                                                <button type="submit" class="btn link" data-text="إضافة">
                                                    <span>
                                                        إضافة
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
            
            
            
                                    </div>
                
                            </div>
                                
                              </div>

                            </div>
                          </div>
                </div>      
                                
            ';




    if ( is_user_logged_in() && !is_null( $Content ) && !is_feed() )
        return $Content;
    return $notLoggedIn;

}

add_shortcode('bikers-trips-dashboard', 'bikers_trips_dashboard_shortcode');





