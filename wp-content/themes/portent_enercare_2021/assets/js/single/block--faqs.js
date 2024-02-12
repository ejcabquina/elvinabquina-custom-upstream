window.addEventListener('load', function(){
  let tcToggle = document.querySelectorAll('.block-faqs--faq-container h4');
  Array.prototype.forEach.call(tcToggle, function(tc) {
  	let button = tc.querySelector('button');
  	let panel = tc.nextElementSibling;

    button.onclick = function(){
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
