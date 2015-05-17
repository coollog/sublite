<script>
  // Function to holistically prepare new renders

  function afterRender() {
    // Tabulation

    function getTabframe(tab) {
      var name = $(tab).attr('for');
      return '.tabframe[name='+name+']';
    }
    function closeTab(tab) {
      $(tab).removeClass('focus');
      $(getTabframe(tab)).hide();
    }
    function openTab(tab) {
      $(tab).addClass('focus');
      $(getTabframe(tab)).show();
    }

    $('tab').off("click").click(function() {
      var me = this;
      $('tab').each(function() {
        if (this != me) {
          closeTab(this);
        }
      });
      openTab(me);
    });
    $('tab').each(function() {
      if ($(this).hasClass('focus'))
        openTab(this);
    });

    // Posts tabbing

    $('.post').off("click").click(function() {
      var postsleft,
          myindex = $(this).attr('index'),
          replies = $(this).parent().children('.thread[for='+myindex+']');

      if (replies.length && replies.html().length) {
        var mytab = $(this).parent().css('marginLeft');
        postsleft = -parseInt(mytab);
        if (!replies.is(":visible")) {
          postsleft = -50 - parseInt(mytab);
        }
        var op = this;
        replies.slideToggle('100', 'easeInOutCubic', function() {
          scrollTo(op);
        });
        var posts = $(this).parent();
        while (!posts.hasClass('posts'))
          posts = posts.parent();
        posts.css('left', postsleft+'px');
      }
    });
    $('.tabframe[name=forum] subtab').off("click").click(function() {
      if (!$(this).hasClass('focus')) {
        var type = $(this).attr('type');
        $('.postsframe').hide();
        $('.postsframe[type='+type+']').show();
        $('.tabframe[name=forum] subtab').removeClass('focus');
        $(this).addClass('focus');
      }
    });

    // Posts replying

    $('.posts .reply textarea').off('blur').off('focus')
      .focus(function() {
        $(this).css('height', '10em')
          .parent().find('button').slideDown(200, 'easeOutCubic');
      })
      .blur(function() {
        $(this).css('height', '3em')
          .parent().find('button').slideUp(200, 'easeOutCubic');
      });

    // Meetup view switching
    $('.meetup button').off("click").click(function () {
      Views.render('meetup', {
        banner: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why3.jpg',
        name: "Let's Go Party in New York!",
        hub: 'Yale University in New York City',
        datetime: 'Tuesday Aug 15, 9:00 PM - 11:00 PM',
        place: 'General Assembly<br />1933 S. Broadway, 11th Floor, Los Angeles, 900007, CA',
        host: 'Name of Person',
        hostpic: '<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/why3.jpg',
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
      }, false, function () {
        addTestContent(); // remove this
      });
    });
    $('meetupview .details hub').off("click").click(function () {
      Views.render('hub', {}, true, function () {
        addTestContent(); // remove this
      });
    });

    // Meetup creation button
    $('#createmeetup').off("click").click(function () {
      $('tab[for=createmeetup]').click();
    });

    // Form submission
    $('form').off('submit').submit(function () {
      console.log(formJSON(this));
      return false;
    });

    // Communications setup
    Comm.afterRender();
  }
</script>