    <style>
      footer {
        background: #000;
        height: 120px;
        padding: 20px;
      }
      opt img {
        width: 25px;
        height: 25px;
        margin-left: 20px;
      }
      opt img:hover {
        opacity: 0.5;
      }
    </style>
    <footer class="blackbar">
      <options class="left">
        <a href="<?php echo $GLOBALS['dirpre']; ?>../team.php"><opt>The Team</opt></a>
        <a href="<?php echo $GLOBALS['dirpre']; ?>../faq.php"><opt>FAQ</opt></a>
        <a href="<?php echo $GLOBALS['dirpre']; ?>../privacy.php"><opt>Privacy</opt></a>
        <a href="<?php echo $GLOBALS['dirpre']; ?>../terms.php"><opt>Terms of Service</opt></a>
        <a href="<?php echo $GLOBALS['dirpre']; ?>../feedback.php"><opt>Report a Bug/Feedback</opt></a>
      </options>
      <options class="right">
        <opt>
          <a href="https://www.facebook.com/SubLiteNet"><img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/fbthumb.png" /></a>
          <a href="http://sublite.wordpress.com/"><img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/bloggerthumb.png" /></a>
          <a href="https://twitter.com/sublitenet"><img src="<?php echo $GLOBALS['dirpre']; ?>assets/gfx/twitterthumb.png" /></a>
        </opt>
      </options>
    </footer>
    <script>
      function repositionFooter() {
        var h = 0;
        $('.blackbar, panel').each(function() {
          h += $(this).height();
        });
        var mt = $(window).height() - h - $('footer').height();
        if (mt > 0) {
          $('footer').css('margin-top', mt);
        } else {
          $('footer').css('margin-top', 0);
        }
      }

      $(window).on('resize', function() {
        repositionFooter();
      });
      $(function () { repositionFooter(); });
    </script>
  </body>
</html>
