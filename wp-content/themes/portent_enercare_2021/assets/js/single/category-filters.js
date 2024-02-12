"use strict";

jQuery(function ($) {
  //Detect clicks outside of an open filter to close it.
  $(document).on('click', function (event) {
    if (!$(event.target).closest('.taxonomy-filters__category-control').length) {
      closeAllFilters();
    }
  });
  /**
   * Filter Keybinds
   */

  $(document).on('keyup', function (event) {
    //esc key binds
    if (27 === event.keyCode) {
      closeAllFilters();
    }
  }); //Close all open filters
  //Pass a selection to ignore it during the close out process

  function closeAllFilters(currentSelection) {
    $('.multiSelect').each(function () {
      if ($(this) !== currentSelection) {
        $(this).hide();
        $(this).prev().attr('aria-expanded', 'false');
      }
    });
  } //Search each select container for buttons with aria-pressed = true
  //If a filter group has a child element that has been pressed add a data-active attribute to the group control


  function flagActiveFilters() {
    $('.select-container').each(function () {
      var activeFilters = $(this).find('.multi-dropdown-button[aria-pressed="true"]');
      var thisFilterControl = $(this).find('.taxonomy-filters__category-control');

      if (0 < activeFilters.length) {
        thisFilterControl.attr('data-active', 'true');
      } else {
        thisFilterControl.attr('data-active', 'false');
      }
    });
  } //Reset the active filter by stripping query parameters from the url and pushing a new history state.


  function resetFilter() {
    var vanillaURL = window.location.href.split('?')[0];
    $('.multi-dropdown-button[aria-pressed="true"]').attr('aria-pressed', 'false');
    flagActiveFilters();
    pushHistoryState(vanillaURL);
    displayFilteredResults(vanillaURL);
  } //DRY helper function to push a new url into the window history


  function pushHistoryState(updatedUrl) {
    if (history.pushState) {
      window.history.pushState('', '', updatedUrl);
    } else {
      document.location.href = updatedUrl;
    }
  } //Using a url collect and display new filtered results


  function displayFilteredResults(updatedUrl) {
    console.log("updatedUrl", updatedUrl);
    $.get(updatedUrl, function (data, textStatus, xhr) {
      //console.log(xhr.status);
      if (404 === xhr.status) {
        $('.archive-wrapper').fadeIn(1000).html('<h2>No results found.</h2>');
      } else if (data && data.length) {
        if ($(data).find('.no-results').length) {
          $('.archive-wrapper').fadeIn(1000).html('<h2>No results found.</h2>');
        } else {
          $('.archive-wrapper').fadeIn(1000).html($(data).find('.archive-wrapper').html());
        }
      }
    });
  } //Filter reset hookup


  $('.filter-reset').on('click', function () {
    resetFilter();
  }); //Top level filter group control hookup

  $('.taxonomy-filters__category-control').on('click', function (event) {
    if (!$(this).next().is(':visible')) {
      closeAllFilters($(this).next());
      $(this).next().show();
      $(this).attr('aria-expanded', 'true'); //let thisElementFor = $( this ).attr( 'for' );
    } else {
      $(this).next().hide();
      $(this).attr('aria-expanded', 'false');
    }
  }); //Single filter criteria control hookup

  $('.multi-dropdown-button').click(function () {
    // @TODO: when first engagement with filters, yank /page/#/ from URL
    var remove = false;
    var firstInteraction = false;

    if ('true' === $(this).attr('aria-pressed')) {
      $(this).attr('aria-pressed', false);
      remove = true;
    } else {
      $(this).attr('aria-pressed', true);
    } //Run check for active filter buttons to better signal filter group status after aria-pressed status is updated for this target
    // eslint-disable-next-line no-mixed-spaces-and-tabs


    flagActiveFilters();
    var currentUrl = new URL(window.location.href);
    var cat = currentUrl.searchParams.get('cat');
    var cats = [];

    if (cat && cat.length) {
      cats = cat.split(',');
    } else {
      firstInteraction = true;
    }

    if (!cat || !remove && !cat.includes($(this).attr('data-taxonomy'))) {
      cats.push($(this).attr('data-taxonomy'));
    }

    for (var i = 0; i < cats.length; i++) {
      window['terms-' + cats[i]] = currentUrl.searchParams.get('terms-' + cats[i]); // only make adjustments to the term param associated with the button pressed

      if ($(this).attr('data-taxonomy') === cats[i]) {
        if (window['terms-' + cats[i]] && window['terms-' + cats[i]].length) {
          if (remove) {
            // remove from list attempts
            window['terms-' + cats[i]] = window['terms-' + cats[i]].replace(',' + $(this).attr('data-term'), '');
            window['terms-' + cats[i]] = window['terms-' + cats[i]].replace($(this).attr('data-term') + ',', ''); // remove solo attempt

            window['terms-' + cats[i]] = window['terms-' + cats[i]].replace($(this).attr('data-term'), '');
          } else {
            window['terms-' + cats[i]] = window['terms-' + cats[i]] + ',' + $(this).attr('data-term');
          }
        } else {
          window['terms-' + cats[i]] = $(this).attr('data-term');
        }
      }

      if ('' === window['terms-' + cats[i]]) {
        currentUrl.searchParams.delete('terms-' + cats[i]);
      } else {
        // set the URL term params
        currentUrl.searchParams.set('terms-' + cats[i], window['terms-' + cats[i]]);
      }
    }

    if (cat && cat.length) {
      if (remove && 0 === window['terms-' + $(this).attr('data-taxonomy')].length) {
        // remove from list attempt
        cat = cat.replace(',' + $(this).attr('data-taxonomy'), '');
        cat = cat.replace($(this).attr('data-taxonomy') + ',', ''); // remove solo attempt

        cat = cat.replace($(this).attr('data-taxonomy'), '');
      } else if (!cat.includes($(this).attr('data-taxonomy'))) {
        cat = cat + ',' + $(this).attr('data-taxonomy');
      }
    } else {
      cat = $(this).attr('data-taxonomy');
    } // set the URL cat params


    if ('' === cat) {
      currentUrl.searchParams.delete('cat');
    } else {
      currentUrl.searchParams.set('cat', cat);
    }

    var updatedUrl = currentUrl.href;

    if (firstInteraction) {
      updatedUrl = currentUrl.href.replace(/\/page\/[0-9]+/, '');
    }

    pushHistoryState(updatedUrl);
    displayFilteredResults(updatedUrl);
  }); //Start the page load with a filter check

  flagActiveFilters();

  $('.category-filter__select').on('change', function (event) {
    var searchedTaxonomy = this.value;
    //console.log(searchedTaxonomy);
    var currentUrl = new URL(window.location.href);
    var taxonomySlug = $(this).attr('data-taxonomy');
    var category = currentUrl.searchParams.get(taxonomySlug);

    currentUrl.searchParams.set(taxonomySlug, searchedTaxonomy);

    var updatedUrl = currentUrl.href;
    pushHistoryState(updatedUrl);

    displayFilteredResults(updatedUrl);
  }); //Single filter criteria control hookup

  $('.postal-code-input-container button').on('click', function (event) {
    var searchedPostalCode = $('.postal-code-input-container #postalCode').val();
    var currentUrl = new URL(window.location.href);
    let topLevelContainer = $(this).parent().parent();

    var isPostalCode = /^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i.test(searchedPostalCode);

    if(isPostalCode) {
      var postalCode = currentUrl.searchParams.get('postal_code');
      currentUrl.searchParams.set('postal_code', searchedPostalCode);
      var updatedUrl = currentUrl.href;
      topLevelContainer.removeClass('has-errors');
      pushHistoryState(updatedUrl);
      displayFilteredResults(updatedUrl);
    } else {
      topLevelContainer.addClass('has-errors');
      let errorLabel = $(this).parent().parent().find('.form-error');
      console.log(errorLabel);
      errorLabel.text('Please enter a valid postal code');
      // set the global enercare polite status element for screen reading
      $('#enercare-polite-status').html('').html("Please enter a valid postal code.");
    }

  }); //Single filter criteria control hookup

  $('.province-filter__select').on('change', function (event) {
    var searchedProvince = this.value;
    console.log(searchedProvince);
    var currentUrl = new URL(window.location.href);
    var province = currentUrl.searchParams.get('province');

    currentUrl.searchParams.set('province', searchedProvince);

    var updatedUrl = currentUrl.href;
    pushHistoryState(updatedUrl);

    displayFilteredResults(updatedUrl);
  }); //Single filter criteria control hookup
});
