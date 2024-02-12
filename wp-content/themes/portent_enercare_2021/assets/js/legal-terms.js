"use strict";

window.addEventListener('load', function () {
  var tcToggle = document.querySelectorAll('.legal__terms');
  tcToggle.forEach(function (tc) {
    tc.addEventListener("click", function () {
      var termsContent = tc.querySelector('.legal__terms-details');
      var termsButton = tc.querySelector('.legal__terms-toggle');
      if (termsButton.getAttribute("aria-expanded") == "true") {
        termsButton.setAttribute("aria-expanded", "false");
        termsContent.setAttribute("data-state", "closed");
        termsButton.innerHTML = termsButton.innerHTML.replace("navigate-up", "navigate-down");
      } else {
        termsButton.setAttribute("aria-expanded", "true");
        termsContent.setAttribute("data-state", "open");
        termsButton.innerHTML = termsButton.innerHTML.replace("navigate-down", "navigate-up");
      }
    });
  });
});