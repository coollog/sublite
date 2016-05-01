<style>
  #dashboard {
    background: #fbfaf6;
  }
  #dashboard .content > * > * {
    box-shadow: 0px 0px 2px #ccc;
  }
  leftside {
    width: 25%;
  }
    profilelinks, messages {
      width: 100%;
      margin-bottom: 1em;
      vertical-align: top;
    }
    profilelinks {
      background: #123456;
    }
      profile {
        background: #ffd800;
        padding: 2em;
        text-align: left;
      }
        profile photo {
          width: 120px;
          height: 120px;
          border-radius: 50%;
          margin: 0 auto;
          margin-bottom: 1em;
        }
        profile name {
          font-weight: bold;
          font-size: 1.3em;
          display: block;
        }
      links {
        background: white;
        text-align: left;
      }
      links linkOption {
        color: black;
        padding: 1em 2em;
        font-weight: bold;
        transition: 0.1s all ease-out;
        cursor: pointer;
        border-bottom: 0.5px solid #ddd;
        display: block;
      }
      links linkOption:hover {
        opacity: 0.5;
      }
    messages {
      background: white;
      border-top: 0.5em solid #ffd800;
      font-size: 0.8em;
      padding-bottom: 2em;
    }
      messages .message {
        padding: 1em;
        border-bottom: 1px solid #eee;
        text-align: left;
        width: 100%;
      }
      messagelist {
        display: block;
        margin-bottom: 1em;
      }
      .message photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 0.5em;
      }
      .message name {
        font-size: 1.2em;
      }
      .message text {
        overflow-y: hidden;
        height: 2em;
        display: block;
      }
  rightside {
    float: right;
    width: 70%;
  }
  .clearfix:after {
    content: " ";
    display: block;
    clear: both;
  }
    section {
      width: 100%;
      margin-bottom: 1em;
      background: white;
      padding: 2em 1em;
      border-top: 1em solid transparent;
      box-sizing: border-box;
    }
    #section_job_applications {
      border-top-color: #00cada;
    }
    #section_sublets {
      border-top-color: #ff7900;
    }
      #section_sublets listsublet {
        margin-top: -1em;
        font-size: 0.8em;
        display: block;
      }
      section headline {
        font-size: 2em;
        margin-bottom: 0.5em;
      }
      section items {
        text-align: left;
        height: calc(225px + 2em);
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
      }
      section item {
        white-space: normal;
        width: 175px;
        height: 225px;
        box-shadow: 1px 1px 3px #ccc;
        background: white;
        box-sizing: border-box;
        margin: 1em;
        transition: 0.1s all ease-out;
        cursor: pointer;
        font-size: 0.8em;
        line-height: 1.5em;
        color: black;
      }
      section item:hover {
        opacity: 0.8;
        background: #fff4b6;
      }
      section item:active {
        opacity: 0.5;
      }
        items.applications item {
          padding: 1em;
        }
        items.applications item headerinfo {
          width: 75px;
        }
        items.applications item logo {
          width: 50px;
          height: 50px;
          border-radius: 50%;
          margin-right: 1em;
          vertical-align: top;
        }
        items.applications item name {
          font-weight: bold;
          max-height: 35px;
          overflow: hidden;
        }
        items.applications item company {
          max-height: 15px;
          overflow: hidden;
        }
        items.applications item desc {
          margin: 1.5em 0;
          height: 90px;
          overflow: hidden;
        }
        items.applications item deadline {
          color: #999;
        }
        items.applications item deadline:before {
          content: "Deadline: ";
        }
        items.applications item submitted {
          text-transform: uppercase;
          display: block;
        }
        items.applications item submitted.submitted {
          color: #00B233;
        }
        items.applications item submitted.submitted:before {
          content: "submitted";
        }
        items.applications item submitted.notSubmitted {
          color: #FF1919;
        }
        items.applications item submitted.notSubmitted:before {
          content: "not submitted";
        }
        items.sublets item photo {
          width: 100%;
          height: 145px;
        }
        items.sublets item info {
          padding: 1em;
          height: 80px;
          box-sizing: border-box;
          line-height: 1.3em;
        }
        items.sublets item name {
          font-weight: bold;
        }
        items.sublets item address {
          font-size: 0.8em;
        }
        items.sublets item price {
          font-weight: bold;
          font-size: 1.2em;
        }
        items.sublets item period {
          font-size: 0.8em;
          margin-left: 0.5em;
        }
  @media (max-width: 1000px) {
    leftside, rightside {
      display: block;
      width: 100% !important;
    }
    leftside {
      margin-bottom: 2em;
    }
      profilelinks, messages {
        width: 49% !important;
      }
  }
</style>

<templates>
  <messagetemplate>
    <table class="message">
      <td>
        <photo class="div imagecover"
               style="background-image: url('{frompic}');">
        </photo>
      </td>
      <td>
        <name>{fromname}</name> | <date>{time}</date>
        <br />
        <text>{msg}</text>
      </td>
    </table>
  </messagetemplate>
  <applicationtemplate>
    <a href="<?php View::echoLink('jobs/apply/{jobId}'); ?>">
      <item class="inlinediv">
        <logo class="inlinediv imagecover"
              style="background-image: url('{logo}');">
        </logo>
        <headerinfo class="inlinediv">
          <name class="div">{title}</name>
          <company class="div">{company}</company>
        </headerinfo>
        <desc class="div">{desc}</desc>
        <deadline>{deadline}</deadline>
        <submitted></submitted>
      </item>
    </a>
  </applicationtemplate>
  <sublettemplate>
    <a href="<?php View::echoLink('housing/editsublet?id={_id}'); ?>">
      <item class="inlinediv">
        <photo class="div imagecover"
               style="background-image: url('{photo}');"></photo>
        <info class="div">
          <name>{title}</name>
          <address>{address}</address>
          <price>${price}</price><period>/{pricetype}</period>
        </info>
      </item>
    </a>
  </sublettemplate>
