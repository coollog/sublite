<!--To add a new person to this team page:

(1) Look at how many current member boxes there are. The new box will be the next number (If there are 12 members, you will be making number 13)

(2) Go to <head> section, to second set <script> tags containing Javascript code, and add the script written below under the line denoted (2). Wherever it says "#picBox12", add the new number.


  $("#picBox12").click(function(){
      $(".memberbox").slideUp("slow");
        $("#box12").slideDown("slow");
        $(".picBox .overlay").css("opacity", "");
        $("#picBox12 .overlay").css("opacity", "1");
    });

(3) Go to <head> section, to <style> CSS section. Find line denoted (3), where picBox will be added. Change the picBox number to the new number, and change the url to url of new .jpg of new member.


  #picBox1{
    background:url(images/yuanlingyuan.jpg);
    background-size: 160px 160px;
  }

(4) Go to HTML Body section and find <div class="memberbox"> sections. Change the following:
  - id="box1" - change the number of the box to that of the new number.
  - Change "images/newmemberimg.jpg" into the jpg file of new member
  - Change "NewMemberName" to new member name
  - Change "NewMemberPosition" to "Member Position, School"
  - Change "NewMemberBio" to the new member's bio


  <div class="memberbox" id="box1">
    <div>
      <img class= "memberimage" src="images/newmemberimg.jpg" />
      <p>
        <span class="membername">NewMemberName</span>
        <br>
        <span class="memberposition">NewMemberPosition</span>
      </p>
      <p class="memberbio">
        NewMemberBio
      </p>
    </div>
      <div class="exit">
        <p>X</p>
      </div>
  </div>

(5) Go the HTML Body section and find <div class ="clear" id="teamBlock">.
Change the following of the code below:
  - id="picBox9" - change the number of the picBox to that of the new number
  - Change "NewMemberName" to the newest member's name
  - Change "PositionName" to the member's position
  - Change "SchoolName" to the member's school name (abbreviated)
Insert this code into one of two sections, one which is for the main team, and the other which is for summer interns.


  <div class="picBox" id="picBox9">
    <div class="overlay">
      <i class="fa fa-angle-double-up"></i>
      <p><span>NewMemberName</span>
        <br>
        PositionName, SchoolName
      </p>
    </div>
  </div>

-->
<head>

<!-- JQuery script tags -->

<!---------------------------------------------------------------------->
<!--JQuery script - add or take away if adding or removing new members-->
<!---------------------------------------------------------------------->

<!-- Font Awesome link to access double up arrow icon for member overlay hover-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<!--CSS styles-->
<style>

/*.panel and .content should be already built into the website, and included here as I have been coding without the PHP server*/

  .panel {
      display: block;
      width: 100%;
      padding: 20px 0;
      background-color: #FFFFFF;
    }

    .content {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 50px;
    }

    #teamBlock {
      padding-bottom: 10%
    }



  /*----------------------------------------------------------------------*/
  /*CSS that controls member profiles that appear when team member clicked*/
  /*----------------------------------------------------------------------*/

    .memberbox{
      display: none;

    }

    .membername {
      font-size: 2em;
      font-weight: bold;
  }

  .memberposition {
      font-weight: bold;
      color: #035d75;
  }

  .memberimage {
      height:18em;
      float: left;
      margin-right: 15px;
  }

  .memberbox p {
      line-height: 1.3em;
    }

  .memberbio {
      font-size: 0.9em;
  }

  /*-------------------------------------------------*/
  /*CSS for the X that exits seleceted member profile*/
  /*-------------------------------------------------*/

  .exit{
    margin: 20px 0 20px 8.5%;
    padding: 10px 0 10px 5%;
    width: 700px;
    float: left;
    text-align: center;
    font-weight: bold;
    border-top: #e1e1e1 1px solid;
    border-bottom: #e1e1e1 1px solid;
    display:block;
  }

  .exit p{
    margin: 0;
  }

  .exit:hover{
    background-color: #e1e1e1;
  }


  /*--------------------------------------------------------*/
  /*CSS for the team block (grid of clickable member photos)*/
  /*--------------------------------------------------------*/

  .clear{
    clear: both;
  }

  #teamBlock{
      float: center;
      height: 600px;
      width: 700px;
    }

  .picBox{
      margin: 7px 7px;
      float: left;
      height: 160px;
    width: 160px;
    }

  #picBoxTeam{
    background-color: #FFD800;
    height: 160px;
    width: 334px;
    margin: 7px 7px;
      float: left;
      text-align: center;
      font-size: 50px;
  }

  /*-----------------------------------*/
  /*(3)--------------------------------*/
  /*CSS for each individual picture box*/
  /*EDIT HERE TO ADD OR REMOVE MEMBERS-*/
  /*-----------------------------------*/



  /*-------------------------*/
  /*CSS for join black square*/
  /*-------------------------*/
  #join{
    background-color: black;
    color: #FFD800;
    text-align: center;
  }

  #join .fa{
    padding-top: 35px;
    font-size: 70px;
  }

  #join p{
    margin-top: 5px;
    color: white;
    font-size: 18px;
  }

  #join:hover{
    opacity: 0.3;
  }

  /*CSS for summer team square*/
  #summer{
    background-color: black;
    color: yellow;
    text-align: center;
  }

  #summer p{
    font-size: 30px;
    padding-top: 20px;
  }


  /*CSS for overlay over profile squares*/
  .overlay{
    background:rgba(0,0,0,.75);
        opacity:0;
        padding: 40px 0px 6px 0px;
        color: white;
        text-align: center;
        font-size: 50px;
        -webkit-transition: opacity .25s ease;
    -moz-transition: opacity .25s ease;
  }

  .overlay p {
    text-align: left;
    font-size: 10px;
    margin-left: 15px;
  }

  .overlay p span{
    color: #FFD800;
    font-size: 18px;
  }

  .picBox:hover .overlay {
    opacity: 1;
  }
