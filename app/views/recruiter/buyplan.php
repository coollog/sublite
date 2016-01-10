<style>
  plan {
    display: inline-block;
    padding: 30px 50px;
    border: 2px solid #BFF5F1;
    border-radius: 10px;
    min-width: 200px;
    box-sizing: border-box;
    transition: 0.1s all ease-in-out;
    cursor: pointer;
    margin: 1em;
  }
  plan:hover {
    opacity: 0.5;
  }
  plan:active, plan.selected {
    background: #EDFCFC;
  }
  plan type {
    font-size: 3em;
    font-weight: bold;
    font-family: 'BebasNeue', sans-serif;
    letter-spacing: 1px;
    margin-bottom: 0.5em;
    display: block;
    line-height: 1.5em;
  }
  plan info {
    display: block;
    text-align: left;
  }
  ul.includes {
    padding: 0;
    list-style-type: none;
  }
  ul li:before {
    content: "";
    background: url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/check.png') no-repeat center center;
    height: 3em;
    width: 3em;
    background-size: cover;
    display: inline-block;
    vertical-align: middle;
    margin-right: 1em;
  }

  terms term {
    padding: 10px 20px;
    border: 2px solid #BFF5F1;
    border-radius: 10px;
    color: #267F79;
    cursor: pointer;
    transition: 0.1s all ease-in-out;
    display: inline-block;
  }
  terms term:hover {
    opacity: 0.5;
  }
  terms term:active, terms term.selected {
    background: #EDFCFC;
  }

  payment {
    display: block;
    text-align: left;
  }
  paymentform, paymentselect, verifyingcard {
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
    position: relative;
  }
  paymentinfo.selected {
    background: #daebf2;
  }
  paymentinfo deleteme {
    width: 2em;
    height: 2em;
    position: absolute;
    background:
      url('<?php echo $GLOBALS['dirpre']; ?>assets/gfx/applications/x.png')
      no-repeat center center;
    background-size: contain;
    display: block;
    opacity: 0.2;
    cursor: pointer;
    transition: 0.1s all ease-in-out;
    right: 1em;
    top: 50%;
    margin-top: -1em;
  }
  paymentinfo:hover {
    opacity: 0.5;
  }
  paymentinfo deleteme:hover {
    opacity: 1;
  }
  #addcreditcard {
    margin-top: 1em;
  }
  purchased {
    display: block;
    color: green;
  }
  purchasefailed {
    display: block;
    color: red;
  }
</style>

<templates>
  <paymentinfotemplate>
    <paymentinfo cardid="{cardId}">
      <b>Card: </b>Ending in {last4}<br />
      <b>Exp: </b>{expMonth}/{expYear}
      <deleteme></deleteme>
    </paymentinfo>
  </paymentinfotemplate>
</templates>

<script src="https://checkout.stripe.com/checkout.js"></script>

