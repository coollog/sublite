$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

var loadgif = '<img src="gfx/load.gif" />';
function Form(loader, form, msg, callback, beforePost) {
	var HTML;
	
	function setup(resetup) {
		$(form).submit(function() {
			var postData = $(this).serializeObject();
			
			if (typeof beforePost !== 'undefined') beforePost();
			
			HTML = $(loader).html();
			$(loader).html(loadgif);
			
			$.post($(this).attr('action'), postData).done(function(data) {
				$(loader).html(HTML);
				$(msg).show().html(data);
				if (resetup) setup();
				callback(data);
			});
			
			return false;
		});
	}
	setup($(loader).find($(form)).length > 0);
}

function buttonset(sel) {
	$(sel).children('input[type=radio]').hide();
	function getRadio(label) {
		return $('#' + label.attr('for'));
	}
	function deselect(label) {
		label.each(function() {
			$(this).removeClass('buttonset-sel');
			getRadio($(this)).prop('checked', false);
		});
	}
	function select(label) {
		label.each(function() {
			label.addClass('buttonset-sel');
			getRadio($(this)).prop('checked', true);
		});
	}
	$(sel).children('input[type=button]').click(function() {
		deselect($(this).parent().children('input[type=button]'));
		select($(this));
	}).each(function() {
		if (getRadio($(this)).prop('checked')) {
			select($(this));
		}
	});
}

function hasAttr(element, attr) {
	attr = $(element).attr(attr);
	return (typeof attr !== 'undefined' && attr !== false);
}

function setupModal() {
	setTimeout(function() {
		$('.modal')/*.css('width', Math.min($('.modal').outerWidth(), .9 * $('.overlay').width()))
			.css('height', Math.min($('.modal').outerHeight(), $('.overlay').height()))*/
			.css('margin-left', -$('.modal').outerWidth() / 2)
			.css('margin-top', -$('.modal').outerHeight() / 2)
			.css('opacity', 1);
	}, 200);
	$('.overlay').css('opacity', 1).click(function() {
		$('.modal').stop(true).css('opacity', 0);
		$(this).stop(true).css('opacity', 0);
		setTimeout(function() {
			$('.modal').remove();
			$('.overlay').remove();
		}, 500);
	});
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			$('.overlay').click();
		}
	});
}
function overlay(html) {
	$('.overlay, .modal').remove();
	$('body')
		.append('<div class="overlay"></div>')
		.append('<div class="modal">' + html + '</div>')
		.append('<script>setupModal();</script>');
}

function preloadImages(array) {
    if (!preloadImages.list) {
        preloadImages.list = [];
    }
    for (var i = 0; i < array.length; i++) {
        var img = new Image();
        img.src = array[i];
        preloadImages.list.push(img);
    }
}

function scrollTo(top) {
	$('html, body').animate({
		scrollTop: top
	}, 500);
}