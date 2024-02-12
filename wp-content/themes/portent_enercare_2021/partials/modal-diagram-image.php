<?php
/*
 * Single modal for diplaying lightboxed images on blogs
 */
?>
<div class="modal micromodal-slide" id="modal-post-image" aria-hidden="true">
    <div class="modal__overlay" style="z-index:9999;" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-post-image-title">
            <header class="modal__header">
                <strong class="modal__title" id="modal-post-image-title"></strong>
                <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close Image</button>
            </header>
            <section class="modal__content" id="modal-post-image-content">
                <img id="modal-post-image-element" src="" />
            </section>
<!--            <footer class="modal__footer">-->
<!--                <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close Image</button>-->
<!--            </footer>-->
        </div>
    </div>
</div>