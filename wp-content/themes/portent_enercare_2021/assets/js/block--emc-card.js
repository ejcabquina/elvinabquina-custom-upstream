"use strict";

window.addEventListener('load', function (event) {
  console.log('EMC Card Setup');
  var emcCards = document.querySelectorAll('.block-emc-card__wrapper');
  console.log(emcCards);
  emcCards.forEach(function (card) {
    var cardRead, cardClose, cardContent;
    cardRead = card.querySelector('.block-emc-card__trigger');
    cardClose = card.querySelector('.block-emc-card__close-button');
    cardContent = card.querySelector('.block-emc-card__content');
    cardRead.addEventListener('click', function (event) {
      cardContent.setAttribute('aria-hidden', 'false');
      cardClose.focus();
    });
    cardClose.addEventListener('click', function (event) {
      cardContent.setAttribute('aria-hidden', 'true');
      cardRead.focus();
    });
  });
});