</templates>

<script>
  var DataLoader = {
    Messages: {
      count: 0,
      container: 'messagelist',
      add: function (data) {
        DataLoader.add(this, data, 'messagetemplate');
      },
      load: function (start, count) {
        this.hasNoneText = $(this.container).html();
        $(this.container).text('Loading messages...');
        DataLoader.loadContent(this, 'messages', start, count, function (data) {
          $('messages unread').text(' (' + data.unread + ')');
        });
      }
    },
    Applications: {
      count: 0,
      container: 'items.applications',
      add: function (data) {
        DataLoader.add(this, data, 'applicationtemplate');

        // Submitted showing.
        $('items.applications item').last().each(function () {
          if (data.submitted) $(this).find('submitted').addClass('submitted');
          else $(this).find('submitted').addClass('notSubmitted');
        });
      },
      load: function (start, count) {
        this.hasNoneText = $(this.container).html();
        $(this.container).text('Loading your applications...');
        DataLoader.loadContent(this, 'applications', start, count);
      }
    },
    Sublets: {
      count: 0,
      container: 'items.sublets',
      add: function (data) {
        DataLoader.add(this, data, 'sublettemplate');
      },
      load: function (start, count) {
        this.hasNoneText = $(this.container).html();
        $(this.container).text('Loading your sublets...');
        DataLoader.loadContent(this, 'sublets', start, count);
      }
    },
    add: function (self, data, template) {
      var html = Templates.use(template, data);

      if (self.count == 0) $(self.container).html('');
      $(self.container).append(html);

      self.count ++;
    },
    hasNone: function (self) {
      $(self.container).html(self.hasNoneText);
    },
    loadContent: function (self, id, start, count, callback) {
      var route = 'home/ajax/' + id;

      var data = {
        start: start,
        count: count
      };

      $.post(route, data, function (data) {
        console.log("'" + route + "' returned with:");
        console.log(data);
        data = JSON.parse(data);

        data[id].forEach(function (item) {
          self.add(item);
        });
        if (data[id].length == 0) DataLoader.hasNone(self);

        if (typeof callback !== 'undefined') callback(data);
      });
    },
    load: function () {
      this.Sublets.load(0, 3);
      this.Applications.load(0, 3);
      this.Messages.load(0, 5);
    }
  };

  $(function () {
    Templates.init();

    DataLoader.load();
  });
</script>

<panel id="dashboard">
  <div class="content clearfix">
    <leftside class="inlinediv">
      <profilelinks class="inlinediv">
        <profile class="div">
          <photo class="div imagecover"
                 style="background-image: url('<?php View::echof('photo'); ?>');">
          </photo>
          <name><?php View::echof('name'); ?></name>
          <school>
            <?php View::echof('school'); ?> '<?php View::echof('class'); ?>
          </school>
        </profile>
        <links class="div">
          <?php echo View::linkTo('<linkOption>Edit Profile</linkOption>',
                                  "$GLOBALS[dirpre]../housing/editprofile"); ?>
          <?php echo View::linkTo('<linkOption>View Career Profile</linkOption>',
                                  "$GLOBALS[dirpre]../jobs/viewprofile"); ?>
          <?php echo View::linkTo('<linkOption>Edit Career Profile</linkOption>',
                                  "$GLOBALS[dirpre]../jobs/editprofile"); ?>
          <?php echo View::linkTo('<linkOption>Housing Search</linkOption>',
                                  "$GLOBALS[dirpre]../housing/search"); ?>
          <?php echo View::linkTo('<linkOption>Job Search</linkOption>',
                                  "$GLOBALS[dirpre]../jobs/search"); ?>
          <?php echo View::linkTo('<linkOption>Messages</linkOption>',
                                  "$GLOBALS[dirpre]../messages"); ?>
        </links>
      </profilelinks>
      <messages class="inlinediv">
        <subheadline>Messages<unread></unread></subheadline>
        <messagelist>No messages.</messagelist>
        <a href="<?php View::echoLink('messages'); ?>">
          <input type="button" value="Go to Messages" />
        </a>
      </messages>
    </leftside>
    <rightside class="inlinediv">
      <section class="inlinediv" id="section_job_applications">
        <headline>Your Job Applications</headline>
        <items class="div applications">
          You have no applications.
          <a href="<?php View::echoLink('jobs/search'); ?>">
            Start searching for jobs.
          </a>
        </items>
      </section>
      <section class="div" id="section_sublets">
        <headline>Your Sublet Listings</headline>
        <listsublet>
          <a href="<?php View::echoLink('housing/addsublet'); ?>">
            Add New Sublet
          </a>
        </listsublet>
        <items class="div sublets">
          You have no sublet listings.
          <a href="<?php View::echoLink('housing/addsublet'); ?>">
            List your sublet.
          </a>
        </items>
      </section>
    </rightside>
  </div>
</panel>