"use strict";

window.addEventListener('load', function () {
  var tcToggle = document.querySelectorAll('.block-faqs--faq-container h4');
  Array.prototype.forEach.call(tcToggle, function (tc) {
    var button = tc.querySelector('button');
    var panel = tc.nextElementSibling;
    button.onclick = function () {
      console.log('clicking');
      if (button.getAttribute("aria-expanded") === "true") {
        button.setAttribute("aria-expanded", "false");
        panel.setAttribute("data-state", "closed");
      } else {
        button.setAttribute("aria-expanded", "true");
        panel.setAttribute("data-state", "open");
      }
    };
  });
});