</style>

</head>

<templates>
  <membertemplate>
    <div class="memberbox" id="box{index}">
      <div>
        <img class= "memberimage" src="{photo}"  />
        <p>
          <span class="membername">{name}</span>
          <br>
          <span class="memberposition">{position}</span>
        </p>
        <p class="memberbio">{bio}</p>
      </div>
        <div class="exit">
          <p>X</p>
        </div>
    </div>
  </membertemplate>
</templates>



<templates>
  <boxtemplate>
    <div class = "picBox" id="picBox{index}">
      <div class = "overlay">
        <i class = "fa fa-angle-double-up"></i>
        <p><span>{name}</span>
          <br>
          {shortPosition}
        </p>
      </div>
    </div>
  </boxtemplate>
</templates>


<panel>
    <div class="clear content" id="team"></div>
</panel>

<!--HTML FOR MEMBER PROFILE SQUARES-->
<!--(5) ADD NEW MEMBER SQUARES BELOW-->

<panel>

<div class ="clear content" id="teamBlock">

    <div id="picBoxTeam">
      <p style="margin-top: 22%">OUR TEAM</p>
    </div>

    <a id="boxes" href="#team">



      <!-------------------------------------------------->
      <!--(5) ADD NEW PROFILE SQUARES FOR MAIN TEAM HERE-->
      <!-------------------------------------------------->

    </a>

    <!--dead "join us link", insert some link to form to join Sublite-->
    <!-- link is to campus rep program. Better way to embed link? -->
    <a href="jobs/job?id=561576fcd83594905f7eb765">
    <div class="picBox" id="join">
      <i class="fa fa-plus"></i>
      <p>JOIN US NOW!</p>
    </div>
    </a>



      <!------------------------------------------------>
      <!--(5) ADD NEW PROFILE SQUARES FOR INTERNS HERE-->
      <!------------------------------------------------>


</div>
</panel>

