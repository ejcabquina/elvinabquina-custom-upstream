"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
(function ($, document) {
  /**
   * Check for the gravity support area. We may have already created it
   * If we have not, set one up and place it within the body
   */
  if (!document.body.contains(document.getElementById('gravity-report-area'))) {
    var newGravityReportArea = document.createElement('div');
    newGravityReportArea.id = 'gravity-report-area';
    newGravityReportArea.setAttribute('aria-atomic', 'true');
    newGravityReportArea.setAttribute('aria-live', 'polite');
    newGravityReportArea.classList.add('screen-reader-text');
    document.body.appendChild(newGravityReportArea);
  }
  var gravityReportArea;
  gravityReportArea = document.querySelector('#gravity-report-area');
  $(document).on('gform_post_render', function (event, form_id, current_page) {
    var gravityForm = document.querySelector('#gform_' + form_id);
    var formReport = '';
    /**
     * Check for errors and report them
     */
    if (gravityForm.parentElement.classList.contains('gform_validation_error')) {
      formReport += 'Your submission did not go through. Please address the following issues and try your submission again';
      var errors = gravityForm.querySelectorAll('.gfield_validation_message');
      errors.forEach(function (error) {
        formReport += " ".concat(error.textContent);
      });
    }
    gravityReportArea.textContent = formReport;
  });

  /**
   * Check for submission updates and notify the user if the spinner is on screen
   * and in the process of submitting the form.
   */
  var observedGravityForm = document.querySelector('.gform_wrapper');
  var observerConfig = {
    subtree: true,
    childList: true
  };
  var gformObserverCallback = function gformObserverCallback(mutationList, observer) {
    var _iterator = _createForOfIteratorHelper(mutationList),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var mutation = _step.value;
        var addedNodes = mutation.addedNodes;
        var submissionUpdate = '';
        addedNodes.forEach(function (node) {
          if (node.classList && node.classList.contains('gform_ajax_spinner')) {
            gravityReportArea.textContent = 'Submitting your request. Please wait';
          }
        });
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  };
  var gformObserver = new MutationObserver(gformObserverCallback);
  gformObserver.observe(observedGravityForm, observerConfig);

  /**
   * Report on gravity forms success page.
   */
  $(document).on('gform_confirmation_loaded', function (event, form_id) {
    gravityReportArea.textContent = 'Thank you. We\'ll be in touch shortly. Please note that you may receive a call from Enercare or an \'unknown\' number when we try to contact you.';
  });
})(jQuery, document);