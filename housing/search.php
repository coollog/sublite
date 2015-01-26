<?php
	$requireLogin = true;
	require_once('header.php');	
	
	function inCity($city) {
		global $L;
		
		$geocode = geocode($city);
		$N = $geocode['latitude'];
		$W = $geocode['longitude'];
		
		$cursor = $L->cListings->find(array('publish' => true));
		
		$docs = array();
		foreach ($cursor as $doc) {
			$dN = $doc['N'];
			$dW = $doc['W'];
			$doc['proximity'] = distance($N, $W, $dN, $dW);
			if ($doc['proximity'] <= 50)
				$docs[] = $doc;
			for ($i = 0; $i < count($doc['imgs']); $i ++) {
				$doc['imgs'][$i] = https($doc['imgs'][$i]);
			}
		}
		
		return count($docs);
	}
	
	$sugg['Philadelphia'] = 'Philadelphia, PA';
	$sugg['Los Angeles'] = 'Los Angeles, CA';
	$sugg['Chicago'] = 'Chicago, IL';
	$sugg['Boston'] = 'Boston, MA';
	$sugg['New York City'] = 'New York, NY';
	$sugg['New Haven'] = 'New Haven, CT';
	$sugg['Washington D.C.'] = 'Washington, D.C.';
	$sugg['San Francisco, CA'] = 'San Francisco, CA';
	
	require_once('htmlheader.php');
?>

<?php require_once('navbar.php'); ?>

<?php require_once('formcss.php'); ?>
<?php require_once('contentcss.php'); ?>
<style>
	input {
		display: block;
	}
	.sortby {
		cursor: pointer;
	}
	.timespan, .options, .sort {
		display: none;
	}
	.search {
		position: relative;
	}
	.search .formoverlay {
		position: absolute;
		background: rgba(0, 0, 0, 0.5);
		z-index: 10;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		display: none;
	}
	input[name=loadMore] {
		display: none;
	}
	.results img {
		height: 4em;
	}
	.sort {
		margin-bottom: 2em;
	}
	.sortby {
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
		display: inline-block;
		margin-left: 2em;
	}
	.sortby:hover {
		opacity: 0.5;
	}
	.suggs {
		margin-top: -0.5em;
		display: none;
	}
	.sugg {
		margin: 0;
		border: 1px solid #ccc;
		<?php cssCross('transition: 0.1s all ease-in-out;'); ?>
		cursor: pointer;
	}
	.sugg:first-of-type {
		font-weight: bold;
		background: #fefafa;
	}
	.sugg:not(:first-of-type):hover {
		opacity: 0.5;
	}
</style>
<script>
	$(function() {
		
		new Form('.timespan .submit', 'form.fSearch', '', function(data) {
			console.log(data);
			try {
				data = $.parseJSON(data);
				$('.results').html(data['error']);
			} catch(e) {
				$('.results').html(data);
			}
			$('.options').show();
			$('.search .formoverlay').hide();
			$('.searchleft').addClass('searchleft-left');
			$('.searchright').show().css('display', 'inline-block').addClass('searchright-left');;
		}, function() {
			$('.suggs').slideUp(300, "easeInOutCubic");
			$('.searchright h3').html('Search Results');
			$('.sort').hide();
			$('.search .formoverlay').show();
		});
		
		$('input[name=sort]').change($('form.fSearch').submit);
		$(':checkbox, select').change(function() {$('form.fSearch').submit()});
		
		$("input[name='from'], input[name='to']").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: 0,
			onClose: function(selectedDate) {
				if ($(this).attr('name') == 'from') {
					$("input[name='to']").datepicker("option", "minDate", selectedDate);
				}
			}
		});
		
		/* SLIDERS */
		function setSlider(input, slider) {
			var min = $(slider).slider("option", "min");
			var max = $(slider).slider("option", "max");
			var val = Math.max(min, Math.min(max, $(input).val()));
			$(input).val(val);
			$('form.fSearch').submit();
			$(slider).slider('value', val);
		}
		function setSliderRange(inputmin, inputmax, slider, minmax) {
			if (minmax == 'min') {
				var min = $(slider).slider("option", "min");
				var max = $(inputmax).val();
				var val = Math.max(min, Math.min(max, $(inputmin).val()));
				$(inputmin).val(val);
				$(slider).slider('values', 0, val);
			} else {
				var min = $(inputmin).val();
				var max = $(slider).slider("option", "max");
				var val = Math.max(min, Math.min(max, $(inputmax).val()));
				$(inputmax).val(val);
				$(slider).slider('values', 1, val);
			}
		}
		$('.searchleft .proximity').slider({
			range: 'min',
			min: 0,
			max: 50,
			value: 50,
			slide: function(e, ui) {
				$("input[name=proximity]").val(ui.value);
			},
			stop: function() {
				$('form.fSearch').submit();
			}
		});
		$("input[name=proximity]").val($(".searchleft .proximity").slider("value")).change(function() {
			setSlider(this, '.searchleft .proximity');
		});
		
		$(".searchleft .price").slider({
			range: true,
			min: 0,
			max: 2000,
			values: [0, 2000],
			slide: function(e, ui) {
				$("input[name=price0]").val(ui.values[0]);
				$("input[name=price1]").val(ui.values[1]);
			},
			stop: function() {
				$('form.fSearch').submit();
			}
		});
		$("input[name=price0]").val($(".searchleft .price").slider("values", 0)).change(function() {
			setSliderRange(this, 'input[name=price1]', '.searchleft .price', 'min');
		});
		$("input[name=price1]").val($(".searchleft .price").slider("values", 1)).change(function() {
			setSliderRange(this, 'input[name=price1]', '.searchleft .price', 'max');
		});
		
		$('.searchleft .occ').slider({
			range: 'max',
			min: 1,
			max: 10,
			value: 1,
			slide: function(e, ui) {
				$("input[name=occ]").val(ui.value);
			},
			stop: function() {
				$('form.fSearch').submit();
			}
		});
		$("input[name=occ]").val($(".searchleft .occ").slider("value")).change(function() {
			setSlider(this, '.searchleft .occ');
		});
		
		$('input[name=location]').keypress(function(e) {
			if (e.which == 13) {
				$('input[name=next]').click();
				e.preventDefault();
			}
		}).click(function() {
			$('.suggs').slideDown(300, "easeInOutCubic");
		});
		$('.sugg').click(function() {
			$('input[name=location]').val($(this).attr('val'));
		});
		
		$('input[name=next]').click(function() {
			$('.location .msg').html('');
			$('.timespan').show();
			$('.suggs').slideUp(300, "easeInOutCubic");
			$(this).hide();
		});
		
		function sortBy(type) {
			$('.sortby').css('font-weight', 400);
			$(type).css('font-weight', 700);
			$('input[name=sort]').val($(type).attr('value'));
		}
		$('.sortby').click(function() {
			sortBy(this);
			$('form.fSearch').submit();
		});
		sortBy('.sortby[value=priceIncreasing]');
	});
	function showSort() {
		$('.sort').show();
	}
