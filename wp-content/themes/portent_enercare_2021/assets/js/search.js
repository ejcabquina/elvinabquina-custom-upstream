"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
// Select the node that will be observed for mutations
var targetNode = document.getElementById('addsearch-results');

// Options for the observer (which mutations to observe)
var config = {
  childList: true,
  subtree: true
};

// Callback function to execute when mutations are observed
var callback = function callback(mutationList, observer) {
  // Use traditional 'for loops' for IE 11
  var _iterator = _createForOfIteratorHelper(mutationList),
    _step;
  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var mutation = _step.value;
      var _iterator2 = _createForOfIteratorHelper(mutation.addedNodes),
        _step2;
      try {
        var _loop = function _loop() {
          var node = _step2.value;
          if (node.id === 'addsearch-nohits') {
            var searchTerm = node.querySelector('p em');
            var addSearchAlertNode = document.getElementById('addsearch-notifications');
            //Clear any existing message
            setTimeout(function () {
              addSearchAlertNode.textContent = '';
              //Push in status message
              addSearchAlertNode.textContent = "No results found for ".concat(searchTerm.textContent, ". Please try different term");
            }, 300);
          }
          if (node.id === 'addsearch-result-item-container-1') {
            setTimeout(function () {
              console.log("Found Results");
              var addSearchBox = document.querySelector('input.addsearch');
              var addSearchAlertNode = document.getElementById('addsearch-notifications');
              addSearchAlertNode.textContent = '';
              addSearchAlertNode.textContent = "Results have been found for your search term, ".concat(addSearchBox.value);
            }, 300);
          }
          if (node.parentElement.id === 'addsearch-rp-paging') {
            //Check for numerical values
            if (node.innerHTML.match(/\d/, 'gm')) {
              console.log('Found a number');
              node.setAttribute('aria-label', "Search Result Page ".concat(node.textContent));
              node.innerHTML = "<span class=\"screen-reader-text\">Page</span> ".concat(node.innerHTML);
            }
            if (node.textContent.includes('previous')) {
              console.log('Found a previous');
              node.setAttribute('aria-label', 'Previous Search Results Page');
              node.innerHTML = "".concat(node.innerHTML, " <span class=\"screen-reader-text\">Page</span>");
            }
            if (node.textContent.includes('next')) {
              console.log('Found a next');
              node.setAttribute('aria-label', 'Next Search Results Page');
              node.innerHTML = "".concat(node.innerHTML, " <span class=\"screen-reader-text\">Page</span>");
            }
          }
        };
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          _loop();
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
};

// Create an observer instance linked to the callback function
var observer = new MutationObserver(callback);

// Start observing the target node for configured mutations
observer.observe(targetNode, config);