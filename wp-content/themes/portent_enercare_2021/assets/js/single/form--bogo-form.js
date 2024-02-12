(function($, document) {
	function bogoFormPresentation() {
		var boilerFurnaceFieldset = $('fieldset.radio__boiler-furnace');
		var furnaceRadios = boilerFurnaceFieldset.find('input[value=Furnace][type=radio]');
		var boilerRadios = boilerFurnaceFieldset.find('input[value=Boiler][type=radio]');
		var choiceLabels = boilerFurnaceFieldset.find('.gchoice label');
		var choiceInputs = boilerFurnaceFieldset.find('input');
		var inputChanges = function(target) {
			$('.gchoice.selected').removeClass('selected');

			if(target.parent().hasClass('selected') ) {
				target.parent().removeClass('selected');
			} else {
				target.parent().addClass('selected');
			}
		};

		var setSelect = function(target) {
			console.log(target);
			if(target[0].checked) {
				target.parent().addClass('selected');
			}
		};

		choiceInputs.each(function() {
			setSelect($(this));
		});

		choiceInputs.on('change', function() {
			inputChanges($(this));
		});

		choiceLabels.on('click', function(event){
			inputChanges($(this));
		});

    furnaceRadios.each(function() {
      var furnaceId = "furnace_" + $(this).attr('id');
      if (document.getElementById(furnaceId)) {
      } else {
        $('<img id="' + furnaceId + '" alt="" role="presentation" width=60 height=60 src="/wp-content/uploads/2021/10/furnace-24px-b.svg"/>').insertBefore($(this));
      }
    });
    boilerRadios.each(function() {
      var boilerId = "boiler_" + $(this).attr('id');
      if (document.getElementById(boilerId)) {
      } else {
        $('<img id="' + boilerId + '" alt="" role="presentation" width=60 height=60 src="/wp-content/uploads/2021/10/boiler-24px-b.svg"/>').insertBefore($(this));
      }
    });
	}

	$(document).on('gform_post_render', function() {
		bogoFormPresentation();
	});
})(jQuery, document);


