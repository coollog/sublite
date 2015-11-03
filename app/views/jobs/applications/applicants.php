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
      transition: 0.1s all ease-in-out;
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
      transition: 0.1s all ease-in-out;
    }
    subtab.focus, subtab:hover {
      color: #000;
    }
    subtabframe {
      display: none;
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

  creditsenough {
    display: block;
  }
  nonetomove, unlockingtoomany, creditsnotenough, invalidamount, paymentadderror {
    display: block;
    color: red;
  }
  applicantList {
    display: block;
    position: relative;
  }
  applicantList info {
    display: table-cell;
  }
  applicantList applicant {
    display: table;
    table-layout: fixed;
    box-sizing: border-box;
    width: 100%;
    background: white;
    padding: 2em;
    margin-top: 2em;
    box-shadow: 1px 1px 1px #ccc;
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
  applicantList applicant:hover {
    opacity: 0.5;
  }
  applicantList applicant.selected {
    background: #daebf2;
  }
  applicantList applicant name {
    font-size: 2em;
    line-height: 1.2em;
    font-weight: bold;
    display: block;
  }
  applicantList applicant school {
    font-size: 1.5em;
    line-height: 1.5em;
    display: block;
  }
  applicantList applicant buttons {
    display: table-cell;
    vertical-align: bottom;
    text-align: right;
  }

  paymentform {
    display: block;
  }
  paymentamount, paymentcredits {
    font-weight: bold;
  }
  paymentinfo {
    background: white;
    box-shadow: 1px 1px 1px #ccc;
    box-sizing: border-box;
    display: block;
    padding: 1em;
    margin-top: 1em;
    cursor: pointer;
    transition: 0.1s all ease-in-out;
  }
</style>

<templates>
  <unclaimedtemplate>
    <div class="headline">
      Your job has <b>{unclaimedcount}</b> new applicants!
    </div>

    <h2>Insight</h2>
    Your applicants include students from schools including
    <b>Yale University</b>, and <b>University of Pennsylvania</b>!

    <br /><br />

    <h2>Unlock Applications</h2>
    To view these applications, you must unlock them with <i>Credit</i>.
    <br /><br />
    <unlockchoose>
      <unlockbuttons></unlockbuttons>
      <subheadline>- or -</subheadline>
      <fade class="nohover">How many applications to unlock?</fade>
      <input id="unlockcustom" type="number"
             style="width: 6em; margin-right: 1em;" placeholder="(eg. 100)" />
      <input id="unlockcustombutton" type="button" count="0"
             class="smallbutton nohover unlockbutton"
             value="Unlock 0 Applications" disabled />
      <unlockingtoomany class="hide">
        You are trying to unlock too many applications!
      </unlockingtoomany>
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
    You are about to unlock <count id="countToUnlock">{count}</count>
    application(s) at <i>1 Credit per application</i>.<br />
    <creditsenough>
      Your new <i>Credits</i> will be
      <count>{oldCredits} - {count} = {newCredits}</count>.
    </creditsenough>
    <creditsnotenough class="hide">
      You do not have enough credits!
      To purchase more credits, click on the
      <a href="#" onclick="$('tab[for=credits]').click()">Credits</a> tab.
    </creditsnotenough>
    <br />
    <unlocking>
      <input id="unlockconfirmbutton" type="button" class="smallbutton"
             value="Confirm" />
      <fade>
        <input id="unlockback" type="button" class="reverse smallbutton"
               value="Back" />
      </fade>
    </unlocking>
  </unlockconfirmtemplate>
  <unlockbuttontemplate>
    <input type="button" class="smallbutton unlockbutton" count="{count}"
           value="Unlock {text} Application{s}" /><br />
  </unlockbuttontemplate>

  <claimedtemplate>
    <subtabs>
      <subtab for="review" class="focus">In Review</subtab> |
      <subtab for="accepted">Accepted</subtab> |
      <subtab for="rejected">Rejected</subtab>
    </subtabs>

    <input class="moveToButton smallbutton reverse" to="review" type="button"
           value="Move to In Review" />
    <input class="moveToButton smallbutton reverse" to="accepted" type="button"
           value="Move to Accepted" />
    <input class="moveToButton smallbutton reverse" to="rejected" type="button"
           value="Move to Rejected" />
    <nonetomove>Click on applications to select them!</nonetomove>

    <applicantList>
      <subtabframe for="review">
        <br />
        You do not have any unlocked applications that still need review.
        Check the <a href="#" onclick="$('tab[for=unclaimed]').click()">New</a>
        tab to see if you have any new applications that need to be unlocked!
      </subtabframe>
      <subtabframe for="accepted">
        <br />
        You do not have any applications in this folder.
        You can move applications here using the buttons above.
        This is simply for organization.
        Students will NOT be notified.
      </subtabframe>
      <subtabframe for="rejected">
        <br />
        You do not have any applications in this folder.
        You can move applications here using the buttons above.
        This is simply for organization.
        Students will NOT be notified.
      </subtabframe>
    </applicantList>
  </claimedtemplate>
  <applicanttemplate>
    <applicant applicationId="{_id}">
      <info>
        <name>{name}</name>
        <school>{school}</school>
        <fade class="nohover">Applied on <date>{date}</date></fade>
      </info>
      <buttons>
        <a href="../../jobs/application/{_id}" target="_blank"
           class="stopPropagation">
          <input type="button" value="View" />
        </a>
      </buttons>
    </applicant>
  </applicanttemplate>

  <creditstemplate>
    <div class="headline">
      You currently have <b>{creditcount}</b> <i>Credits</i>.
    </div>

    <input id="buycreditsbutton" type="button" value="Buy Credits" />
    <paymentform class="hide">
      How many credits?
      <input id="paymentcredits" type="number" placeholder="(eg. 20)" />
      <invalidamount class="hide">
        You have to input a positive number of credits.
      </invalidamount>

      <paymentselect></paymentselect>
      <form id="paymentadd">
        Credit Card Information:
        <div class="form-slider">
          <label for="cardnumber">Card Number:</label>
          <input type="text" id="cardnumber" data-stripe="number" />
        </div>
        <div class="form-slider">
          <label for="cardcvc">CVC:</label>
          <input type="text" id="cardcvc" data-stripe="cvc" />
        </div>
        <div class="form-slider">
          <label for="cardexp">Expiration (MM/YYYY):</label>
          <input type="text" id="cardexp" data-stripe="exp" />
        </div>
        Billing Information:
        <div class="form-slider">
          <label for="billingname">Full Name:</label>
          <input type="text" id="billingname" data-stripe="name" />
        </div>
        <div class="form-slider">
          <label for="billingaddress_line1">Address Line 1:</label>
          <input type="text" id="billingaddress_line1"
                 data-stripe="address_line1" />
        </div>
        <div class="form-slider">
          <label for="billingaddress_line2">Address Line 2:</label>
          <input type="text" id="billingaddress_line2"
                 data-stripe="address_line2" />
        </div>
        <div class="form-slider">
          <label for="billingaddress_city">City:</label>
          <input type="text" id="billingaddress_city"
                 data-stripe="address_city" />
        </div>
        <div class="form-slider">
          <label for="billingaddress_state">State:</label>
          <input type="text" id="billingaddress_state"
                 data-stripe="address_state" />
        </div>
        <div class="form-slider">
          <label for="billingaddress_zip">Zip Code:</label>
          <input type="text" id="billingaddress_zip"
                 data-stripe="address_zip" />
        </div>
        <center>
          <input id="addcreditcard" type="button" value="Add Credit Card" />
        </center>
        <paymentadderror></paymentadderror>
      </form>

      You will be charged $<paymentamount>0</paymentamount> to purchase
      <paymentcredits>0</paymentcredits> credits.
      <br /><br />
      <input id="confirmpurchase" type="button" value="Confirm Purchase"
             disabled />
    </paymentform>

    <!-- <h2>Add Payment Information</h2>
     -->
  </creditstemplate>
  <paymentinfotemplate>
    <paymentinfo cardid="{cardId}">
      <b>Card: </b>Ending in {last4}<br />
      <b>Exp: </b>{expMonth}/{expYear}
    </paymentinfo>
  </paymentinfotemplate>
</templates>

<script>
  function loadContent(id, data, callback) {
    var route = 'ajax/' + id;
    data.jobId = '<?php View::echof('jobId'); ?>';

    $.post(route, data, function (data) {
      console.log("'" + route + "' returned with:");
      console.log(data);
      data = JSON.parse(data);
      callback(data);
    });
  }
  function setupSubtabs(callOnOpen) {
    function getSubtabFrame(subtab) {
      var name = $(subtab).attr('for');
      return 'subtabframe[for='+name+']';
    }
    function showSubtabFrame(subtabframe) {
      $(subtabframe).show();
    }
    function hideSubtabFrame(subtabframe) {
      $(subtabframe).hide();
    }
    function closeSubtab(subtab) {
      $(subtab).removeClass('focus');
      hideSubtabFrame(getSubtabFrame(subtab));
    }
    function openSubtab(subtab) {
      $(subtab).addClass('focus');
      showSubtabFrame(getSubtabFrame(subtab));
      if (typeof callOnOpen !== 'undefined') {
        callOnOpen(subtab);
      }
    }

    $('subtab').off('click').click(function() {
      var me = this;
      $('subtab').each(function() {
        if (this != me) {
          closeSubtab(this);
        }
      });
      openSubtab(me);
    });
    $('subtab').each(function() {
      if ($(this).hasClass('focus')) {
        openSubtab(this);
      }
    });
  }

  function setupUnclaimed(data) {
    function confirmUnlock() {
      var count = parseInt($('#countToUnlock').html());

      var data = {
        jobId: '<?php View::echof('jobId'); ?>',
        count: count
      };
      $('unlocking').html('Unlocking...');
      loadContent('claimapplications', data, function (data) {
        $('tab[for=unclaimed]').click();
        // $('tab[for=claimed]').click();
      });
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
      if (count <= 0) {
        button.addClass('nohover').prop('disabled', true);
      } else {
        button.removeClass('nohover').prop('disabled', false);
      }

      $('unlockingtoomany').hide();
    });

    (function setupUnlockButtons() {
      var unclaimedCount = <?php View::echof('unclaimedcount'); ?>;
      var unlockCounts = [1, 5, 20, 50, 100];

      function getUnlockButtonHTML(count) {
        var showS = count > 1;
        if (count == unclaimedCount) {
          var text = 'All';
          showS = true;
        } else {
          var text = count;
        }
        var data = {
          text: text,
          count: count,
          s: showS ? 's' : ''
        };
        return Templates.use('unlockbuttontemplate', data);
      }

      var unlockButtonsHTML = '';
      unlockCounts.forEach(function (count) {
        if (count >= unclaimedCount) return;
        unlockButtonsHTML += getUnlockButtonHTML(count);
      });
      unlockButtonsHTML += getUnlockButtonHTML(unclaimedCount);
      $('unlockbuttons').html(unlockButtonsHTML);

      $('.unlockbutton').off('click').click(function () {
        var count = strToInt($(this).attr('count'));
        if (count > unclaimedCount) {
          $('unlockingtoomany').show();
          return;
        }

        // TODO: Change this to actual credit count.
        var oldCredits = <?php View::echof('creditcount'); ?>;
        var newCredits = oldCredits - count;

        var data = {
          count: count,
          oldCredits: oldCredits,
          newCredits: newCredits
        };
        var unlockConfirmHTML = Templates.use('unlockconfirmtemplate', data);

        showUnlockConfirm(unlockConfirmHTML);

        if (oldCredits < count) {
          // Credits not enough.
          $('creditsnotenough').show();
          $('creditsenough').hide();
          $('#unlockconfirmbutton').remove();
          return;
        }
      });
    })();

    $('unlockconfirm').hide();
  }
  function setupClaimed(data) {
    function getSelectedApplicants() {
      var _idList = [];
      $('applicantList applicant').filter(':visible').each(function() {
        if ($(this).hasClass('selected')) {
          _idList.push($(this).attr('applicationId'));
        }
      });
      return _idList;
    }

    (function loadApplicantList(data) {
      function getApplicationHTML(application) {
        application['_id'] = application['_id'].$id;
        var html = Templates.use('applicanttemplate', application);
        return html;
      }

      for (var status in data) {
        switch (status) {
          case 'review': case 'rejected': case 'accepted':
            var html = '';
            data[status].forEach(function (application) {
              html += getApplicationHTML(application);
            });
            if (html == '') continue;
            $('.tabframe[name=claimed] applicantList subtabframe[for='+status+']')
              .html(html);
            break;
        }
      }
    })(data);

    (function setupApplicant() {
      $('applicantList applicant').click(function() {
        $(this).toggleClass('selected');
        $('nonetomove').hide();
      });
    })();

    (function setupMoveTo() {
      $('.moveToButton').each(function() {
        $(this).click(function() {
          var selected = getSelectedApplicants();
          if (selected.length == 0) {
            $('nonetomove').show();
            return;
          }

          var to = $(this).attr('to');
          var data = {
            selected: selected,
            to: to
          };
          $('.tabframe[name=claimed]')
            .show().children('content').html('Loading...');

          loadContent('moveapplications', data, function() {
            $('tab[for=claimed]').click();
          });
        });
      });

      $('nonetomove').hide();
    })();

    setupSubtabs(function (me) {
      var name = $(me).attr('for');
      $('.moveToButton').show();
      $('.moveToButton[to='+name+']').hide();
      console.log(name);
    });

    $('.stopPropagation').off('click').click(function (e) {
      e.stopPropagation();
      console.log('stoped');
    });
  }
  function setupCredits(data) {
    function buy(cardId, credits) {
      var data = {
        cardId: cardId,
        credits: credits
      };
      $.post('../ajax/buycredits', data, function (data) {

      });
    }

    function addCard(cardId, last4, expMonth, expYear) {
      var html = Templates.use('paymentinfo', {
        cardId: cardId,
        last4: last4,
        expMonth: expMonth,
        expYear: expYear
      });
      $('paymentselect').append(html);
    }

    function processToken(status, response) {
      $('#addcreditcard').prop('disabled', false);

      if (response.error) {
        $('paymentadderror').html(response.error.message).show();
        return;
      }

      var token = response.id;
      $.post('../ajax/addcard', { token: token }, function (data) {
        addCard(data.cardId, data.last4, data.expMonth, data.expYear);
      });
    }

    $('#buycreditsbutton').off('click').click(function () {
      $('paymentform').slideDown(200, 'easeInOutCubic');
      $(this).slideUp(200, 'easeInOutCubic');
    });

    $('#paymentcredits').off('change').change(function () {
      var credits = parseInt($(this).val());
      if (credits <= 0) {
        $('invalidamount').show();
        return;
      }

      var amount = credits * 8;

      $('invalidamount').hide();
      $('paymentcredits').html(credits);
      $('paymentamount').html(amount);
      $('#confirmpurchase').prop('disabled', false);
    });

    $('#confirmpurchase').off('click').click(function () {
      var credits = $('paymentcredits').html();
      var amount = $('paymentamount').html();

      var goodToGo = confirm('Are you sure you wish to purchase ' + credits +
                             ' Credits for $' + amount + '?');
      if (!goodToGo) return;

      // Get cardId.
      // var cardId = ;

      buy(cardId, credits);
    });

    $('#addcreditcard').off('click').click(function () {
      var $form = $('paymentform');

      $(this).prop('disabled', true);

      Stripe.card.createToken($form, processToken);
    });
  }

  function getHTMLSetup(id, data) {
    switch (id) {
      case 'unclaimed':
        if (data.unclaimedcount == 0) {
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
    $('unclaimedcount').html(data.unclaimedcount);
    $('claimedcount').html(data.claimedcount);
    $('creditcount').html(data.creditcount);
    return {html: html, setup: setup};
  }

  $(function () {
    Templates.init();

    (function setupTabs() {
      // Tracks which tabs have been loaded already.
      var loaded = {};

      function getTabframe(tab) {
        var name = $(tab).attr('for');
        return '.tabframe[name='+name+']';
      }
      function showTabFrame(tabframe, reload) {
        var name = $(tabframe).attr('name');
        $(tabframe).show();

        // If already loaded, don't reload unless opening self again.
        if (!reload && name in loaded) return;

        $(tabframe).children('content').html('Loading...');
        loadContent('tab' + name, {}, function (data) {
          var htmlSetup = getHTMLSetup(name, data);
          $(tabframe).children('content').html(htmlSetup.html);
          htmlSetup.setup(data);

          loaded[name] = true;
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
        var reload = $(tab).hasClass('focus');
        $(tab).addClass('focus');
        showTabFrame(getTabframe(tab), reload);
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
          <?php View::echof('jobTitle'); ?> |
          <?php View::echof('jobLocation'); ?>
        </b>
      </div>

      <a href="../home">
        <input type="button" value="See All Jobs" />
      </a>
      <a href="../editjob?id=<?php View::echof('jobId'); ?>">
        <input type="button" value="Edit Job" />
      </a>
      <a href="../editapplication/<?php View::echof('jobId'); ?>">
        <input type="button" value="Edit Application" />
      </a>
    </left>
  </div>
</panel>

<panel class="tabs">
  <content class="nopadding">
    <tab for="unclaimed" class="focus">
      New (<unclaimedcount><?php View::echof('unclaimedcount'); ?></unclaimedcount>)
    </tab><tab for="claimed">
      Applicants (<claimedcount><?php View::echof('claimedcount'); ?></claimedcount>)
    </tab><tab for="credits">
      Credits (<creditcount><?php View::echof('creditcount'); ?></creditcount>)
    </tab>
  </content>
</panel>

<panel class="tabframe" name="unclaimed"><content></content></panel>
<panel class="tabframe" name="claimed"><content></content></panel>
<panel class="tabframe" name="credits"><content></content></panel>