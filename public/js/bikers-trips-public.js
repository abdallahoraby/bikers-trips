(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */




	// Acc
	$(document).on("click", ".naccs .menu div", function () {
		var numberIndex = $(this).index();

		if (!$(this).is("active")) {
			$(".naccs .menu div").removeClass("active");
			$(".naccs ul li").removeClass("active");

			$(this).addClass("active");
			$(".naccs ul").find("li:eq(" + numberIndex + ")").addClass("active");

			var listItemHeight = $(".naccs ul")
				.find("li:eq(" + numberIndex + ")")
				.innerHeight();
			$(".naccs ul").height(listItemHeight + "px");
		}
	});
// window.document.onload = myFunc();

// function closeForm() {
//     var x = document.getElementById("formDiv");
//     var y = document.getElementsByClassName("addBTN")[0];
//     x.style.display = "none";
//     y.style.transform = "rotate(90deg)";
// }

// function toggleForm() {
//     var x = document.getElementById("formDiv");
//     var y = document.getElementsByClassName("addBTN")[0];
//     if (x.style.display == "none") {
//         x.style.display = "block";
//         y.style.transform = "rotate(45deg)";
//     } else {
//         x.style.display = "none";
//         y.style.transform = "rotate(90deg)";
//     }
// }



// $(function () {

//     window.sr = ScrollReveal();

//     if ($(window).width() < 768) {

//         if ($('.timeline-content').hasClass('js--fadeInLeft')) {
//             $('.timeline-content').removeClass('js--fadeInLeft').addClass('js--fadeInRight');
//         }

//         sr.reveal('.js--fadeInRight', {
//             origin: 'right',
//             distance: '300px',
//             easing: 'ease-in-out',
//             duration: 800,
//         });

//     } else {

//         sr.reveal('.js--fadeInLeft', {
//             origin: 'left',
//             distance: '300px',
//             easing: 'ease-in-out',
//             duration: 800,
//         });

//         sr.reveal('.js--fadeInRight', {
//             origin: 'right',
//             distance: '300px',
//             easing: 'ease-in-out',
//             duration: 800,
//         });

//     }

//     sr.reveal('.js--fadeInLeft', {
//         origin: 'left',
//         distance: '300px',
//         easing: 'ease-in-out',
//         duration: 800,
//     });

//     sr.reveal('.js--fadeInRight', {
//         origin: 'right',
//         distance: '300px',
//         easing: 'ease-in-out',
//         duration: 800,
//     });


// });

	$(document).ready(function () {
		$('.js-edit, .js-save').on('click', function () {
			var $form = $(this).closest('form');
			$form.toggleClass('is-readonly is-editing');
			var isReadonly = $form.hasClass('is-readonly');
			$form.find('input,textarea').prop('disabled', isReadonly);


		});
	});
	var hasBeenClicked = false;

	jQuery('.js-edit').click(function () {
		hasBeenClicked = true;
		if (hasBeenClicked) {
			$('.is-editing .gj-datepicker-bootstrap [role=right-icon] button').css({
				display: "block",
			});
		}
	});



	jQuery('.js-save').click(function () {
		if (hasBeenClicked) {
			$('.is-readonly .gj-datepicker-bootstrap [role=right-icon] button').css({
				display: "none",
			});
		}
	});




// var timepicker = new TimePicker('time', {
//     lang: 'en',
//     theme: 'dark'
// });
// timepicker.on('change', function (evt) {

//     var value = (evt.hour || '00') + ':' + (evt.minute || '00');
//     evt.element.value = value;

// });





// hello heart


	$(".heart.fa").click(function () {
		$(this).toggleClass("heart heart-des");
	});




	function changeIcons(cla1, cla2) {
		var Clicked = false;
		jQuery(cla1).click(function () {
			Clicked = true;
			if (Clicked) {
				$(cla1).addClass('herats');
				$(cla2).css({
					color: "red",
					display: "inline-block",
				})
			}

		});


		jQuery(cla2).click(function () {

			if (Clicked) {
				$(cla1).removeClass('herats');
				// The link has not been clicked.
				$(cla2).css({
					display: "none",
				})
			}
		});

	}
	changeIcons('.far.fa-heart', '.fas.fa-heart');


// function changeIconscomm(cla1, cla2) {
	var Clicked = false;
	jQuery('.far.fa-comment-alt').click(function () {
		Clicked = true;
		if (Clicked) {
			$('.far.fa-comment-alt').addClass('comment');
			$('.fas.fa-comment-alt').css({
				color: "#0084e1",
				display: "inline-block",
			});
			$('#comments-container').show();
			// $('#comments-container').css({
			//     // color: "red",
			//     display: "block",
			// });
		}
		// else {
		// }


	});
	var Clickes = false;
	jQuery('.fas.fa-comment-alt').click(function () {
		var Clickes = true;
		if (Clickes) {

			$('#comments-container').hide();
			$('.far.fa-comment-alt').removeClass('comment');

			$('.fas.fa-comment-alt').hide();

		}


	});


	(function () {

		[].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
			new CBPFWTabs(el);
		});

	})();

	$('#datepicker').datepicker({
		uiLibrary: 'bootstrap4',
		rtl: true
	});

	$('#datepicker1').datepicker({
		uiLibrary: 'bootstrap4',
		rtl: true
	});

	$('#datepicker2').datepicker({
		uiLibrary: 'bootstrap4',
		rtl: true
	});

	var config =
		`function selectDate(date) {
		  $('.calendar-wrapper').updateCalendarOptions({
			date: date
		  });
		}
		
		var defaultConfig = {
		  weekDayLength: 1,
		  date: new Date(),
		  /*onClickDate: selectDate,*/
		  showYearDropdown: true,
		  startOnMonday: true,
		  onClickDate: function(date){
		  	
			let day = new Date(date).getDate();
			let month = new Date(date).getMonth() + 1;
			let year = new Date(date).getFullYear();
			
			//let trip_date_filter =  month + '/' + day + '/' + year;
			let trip_date_filter = ('0' + ( month )).slice(-2) + '/' + ('0' + day ).slice(-2) + '/' + year;
			
			filterTripsByDate(trip_date_filter);
		  		
		  },
		};
		
		$('.calendar-wrapper').calendar(defaultConfig);`;
	eval(config);

	// Ajax biker user meta update action

	$('button#savedata').on('click', function (e){
		e.preventDefault();

		let user_full_name = document.getElementById('user_full_name').value;
		let user_id_num = document.getElementById('user_id_num').value;
		let user_license_id = document.getElementById('user_license_id').value;
		let user_license_exp = $('.user_license_exp').val();

		$.post(my_ajax_object.ajaxurl, {
			action: 'update_biker_custom_meta',
			user_full_name: user_full_name,
			user_id_num: user_id_num,
			user_license_id: user_license_id,
			user_license_exp: user_license_exp
		}, function (){ // response callback function

		});
	});


	// Add new trip form
	$('form#add_trip').on('submit', function (e){
		e.preventDefault();

		let trip_dest = $('.trip_dest').val();
		let trip_members_count = $('.trip_members_count').val();
		let trip_date = $('.trip_date').val();
		let trip_duration = $('.trip_duration').val();
		let trip_distance = $('.trip_distance').val();

		$.post(my_ajax_object.ajaxurl, {
			action: 'add_new_trip',
			trip_dest: trip_dest,
			trip_members_count: trip_members_count,
			trip_date: trip_date,
			trip_duration: trip_duration,
			trip_distance: trip_distance,
		}, function (response){ // response callback function

		})
			.done(function() {
				//alert( "second success" );
				location.reload();
			});
	});


	$(document).on("click", ".add_member", function () {
		let trip_id = $(this).data('trip_id');
		let trip_title = $(this).data('trip_title');
		let trip_members_count = $(this).data('trip_members_count');
		let registered_members_count = $(this).data('registered_members_count');
		let remaining_members = parseInt( trip_members_count - registered_members_count );

		if( remaining_members === 0  ){
			$('.trip_members_count_span').removeClass('btn-primary').addClass('btn-danger');
			$('form#regForm input').attr('disabled', true);
			$('form#regForm button').attr('disabled', true);
		} else {
			$('.trip_members_count_span').addClass('btn-primary').removeClass('btn-danger');
			$('form#regForm input').attr('disabled', false);
			$('form#regForm button').attr('disabled', false);
		}

		$(".modal-body #trip_id").val( trip_id );
		$(".modal-body #trip_title").text( trip_title );
		$(".modal-body .trip_members_count").text( remaining_members );
		$('.trip_remaining_members').val( remaining_members );

		// As pointed out in comments,
		// it is unnecessary to have to manually call the modal.
		// $('#addBookDialog').modal('show');
	});

	// Add new trip form
	$('form#regForm').on('submit', function (e){
		e.preventDefault();

		let member_name = $('.member_name').val();
		let member_id_num = $('.member_id_num').val();
		let member_license_id = $('.member_license_id').val();
		let member_license_exp = $('.member_license_exp').val();
		let trip_remaining_members = $('.trip_remaining_members').val();
		let trip_id = $('.trip_id').val();

		$.post(my_ajax_object.ajaxurl, {
			action: 'add_new_member',
			member_name: member_name,
			member_id_num: member_id_num,
			member_license_id: member_license_id,
			member_license_exp: member_license_exp,
			trip_remaining_members: trip_remaining_members,
			trip_id: trip_id,

		}, function (response){ // response callback function

		})
			.done(function() {
				//alert( "second success" );
				location.reload();
		});

	});

	// filter trips based on trip_date
	function filterTripsByDate(trip_date_filter){
		

		// send ajax to filter data
		$.post(my_ajax_object.ajaxurl, {
			action: 'filter_trips_by_date',
			trip_date_filter : trip_date_filter

		}, function (response){ // response callback function
			// update div with new data

			$('#trip_data_by_date').html(response);
		})
			.done(function() {
				//alert( "second success" );

		});

	}


	/*********
	 * Open Trip Info
	********/

	$(document).on("click", ".open_trip", function () {
		let trip_id = $(this).data('trip_id');

		$.post(my_ajax_object.ajaxurl, {
			action: 'get_single_trip',
			trip_id : trip_id

		}, function (response){ // response callback function
			// update div with new data
			$('.entry-content').html(response);
		})
			.done(function() {
				//alert( "second success" );

			});

	});


	/*********
	 * start trip
	 ********/

	$(document).on("click", ".start_trip", function () {
		let trip_id = $(this).data('trip_id');

		$.post(my_ajax_object.ajaxurl, {
			action: 'start_trip',
			trip_id : trip_id,
			latitude: 29.9804626,
			longitude: 31.159572999999998,

		}, function (response){ // response callback function
			// update div with new data
			if( response === 'success' ){
				$('.start_trip').attr('disabled', true);
				$('.finish_trip').attr('disabled', false);
				window.location.reload();
			}

		})
			.done(function() {
				//alert( "second success" );

			});

	});


	/*********
	 * finish trip
	 ********/

	$(document).on("click", ".finish_trip", function () {

		let trip_id = $(this).data('trip_id');

		$.post(my_ajax_object.ajaxurl, {
			action: 'finish_trip',
			trip_id : trip_id,
			latitude: 29.9804626,
			longitude: 31.159572999999998,

		}, function (response){ // response callback function
			// update div with new data
			if( response === 'success' ){
				$('.start_trip').attr('disabled', true);
				$('.finish_trip').attr('disabled', true);
				window.location.reload();
			}

		})
			.done(function() {
				//alert( "second success" );

			});

	});


	$('.week .day').on('click', function(){

		$(this).addClass('selected');
		$('.week .day').not(this).removeClass('selected');

	});


	/*********
	 * track trip
	 ********/

	let trip_status_refresh = $('input.trip_status_refresh').val();

	if( trip_status_refresh === 'live' ){
		setInterval(function() {
			$("#click_me").trigger('click');
		}, 10000);
	}

	$(document).on("click", "#click_me", function () {

		let trip_id = $(this).data('trip_id');
		let current_longitude = document.getElementById('current_longitude').value;
		let current_latitude = document.getElementById('current_latitude').value;
		let google_map_key = document.getElementById('google_map_key').value;

		$.post(my_ajax_object.ajaxurl, {
			action: 'track_trip',
			trip_id : trip_id,
			latitude: current_latitude,
			longitude: current_longitude,

		}, function (response){ // response callback function
			// update div with new data
			if( response === 'success' ){
				// set iframe src with new data
				$('#current_map').attr('src', 'https://www.google.com/maps/embed/v1/place?key='+ google_map_key +'&q='+ current_latitude +','+ current_longitude +'&zoom=14&maptype=roadmap');
			}

		})
			.done(function() {
				//alert( "second success" );

			});

	});






})
(jQuery);



function showPosition() {
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			//var positionInfo = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
			document.getElementById("current_longitude").value = position.coords.longitude;
			document.getElementById("current_latitude").value = position.coords.latitude;
		});
	} else {
		alert("Sorry, your browser does not support HTML5 geolocation.");
	}
}