<script>
  var memberInfo = [
    {
      photo: 'yuanling',
      name: 'Yuanling Yuan',
      position: 'Co-founder & Chief Executive Officer, Yale University',
      shortPosition: 'Co-founder &amp; CEO, Yale',
      bio: 'Yuanling is a senior at Yale University majoring in Economics and has always been a passionate and dedicated entrepreneur. In 2009, she founded Chess in the Library, a non-profit organization that runs chess programs in over 40 public libraries across North America. During the past two summers, she first-handedly experienced the difficulties of securing a rewarding summer internship followed by a verified and comfortable sublet. Along with Alisa, a dedicated friend from international chess tournaments, Yuanling co-founded SubLite in February 2014 to provide university students a one-stop shop for the complete summer experience. She is excited to bring her entrepreneurial expertise to the team and revolutionize the summer internship and housing search process for university students across the country.'
    },
    {
      photo: 'alisa',
      name: 'Alisa Melekhina',
      position: 'Co-founder, University of Pennsylvania Law School',
      shortPosition: 'Co-founder, Penn',
      bio: 'Alisa graduated with her Juris Doctor from the University of Pennsylvania Law School in May 2014 and also completed a Certificate in Business Economics and Public Policy at Wharton Business School. She is currently working at a corporate law firm in NYC in the fields of intellectual property and white-collar litigation. Alisa worked as an intern in the legal department of FOX Entertainment in Los Angeles in the summer of 2013 and interned for a judge in DC over the summer of 2012. After optimistically thinking that the internship search process was over, she was rudely awakened by a newfound realization: finding summer housing was a pain. For months it was the same old story: getting directed to the same sites by both the university’s career service and HR departments. She decided that it was time to make the summer housing search much easier. And so SubLite was born.'
    },
    {
      photo: 'qingyang',
      name: 'Qingyang Chen',
      position: 'Chief Technology Officer, Yale University',
      shortPosition: 'CTO, Yale',
      bio: 'Qingyang, better known as Q, is a junior at Yale University majoring in Computer Science. Q joined the team as the technical co-founder and led the development of the website. His background in programming started with video games, and gradually expanded into robotics and web development. On the web, Q goes by the username &ldquo;coollog&rdquo;, and is best known for creating the online multiplayer video game Scandux, as well as other contributions to the indie game community. Q has also co-founded The Boola, a social news website, and enjoys producing videos in his free time.'
    },
    {
      photo: 'shirley',
      name: 'Shirley Guo',
      position: 'Chief Marketing Officer, Yale University',
      shortPosition: 'CMO, Yale',
      bio: 'Shirley graduated from Yale University in May 2015 majoring in Psychology and Economics. Through her various experiences in telecommunications, Asian American activism and consulting, she has developed a passion for both marketing and strategy. A series of unrelated events &mdash; namely, a Master&rsquo;s Tea (speaker event) and a residential college mentorship program &mdash; led Shirley to Yuanling and Alisa. At the time, Shirley just so happened to be searching for summer housing (and was having a not-so-great-experience), so she was extremely intrigued by what SubLite had to offer. She joined the SubLite team and is pumped to be driving marketing initiatives for SubLite! In her spare time, Shirley enjoys listening to R&amp;B, playing squash, eating tomatoes, and reading science fiction.',
    },
    {
      photo: 'tony',
      name: 'Tony Jiang',
      position: 'Vice-President of Engineering, Yale University',
      shortPosition: 'VP of Engineering, Yale',
      bio: 'Tony Jiang is a sophomore at Yale University currently studying premed and majoring in computer science. He is an avid competitive programmer, competing in contests such as USACO and ACM-ICPC. In the past couple of summers, he has worked as a researcher and programmer in a bioinformatics lab. He is a member of the Yale Ballroom Dance Team and enjoys eating at Chipotle in his free time.',
    },
    {
      photo: 'michelle',
      name: 'Michelle Chan',
      position: 'Chief Designer, Yale University',
      shortPosition: 'Designer, Yale',
      bio: 'Michelle Chan is a senior at Hong Kong University majoring in International Business &amp; Global Management and Japanese. She was an exchange student at Yale for her junior year. At Yale, she enjoyed great freedom in taking art and film classes such as graphic design, photography, and documentary filmmaking. She was also a staff photographer for Yale Daily News and a photographer and layout designer for the Yale Entrepreneur Magazine. In Hong Kong, Michelle worked for a start-up called Launchpilots and started the Entrepreneurship Apprentice Program for university students. During her free time, she loves taking photos, hiking, and stargazing.',
    },
    {
      photo: 'julie',
      name: 'Julie Slama',
      position: 'Marketing Director, Yale University',
      shortPosition: 'Marketing, Yale',
      bio: 'Julie Slama is a sophomore at Yale University, majoring in Global Affairs. After landing her first internship, she scoured Craigslist and Facebook to find available sublets, eventually leading her to SubLite. She is also Outreach Manager for the Yale Daily News Business Team,  a member of the LEAD Institute and the William F. Buckley, Jr. Program, and works as a lifeguard at the Yale University Pool. In her free time, she loves training for marathons, fishing, and camping with her family back home in Nebraska.',
    },
    {
      photo: 'alex',
      name: 'Alex Croxford',
      position: 'Business Development Director, Yale University',
      shortPosition: 'Business Development, Yale',
      bio: 'Alex Croxford is a sophomore at Yale University and currently planning to major in Electrical Engineering and Computer Science. He loves the outdoors and is a member Branford Sustainability and BSA Venturing Crew. Over the past summers, he has enjoyed working on his family’s farm in Illinois. In his free time he loves camping, hiking, and spending time with family and friends.',
    },
    {
      photo: 'dean',
      name: 'Dean Li',
      position: 'Recruitment Director, Yale University',
      shortPosition: 'Recruitment, Yale',
      bio: 'Dean is a freshman at Yale University interested in Economics and Computer Science. He found Sublite in the craziness of the first month of college, and relating to the company’s purpose because of his summer internship program, he joined and has not looked back. He brings to the company a deep love of food and little to no diversity. Outside of Sublite, he plays basketball and cello, is an avid Bulls fan, and is involved in the Asian-American cultural center at Yale.',
    },
    {
      photo: 'nicolas',
      name: 'Nicholas Jimenez',
      position: 'Communications Director, Yale University',
      shortPosition: 'Communications, Yale',
      bio: 'Nicolas Jimenez is a freshman at Yale University from Bogota, Colombia, who is currently studying a wide range of subjects from Computer Science to French Literature and Philosophy. He is an ardent traveler, and is fortunate to have studied and done service internationally. Nicolas is a member of the Yale Climbing Team and the Yale Ski Team, writes for an international publication, and is proud captain of his college’s Intramural Ping Pong Team. In his free time, he enjoys photography, hiking, and reading about religion.',
    },
    {
      photo: 'eric',
      name: 'Eric Yu',
      position: 'Software Engineer, Yale University',
      shortPosition: 'Software, Yale',
      bio: 'Eric Yu is a freshman at Yale University currently exploring the fields of computer science and medicine. His background is mainly in web development, but he is always open to new technologies and is an avid learner of all things new. Besides programming Eric is passionate about various things, from studying Japanese and Japanese History to the art of eating Hot Cheetos with chopsticks. At Yale, he is also a member of the Ballroom Dancing Team and the Elmseed Enterprise Fund, and manages the Yale Politic’s website as its Director of Technology.',
    },
    {
      photo: 'edward',
      name: 'Edward She',
      position: 'Software Engineer, Yale University',
      shortPosition: 'Software, Yale',
      bio: 'Edward She is a sophomore at Yale University studying Computer Science and Economics, although he is interested in practically anything STEM-related. In particular, he enjoys math, problem solving, and puzzles of all types. Edward loves coding because he views it as a combination of all three of these things. Outside of academic pursuits, Edward especially enjoys food and, in particular, searching for free food. His love for food has partially led him to become actively involved in the Chinese American Students Association, where he organizes events with free food. He is also involved with Yale University Diversified Investments and YHack.',
    }
  ];

  $(function () {
    (function setupMembers() {
      teamHTML = '';

      memberInfo.forEach(function (member, index) {
        var photo = '<?php echo $GLOBALS['dirpre']; ?>assets/gfx/headshots/' + member.photo + '.jpg';
        var name = member.name;
        var position = member.position;
        var bio = member.bio;
        teamHTML += useTemplate('membertemplate', {
          index: index + 1,
          photo: photo,
          name: name,
          position: position,
          bio: bio
        });
      });

      $('#team').html(teamHTML);
    })();

    (function setupBoxes() {
      boxesHTML = '';
      memberInfo.forEach(function (member, index) {
        var name = member.name;
        var shortPosition = member.shortPosition;
        boxesHTML += useTemplate('boxtemplate', {
          index: index + 1,
          name: name,
          shortPosition: shortPosition
        });

      });

      $('#boxes').html(boxesHTML);

      //add background images to each box and set size
      memberInfo.forEach(function (member, index) {
        console.log(document.getElementById('picBox'+(index+1)));
        var box = document.getElementById('picBox'+(index+1));
        box.style.backgroundImage =
        'url(<?php echo $GLOBALS['dirpre']; ?>assets/gfx/headshots/'
          + member.photo + '.jpg)';
        box.style.backgroundSize = "160px 160px";
      });

    })();

    (function setupBoxAnimation () {

      //Have to make another function to avoid closure
      function createClickForBox(i) {
        $("#picBox"+i).click(function(){
          $(".memberbox").slideUp("slow");
          $('#box'+i).slideDown("slow");
          $('.picBox .overlay').css("opacity","");
          $('#picBox'+i+' .overlay').css("opacity","1");
        });
      }

      var numPicBoxes = memberInfo.length;
      for (var i = 1;i < numPicBoxes + 1; i++) {
        //call another function, using basic for loop with i as counter won't
        //work
        createClickForBox(i);
      }

      $(".exit").click(function(){
        $(".memberbox").slideUp("slow");
        $(".picBox .overlay").css("opacity", "");
      });

      //script from https://css-tricks.com/snippets/jquery/smooth-scrolling/
      //allows smooth scrolling
      $('a[href*=#]:not([href=#])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
          || location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1)
                +']');
                 if (target.length) {
                   $('html,body').animate({
                       scrollTop: target.offset().top
                  }, 1000);
                  return false;
              }
          }
      });
    })();



  });

</script>
