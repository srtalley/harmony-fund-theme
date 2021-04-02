//version: 1.6.5

jQuery(function($) {


    $(document).ready(function() {
        // Remove the loader after 1 second
        // setTimeout(function(){
        //     $('iframe[name="give-embed-form"]').css('visibility', 'visible');
        //     $('.iframe-loader ').hide();
        // }, 1000);

        // Add a back button for the monthly
        // Sets the labels appropriately on the tabs and in other places according
        // to the recurring period chosen. 
        $('form.give-form').each(function() {


            var current_form = $(this).parentsUntil('.give-form').parent();

            $('.give-recurring-donors-choice').on('click', function() {
                if($(this).hasClass('active')) {
                    $(current_form).find('.donation-amount-period .one-time').fadeOut(function() {
                        $(current_form).find('.donation-amount-period .recurring').fadeIn();
                    });
                } else {
                    $(current_form).find('.donation-amount-period .recurring').fadeOut(function() {
                        $(current_form).find('.donation-amount-period .one-time').fadeIn();
                    });
                }

            });
            // See if this is a recurring form
            if ($(this).hasClass('give-recurring-form')) {

                var recurring_period_label = $('input.give-recurring-period').first().attr('data-period-label');
                var recurring_period = $('input.give-recurring-period').first().attr('data-period');
                // add the recurring labels to the form itself
                $(this).attr('data-period-label', recurring_period_label);
                $(this).attr('data-period', recurring_period);

                // set the labels in the form
                $('.hf-recurring-period-label').text(recurring_period_label);
                $('.hf-recurring-period').text(recurring_period);

                // Add our additional back button
                $(this).parent().find('.give-form-navigator .back-btn').after('<button class="hf-back-btn" tabindex="9"><i class="fas fa-chevron-left"></i></button>');


                // Handles the click on the donation tabs - this can apply to any form 
                // on the page
                // $('button.donation-type').on('click', function(e) {
                //     e.preventDefault();
                //     var donation_type_selector = $(this).parent();
                //     $(donation_type_selector).find('.donation-type').removeClass('selected');
                //     $(this).addClass('selected');

                //     if ($(this).hasClass('recurring')) {
                //         $(this).find('.heart-checkbox').prop("checked", true);
                //         $(current_form).find('.give-recurring-donors-choice .give-recurring-period').prop("checked", true);
                //         $(current_form).find('.donation-amount-period .one-time').fadeOut(function() {
                //             $(current_form).find('.donation-amount-period .recurring').fadeIn();
                //         });
                //         $(current_form).find('.hf-payment-totals #give-recurring-modal-period-wrap').text(recurring_period_label);
                //         // Hide the payment wrapper 
                //         $(current_form).addClass('hide-advance-wrapper');

                //     } else {
                //         $(current_form).find('.heart-checkbox').prop("checked", false);
                //         $(current_form).find('.give-recurring-donors-choice .give-recurring-period').prop("checked", false);
                //         $(current_form).find('.donation-amount-period .recurring').fadeOut(function() {
                //             $('.donation-amount-period .one-time').fadeIn();
                //         });
                //         $(current_form).find('.hf-payment-totals #give-recurring-modal-period-wrap').text('One Time');
                        
                //         // Show the payment wrapper
                //         $(current_form).removeClass('hide-advance-wrapper');
                //     }
                // });
            } // recurring form

            // Show and hide the tribute boxes when the link is clicked
            $(current_form).find('.give-tributes-dedicate-donation legend').on('click', function() {
                $(current_form).find('input[name="give_tributes_first_name"]').attr('tabindex', '3');
                $(current_form).find('textarea[name="give_tributes_ecard_notify[recipient][personalized][]"]').attr('tabindex', '3');
                $(current_form).find('input[name="give_tributes_ecard_notify[recipient][first_name][]"]').attr('tabindex', '3');
                $(current_form).find('input[name="give_tributes_ecard_notify[recipient][email][]"]').attr('tabindex', '3');
                if ($(this).parent().hasClass('tribute-open')) {
                    var parent_item = $(this).parent();
                    $(parent_item).removeClass('tribute-open');
                    $(parent_item).find('.give-tributes-no').click();
                } else {
                    $(current_form).find('.give-tributes-dedicate-donation>div').slideDown();
                    $(this).parent().addClass('tribute-open')
                    $(this).parent().find('.give-tributes-yes').click();
                }
            });
            // Set the placeholder on the tribute form
            $(current_form).find('input[name="give_tributes_first_name"]').attr('placeholder', 'Name');
            $(current_form).find('input[name="give_tributes_ecard_notify[recipient][first_name][]"]').attr('placeholder', 'Recipient Name');

            // Add icon to the payment label
            hfAddGatewayIcons();
        }); // end each give form


        // Add a wrapper around the donation button so we can catch events
        // and show a message if the person hasn't chosen to donate enough
        // $(document).on('click', '.hf-advance-btn-wrapper', function(e) {

        //     if (e.target != this) {
        //         return;
        //     }
        //     var current_form = $(this).parentsUntil('.give-form').parent();

        //     if ($(current_form).hasClass('give-recurring-form')) {

        //         var min_donation_amount = $(this).attr('data-min_donation_amount');
        //         var recurring_period_checkbox = $(this).parentsUntil('.give-form').parent().find('.give-recurring-donors-choice .give-recurring-period');

        //         if (!$(recurring_period_checkbox).prop('checked')) {
        //             var current_give_amount = $(this).parentsUntil('.give-form').parent().find('#give-amount').val();
        //             // Get the amount in the field
        //             var currency_code = $(this).parentsUntil('.give-form').parent().attr('data-currency_code');
        //             if (currency_code == 'EUR') {
        //                 var amount_entered = Number(current_give_amount.replace(',', '.'));
        //             } else {
        //                 var amount_entered = Number(current_give_amount);
        //             }

        //             if (amount_entered >= 50) {
        //                 var new_amount_1 = Math.trunc(amount_entered * .3);
        //                 var new_amount_2 = Math.trunc(amount_entered * .25);
        //             } else if (amount_entered < 50) {
        //                 var new_amount_1 = Math.trunc(amount_entered * .4);
        //                 var new_amount_2 = Math.trunc(amount_entered * .3);
        //             }

        //             // see if the amounts are too low
        //             if (new_amount_2 < min_donation_amount) {
        //                 new_amount_2 = min_donation_amount;
        //                 new_amount_1 = min_donation_amount * 1.5;
        //             }
        //             showGiveSectionRecurring(this, current_give_amount, new_amount_1, new_amount_2);
        //         } else {
        //             // Continue to the payment form with no change
        //             clickPurchaseButton(e.target);
        //         }
        //     } else {
        //         // not a recurring form so just advance
        //         clickPurchaseButton(e.target);
        //     }
        // });

        // // Handle the click when someone wants to keep their original donation amount
        // $('.hf-donate-keep-onetime').on('click', function(e) {
        //     e.preventDefault();
        //     var parent_item = $(this).parentsUntil('.give-form').parent();
        //     hideGiveSectionRecurring(this);
        //     setTimeout(function() {
        //         // $(parent_item).find('#give-purchase-button').click();
        //         clickPurchaseButton(e.target);

        //     }, 1000)

        // });

        // // Handle the click when someone wants to do recurring donation instead
        // $('.hf-donate-modify').on('click', function(e) {
        //     var donate_modify_target = e.target;
        //     e.preventDefault();
        //     var current_section = $(donate_modify_target).parentsUntil('.give-form').parent();

        //     // get the new recurring amount
        //     var recurring_amount = $(this).find('.hf-donate-new-amount').attr('data-recurring-amount');

        //     // change the amount - we have to focus in and out of the 
        //     // box to get GiveWP to recognize the new amount
        //     $(current_section).find('#give-amount').focus();
        //     $(current_section).find('#give-amount').val(recurring_amount);
        //     $(current_section).find('#give-amount').focusout();

        //     $(current_section).find('.donation-type.recurring').click();

        //     hideGiveSectionRecurring(this);
        //     setTimeout(function() {
        //         // $(current_section).find('#give-purchase-button').click();
        //         clickPurchaseButton(current_section);
        //     }, 1000)
        // });

        // $('.hf-back-btn').on('click', function(e) {
        //     e.preventDefault();
        //     hideGiveSectionRecurring(this);
        // });

        // wrap the lower payment buttons in the same correct color
        $('#give-gateway-radio-list li label').on('click', function(event) {
            $('#donate-fieldset, .give-fee-recovery-donors-choice').removeClass('donate-fieldset-stripe donate-fieldset-paypal donate-fieldset-google-pay donate-fieldset-apple-pay');

            var selected_payment_type = $(event.target);
   
            if(selected_payment_type.is('[id=give-gateway-option-stripe]')) {
                $('#donate-fieldset, .give-fee-recovery-donors-choice').addClass('donate-fieldset-stripe');
            } else if(selected_payment_type.is('[id=give-gateway-option-paypal-commerce]')) {
                $('#donate-fieldset, .give-fee-recovery-donors-choice').addClass('donate-fieldset-paypal');
            } else if(selected_payment_type.is('[id=give-gateway-option-stripe_google_pay]')) {
                $('#donate-fieldset, .give-fee-recovery-donors-choice').addClass('donate-fieldset-google-pay');
            } else if(selected_payment_type.is('[id=give-gateway-option-stripe_apple_pay]')) {
                $('#donate-fieldset, .give-fee-recovery-donors-choice').addClass('donate-fieldset-apple-pay');
            }
        });

    }); // end document ready

    // When the payment gateway is selected and reloads, it clears 
    // the label if it says "one time." This listens for that event 
    // and adds back the one time label
    $(document).on('give_gateway_loaded', function(e, response, form_id) {
        // Set the label as needed
        if ($('#' + form_id).find('.give-recurring-donors-choice .give-recurring-period').prop("checked") == false) {
            $('#' + form_id).find('.hf-payment-totals #give-recurring-modal-period-wrap').text('One Time');
        }
    });

    function clickPurchaseButton(element) {
        var current_form = $(element).parentsUntil('.give-form').parent();
        
        $(current_form).addClass('hide-advance-wrapper');

        var gateway_wrapper = $(current_form).find('#give_purchase_form_wrap');
        // Handle Paypal
        if($(gateway_wrapper).hasClass('gateway-paypal-commerce')) {
            var give_purchase_button = '';
            $(current_form).find('.paypal-press-button-msg').first().addClass('show-msg');
            document.addEventListener('click',function(e) {
                $(current_form).find('.paypal-press-button-msg').removeClass('show-msg');
            }, {once: true});
        } else {
            // regular donate button
            var give_purchase_button = $(current_form).find('#give-purchase-button');
        }
        if(give_purchase_button.length) {
            give_purchase_button.click();
        } 

        // Donate button handling
        var give_stripe_button = $(current_form).find('div[id^="give-stripe-payment-request-button"]');
        if(give_stripe_button.length) {
            // Show the stripe message 
            $(current_form).find('.stripe-press-donate-msg').addClass('show-msg');
            document.addEventListener('click',function(e) {
                $(current_form).find('.stripe-press-donate-msg').removeClass('show-msg');
            }, {once: true});
        }
    }

    // Add zeros to a currency
    function addZeroes(num) {
        // Cast as number
        var num = Number(num);
        // If not a number, return 0
        if (isNaN(num)) {
            return 0;
        }
        // If there is no decimal, or the decimal is less than 2 digits, toFixed
        if (String(num).split(".").length < 2 || String(num).split(".")[1].length<=2 ){
            num = num.toFixed(2).replace(/[.,]00$/, "");
        }
        // Return the number
        return num;
    }
    
    function showGiveSectionRecurring(current_element, original_amount, new_amount_1, new_amount_2) {

        new_amount_1 = addZeroes(new_amount_1);
        new_amount_2 = addZeroes(new_amount_2);

        var current_form = $(current_element).parentsUntil('.give-form').parent();
        var back_btn = $(current_form).parent().find('.back-btn');
        $(back_btn).hide();
        var hf_back_btn = $(current_form).parent().find('.hf-back-btn');
        $(hf_back_btn).show();

        var give_recurring_section = $(current_form).find('.give-section.choose-recurring').last();
        $(current_form)[0].scrollIntoView(100);
        var give_recurring_section_height = $(give_recurring_section).outerHeight();

        $(current_form).parent().find('.form-footer').css('margin-top', give_recurring_section_height + 'px');

        // Keep the previous title 
        $(current_form).find('.give-section.payment').attr('data-heading', $(current_form).parent().find('.give-form-navigator .title').text());

        var recurring_period_label = $(current_form).find('input.give-recurring-period').attr('data-period-label');

        $(current_form).parent().find('.give-form-navigator .title').text('Become a ' + recurring_period_label + ' Supporter');
        $(current_form).find('.give-section.payment').removeClass('slide-in-right');
        $(current_form).find('.give-section.payment').addClass('slide-out-left');
        $(current_form).find('.give-section.choose-recurring').removeClass('slide-out-right');
        $(current_form).find('.give-section.choose-recurring').addClass('slide-in-right');
        $(current_form).find('.give-section.choose-recurring').css('display', 'flex');


        var choose_recurring_section = $(current_element).parentsUntil('.give-form').parent().find('.choose-recurring');

        var currency_symbol = $(current_element).parentsUntil('.give-form').parent().attr('data-currency_symbol');

        $(choose_recurring_section).find('.hf-donate-original-amount').text(currency_symbol + original_amount);
        $(choose_recurring_section).find('.hf-donate-new-amount.one').text(currency_symbol + new_amount_1).attr('data-recurring-amount', new_amount_1);
        $(choose_recurring_section).find('.hf-donate-new-amount.two').text(currency_symbol + new_amount_2).attr('data-recurring-amount', new_amount_2);

        // $(choose_recurring_section).data('recurring-amount', new_amount);

    }

    function hideGiveSectionRecurring(current_element) {
        var current_form = $(current_element).parentsUntil('.give-form').parent();
        var back_btn = $(current_form).parent().find('.back-btn');
        $(back_btn).show();

        var hf_back_btn = $(current_form).parent().find('.hf-back-btn');
        $(hf_back_btn).hide();

        var give_payment_section = $(current_form).find('.give-section.payment');

        var give_payment_section_height = $(give_payment_section).outerHeight();

        $(current_form).parent().find('.form-footer').css('margin-top', give_payment_section_height + 'px');

        var form_heading = $(give_payment_section).attr('data-heading');
        $(current_form).parent().find('.give-form-navigator .title').html(form_heading);

        $(current_form).find('.give-section.choose-recurring').removeClass('slide-in-right');
        $(current_form).find('.give-section.choose-recurring').addClass('slide-out-right');
        $(current_form).find('.give-section.payment').removeClass('slide-out-left');
        $(current_form).find('.give-section.payment').addClass('slide-in-left');

    }

    // add icons to the gateway

    function hfAddGatewayIcons() {
        $('#give-gateway-option-stripe').append( `<i class="far fa-credit-card"></i>` );
    }



});