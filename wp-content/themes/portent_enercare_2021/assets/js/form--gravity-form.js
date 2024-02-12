"use strict";

(function ($, document) {
  function enercareGravityFormsPostRender(form_id, current_page) {
    var firstInput = document.querySelector('#gform_' + form_id + ' input:first-of-type');

    // use the gravity form id to create a local storage variable. ie. gform_4_engaged
    var gformEngagedId = "gform_" + form_id + "_engaged";
    if (sessionStorage.getItem(gformEngagedId)) {
      //console.log('removing input listener');
      //firstInput.removeEventListener('input');
    } else {
      console.log("creating input listener for firstInput", firstInput);
      firstInput.addEventListener('input', function () {
        //console.log("input listener fired");
        if (!sessionStorage.getItem(gformEngagedId)) {
          //console.log('setting gform_engaged local variable to true.');
          sessionStorage.setItem(gformEngagedId, true);
          if (dataLayer) {
            console.log('pushing form start event.');
            dataLayer.push({
              'state': 'Form Start',
              'event': 'gaTriggerEvent'
            });
          }
        }
      });
    }

    /** Get the gravity form and handle hidden form field inputs. We execute this in 'gform_post_render' so paginated forms work as well */
    var gravityForm = $('.gform_wrapper form');
    if (gravityForm.length) {
      window.Enercare.handleHiddenFormFields(gravityForm[0]);
    }
  }
  $(document).on('gform_post_render', function (event, form_id, current_page) {
    enercareGravityFormsPostRender(form_id, current_page);
  });
  //enercareGravityFormsPostRender();

  function enercareGravityFormsConfirmationLoaded() {
    var gravityFormMessage = $('.gform_confirmation_message');
    var gravityFormMessageId = gravityFormMessage.attr('id');
    // use the gravity form id to create a local storage variable. ie. gform_4_engaged
    var gformEngagedId = gravityFormMessageId.replace("confirmation_message_", "") + "_engaged";
    // remove local storage variable
    sessionStorage.removeItem(gformEngagedId);

    // do neat GA shit
    if (dataLayer) {
      console.log('pushing form completion event.');
      dataLayer.push({
        'state': 'Form Completion',
        'event': 'gaTriggerEvent'
      });
    }
  }
  $(document).on('gform_confirmation_loaded', function () {
    enercareGravityFormsConfirmationLoaded();
  });
  gform.addFilter('gform_datepicker_options_pre_init', function (optionsObj, formId, fieldId) {
    // don't allow past dates or weekends for any Builder forms
    if (formId == 19) {
      optionsObj.minDate = 1;
      optionsObj.firstDay = 1;
      optionsObj.beforeShowDay = jQuery.datepicker.noWeekends;
    }
    return optionsObj;
  });
})(jQuery, document);