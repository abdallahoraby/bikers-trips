var times = {}; // Added to initialize an object

var timepicker = new TimePicker('time1', {
    theme: 'dark',
    lang: 'en'
});

timepicker.on('change', function (evt) {
    var value = (evt.hour || '00') + ':' + (evt.minute || '00');
    evt.element.value = value;

    //Added the below to store in the object and consoling:
    var id = evt.element.id;
    times[id] = value;
    console.clear();
    console.log(times); // Display the object
});

