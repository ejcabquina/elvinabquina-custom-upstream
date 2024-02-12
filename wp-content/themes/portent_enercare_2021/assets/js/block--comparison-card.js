"use strict";

window.addEventListener('load', function () {
  var ccToggle = document.querySelectorAll('.block-comparison-card__contents-toggle');
  ccToggle.forEach(function (cc) {
    cc.addEventListener("click", function () {
      var $id = cc.getAttribute("data-id");
      var cardContent = document.querySelector('.comparison_card_' + $id);
      var cardContentParent = cardContent.parentElement;
      var toggleOpenText = cc.getAttribute("data-toggle-open");
      var toggleCloseText = cc.getAttribute("data-toggle-close");
      if (cc.getAttribute("aria-expanded") == "true") {
        cc.setAttribute("aria-expanded", "false");
        cardContent.setAttribute("data-state", "closed");
        cardContentParent.setAttribute("data-child", "closed");
        cc.innerHTML = cc.innerHTML.replace("navigate-right", "navigate-down");
        cc.innerHTML = cc.innerHTML.replace(toggleCloseText, toggleOpenText);
      } else {
        cc.setAttribute("aria-expanded", "true");
        cardContent.setAttribute("data-state", "open");
        cardContentParent.setAttribute("data-child", "open");
        cc.innerHTML = cc.innerHTML.replace("navigate-down", "navigate-right");
        cc.innerHTML = cc.innerHTML.replace(toggleOpenText, toggleCloseText);
      }
    });
  });
});