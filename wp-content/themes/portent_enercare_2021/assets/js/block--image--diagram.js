"use strict";

//Setup images with click events
var blogImages = document.querySelectorAll('.wp-block-image.is-style-diagram');
if (typeof blogImages !== 'undefined') {
  blogImages.forEach(function (elem) {
    //elem.setAttribute('data-image-open', 'modal-post-image');
    var newButton = document.createElement('button');
    newButton.classList.add('diagram-modal-button');
    newButton.setAttribute('aria-label', "".concat(elem.getAttribute('alt'), " click this image to enlarge it"));
    newButton.addEventListener('click', function (event) {
      var imageElement = this.querySelector('img');
      var imageCaption = this.querySelector('figcaption') ? this.querySelector('figcaption').textContent : false;
      console.log(this.currentSrc);
      openPostImageModal(imageElement, imageCaption);
    });
    insertAfter(newButton, elem);
    newButton.appendChild(elem.cloneNode(true));
    elem.remove();
  });
}

//Init Micromodal
MicroModal.init({
  onShow: function onShow(modal) {
    return console.info("".concat(modal.id, " is shown"));
  },
  // [1]
  onClose: function onClose(modal) {
    return console.info("".concat(modal.id, " is hidden"));
  },
  // [2]
  openTrigger: 'data-image-open',
  // [3]
  closeTrigger: 'data-custom-close',
  // [4]
  openClass: 'is-open',
  // [5]
  disableScroll: true,
  // [6]
  disableFocus: false,
  // [7]
  awaitOpenAnimation: false,
  // [8]
  awaitCloseAnimation: false,
  // [9]
  debugMode: true // [10]
});

/*
Transfer a new image source into the modal and open it
 */
var openPostImageModal = function openPostImageModal(sourceImage, caption) {
  var imageModal = document.getElementById('modal-post-image');
  var imageModalFigure = document.getElementById('modal-post-image-element');
  imageModalFigure.src = sourceImage.currentSrc;
  imageModalFigure.setAttribute('alt', sourceImage.getAttribute('alt'));
  if (caption) {
    var imageModalCaption = imageModal.querySelector('.modal__title');
    imageModalCaption.textContent = caption;
    imageModalFigure.setAttribute('aria-describedby', imageModalCaption.id);
  }
  MicroModal.show('modal-post-image');
};
//Push image into micromodal

//Show Micromodal