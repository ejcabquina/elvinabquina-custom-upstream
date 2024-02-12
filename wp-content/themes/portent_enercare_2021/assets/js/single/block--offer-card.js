window.addEventListener('load', function(){
  /*let tcToggle = document.querySelectorAll('.block-offer-card__terms');
  tcToggle.forEach(function(tc) {
    tc.addEventListener("click", function() {
      let termsContent = tc.querySelector('.block-offer-card__terms-details');
      if (termsContent.getAttribute("aria-expanded") == "true") {
        termsContent.setAttribute("aria-expanded", "false");
        termsContent.setAttribute("data-state", "closed");
      } else {
        termsContent.setAttribute("aria-expanded", "true");
        termsContent.setAttribute("data-state", "open");
      }
    });
  });*/
  MicroModal.init();
});
