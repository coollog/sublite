<style>
  a {
    color: #035d75;
  }
  a:hover {
    color: #ffd800;
  }
  .msg_head {
  cursor: pointer;
  }
</style>

<script>
$(document).ready(function()
{
  //hide the all of the element with class msg_body
  $(".msg_body").hide();
  //toggle the componenet with class msg_body
  $(".msg_head").click(function()
  {
    $(this).next(".msg_body").slideToggle(600);
  });
});
</script>

<panel class="faq">
  <div class="content">
    <headline>Frequently Asked Questions</headline>

    <left>
      <headline class="small">
        <a name="internships">Students - Internships</a>
      </headline>
      <subheadline class="msg_head">What’s new on Internships 2.0?</subheadline>

      <div class="msg_body">Applying for internships and jobs has never been easier. You can now pre-write your answers standard application questions. Employers will choose from a pool of these questions in their applications, so applying can be done in a matter of seconds, not hours. Companies can view your profile, which has a general overview of what makes you unique, along with your qualifications and resume.</div>

      <subheadline class="msg_head">How do I apply for a job?</subheadline>

      <div class="msg_body">On every listing there is a “Apply Now” button that will redirect you to the company’s application page. Your candidacy will only be considered if you submit an application through SubLite’s internal application system.</div>

      <subheadline class="msg_head">Is there a limit on the number of internships I can apply to?</subheadline>

      <div class="msg_body">Absolutely not! You may apply to as many internships as you wish.</div>

      <subheadline class="msg_head">Is there a word limit to my question responses?</subheadline>

      <div class="msg_body">No, you can answer your questions fully, with no word requirements to hold you back. You can fill in responses as needed for applications, or fill them all out at once. It’s up to you!</div>

      <subheadline class="msg_head">Do I need to fill out my profile?</subheadline>

      <div class="msg_body">Your profile is how recruiters get their first impression of you. If it’s not fully filled out, then what kind of first impression does that make?</div>

      <subheadline class="msg_head">Who do I contact to find out more about an internship listing?</subheadline>

      <div class="msg_body">SubLite provides the platform for recruiters to create a company profile and post internships. A recruiter’s profile next to the listing will contain all the contact information you need to reach out and learn more.</div>

      <subheadline class="msg_head">Can I share an internship listing with a friend not registered on SubLite?</subheadline>

      <div class="msg_body">While you need to be a verified student with a .edu email to search housing and internship listings, you can share each with the listing’s url. Anyone who has the url will see the information although they won’t be able to interact with other registered users.</div>

      <br /> <br /> <br />
      <headline class="small">
        <a name="housing">Students - Housing</a>
      </headline>

      <subheadline class="msg_head">Who can create SubLite accounts?</subheadline>

      <div class="msg_body">Any student at any accredited university.</div>

      <subheadline class="msg_head">What if I have a problem with my account?</subheadline>

      <div class="msg_body">Contact <a href="mailto: info@sublite.net">info@sublite.net</a> for any issues regarding usage of the website, including Log In and password issues.</div>

      <subheadline class="msg_head">How do I reserve a sublet?</subheadline>

      <div class="msg_body">Once you find a sublet that you like, contact the host and work out a deal!</div>

      <subheadline class="msg_head">How do I contact the host?</subheadline>

      <div class="msg_body">Every sublet listing page has a profile card beneath the photos with a button to Contact Owner.</div>

      <subheadline class="msg_head">
        If my space is no longer available, how do I take it down?
        <br />
        Can I hide my listing so I can reuse it next year?
      </subheadline>

      <div class="msg_body">Absolutely. We know that students may want to sublet the same space year after year. To make your life easier, we created a “publish-unpublish” function. You can unpublish your listing anytime by going to <a href="<?php View::echof('dirpre'); ?>../housing/Manage.php">Manage</a>, clicking a listing to access its editing page, and unchecking the Publish? option. Next year when you’re ready to sublet again, simply check Publish? again to reactivate. You can also make edits to your listings anytime.</div>

      <subheadline class="msg_head">What legal issues should I consider before hosting on SubLite?</subheadline>

      <div class="msg_body">Make sure you have permission from your landlord or owner of your space to sublet. Before you list, you will have to agree that you can legally sublet according to our Terms of Use.

      Please also ensure all information in your listing is accurate and not misleading. For more guidance, refer to our Terms of Use.</div>

      <subheadline class="msg_head">When should I contact a host?</subheadline>

      <div class="msg_body">As soon as you find a space that interests you. Don’t wait until the last-minute, or it may be gone!
      <br />
      Legitimate reasons to contact another user are to ask questions about the amenities and to inquire about price. Please contact hosts in good-faith. Spamming is expressly prohibited. For more guidance, refer to our <a href="<?php View::echof('dirpre'); ?>../terms.php">Terms of Use</a>.</div>

      <subheadline class="msg_head">What will my listing and profile information be used for?</subheadline>

      <div class="msg_body">We will use this solely to enhance the search functionality of SubLite to make it easier to connect hosts and guests. We require your property’s address so other users can estimate proximity to their workplace. Your exact address will not be disclosed.
      <br />
      We will not sell your information. You may see targeted advertising from sponsors based solely on non-identifying information such as your university and class year. We will not pass any of your information along to your universities, unless you violate the Terms of Use.
      <br />
      For more information, read our <a href="<?php View::echof('dirpre'); ?>../privacy.php">Privacy Policy</a>.</div>

      <subheadline class="msg_head">Where can I leave feedback?</subheadline>

      <div class="msg_body">Whether you like our website or not, we want to hear what you have to say. If you have a funny or horrific summer housing experience, we also want to hear from you. Email us at <a href="mailto: info@sublite.net">info@sublite.net</a>.</div>

      <br /> <br /> <br />
      <headline class="small">
        <a name="recruiters">Recruiters</a>
      </headline>

      <subheadline class="msg_head">Who can create an internship listing?</subheadline>

      <div class="msg_body">Only verified HR personnel and recruiters can register for a company profile and post internships. Once they register for an account, we follow-up to make sure they are legitimate recruiters before activating their listing.</div>

      <subheadline class="msg_head">What’s new in the SubLite application system?</subheadline>

      <div class="msg_body">We’ve completely redesigned our search and application process. Students can now pre-write responses to common application questions and submit resumes, in order to save their time and yours.</div>

      <subheadline class="msg_head">How do I make a listing?</subheadline>

      <div class="msg_body">First, create a recruiter profile. After that, you may create a new listing. There you’ll be able to personalize your company’s profile, describe your listing, and choose which questions you’d like your applicants to answer. Click “post” and then you’re set!</div>

      <subheadline class="msg_head">If I already have an internal application, can I link it?</subheadline>

      <div class="msg_body">We know you may prefer to place a link to your company’s official website, but students may only apply through the SubLite website. Each listing profile is fully customizable; resume and student profile pages are automatically included in each application.</div>

      <subheadline class="msg_head">Is there a limit to the number of questions I can include in the application?</subheadline>

      <div class="msg_body">No, feel free to include any number of questions in your application!</div>

      <subheadline class="msg_head">Is there a word limit to the answers of the questions?</subheadline>

      <div class="msg_body">No, students are free to fully answer questions.</div>

      <subheadline class="msg_head">How much does it cost?</subheadline>

      <div class="msg_body">We charge nothing for you to list the job on our site, but to access the applicants it cost $8 per applicant for the first fifty, then $5 per applicant after that.</div>

      <subheadline class="msg_head">What happens if I don't use up all of my credits by the end of the recruiting season?</subheadline>

      <div class="msg_body">We have a full refund policy where we will refund you the full value of your remaining credits in your account. If you have used more than 50 credits, all unused credits are refunded at $5 each. If you have redeemed less than 50 credits, all unused credits are refunded at $8 each.</div>

      <subheadline class="msg_head">Will credits expire?</subheadline>

      <div class="msg_body">Absolutely not! You may choose to keep the credits in your account for usage in the following recruiting calendar. Credits never expire.</div>

      <subheadline class="msg_head">Can I get any credits for free?</subheadline>

      <div class="msg_body">Your company will receive two bonus credits for creating a company profile and one bonus credit for each job listing you post.</div>

    </left>
  </div>
</panel>