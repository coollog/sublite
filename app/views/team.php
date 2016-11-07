<!-- Font Awesome link to access double up arrow icon for member overlay hover-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<style>
  #teamBlock {
    padding-bottom: 10%
  }
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

  .clear{
    clear: both;
  }
  #teamBlock{
    float: center;
    height: 900px;
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
  #summer{
    background-color: black;
    color: yellow;
    text-align: center;
  }
  #summer p{
    font-size: 30px;
    padding-top: 20px;
  }

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

<templates>
  <membertemplate>
    <div class="memberbox" id="box{index}">
      <div>
        <img class= "memberimage" photo="{photo}"  />
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

<panel style="padding: 20px 0;">
  <div class="clear content" id="team"></div>
</panel>

<panel>
  <div class ="clear content" id="teamBlock">
    <div id="picBoxTeam">
      <p style="margin-top: 22%">OUR TEAM</p>
    </div>

    <a id="boxes" href="#team"></a>

    <a href="jobs/job?id=561576fcd83594905f7eb765">
      <div class="picBox" id="join">
        <i class="fa fa-plus"></i>
        <p>JOIN US NOW!</p>
      </div>
    </a>
  </div>
</panel>

<script>
  var memberInfo = [
    {
      photo: 'qingyang',
      name: 'Qingyang Chen',
      position: 'Co-founder & Chief Executive Officer, Yale University',
      shortPosition: 'Co-founder &amp; CEO, Yale',
      bio: 'Qingyang, better known as Q, is a junior at Yale University majoring in Computer Science. Q joined the team as the technical co-founder and led the development of the website. His background in programming started with video games, and gradually expanded into robotics and web development. On the web, Q goes by the username &ldquo;coollog&rdquo;, and is best known for creating the online multiplayer video game Scandux, as well as other contributions to the indie game community. Q has also co-founded The Boola, a social news website, and enjoys producing videos in his free time.'
    },
    {
      photo: 'yuanling',
      name: 'Yuanling Yuan',
      position: 'Co-founder, Yale University',
      shortPosition: 'Co-founder, Yale',
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
      photo: 'alex',
      name: 'Alex Croxford',
      position: 'Business Development Director, Yale University',
      shortPosition: 'Business Development, Yale',
      bio: 'Alex Croxford is a sophomore at Yale University and currently planning to major in Electrical Engineering and Computer Science. He loves the outdoors and is a member Branford Sustainability and BSA Venturing Crew. Over the past summers, he has enjoyed working on his family’s farm in Illinois. In his free time he loves camping, hiking, and spending time with family and friends.',
    },
    {
      photo: 'eddie',
      name: 'Edward She',
      position: 'Software Engineer, Yale University',
      shortPosition: 'Software, Yale',
      bio: 'Edward She is a sophomore at Yale University studying Computer Science and Economics, although he is interested in practically anything STEM-related. In particular, he enjoys math, problem solving, and puzzles of all types. Edward loves coding because he views it as a combination of all three of these things. Outside of academic pursuits, Edward especially enjoys food and, in particular, searching for free food. His love for food has partially led him to become actively involved in the Chinese American Students Association, where he organizes events with free food. He is also involved with Yale University Diversified Investments and YHack.',
    },
    {
      photo: 'chris',
      name: 'Christopher Fu',
      position: 'Software Engineer, Yale University',
      shortPosition: 'Software, Yale',
      bio: 'Christopher Fu is a sophomore at Yale University studying computer science. He enjoys any sort of programming but is most experienced with iOS/Mac OS X and web development. He is also a member of the Yale Undergraduate Aerospace Association, where he is working on a team to launch a miniature satellite called a CubeSat into space. In his free time, Chris enjoys playing video games with friends, running, and messing around with technology.',
    },
  ];

  $(function () {

    function hideMemberBox() {
      $(".memberbox").slideUp("slow");
      $(".picBox .overlay").css("opacity", "");
    }

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
        var box = document.getElementById('picBox'+(index+1));
        box.style.backgroundImage =
        'url(<?php echo $GLOBALS['dirpre']; ?>assets/gfx/headshots/'
          + member.photo + '.jpg)';
        box.style.backgroundSize = "160px 160px";
      });

      $('#team img.memberimage').each(function (elem) {
        var src = $(this).attr('photo');
        $(this).attr('src', src);
      });
    })();

    (function setupBoxAnimation () {

      //Have to make another function to avoid closure
      function createClickForBox(i) {
        $("#picBox"+i).click(function(){
          if (this.getAttribute("current") == "current") {
            hideMemberBox();
            this.setAttribute("current","");
          } else {
            // set the old current one to nothing
            $("[current=current]").attr("current","");
            this.setAttribute("current","current");
            $(".memberbox").slideUp("slow");
            $('#box'+i).slideDown("slow");
            $('.picBox .overlay').css("opacity","");
            $('#picBox'+i+' .overlay').css("opacity","1");
          }
        });
      }

      var numPicBoxes = memberInfo.length;
      for (var i = 1;i < numPicBoxes + 1; i++) {
        //call another function, using basic for loop with i as counter won't
        //work
        createClickForBox(i);
      }

      $(".exit").click(function(){
        $("[current=current]").attr("current","");
        hideMemberBox();
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
