$(document).ready(function() {
    let form = $('.subscribe-form');
});

$('.subscribe-form').on('submit', function(e) {
    e.preventDefault();

    let errorLabel = $('#error-label');

    let result = validateForm($('#email-input').val(), $('#terms-checkbox:checked').val());

    if (result.status == false) {
        errorLabel.removeClass('hide');
        errorLabel.text(result.message);
    } else {
        errorLabel.addClass('hide');
        errorLabel.text('');
        
        $('.union_2').removeClass('hide');
        $('.subscribe-form').addClass('hide');
        $('.heading-box').text('Thanks for subscribing!');
        $('.paragraph-box').text('You have successfully subscribed to our email listing. Check your email for the discount code.');
    }
});

function validateForm(email, checkbox) {
    let response = {};
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    // 1. step: check if email is provided
    if (email.length == 0) {
        response.status = false;
        response.message = 'Email address is required';

        return response;
    }

    // 2. step: check if email is valid
    if (!regex.test(email)) {
        response.status = false;
        response.message = 'Please provide a valid e-mail address';

        return response;
    }

    // 3. step: check if checkbox is not marked
    if (typeof checkbox === 'undefined') {
        response.status = false;
        response.message = 'You must accept the terms and conditions';

        return response;
    }


    // 4. step: check if domain suffix is not .co
    let domain = email.substring(email.lastIndexOf('@') + 1);
    let domainSuffix = domain.substring(domain.lastIndexOf('.') + 1);

    if (domainSuffix == 'co') {
        response.status = false;
        response.message = 'We are not accepting subscriptions from Colombia emails';

        return response;
    }

    
    response.status = true;
    response.message = null;

    return response;
}