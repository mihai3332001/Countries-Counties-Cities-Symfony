$(document).ready(function() {

var inputProfilePhone = document.getElementById("profile_phone");
	if(inputProfilePhone != null) {
	      intlTelInput(inputProfilePhone, {
	        separateDialCode: true,
	        preferredCountries: ["ro"],
	        initialCountry:"ro",
	        customContainer: "form-control",
	        nationalMode: true,
	        utilsScript: 'utils.js'
	    });
	}

	var inputUserPhone = document.getElementById("user_phone");
	if(inputUserPhone != null) {
	      intlTelInput(inputUserPhone, {
	        separateDialCode: true,
	        preferredCountries: ["ro"],
	        initialCountry:"ro",
	        customContainer: "form-control",
	        nationalMode: true,
	        utilsScript: 'utils.js'
	    });
	}

	var $country = $('#event_location_location_country');
    var $token = $('#event_location__token');
  
  // When country gets selected ...
    $country.change(function () {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected country value.
        var data = {};
        data[$country.attr('name')] = $country.val();
        data[$token.attr('name')] = $token.val();
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function (html) {
                // Replace current state field ...
                $('#event_location_location_county').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#event_location_location_county')
                );
            },
        });
        event.preventDefault();
    });

    //var $county = $('#event_location_location_county');

    jQuery(document).on('change','#event_location_location_county',function () {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected country value.
        var data2 = {};
        data2[$(this).attr('name')] = $(this).val();
        //data2[$token.attr('name')] = $token.val();
        //console.log(data2);
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data2,
            complete: function (html) {
            //console.log(html.responseText);
                // Replace current state field ...
                $('#event_location_location_city').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html.responseText).find('#event_location_location_city')
                );
            },
        });
    });

    var $country_edit = $('#event_location_edit_location_country');
    var $token_edit = $('#event_location_edit__token');
    // When country gets selected ...
    $country_edit.change(function () {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected country value.
        var data = {};
        data[$country_edit.attr('name')] = $country_edit.val();
        data[$token_edit.attr('name')] = $token_edit.val();
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            complete: function (html) {
                //console.log(html.responseText);
                // Replace current state field ...
                $('#event_location_edit_location_county').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html.responseText).find('#event_location_edit_location_county')
                );
            },
        });
    });

    jQuery(document).on('change','#event_location_edit_location_county',function () {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected country value.
        var data2 = {};
        data2[$(this).attr('name')] = $(this).val();
        //data2[$token.attr('name')] = $token.val();
        //console.log(data2);
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data2,
            complete: function (html) {
            //console.log(html.responseText);
                // Replace current state field ...
                $('#event_location_edit_location_city').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html.responseText).find('#event_location_edit_location_city')
                );
            },
        });
    });

});

import intlTelInput from 'intl-tel-input';
