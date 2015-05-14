<viewtemplate name="hub">
  <panel class="banner">
    <content>
      <name>New York City Area Hub</name>
    </content>
  </panel>

  <panel>
    <content>
      <button class="joinhub">Join Hub</button>
    </content>
  </panel>

  <panel class="tabs">
    <content class="nopadding">
      <tab for="forum" class="focus">
        Forum
      </tab><tab for="meetups">
        Meet-Ups
      </tab><tab for="members">
        Members (<membercount>0</membercount>)
      </tab><tab for="createmeetup" style="display: none;">
        Create Meet-Up
      </tab>
    </content>
  </panel>

  <panel class="tabframe" name="forum">
    <content>
      <subtabs>
        <subtab type="recent" class="focus">Most Recent</subtab> | <subtab type="popular">Most Popular</subtab>
      </subtabs>
      <div class="postsframe" type="recent"><div class="posts"></div></div>
      <div class="postsframe" type="popular"><div class="posts"></div></div>
    </content>
  </panel>
  <panel class="tabframe" name="meetups">
    <content>
      <button id="createmeetup">Create a Meet-Up</button>
      <br /><br />
      <div class="meetups"></div>
    </content>
  </panel>
  <panel class="tabframe" name="createmeetup">
    <content>
      <headline>Create a Meet-Up</headline>
      <form>
        <notice></notice>
        Title:
        <input type="text" name="title" />
        Start Date:
        <input class="datepicker" type="text" name="startdate" />
        Start Time:
        <input class="timepicker" type="time" name="starttime" />
        End Date:
        <input class="datepicker" type="text" name="enddate" />
        End Time:
        <input class="timepicker" type="time" name="endtime" />
        Location Name:
        <input type="text" name="locationname" />
        Location Address:
        <input type="text" name="locationaddress" />
        <notice></notice>
        <right><button>Create</button></right>
      </form>
    </content>
  </panel>
  <panel class="tabframe" name="members">
    <content>
      <subtabs><membercount>0</membercount> Members</subtabs>
      <div class="members"></div>
    </content>
  </panel>
</viewtemplate>