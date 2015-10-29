<style>
  panel.tabs, panel.tabframe {
    padding: 0;
  }
  content {
    box-sizing: border-box;
    padding: 40px;
    position: relative;
    display: block;
    margin: 0 auto;
    height: 100%;
    max-width: 900px;
  }
  .nopadding {
    padding: 0;
  }

  .tabs {
    background: #f3f3f2;
    font-size: 1.4em;
    text-align: left;
    width: ;
  }
    tab {
      color: #b5a8a8;
      padding: 1em 1.5em;
      cursor: pointer;
      height: 100%;
      display: inline-block;
      box-sizing: border-box;
    }
    tab.focus, tab:hover {
      background: #faf9f9;
      color: #000;
    }
  .tabframe {
    background: #f3f3f2;
    display: none;
    text-align: left;
  }
  .tabframe content {
    background: #faf9f9;
    min-height: 50vh;
    position: relative;
    height: 100%;
    display: block;
  }
    subtabs {
      display: block;
      color: #b5a8a8;
      text-align: left;
      margin-bottom: 1em;
    }
    subtab {
      cursor: pointer;
    }
    subtab.focus, subtab:hover {
      color: #000;
    }

  unlockchoose, unlockconfirm {
    display: block;
  }
  unlockconfirm {
    line-height: 2em;
  }
  unlockconfirm count {
    font-weight: bold;
  }
</style>

<templates>
  <unclaimedtemplate>
    <div class="headline">
      Your job has <b>20</b> new applicants!
    </div>

    <h2>Insight</h2>
    Your applicants include students from schools including
    <b>Yale University</b>, and <b>University of Pennsylvania</b>!

    <br /><br />

    <h2>Unlock Applications</h2>
    To view these applications, you must unlock them with <i>Credit</i>.
    <br /><br />
    <unlockchoose>
      <input type="button" class="smallbutton unlockbutton" count="1"
             value="Unlock 1 Application" /><br />
      <input type="button" class="smallbutton unlockbutton" count="5"
             value="Unlock 5 Applications" /><br />
      <input type="button" class="smallbutton unlockbutton" count="20"
             value="Unlock 20 Applications" /><br />
      <input type="button" class="smallbutton unlockbutton" count="20"
             value="Unlock All Applications" /><br />
      <subheadline>- or -</subheadline>
      <fade class="nohover">How many applications to unlock?</fade>
      <input id="unlockcustom" type="number"
             style="width: 6em; margin-right: 1em;" placeholder="(eg. 100)" />
      <input id="unlockcustombutton" type="button" count="0"
             class="smallbutton nohover unlockbutton"
             value="Unlock 0 Applications" disabled />
    </unlockchoose>
    <unlockconfirm></unlockconfirm>
  </unclaimedtemplate>
  <unclaimednonetemplate>
    Your job has no new applicants at the moment. Check back in a day or two!
    <br /><br />
    To view your existing applicants, click on the
    <a href="#" onclick="$('tab[for=claimed]').click()">Applicants</a> tab.
  </unclaimednonetemplate>
  <unlockconfirmtemplate>
    You are about to unlock <count>{count}</count> applications at
    <i>1 Credit per application</i>.<br />
    Your new <i>Credits</i> will be
    <count>{oldCredits} - {count} = {newCredits}</count>.
    <br /><br />
    <input id="unlockconfirmbutton" type="button" class="smallbutton"
           value="Confirm" />
    <fade>
      <input id="unlockback" type="button" class="reverse smallbutton"
             value="Back" />
    </fade>
  </unlockconfirmtemplate>

  <claimedtemplate>
    <subtabs>
      <subtab type="inreview" class="focus">In Review</subtab> |
      <subtab type="accepted">Accepted</subtab> |
      <subtab type="rejected">Rejected</subtab>
    </subtabs>
  </claimedtemplate>

  <creditstemplate>

  </creditstemplate>
</templates>