</script>

<div class="fillcontent">
	<div class="contentblock searchleft">
		<div class="search form">
			<form class="fSearch" method="post" action="aSearch.php">
				<input type="hidden" name="skip" value="0" />
				<div class="location">
					<h3>
						Start searching!
						<smaller><br /><?php echo $L->length(); ?> listings available and growing!</smaller>
					</h3>
					Where do you want to sublet?
					<input type="text" name="location" placeholder="City or Specific Address" />
					<div class="suggs">
						<div class="sugg">Popular Cities:</div>
						<?php
							foreach ($sugg as $short=>$city) {
						?>
						<div class="sugg" val="<?php echo $city; ?>"><?php echo $short; ?> <small><?php if (isset($_GET['showcounts'])) echo '(' . inCity($city) . ')'; ?></small></div>
						<?php
							}
						?>
					</div>
					<div class="next"><input class="button orange" type="button" name="next" value="Next" /></div>
				</div>
				<div class="timespan">
					<h3>Choose Your Dates</h3>
					<input type="text" name="from" placeholder="From" />
					<input type="text" name="to" placeholder="To" />
					<div class="submit"><input class="button orange" type="submit" value="Search" /></div>
				</div>
				<div class="options">
					<h3>Filters</h3>
					Proximity to searched location?
					<div class="slider proximity"></div>
					<input style="width: 70%; display: inline-block;" type="number" name="proximity" placeholder="Max Proximity" /> miles<br />
					Price range?
					<div class="slider price"></div>
					Min Price: $ <input style="width: 50%; display: inline-block;" type="number" name="price0" placeholder="Price Min" /><br />
					Max Price: $ <input style="width: 50%; display: inline-block;" type="number" name="price1" placeholder="Price Max" /><br />
					How many people?
					<div class="slider occ"></div>
					<input style="width: 80%; display: inline-block;" type="number" name="occ" placeholder="Min Occupancy" />
					<?php $search = true; require('fRoom.php'); ?>
					<?php require('fBuilding.php'); ?>
					<?php require('fAmenities.php'); ?>
				</div>
				<input type="hidden" name="sort" />
			</form>
			<div class="formoverlay"></div>
		</div>
	</div>
	<div class="contentblock searchright">
		<!--<h3>Search using the filters on the left.</h3>-->
		<h3>Recent Listings</h3>
		<div class="sort">
			<b>Sort By:</b>
			<div class="sortby" value="proximityIncreasing">Closest Proximity</div>
			<div class="sortby" value="priceIncreasing">Price Increasing</div>
			<div class="sortby" value="priceDecreasing">Price Decreasing</div>
		</div>
		<div class="results">
			<!--Looking to list a sublet?<br />
			<a href="list.php"><input class="button orange" type="button" value="Create a Listing"></a>-->
			<?php require('iRecent.php'); ?>
		</div>
		<!--<input class="button orange" type="button" name="loadMore" value="Load More" />-->
	</div>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>