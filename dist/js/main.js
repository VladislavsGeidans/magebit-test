$('.subscribe-form').on('submit', function(e) {
    e.preventDefault();

    let errorLabel = $('#error-label');
    let emailValue = $('#email-input').val();
    let checkboxValue = $('#terms-checkbox:checked').val();

    let result = validateForm(emailValue, checkboxValue);

    result.status = true;

    if (result.status === false) {
        errorLabel.removeClass('hide');
        errorLabel.text(result.message);
    } else {
        errorLabel.addClass('hide');
        errorLabel.text('');

        $.ajax({
            type: "POST",
            url: "/app/action.php?a=subscribe",
            data: {
                email: emailValue,
                checkbox: checkboxValue
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.status === true) {
                    $('.union_2').removeClass('hide');
                    $('.subscribe-form').addClass('hide');
                    $('.heading-box').text('Thanks for subscribing!');
                    $('.paragraph-box').text('You have successfully subscribed to our email listing. Check your email for the discount code.');
                } else {
                    errorLabel.removeClass('hide');
                    errorLabel.text(response.message);
                }

                console.log(response.message);
            }
        });
    }
});

$('#search-input').on('change', function() {
    let searchString = $(this).val();

    $.ajax({
        type: "POST",
        url: "/app/action.php?a=search",
        data: {
            searchString: searchString
        },
        success: function(response) {
            renderEmails(response);
        }
    });
});

$('.sort-button').on('click', function(e) {
   e.preventDefault();
   let sortButton = $(this);

   let sortBy = sortButton.data('type');
   let sortType = sortButton.data('sort') ? sortButton.data('sort') : 'asc';
   let nextSortType = (sortType === "desc" || sortType === "" || sortType == null) ? 'asc' : 'desc';

   $.ajax({
       type: "POST",
       url: '/app/action.php?a=sortBy',
       data: {
           sortBy: sortBy,
           sortType: sortType
       },
       success: function(response) {
           renderEmails(response);

           sortButton.data('sort', nextSortType);
       }
   });
});

$(document).on('click', '.email-remove', function(e) {
    e.preventDefault();

    let trSelector = $(this);

    if (confirm('Are you sure you want to delete?')) {
        let subscriptionId = trSelector.data('id');

        $.ajax({
            type: 'POST',
            url: '/app/action.php?a=delete',
            data: {
                subscriptionId: subscriptionId
            },
            success: function(response) {
                renderEmails(response);
            }
        });
    }
});

$('.emailsProviders').on('change', function(e) {
   e.preventDefault();

   let selectedEmailProvider = $(this).val();

   $.ajax({
       type: "POST",
       url: "/app/action.php?a=selectEmailProvider",
       data: {
           emailProvider: selectedEmailProvider
       },
       success: function(response) {
           renderEmails(response);
       }
   });
});

function validateForm(email, checkbox) {
    let response = {};
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    // 1. step: check if email is provided
    if (email.length === 0) {
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

    // 3. step: check if checkbox is marked
    if (typeof checkbox === 'undefined') {
        response.status = false;
        response.message = 'You must accept the terms and conditions';

        return response;
    }


    // 4. step: check if domain suffix is not .co
    let domain = email.substring(email.lastIndexOf('@') + 1);
    let domainSuffix = domain.substring(domain.lastIndexOf('.') + 1);

    if (domainSuffix === 'co') {
        response.status = false;
        response.message = 'We are not accepting subscriptions from Colombia emails';

        return response;
    }

    
    response.status = true;
    response.message = null;

    return response;
}

function renderEmails(response) {
    let emails = JSON.parse(response);
    let responseTemplate = '';

    if (emails.length > 0) {
        $.each(emails, function() {
            responseTemplate += '<tr>' +
                '<td><a href="#" class="email-remove" data-id="' + this.id + '">Remove</a></td>' +
                '<td>' + this.email + '</td>' +
                '<td>' + this.created_at + '</td>' +
                '</tr>';
        });
    } else {
        responseTemplate += '<tr>' +
            '<td></td>' +
            '<td>Nothing found</td>' +
            '<td></td>' +
            '</tr>';
    }

    $('.table-body').html(responseTemplate);
    renderEmailProviders();
}

function renderEmailProviders() {
    $.ajax({
       type: "POST",
       url: "/app/action.php?a=getEmailsProviders",
       success: function(response) {
           let providers = JSON.parse(response);
           let responseTemplate = '';

           responseTemplate += '<option value="">---</option>';
           if (providers.length > 0) {
               $.each(providers, function() {
                   responseTemplate += '<option value="' + this.provider + '" ' + this.status + '>' + this.provider + '</option>';
               });
           }

           $('.emailsProviders').html(responseTemplate);
       }
    });
}