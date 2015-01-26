<?php
	require_once('header.php');
	
	require_once('htmlheader.php');
	require_once('panelcss.php');
?>

<?php $page = 'Team'; require('menu.php'); ?>
<div class="panel" style="background: #fc9a50; color: #fff; padding: 2em 0; margin-bottom: -2em;">
	<div style="font-size: 1.4em; font-weight: 600;">The Team</div>
</div>
<style>
	.content .pic {
		display: inline-block;
		background: #fff no-repeat top left;
		background-size: contain;
		width: 100%;
		height: 25em;
	}
	.content .bio {
		width: 60%;
		display: inline-block;
		padding: 2em;
		float: right;
	}
	.bio bigger {
		display: block;
		margin-bottom: 1em;
	}
	compress {
		font-size: 0.9em;
	}
	compresser {
		font-size: 0.9em;
		line-height: 1.5em;
		display: block;
	}
</style>
<div class="panel" style="text-align: left; background: #fc9a50; color: #a14233;">
	<div class="content">
		<div class="pic" style="background-image: url('gfx/alisa.jpg');">
			<div class="bio">
				<bigger>Alisa Melekhina</bigger>
				
				Co-Founder, University of Pennsylvania Law School
				<br /><br />
				<compresser>Alisa graduated with her Juris Doctor from the University of Pennsylvania Law School in May 2014 and also completed a Certificate in Business Economics and Public Policy at Wharton Business School. She is currently working at a corporate law firm in NYC in the fields of intellectual property and white-collar litigation.
				<br />
				Alisa worked as an intern in the legal department of FOX Entertainment in Los Angeles in the summer of 2013 and interned for a judge in DC over the summer of 2012. After optimistically thinking that the internship search process was over, she was rudely awakened by a newfound realization: finding summer housing was a pain. For months it was the same old story: getting directed to the same sites by both the university’s career service and HR departments. She decided that it was time to make the summer housing search much easier. And so SubLite was born.</compresser>
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div>
	</div>
	<div class="content" style="margin-top: 4em;">
		<div class="pic" style="background-image: url('gfx/yuanling.jpg');">
			<div class="bio">
				<bigger>Yuanling Yuan</bigger>
				
				Co-Founder, Yale University
				<br /><br />
				<compresser>Yuanling is a junior at Yale University majoring in Economics and has always been a passionate and dedicated entrepreneur. In 2009, she founded Chess in the Library, a non-profit organization that runs chess programs in over 40 public libraries across North America. During the past two summers, she first-handedly experienced the difficulties of securing a rewarding summer internship followed by a verified and comfortable sublet. Along with Alisa, a dedicated friend from international chess tournaments, Yuanling co-founded SubLite in February 2014 to provide university students a one-stop shop for the complete summer experience.  She is excited to bring her entrepreneurial expertise to the team and revolutionize the summer internship and housing search process for university students across the country.</compresser>
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div>
	</div>
	<div class="content" style="margin-top: 4em;">
		<div class="pic" style="background-image: url('gfx/qingyang.jpg');">
			<div class="bio">
				<bigger>Qingyang Chen</bigger>
				
				CTO, Yale University
				<br /><br />
				<compresser>Qingyang, better known as Q, is a sophomore at Yale University majoring in Computer Science. Q joined the team as the technical co-founder and led the development of the website. His background in programming started with video games, and gradually expanded into robotics and web development. On the web, Q goes by the username &ldquo;coollog&rdquo;, and is best known for creating the online multiplayer video game Scandux, as well as other contributions to the indie game community. Q has also co-founded The Boola, a social news website, and enjoys producing videos in his free time.</compresser>
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div>
	</div>
	<div class="content" style="margin-top: 4em;">
		<div class="pic" style="background-image: url('gfx/shirley.jpg');">
			<div class="bio">
				<bigger>Shirley Guo</bigger>
				
				CMO, Yale University
				<br /><br />
				<compresser>Shirley is a senior at Yale University majoring in Psychology and Economics. Through her various experiences in telecommunications, Asian American activism and consulting, she has developed a passion for both marketing and strategy. A series of unrelated events &mdash; namely, a Master&rsquo;s Tea (speaker event) and a residential college mentorship program &mdash; led Shirley to Yuanling and Alisa. At the time, Shirley just so happened to be searching for summer housing (and was having a not-so-great-experience), so she was extremely intrigued by what SubLite had to offer. She joined the SubLite team and is pumped to be driving marketing initiatives for SubLite! In her spare time, Shirley enjoys listening to R&amp;B, playing squash, eating tomatoes, and reading science fiction.</compresser>
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div>
	</div>
	<div class="content" style="margin-top: 4em;">
		<div class="pic" style="background-image: url('gfx/michelle.jpg');">
			<div class="bio">
				<bigger>Michelle Chan</bigger>
				
				Designer, Yale University
				<br /><br />
				<compresser>Michelle Chan is a junior at Yale University as an exchange student. She is originally from the University of Hong Kong, majoring in International Business &amp; Global Management and Japanese. At Yale, she enjoys great freedom in taking art and film classes such as graphic design, photography, and documentary filmmaking. She is also a staff photographer for Yale Daily News and a photographer and layout designer for Yale Entrepreneur Magazine. In Hong Kong, Michelle worked for a start-up called Launchpilots and started the Entrepreneurship Apprentice Program for university students. During her free time, she loves taking photos, hiking, and stargazing.</compresser>
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div>
	</div>
	<div class="content" style="margin-top: 4em;">
		<div class="pic" style="background-image: url('gfx/tony.jpg');">
			<div class="bio">
				<bigger>Tony Jiang</bigger>
				
				Developer, Yale University
				<br /><br />
				<compresser>Tony Jiang is a freshman at Yale University currently studying premed and majoring in computer science. He is an avid competitive programmer, competing in contests such as USACO and ACM-ICPC. In the past couple of summers, he has worked as a researcher and programmer in a bioinformatics lab. He is a member of the Yale Ballroom Dance Team and enjoys eating at Chipotle in his free time.</compresser>
			</div>
			<div class="spacer" style="clear: both;"></div>
		</div>
	</div>
</div>

<?php require_once('htmlfooter.php'); 
      require_once('footer.php'); ?>
