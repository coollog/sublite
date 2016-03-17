<style>
  .refer {
    background: #fff;
    padding: 50px;
  }
  .refer headline {
    margin: 0 0 20px 0;
    line-height: 1.1em;
  }
  .rblock {
    width: 40%;
    font-size: 1.5em;
    line-height: 1.5em;
  }
  .ror {
    width: 20%;
    font-size: 1.5em;
    color: #ffd800;
    font-weight: bold;
  }
  td {
    vertical-align: center;
  }

  #inviteemail {
    margin: 20px;
  }
  iframe {
    margin: 0;
  }

  .selectcontacts {
    display: none;
  }
  .selectcontacts .contacts {
    width: 100%;
    height: 300px;
    overflow: scroll;
    text-align: left;
  }
</style>

<div class="refer">
  <headline>Tell your friends and win an iPad Mini!</headline>

  <table class="roptions">
    <tr>
      <td class="rblock">
        Like us on Facebook to get updates and news on latest jobs.
      </td>
      <td class="ror">or</td>
      <td class="rblock">
        Invite your friends to join SubLite!
      </td>
    </tr>
    <tr>
      <td>
        <div class="fb-like-box" data-href="https://www.facebook.com/SubLiteNet" data-width="200" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>
        <br />
        <div class="fb-share-button" data-href="https://sublite.net?r=<?php View::echof('id'); ?>" data-layout="button"></div>
      </td>
      <td></td>
      <td class="invitecontacts">
        <input type="button" value="Select Gmail Contacts" id="inviteemail" />
      </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td class="selectcontacts">
        <form class="contacts"></form>
        <right>
          <form>
            <div class="form-slider"><label for="name">Your Name: </label><input type="text" id="name" name="name" /></div>
            <input type="button" value="Select All" id="selectall" />
            <br /><br />
            <input type="button" value="Send Invite" id="sendinvite" />
          </form>
        </right>
      </td>
    </tr>
  </table>
</div>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=478408982286879&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script src="https://apis.google.com/js/client.js"></script>
<script>
  function showContacts(data) {
    $('#inviteemail').hide();
    $('.invitecontacts').html('Retrieving contacts list...');

    setTimeout(function() {
      var emails = [],
          entries = data['feed']['entry'];

      for (var i = 0; i < entries.length; i ++) {
        var entry = entries[i],
            name = entry['title']['$t'],
            addresses = entry['gd$email'];

        if (addresses instanceof Array) {
          for (var j = 0; j < addresses.length; j ++) {
            var email = addresses[j]['address'];
            if (email.split(".").pop() == 'edu') {
              emails.push({
                'name': name,
                'email': email
              });
            }
          }
        }
      }

      var checkboxes = '';
      for (var i = 0; i < emails.length; i ++) {
        var name = emails[i]['name'],
            email = emails[i]['email'];

        if (name.length > 0)
          label = name + ' - ' + email;
        else
          label = email;

        checkboxes += '<input type="checkbox" name="email" value="' + email + '" /> ' + label + '<br />';
      }

      $('.invitecontacts').html('Select contacts to invite:');
      $('.selectcontacts').show();
      $('.selectcontacts .contacts').html(checkboxes);
    }, 100);
  }
  function inviteSuccess(data) {
    $('.invitecontacts').html('Thanks for inviting your contacts!');
    console.log(data);
  }

  function auth() {
    var config = {
      'client_id': '454863811654-nbjm543os90c8v3fmrace7uj5rsts0pf.apps.googleusercontent.com',
      'scope': 'https://www.google.com/m8/feeds'
    };
    gapi.auth.authorize(config, function() {
      fetch(gapi.auth.getToken());
    });
  }
  function fetch(token) {
    var url = "https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token +  "&alt=json&max-results=1000";
    $.ajax({
      url: url,
      dataType: "jsonp",
      success: function(data) {
        showContacts(data);
      }
    });
  }
  $('#inviteemail').click(function() { auth(); });
  $('#selectall').click(function() {
    $('.selectcontacts input').prop("checked", true);
  });
  $('#sendinvite').click(function() {
    var name = $('#name').val();

    var emails = [];
    $('.selectcontacts input').each(function() {
      if ($(this).prop('checked'))
        emails.push($(this).val());
    });

    $('.invitecontacts').html('Sending invites...');
    $('.selectcontacts').hide();

    var data = {
      emails: emails,
      name: name,
      email: '<?php View::echof('email'); ?>',
      r: "<?php View::echof('id'); ?>",
    };
    $.ajax({
      type: "POST",
      url: 'refer.php',
      data: data,
      success: function(data) {
        inviteSuccess(data);
      }
    })
  });
</script>