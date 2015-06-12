<viewtemplate name="meetup">
  <meetupview for="{id}">
    <panel class="banner" style="background-image: url('{banner}');"></panel>

    <panel class="details">
      <content>
        <name>{name}</name>
        <hub><a>{hub}</a></hub>
        <table class="info">
          <tr>
            <td class="l" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/calendar.png');"></td>
            <td>
              <datetime>{datetime}</datetime>
            </td>
          </tr>
          <tr>
            <td class="l" style="background-image: url('<?php echo $GLOBALS['dirpre']; ?>../app/assets/gfx/hubs/place.png');"></td>
            <td>
              <place>{place}</place>
            </td>
          </tr>
          <tr>
            <td class="l"><pic style="background-image: url('{hostpic}');"></pic></td>
            <td>
              Hosted by {host}
            </td>
          </tr>
        </table>
      </content>
    </panel>

    <panel class="goingornot">
      <content>
        <areyou>Are you going?</areyou>
        <button class="half" id="going">Yes</button>
        <button class="gray half" id="notgoing">No</button>
      </content>
    </panel>

    <panel class="tabs">
      <content class="nopadding">
        <tab for="members" class="focus">
          Going (<membercount>0</membercount>)
        </tab><tab for="description">
          Description
        </tab><tab for="forum">
          Comments
        </tab><tab for="editmeetup">
          Edit<!-- maybe have this be icon? -->
        </tab>
      </content>
    </panel>

    <panel class="tabframe" name="members">
      <content>
        <div id="leavemeetupdiv">
          <button id="leavemeetup" class="smallbutton gray">Not Going Anymore</button>
          <br /><br />
        </div>
        <subtabs><membercount>0</membercount> Going</subtabs>
        <div class="members"></div>
      </content>
    </panel>
    <panel class="tabframe" name="description">
      <content>{description}</content>
    </panel>
    <panel class="tabframe" name="forum">
      <content>
        <!-- <subtabs>
          <subtab type="recent" class="focus">Most Recent</subtab> | <subtab type="popular">Most Popular</subtab>
        </subtabs> -->
        <div class="postsframe" type="recent"><div class="posts"></div></div>
        <div class="postsframe" type="popular"><div class="posts"></div></div>
        <div class="thread" for="">
          <div class="reply">
            Write your post:
            <form>
              <textarea name="text"></textarea>
              <right><button>Share</button></right>
            </form>
          </div>
        </div>
      </content>
    </panel>
    <panel class="tabframe" name="editmeetup">
      <content>
        <headline>Edit Meet-Up</headline>
        <form>
          <div class="error"></div>
          <input type="hidden" name="id" value="{id}" />
          Title:
          <input type="text" name="title" value="{name}" />
          Start:
          <input type="text" name="starttime" value="{starttime}" />
          End:
          <input type="text" name="endtime" value="{endtime}" />
          Location Name:
          <input type="text" name="locationname" value="{locationname}" />
          Location Address:
          <input type="text" name="address" value="{address}" />
          Description:
          <textarea name="description">{description}</textarea>
          Upload a banner:
          <?php
            vpartial('s3single', array(
              's3name' => 'banner',
              's3title' => 'What would you like your banner image to be?*',
              's3link' => '{banner}'
            ));
          ?>
          <div class="error"></div>
          <right><button>Edit</button></right>
          <br />
          <right><button id="deletemeetup">Delete Meet-Up</button></right>
        </form>
      </content>
    </panel>
    <script>
      if ({iscreator}) {
        $('.tabframe[name=editmeetup]').show();
      }
    </script>
  </viewtemplate>
  </meetupview>
</viewtemplate>