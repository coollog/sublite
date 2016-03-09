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
      links option {
        padding: 1em 2em;
        font-weight: bold;
        transition: 0.1s all ease-out;
        cursor: pointer;
        border-bottom: 0.5px solid #ddd;
      }
      links option:hover {
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
      section headline {
        font-size: 2em;
        margin-bottom: 0.5em;
      }
      section items {
        text-align: left;
      }
      section item {
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
        }
        items.applications item desc {
          margin: 1.5em 0;
          height: 100px;
          overflow: hidden;
        }
        items.applications item deadline {
          color: #999;
        }
        items.applications item deadline:before {
          content: "Deadline: ";
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
          <option>
            View Career Profile
          </option>
          <option>
            Edit Career Profile
          </option>
          <option>
            Housing Search
          </option>
          <option>
            Internships
          </option>
          <option>
            Messages
          </option>
        </links>
      </profilelinks>
      <messages class="inlinediv">
        <subheadline>Messages</subheadline>
        <messagelist>
          <table class="message">
            <td>
              <photo class="div imagecover"
                     style="background-image: url('<?php View::echof('photo'); ?>');">
              </photo>
            </td>
            <td>
              <name>Tony J.</name> | <date>Jan 1, 2018</date>
              <br />
              <text>asfaskjfwe jfelk jfawej laweasdfdsaf sadfs fsdaas</text>
            </td>
          </table>
          <td></td>
          <table class="message">
            <td>
              <photo class="div imagecover"
                     style="background-image: url('<?php View::echof('photo'); ?>');">
              </photo>
            </td>
            <td>
              <name>Tony J.</name> | <date>Jan 1, 2018</date>
              <br />
              <text>Hello! I got your message!</text>
            </td>
          </table>
        </messagelist>
        <input type="button" value="Go to Messages" />
      </messages>
    </leftside>
    <rightside class="inlinediv">
      <section class="inlinediv" id="section_job_applications">
        <headline>Your Job Applications</headline>
        <items class="div applications">
          <item class="inlinediv">
            <logo class="inlinediv imagecover"
                  style="background-image: url('<?php View::echof('photo'); ?>');">
            </logo>
            <headerinfo class="inlinediv">
              <name>Campus Ambassador</name>
              <company>SubLite LLC.</company>
            </headerinfo>
            <desc class="div">
              Help your classmates find internships and housing by advertising SubLite on campus! Ambassadors will lead their college's SubLite promotional activities by constructing their own team of assistants and completing tasks throughout the fall and spring semesters. There will be performance-based incentives abound for campus ambassadors that execute successful marketing campaigns.
            </desc>
            <deadline>2/20/2016</deadline>
          </item>
          <item class="inlinediv">
            <logo class="inlinediv imagecover"
                  style="background-image: url('<?php View::echof('photo'); ?>');">
            </logo>
            <headerinfo class="inlinediv">
              <name>Campus Ambassador</name>
              <company>SubLite LLC.</company>
            </headerinfo>
            <desc class="div">
              Help your classmates find internships and housing by advertising SubLite on campus! Ambassadors will lead their college's SubLite promotional activities by constructing their own team of assistants and completing tasks throughout the fall and spring semesters. There will be performance-based incentives abound for campus ambassadors that execute successful marketing campaigns.
            </desc>
            <deadline>2/20/2016</deadline>
          </item>
          <item class="inlinediv">
            <logo class="inlinediv imagecover"
                  style="background-image: url('<?php View::echof('photo'); ?>');">
            </logo>
            <headerinfo class="inlinediv">
              <name>Campus Ambassador</name>
              <company>SubLite LLC.</company>
            </headerinfo>
            <desc class="div">
              Help your classmates find internships and housing by advertising SubLite on campus! Ambassadors will lead their college's SubLite promotional activities by constructing their own team of assistants and completing tasks throughout the fall and spring semesters. There will be performance-based incentives abound for campus ambassadors that execute successful marketing campaigns.
            </desc>
            <deadline>2/20/2016</deadline>
          </item>
        </items>
      </section>
      <section class="div" id="section_sublets">
        <headline>Your Sublet Listings</headline>
        <items class="div sublets">
          <item class="inlinediv">
            <photo class="div imagecover"
                   style="background-image: url('https://sublite.s3.amazonaws.com/1457310490.jpeg');"></photo>
            <info class="div">
              <name>Sublease available asap at University Trails</name>
              <address>1 Prospect St, New Haven 06520</address>
              <price>$410</price><period>/month</period>
            </info>
          </item>
        </items>
      </section>
    </rightside>
  </div>
</panel>

<!--
<style>
  .studentpic {
    background: transparent no-repeat center center;
    background-size: cover;
    width: 100px;
    height: 100px;
    border-radius: 50px;
    margin: 0 auto;
  }
</style>
<panel>
  <div class="content">
    <headline>Personal Profile</headline>
    <div class="studentinfo">
      <div class="studentpic" style="background-image: url('<?php vecho('photo'); ?>');"></div>

      <subheadline><?php vecho('name'); ?></subheadline>
      <?php vecho('school'); ?> '<?php vecho('class'); ?>

      <br /><br />
      <div><?php echo vlinkto('<input type="button" value="Edit Profile" />', 'editprofile'); ?></div>
    </div>
  </div>
</panel>
-->