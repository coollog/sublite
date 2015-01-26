<?php
	require_once('header.php');
	
	require_once('htmlheader.php');
	require_once('panelcss.php');
?>

<?php $page = 'FAQ'; require('menu.php'); ?>
<div class="panel" style="background: #fc9a50; color: #fff; padding: 2em 0; margin-bottom: -2em;">
	<div style="font-size: 1.4em; font-weight: 600;">Frequently Asked Questions</div>
</div>
<style>
	.content a {
		color: inherit;
	}
	.content a:hover {
		color: inherit;
	}
	.content a:active {
		color: inherit;
	}
	.content a:visited; {
		color: inherit;
	}
</style>
<div class="panel" style="background: #fc9a50; color: #a14233;">
	<div class="content" style="text-align: left; max-width: 30em;">
		<style>
			links a {
				display: block;
			}
			links a:active {
				opacity: 0.5;
			}
		</style>
		<links>
			<a href="#1">Who can create SubLite accounts?</a>
			<a href="#2">What if I have a problem with my account?</a>
			<a href="#3">How do I edit my account information?</a>
			<a href="#4">How do I reserve a sublet?</a>
			<a href="#5">How do I contact the host?</a>
			<a href="#6">If my space is no longer available, how do I take it down?</a>
			<a href="#7">Can I hide my listing so I can reuse it next year?</a>
			<a href="#8">What legal issues should I consider before hosting on SubLite?</a>
			<a href="#9">When should I contact a host?</a>
			<a href="#10">What will my listing and profile information be used for?</a>
			<a href="#11">Where can I leave feedback?</a>
		</links>
	</div>
</div>
<div class="panel" style="background: #fff; color: #a14233;">
	<div class="content" style="text-align: left;">
		<style>
			faq a.q {
				font-weight: 600;
				display: block;
				font-size: 1.4em;
				line-height: 1.5em;
				margin: 0.5em 0;
			}
			faq a.q:hover {
				text-decoration: none;
			}
		</style>
		<faq>
			<a class="q" name="1">Who can create SubLite accounts?</a>
			Any student at any accredited university.<br /><br />
			<a class="q" name="2">What if I have a problem with my account?</a>
			Contact <a href="#contact">info@sublite.net</a> for any issues regarding usage of the website, including Log In and password issues.<br /><br />
			<a class="q" name="3">How do I edit my account information?</a>
			Navigate to your account (by pressing the account icon at the top-right navigation menu), and click on any field to change it. Press enter or the button provided to lock in the update. Remember to save changes by pressing the Save Changes button.<br /><br />
			<a class="q" name="4">How do I reserve a sublet?</a>
			Once you find a sublet that you like, contact the host and work out a deal!<br /><br />
			<a class="q" name="5">How do I contact the host?</a>
			Every host will have an university email address and phone number listed in his or her profile. We have verified all of these email addresses so go ahead and click send.<br /><br />
			<a class="q" name="6">If my space is no longer available, how do I take it down?</a>
			You can delete your listing anytime by going to your account (by pressing the account icon at the top-right navigation menu), clicking a listing to access its Editing page, and pressing the Delete button.<br /><br />
			<a class="q" name="7">Can I hide my listing so I can reuse it next year?</a>
			Absolutely. We know that students may want to sublet the same space year after year. To make your life easier, we created a &ldquo;publish-unpublish&rdquo; function. You can simply deactivate your listing by pressing the Unpublish button on the Editing page (see instructions above) and it will not show up in search results. Next year when you&rsquo;re ready to sublet again, simply click on Publish button to reactivate. You can also make edits to your listings anytime.<br /><br />
			<a class="q" name="8">What legal issues should I consider before hosting on SubLite?</a>
			Make sure you have permission from your landlord or owner of your space to sublet. Before you list, you will have to agree that you can legally sublet according to our <a href="#terms">Terms of Use</a>.
			<br /><br />
			Please also ensure all information in your listing is accurate and not misleading. For more guidance, refer to our <a href="#terms">Terms of Use</a>.<br /><br />
			<a class="q" name="9">When should I contact a host?</a>
			As soon as you find a space that interests you. Don&rsquo;t wait until the last-minute, or it may be gone!
			<br /><br />
			Legitimate reasons to contact another user are to ask questions about the amenities and to inquire about price. Please contact hosts in good-faith. Spamming is expressly prohibited. For more guidance, refer to our <a href="#terms">Terms of Use</a>.<br /><br />
			<a class="q" name="10">What will my listing and profile information be used for?</a>
			We will use this solely to enhance the search functionality of SubLite to make it easier to connect hosts and guests. We require your property&rsquo;s address so other users can estimate proximity to their workplace. Your exact address will not be disclosed. 
			<br /><br />
			We will not sell your information. You may see targeted advertising from sponsors based solely on non-identifying information such as your university and class year. We will not pass any of your information along to your universities, unless you violate the Terms of Use.
			<br /><br />
			For more information, read our <a href="#privacy">Privacy Policy</a>.<br /><br />
			<a class="q" name="11">Where can I leave feedback?</a>
			Whether you like our website or not, we want to hear what you have to say. If you have a funny or horrific summer housing experience, we also want to hear from you. Email us at <a href="#contact">info@sublite.net</a>.
		</faq>
	</div>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>
