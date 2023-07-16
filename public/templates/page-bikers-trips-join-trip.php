<?php get_header(); ?>

<?php


    $trip_id = $_GET['trip_id'];


    $trip_members_count = get_post_meta($trip_id, 'trip_members_count')[0];

    $trip_date = get_post_meta($trip_id, 'trip_date')[0];
    $trip_date = __(date("l j F Y", strtotime($trip_date)), 'bikers-trips');

    $trip_dest = get_post_meta($trip_id, 'trip_dest')[0];

    $trip_distance = get_post_meta($trip_id, 'trip_distance')[0];

    $trip_status_meta = get_post_meta($trip_id, 'trip_status');
    if( !empty($trip_status_meta)){
        $trip_status = $trip_status_meta[0];
    }

    $posted_date = get_the_date('U', $trip_id);
    $posted_since = human_time_diff($posted_date,current_time( 'U' ));


    // get members added in wp_bikers_members
    global $wpdb;
    $current_user = wp_get_current_user();
    $studentTable = $wpdb->prefix.'bikers_members';


    $result = $wpdb->get_results ( "SELECT * FROM $studentTable WHERE `trip_id` = $trip_id  ");
    $registered_members_count = count($result);

    $available_members = (int) $trip_members_count - $registered_members_count;
?>

<div class="container mt-5 mb-5">
    <div class="title-tour-detailes d-flex justify-content-center ">
        <h4>انضم للرحلة</h4>
    </div>
    <div class="row justify-content-center">

        <ul class="col-md-9">
            <div class="active col-md-12">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <div class="background-test">
                                    <div class="tour-wapper">
                                        <div>
                                            <span class="distance"><?php echo $trip_distance ;?> KM</span>
                                            <h6 class="mt-2"> <?php echo $trip_date; ?> </h6>
                                        </div>
                                        <div>

                                            <h6> <?php echo get_the_title($trip_id) ;?> </h6>
                                            <div class="time-tour">
                                                <h6 class="ml-1">منذ</h6>
                                                <span> <?php echo $posted_since; ?> </span>
                                            </div>
                                            <div class="text-center tour-current">
                                                <a href="<?php echo  home_url() . '/trip?trip_id='. $trip_id ;?>" class=" btn btn-fill-out">
                                                    تابع الرحلة مباشرة
                                                </a>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">


                    <div class="col-md-12 info-wappeer ">
                        <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px; ">
                            <div class="join_header d-flex">
                                <h4 class="col-md-6">المعلومات الشخصية</h4>
                                <span class="col-md-6 btn btn-primary trip_members_count_span">
                                         الأعضاء المتبقين للتسجيل في الرحلة:  <span class="badge badge-light trip_members_count"> <?php echo $available_members ;?> </span>
                            </span>
                            </div>

                            <br>

                            <form id="regForm">

                                <input type="hidden" name="trip_id" class="trip_id" id="trip_id" value="<?php echo $trip_id;?>"/>
                                <input type="hidden" name="trip_remaining_members" class="trip_remaining_members" id="trip_remaining_members" value="<?php echo $available_members ;?>"/>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <div class="info">
                                            <i class="fas fa-user"></i>
                                            <label for="inputEmail4">الاسم</label>
                                            <input type="text" class="form-control member_name" id="inputEmail4" placeholder="الاسم" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="info">
                                            <i class="fas fa-id-card"></i>
                                            <label for="cardId">رقم الهوية</label>
                                            <input type="text" class="form-control member_id_num" id="cardId" placeholder="رقم الهوية" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="info">
                                        <i class="fas fa-id-card"></i>
                                        <label for="inputAddress">رقم الرخصة</label>
                                        <input type="text" class="form-control member_license_id" id="inputAddress" placeholder="رقم الرخصة" required>
                                    </div>
                                </div>
                                <div class="form-group text-right info">
                                    <!-- <div class=""> -->
                                    <i class="far fa-calendar-alt"></i>
                                    <label for="inputAddress2">تاريخ انتهاء الرخصة</label>
                                    <input id="datepicker" width="100%" class="form-control member_license_exp" placeholder="تاريخ انتهاء الرخصة" value="" />

                                </div>


                                <!-- <div class="d-flex justify-content-center data-add mt-3">
                                    <button type="submit" class="btn link" data-text="إضافة">
                                        <span>
                                            إضافة
                                        </span>
                                    </button>
                                </div> -->


                                <div class="col-md-12">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                        <label class="form-check-label" for="exampleCheck1" style="margin-right: 18px; color: #2f2f2f;">اتعهد
                                            بأن جميع هذه البيانات صحيحة وانى اوافق على
                                            الشروط والاحكام</label>
                                    </div>
                                    <div class="d-flex justify-content-center data-submit">
                                        <button type="submit" class="btn link " data-text="تقديم الطلب">
                                    <span>
                                        تقديم الطلب
                                    </span>
                                        </button>
                                    </div>
                                </div>
                            </form>



                        </div>
                    </div>


                </div>
            </div></ul>
        <!-- </div> -->
    </div>
</div>


<?php get_footer(); ?>