<script>
  function loadContent(id, callback) {
    var route = 'ajax/' + id;
    $.post(route, callback);
  }

  function setupUnclaimed(data) {
    function confirmUnlock() {
      console.log('confirmed unlock!');
    }

    function hideUnlockConfirm() {
      $('unlockchoose').slideDown(200, 'easeInOutCubic');
      $('unlockconfirm').slideUp(200, 'easeInOutCubic');
    }
    function showUnlockConfirm(html) {
      $('unlockconfirm').html(html);

      $('#unlockback').off('click').click(hideUnlockConfirm);
      $('#unlockconfirmbutton').off('click').click(confirmUnlock);

      $('unlockconfirm').slideDown(200, 'easeInOutCubic');
      $('unlockchoose').slideUp(200, 'easeInOutCubic');
    }

    $('#unlockcustom').change(function () {
      var count = strToInt($(this).val());

      var button = $('#unlockcustombutton');
      var text = 'Unlock ' + count + ' Applications';

      button.val(text).attr('count', count);
      if (count == 0) {
        button.addClass('nohover').prop('disabled', true);
      } else {
        button.removeClass('nohover').prop('disabled', false);
      }
    });

    $('.unlockbutton').off('click').click(function () {
      var count = strToInt($(this).attr('count'));
      var oldCredits = 204;
      var newCredits = oldCredits - count;

      var data = {
        count: count,
        oldCredits: oldCredits,
        newCredits: newCredits
      };
      var unlockConfirmHTML = Templates.use('unlockconfirmtemplate', data);

      showUnlockConfirm(unlockConfirmHTML);
    });

    $('unlockconfirm').hide();
  }
  function setupClaimed(data) {

  }
  function setupCredits(data) {

  }

  function getHTMLSetup(id, data) {
    switch (id) {
      case 'unclaimed':
        if (data.count == 0) {
          var html = Templates.use('unclaimednonetemplate', data);
        } else {
          var html = Templates.use('unclaimedtemplate', data);
        }
        var setup = setupUnclaimed;
        break;
      case 'claimed':
        var html = Templates.use('claimedtemplate', data);
        var setup = setupClaimed;
        break;
      case 'credits':
        var html = Templates.use('creditstemplate', data);
        var setup = setupCredits;
        break;
    }
    return {html: html, setup: setup};
  }

  $(function () {
    Templates.init();

    (function setupTabs() {
      function getTabframe(tab) {
        var name = $(tab).attr('for');
        return '.tabframe[name='+name+']';
      }
      function showTabFrame(tabframe) {
        $(tabframe).children('content').html('Loading...');

        var name = $(tabframe).attr('name');
        loadContent('tab' + name, function (data) {
          var htmlSetup = getHTMLSetup(name, data);
          $(tabframe).show().children('content').html(htmlSetup.html);
          htmlSetup.setup(data);
        });
      }
      function hideTabFrame(tabframe) {
        $(tabframe).hide();
      }
      function closeTab(tab) {
        $(tab).removeClass('focus');
        hideTabFrame(getTabframe(tab));
      }
      function openTab(tab) {
        $(tab).addClass('focus');
        showTabFrame(getTabframe(tab));
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
    })();
  });
</script>

<panel>
  <div class="content">
    <left>
      <div class="headline">
        Viewing job applicants for:
        <b>
          <?php View::echof('jobtitle'); ?> |
          <?php View::echof('joblocation'); ?>
        </b>
      </div>

      <a href="../home">
        <input type="button" value="See All Jobs" />
      </a>
      <a href="../editjob?id=<?php View::echof('jobid'); ?>">
        <input type="button" value="Edit Job" />
      </a>
      <a href="../editapplication/<?php View::echof('jobid'); ?>">
        <input type="button" value="Edit Application" />
      </a>
    </left>
  </div>
</panel>

<panel class="tabs">
  <content class="nopadding">
    <tab for="unclaimed" class="focus">
      New (<unclaimedcount>0</unclaimedcount>)
    </tab><tab for="claimed">
      Applicants (<claimedcount>0</claimedcount>)
    </tab><tab for="credits">
      Credits (<creditcount>0</creditcount>)
    </tab>
  </content>
</panel>

<panel class="tabframe" name="unclaimed"><content></content></panel>
<panel class="tabframe" name="claimed"><content></content></panel>
<panel class="tabframe" name="credits"><content></content></panel>