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

    $('.reply textarea').off('blur').off('focus')
      .focus(function() {
        $(this).css('height', '10em')
          .parent().find('button').slideDown(200, 'easeOutCubic');
      })
    $('html').click(function() {
      $('.reply textarea').css('height', '3em')
        .parent().find('button').slideUp(200, 'easeOutCubic');
    });
    $('.reply').click(function(event){
      event.stopPropagation();
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

    formSetup();
    repositionFooter();
  }
</script>