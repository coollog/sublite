<style>
  #dashboard {
    background: #fbfaf6;
  }
  #dashboard .content > * > * {
    box-shadow: 0px 0px 2px #ccc;
  }
  leftside {
    width: 25%;
    background: red;
  }
    profilelinks, messages {
      width: 100%;
      margin-bottom: 1em;
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
      background: #654321;
    }
  rightside {
    float: right;
    width: 70%;
    background: green;
  }
  .clearfix:after {
    content: " ";
    display: block;
    clear: both;
  }
    section {
      width: 100%;
      height: 200px;
      margin-bottom: 1em;
      background: #abcdef;
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
        asdfasd
      </messages>
    </leftside>
    <rightside class="inlinediv">
      <section class="div">
        asdf
      </section>
      <section class="div">
        asdf
      </section>
      <section class="div">
        asdf
      </section>
    </rightside>
  </div>
</panel>

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