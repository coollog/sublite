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
        <div class="fb-share-button" data-href="https://sublite.net" data-layout="button_count"></div>
      </td>
      <td></td>
      <td>
        <input type="button" value="Select Gmail Contacts" id="inviteemail" />
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
    $.ajax({
      url:"https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token +  "&alt=json",
      dataType: "jsonp",
      success:function(data) {
        console.log(JSON.stringify(data));
      }
    });
  }
  $('#inviteemail').click(function() { auth(); });
</script>