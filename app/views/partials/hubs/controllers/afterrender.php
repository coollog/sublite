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
      // This is to make sure iframes load correctly.
      $('iframe.S3')[0].contentDocument.location.reload(true);
      console.log('blah');
    });
    $('tab').each(function() {
      if ($(this).hasClass('focus'))
        openTab(this);
    });

    // Posts tabbing

    $('replies').off("click").click(function() {
      var post = $(this).parents('.post'),
          postsleft,
          myindex = post.attr('index'),
          replies = post.parent().children('.thread[for='+myindex+']');

      var mytab = post.parent().css('marginLeft');
      postsleft = -parseInt(mytab);
      if (!replies.is(":visible")) {
        postsleft = -50 - parseInt(mytab);
      }
      var op = post[0];
      replies.slideToggle('100', 'easeInOutCubic', function() {
        if (replies.is(":visible")) scrollTo(op);
      });
      var posts = post.parent();
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
    $('html').off('click').click(function() {
      $('.reply textarea').css('height', '3em')
        .parent().find('button').slideUp(200, 'easeOutCubic');
    });
    $('.reply').off('click').click(function(event){
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