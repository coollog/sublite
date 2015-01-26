		<style>
			.footer {
				min-height: 3em;
				background: #fca05a;
				text-align: center;
				line-height: 3em;
				color: #fff;
			}
			.footerlink {
				width: 20%;
				text-align: center;
				display: inline-block;
				cursor: pointer;
				<?php cssCross('user-select: none;'); ?>
				white-space: nowrap;
			}
			.footerlink:hover {
				background: #fe8a32;
			}
			@media (max-width: 900px) {
				.footerlink {
					width: 100%;
					display: block;
				}
			}
			.copy {
				text-align: center;
				line-height: 3em;
			}
			.footertext {
				display: none;
				padding: 2em;
				background: #fff;
			}
			.footertext h3 {
				font-size: 1.3em;
				font-weight: normal;
				margin: 1.5em 0;
				line-height: 1.1em;
				letter-spacing: 0.5px;
				text-align: center;
			}
		</style>
		
		<div style="text-align: center;">
			<!--<div class="fb-like" style="margin-left: -60px; display: inline-block; height: 40px; width: 240px; text-align: center;" data-href="http://sublite.net" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div><br />-->
			<div class="fb-like-box" style="display: block; margin-left: 40px;" data-href="https://www.facebook.com/SubLiteNet" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>
			<a style="display: block; margin: 0 auto;" href="https://twitter.com/SubLiteNet" class="twitter-follow-button" data-show-count="true" data-lang="en">Follow @SubLiteNet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<br />
		<div class="footer">
			<div class="footerlink" tag="contact">Contact</div>
			<div class="footerlink" tag="privacy">Privacy</div>
			<div class="footerlink" tag="terms">Terms of Service</div>
			<a href="faq.php" target="_blank" style="color: #fff;"><div class="footerlink">FAQ</div></a>
		</div>
		<div class="copy" style="background: #fff;">&copy; 2014 Sublite, LLC</div>
		<div class="footertext" tag="privacy">
			<iframe src="privacy.html"></iframe>
		</div>
		<div class="footertext" tag="terms">
			<iframe src="terms.html"></iframe>
		</div>
		<div class="footertext" tag="contact">
			<h3>Contact Us</h3>
			<?php require('contactus.php'); ?>
		</div>
		<script>
			$('.footerlink, .hashchange').click(function() {
				if (hasAttr(this, 'tag')) {
					if (window.location.hash == '#' + $(this).attr('tag'))
						clickFooter();
					else
						window.location.hash = '#' + $(this).attr('tag');
				} else clickFooter();
			});
			function clickFooter() {
				if (window.location.hash) {
					if (window.location.hash == '#about') {
						scrollTo($('anchor[name=about]').offset().top);
					}
					$('.footertext').each(function() {
						if ('#' + $(this).attr('tag') == window.location.hash) {
							$('.footertext').stop(true).slideUp(500, "easeInOutCubic");
							if (!$(this).is(':visible')) {
								$(this).stop(true).slideDown(500, "easeInOutCubic", function () {
									scrollTo($(this).offset().top);
								});
							}
						}
					});
				}
			}
			$(window).on('hashchange', function() {
				clickFooter();
			});
			clickFooter();
			$('.msg').click(function() {
				$(this).hide();
			});
			
			$('oboptions').hide();
			$('obtitle').click(function() {
				$('oboptions').slideToggle(300, "easeInOutCubic");
				if ($('obtitle').hasClass('obup')) {
					$('obtitle').removeClass('obup');
				} else {
					$('obtitle').addClass('obup');
				}
			});
		</script>
	</body>
</html>