<script>
  var DISCOUNT_CODE = true; <?php View::echof('discount'); ?>

  function setupMultiselect() {
    $('.multiselect').click(function () {
      if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
      } else {
        var name = $(this).attr('name');
        $('.multiselect[name='+name+']').removeClass('selected');
        $(this).addClass('selected');
      }
    });
  }

  function setupPayment() {
    function buy(cardId, type, term) {
      var data = {
        cardId: cardId,
        type: type,
        term: term
      };
      $.post('ajax/buyplanfinish', data, function (data) {
        console.log(data);

        $('#confirmpurchase').prop('disabled', false);
        $('purchasefailed')
          .html("We're sorry, the transaction was unsuccessful: " + data)
          .show();
        $('body').click(function() { $('purchasefailed, purchased').hide(); });

        data = JSON.parse(data);

        $('purchased').show();
        $('purchasefailed').hide();
      });
    }

    function addCard(cardId, last4, expMonth, expYear) {
      var html = Templates.use('paymentinfotemplate', {
        cardId: cardId,
        last4: last4,
        expMonth: expMonth,
        expYear: expYear
      });
      $('paymentselect').append(html);
      setupSelectCard();
    }

    function processToken(token) {
      $('verifyingcard').show();

      $.post('ajax/addcard', { token: token }, function (data) {
        console.log(data);
        data = JSON.parse(data);

        addCard(data.cardId, data.last4, data.expMonth, data.expYear);
        $('#addcreditcard').prop('disabled', false);
        $('verifyingcard').hide();
      });
    }

    function setupSelectCard() {
      $('paymentinfo').off('click').click(function () {
        $('selectpayment').hide();
        if ($(this).hasClass('selected')) {
          $(this).removeClass('selected');
          return;
        }
        $('paymentinfo').removeClass('selected');
        $(this).addClass('selected');
      });

      $('paymentinfo deleteme').off('click').click(function (e) {
        var $paymentinfo = $(this).parent();
        var cardId = $paymentinfo.attr('cardId');
        $paymentinfo.remove();
        $.post('ajax/removecard', { cardId: cardId }, function (data) {
          console.log(data);
          console.log('Removed card ' + cardId);
        });
        e.stopPropagation();
      });
    }

    function getSelectedCard() {
      var cardId = null;
      $('paymentinfo').each(function () {
        if ($(this).hasClass('selected')) cardId = $(this).attr('cardId');
      });
      return cardId;
    }

    $('#confirmpurchase').off('click').click(function () {
      var type = $('plan.selected').attr('type');
      var term = $('term.selected').attr('len');
      var amount = $('term.selected').attr('cost');

      // Get cardId.
      var cardId = getSelectedCard();
      if (cardId === null) {
        $('selectpayment').show();
        return;
      }

      var goodToGo =
        confirm('Are you sure you wish to purchase the ' + type + ' plan for '+
                term + ' month(s) for $' + amount + '?');
      if (!goodToGo) return;

      $(this).prop('disabled', true);

      $('purchased type').html(type);
      $('purchased term').html(term);

      buy(cardId, type, term);
    });

    (function setupCheckout() {
      var handler = StripeCheckout.configure({
        key: '<?php echo $GLOBALS['stripe']['publishable_key']; ?>',
        image: 'https://s3.amazonaws.com/stripe-uploads/acct_170LBgJZ3jUzUWXamerchant-icon-1445905399663-favicon.png',
        locale: 'auto',
        token: processToken
      });

      $('#addcreditcard').off('click').click(function (e) {
        var credits = $('paymentcredits').html();
        var amount = $('paymentamount').html();

        // $('#addcreditcard').prop('disabled', true);

        // Open Checkout with further options.
        handler.open({
          name: 'SubLite, LLC.',
          description: amount + ' Credits',
          zipCode: true,
          panelLabel: 'Add Credit Card',
          billingAddress: true,
          email: '<?php echo $_SESSION['email']; ?>'
        });
        e.preventDefault();
      });

      // Close Checkout on page navigation
      $(window).on('popstate', function() {
        handler.close();
      });
    })();

    function setupCardData(cards) {
      $('paymentselect').html('');
      cards.forEach(function (card) {
        addCard(card.cardId, card.last4, card.expMonth, card.expYear);
      });
      setupSelectCard();
    }

    (function loadCardData() {
      $.post('ajax/getcards', {}, function (data) {
        console.log(data);
        data = JSON.parse(data);

        setupCardData(data);
      });
    })();
  }

  $(function () {
    Templates.init();

    setupMultiselect();
    setupPayment();

    $('plan').click(function () {
      if (!$(this).hasClass('selected')) {
        $('terms, level2').hide();
        return;
      }

      var type = $(this).attr('type');
      switch (type) {
        case 'basic':
          var costs = [39, 99, 179, 299];
          var discounted = [29, 79, 149, 259];
          break;
        case 'premium':
          var costs = [99, 249, 429, 799];
          var discounted = [79, 219, 379, 739];
          break;
      }

      $('term[len=1]').html('1 Month ');
      $('term[len=3]').html('3 Months ');
      $('term[len=6]').html('6 Months ');
      $('term[len=12]').html('1 Year ');
      if (DISCOUNT_CODE) {
        $('term[len=1]')
          .attr('cost', discounted[0])
          .append('<del>$'+costs[0]+'</del> $'+discounted[0]);
        $('term[len=3]')
          .attr('cost', discounted[1])
          .append('<del>$'+costs[1]+'</del> $'+discounted[1]);
        $('term[len=6]')
          .attr('cost', discounted[2])
          .append('<del>$'+costs[2]+'</del> $'+discounted[2]);
        $('term[len=12]')
          .attr('cost', discounted[3])
          .append('<del>$'+costs[3]+'</del> $'+discounted[3]);
      } else {
        $('term[len=1]')
          .attr('cost', costs[0])
          .append('$'+costs[0]);
        $('term[len=3]')
          .attr('cost', costs[1])
          .append('$'+costs[1]);
        $('term[len=6]')
          .attr('cost', costs[2])
          .append('$'+costs[2]);
        $('term[len=12]')
          .attr('cost', costs[3])
          .append('$'+costs[3]);
      }

      $('terms, level2').show();
    });

    $('term').click(function() {
      if (!$(this).hasClass('selected')) {
        $('payment').hide();
        return;
      }

      $('payment').show();
    });
  });
</script>

<panel>
  <div class="content">
    <headline>Choose a Purchase Plan</headline>

    <plan class="multiselect" name="plans" type="basic">
      <type>Basic</type>
      <info>
        <b>Includes:</b>
        <br />
        <ul class="includes">
          <li>Link to external application</li>
          <li>Facebook/social media promotion</li>
          <li>Newsletter promotion</li>
        </ul>
      </info>
    </plan>
    <plan class="multiselect" name="plans" type="premium">
      <type>Premium</type>
      <info>
        <b>Includes:</b>
        <br />
        <ul class="includes">
          <li>Everything in Basic</li>
          <li>Filter applications by criteria</li>
          <li>Start conversations with 50 applicants</li>
        </ul>
      </info>
    </plan>

    <br /><br />

    <level2>
      <terms class="hide">
        <subheadline>Select a term</subheadline>
        <br /><br />

        <term len="1" class="multiselect" name="terms">1 Month $39</term>
        <term len="3" class="multiselect" name="terms">3 Months $99</term>
        <term len="6" class="multiselect" name="terms">6 Months $179</term>
        <term len="12" class="multiselect" name="terms">1 Year $299</term>
      </terms>

      <payment class="hide">
        <br /><br />
        Select payment method:
        <paymentselect>Loading credit card data...</paymentselect>
        <verifyingcard class="hide">Verifying credit card...</verifyingcard>
        <input id="addcreditcard" type="button" class="smallbutton"
               value="Add Credit Card" />

        <selectpayment class="hide">
          Please select a payment method from above.
        </selectpayment>
        <purchased class="hide">
          You have successfully purchased the <type></type> plan for <term></term> month(s)!
        </purchased>
        <purchasefailed class="hide"></purchasefailed>
        <br /><br />

        <input id="confirmpurchase" type="button" value="Confirm Purchase" />
        <br />

        <small><a href="<?php echo $GLOBALS['dirpre']; ?>../feedback">
          Trouble buying Credits?
        </a></small>
      </payment>
    </level2>
  </div>
</panel>