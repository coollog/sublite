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
        <button class="half">Yes</button><button class="gray half">No</button>
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
        </tab>
      </content>
    </panel>

    <panel class="tabframe" name="members">
      <content>
        <subtabs><membercount>0</membercount> Going</subtabs>
        <div class="members"></div>
      </content>
    </panel>
    <panel class="tabframe" name="description">
      <content>{description}</content>
    </panel>
    <panel class="tabframe" name="forum">
      <content>
        <subtabs>
          <subtab type="recent" class="focus">Most Recent</subtab> | <subtab type="popular">Most Popular</subtab>
        </subtabs>
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
  </viewtemplate>
  </meetupview>
</viewtemplate>