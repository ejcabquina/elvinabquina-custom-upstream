"use strict";

var players = Array.from(document.querySelectorAll('.plyr__youtube')).map(function (p) {
  return new Plyr(p);
});