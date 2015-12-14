<panel class="form">
  <div class="content">
    <headline>Register as a Student!</headline>
    <subheadline><?php vecho('email'); ?></subheadline>
    <form method="post">
      <?php vnotice(); ?>
      
      <div class="form-slider"><label for="name">Full Name</label><input type="text" id="name" name="name" value="<?php vecho('name'); ?>" maxlength="100" required /></div>

      <div class="form-slider"><label for="pass">Password (6 chars min)</label><input type="password" id="pass" name="pass" required pattern=".{6,}" /></div>
      <div class="form-slider"><label for="pass2">Confirm Password</label><input type="password" id="pass2" name="pass2" required pattern=".{6,}" /></div>
        
      <p style="text-align:left;margin-bottom:0px">Are you an undergraduate or graduate student? If you have graduated, select your most recent degree.</p>
      <input class="undergraduate" onchange="educationValueChanged()" type="radio" name="education" id="undergraduate" value="undergraduate" checked> Undergraduate
      <input class="graduate" onchange="educationValueChanged()" type="radio" name="education" id="graduate" value="graduate"> Graduate

      <div class="undergraduateShow">
        <p style="text-align:left;margin-bottom:0px">What degree are you pursuing or have already attained?</p>
        <select name="undergraduateDegree" id="degreeChooser" type="singleselect">
          <option value="Bachelor of Arts">Bachelor of Arts</option>
          <option value="Bachelor of Science">Bachelor of Science</option>
          <option value="Associate Degree">Associate Degree</option>
          <option value="Other">Other</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What year are you?</p>
        <select name="undergraduateYear" id="yearChooser" type="singleselect">
          <option value="Freshman">Freshman</option>
          <option value="Sophomore">Sophomore</option>
          <option value="Junior">Junior</option>
          <option value="Senior">Senior</option>
          <option value="I have already graduated">I have already graduated</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What is the month and year of your (projected) graduation?</p>
        <select name="undergraduateGraduationMonth" id="graduationMonthChooser" type="singleselect">
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        <select name="undergraduateGraduationYear" id="grduationYearChooser" type="singleselect">
          <option value="2000">2000</option>
          <option value="2001">2001</option>
          <option value="2002">2002</option>
          <option value="2003">2003</option>
          <option value="2004">2004</option>
          <option value="2005">2005</option>
          <option value="2006">2006</option>
          <option value="2007">2007</option>
          <option value="2008">2008</option>
          <option value="2009">2009</option>
          <option value="2010">2010</option>
          <option value="2011">2011</option>
          <option value="2012">2012</option>
          <option value="2013">2013</option>
          <option value="2014">2014</option>
          <option value="2015">2015</option>
          <option value="2016">2016</option>
          <option value="2017">2017</option>
          <option value="2018">2018</option>
          <option value="2019">2019</option>
          <option value="2020">2020</option>
          <option value="2021">2021</option>
          <option value="2022">2022</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
          <option value="2025">2025</option>
        </select>
      </div>

      <div class="graduateShow" style="display:none">
        What degree are you pursuing or have already attained?
        <select name="graduateDegree" id="degreeChooser" type="singleselect">
          <option value="Masters (M.A)">Masters (M.A)</option>
          <option value="Masters (M.S)">Masters (M.S)</option>
          <option value="PhD">PhD</option>
          <option value="Law (J.D)">Law (J.D)</option>
          <option value="Doctor (M.D)">Doctor (M.D)</option>
          <option value="Business (M.B.A)">Business (M.B.A)</option>
          <option value="Other">Other</option>
        </select>

        What year are you in your program?
        <select name="graduateYear" id="yearChooser" type="singleselect">
          <option value="First year">First year</option>
          <option value="Second year">Second year</option>
          <option value="Third year">Third year</option>
          <option value="Fourth year">Fourth year</option>
          <option value="Fifth year">Fifth year</option>
          <option value="Sixth year">Sixth year</option>
          <option value="Seventh year">Seventh year</option>
          <option value="Eighth year">Eighth year</option>
          <option value="Ninth year">Ninth year</option>
          <option value="Tenth year">Tenth year</option>
        </select>

        What is the month and year of your (projected) graduation?
        <select name="graduateGraduationMonth" id="graduationMonthChooser" type="singleselect">
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        <select name="graduateGraduationYear" id="grduationYearChooser" type="singleselect">
          <option value="2000">2000</option>
          <option value="2001">2001</option>
          <option value="2002">2002</option>
          <option value="2003">2003</option>
          <option value="2004">2004</option>
          <option value="2005">2005</option>
          <option value="2006">2006</option>
          <option value="2007">2007</option>
          <option value="2008">2008</option>
          <option value="2009">2009</option>
          <option value="2010">2010</option>
          <option value="2011">2011</option>
          <option value="2012">2012</option>
          <option value="2013">2013</option>
          <option value="2014">2014</option>
          <option value="2015">2015</option>
          <option value="2016">2016</option>
          <option value="2017">2017</option>
          <option value="2018">2018</option>
          <option value="2019">2019</option>
          <option value="2020">2020</option>
          <option value="2021">2021</option>
          <option value="2022">2022</option>
          <option value="2023">2023</option>
          <option value="2024">2024</option>
          <option value="2025">2025</option>
        </select>
      </div>

      <!-- If you are an undergraduate student, enter your class year:
      <div class="form-slider"><label for="class">Class Year</label><input type="text" id="class" name="class" value="<?php vecho('class'); ?>" /></div>
      If you are a graduate student, enter your school:
      <div class="form-slider"><label for="school">(eg. Law School, Business School)</label><input type="text" id="name" name="school" value="<?php vecho('school'); ?>" /></div> -->

      <div class="form-slider"><label for="gender">Gender </label>
        <select id="gender" name="gender" required>
          <?php vecho('gender', '<option selected="selected">{var}</option>'); ?>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

      <?php 
        vpartial('s3single', array(
          's3name' => 'photo', 
          's3title' => 'Upload a profile picture *: ',
          's3link' => vget('photo')
        ));
      ?>

      <?php vnotice(); ?>

      <p style="text-align:left;margin-bottom:0px">What are you looking for? Please select all that apply. <br> Your answers to the following will help us find better opportunities for you.</p>
      <label for="looking">Internship</label><input class="internship" onchange="lookingForValueChanged()" type="checkbox" id="internship" name="internship" value="<?php vecho('internship'); ?>" />
      <label for="looking">Full-Time</label><input class="fulltime" onchange="lookingForValueChanged()" type="checkbox" id="fulltime" name="fulltime" value="<?php vecho('fulltime'); ?>" />
      <label for="looking">Housing</label><input class="housing" onchange="lookingForValueChanged()" type="checkbox" id="housing" name="housing" value="<?php vecho('housing'); ?>" />
      <br>

      <div class="jobShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">What industries are you interested in? Please select all that apply. (Hold Control to select multiple options.)</p>
        <select name="industryChooser[]" id="industryChooser" style="height:10em;" multiple>
          <option value="Accounting">Accounting</option>
          <option value="Airlines/Aviation">Airlines/Aviation</option>
          <option value="Alternative Dispute Resolution">Alternative Dispute Resolution</option>
          <option value="Alternative Medicine">Alternative Medicine</option>
          <option value="Animation">Animation</option>
          <option value="Apparel &amp; Fashion">Apparel &amp; Fashion</option>
          <option value="Architecture &amp; Planning">Architecture &amp; Planning</option>
          <option value="Arts and Crafts">Arts and Crafts</option>
          <option value="Automotive">Automotive</option>
          <option value="Aviation &amp; Aerospace">Aviation &amp; Aerospace</option>
          <option value="Banking">Banking</option>
          <option value="Biotechnology">Biotechnology</option>
          <option value="Broadcast Media">Broadcast Media</option>
          <option value="Building Materials">Building Materials</option>
          <option value="Business Supplies and Equipment">Business Supplies and Equipment</option>
          <option value="Capital Markets">Capital Markets</option>
          <option value="Chemicals">Chemicals</option>
          <option value="Civic &amp; Social Organization">Civic &amp; Social Organization</option>
          <option value="Civil Engineering">Civil Engineering</option>
          <option value="Commercial Real Estate">Commercial Real Estate</option>
          <option value="Computer &amp; Network Security">Computer &amp; Network Security</option>
          <option value="Computer Games">Computer Games</option>
          <option value="Computer Hardware">Computer Hardware</option>
          <option value="Computer Networking">Computer Networking</option>
          <option value="Computer Software">Computer Software</option>
          <option value="Construction">Construction</option>
          <option value="Consumer Electronics">Consumer Electronics</option>
          <option value="Consumer Goods">Consumer Goods</option>
          <option value="Consumer Services">Consumer Services</option>
          <option value="Cosmetics">Cosmetics</option>
          <option value="Dairy">Dairy</option>
          <option value="Defense &amp; Space">Defense &amp; Space</option>
          <option value="Design">Design</option>
          <option value="Education Management">Education Management</option>
          <option value="E-Learning">E-Learning</option>
          <option value="Electrical/Electronic Manufacturing">Electrical/Electronic Manufacturing</option>
          <option value="Entertainment">Entertainment</option>
          <option value="Environmental Services">Environmental Services</option>
          <option value="Events Services">Events Services</option>
          <option value="Executive Office">Executive Office</option>
          <option value="Facilities Services">Facilities Services</option>
          <option value="Farming">Farming</option>
          <option value="Financial Services">Financial Services</option>
          <option value="Fine Art">Fine Art</option>
          <option value="Fishery">Fishery</option>
          <option value="Food &amp; Beverages">Food &amp; Beverages</option>
          <option value="Food Production">Food Production</option>
          <option value="Fund-Raising">Fund-Raising</option>
          <option value="Furniture">Furniture</option>
          <option value="Gambling &amp; Casinos">Gambling &amp; Casinos</option>
          <option value="Glass, Ceramics &amp; Concrete">Glass, Ceramics &amp; Concrete</option>
          <option value="Government Administration">Government Administration</option>
          <option value="Government Relations">Government Relations</option>
          <option value="Graphic Design">Graphic Design</option>
          <option value="Health, Wellness and Fitness">Health, Wellness and Fitness</option>
          <option value="Higher Education">Higher Education</option>
          <option value="Hospital &amp; Health Care">Hospital &amp; Health Care</option>
          <option value="Hospitality">Hospitality</option>
          <option value="Human Resources">Human Resources</option>
          <option value="Import and Export">Import and Export</option>
          <option value="Individual &amp; Family Services">Individual &amp; Family Services</option>
          <option value="Industrial Automation">Industrial Automation</option>
          <option value="Information Services">Information Services</option>
          <option value="Information Technology and Services">Information Technology and Services</option>
          <option value="Insurance">Insurance</option>
          <option value="International Affairs">International Affairs</option>
          <option value="International Trade and Development">International Trade and Development</option>
          <option value="Internet">Internet</option>
          <option value="Investment Banking">Investment Banking</option>
          <option value="Investment Management">Investment Management</option>
          <option value="Judiciary">Judiciary</option>
          <option value="Law Enforcement">Law Enforcement</option>
          <option value="Law Practice">Law Practice</option>
          <option value="Legal Services">Legal Services</option>
          <option value="Legislative Office">Legislative Office</option>
          <option value="Leisure, Travel &amp; Tourism">Leisure, Travel &amp; Tourism</option>
          <option value="Libraries">Libraries</option>
          <option value="Logistics and Supply Chain">Logistics and Supply Chain</option>
          <option value="Luxury Goods &amp; Jewelry">Luxury Goods &amp; Jewelry</option>
          <option value="Machinery">Machinery</option>
          <option value="Management Consulting">Management Consulting</option>
          <option value="Maritime">Maritime</option>
          <option value="Marketing and Advertising">Marketing and Advertising</option>
          <option value="Market Research">Market Research</option>
          <option value="Mechanical or Industrial Engineering">Mechanical or Industrial Engineering</option>
          <option value="Media Production">Media Production</option>
          <option value="Medical Devices">Medical Devices</option>
          <option value="Medical Practice">Medical Practice</option>
          <option value="Mental Health Care">Mental Health Care</option>
          <option value="Military">Military</option>
          <option value="Mining &amp; Metals">Mining &amp; Metals</option>
          <option value="Motion Pictures and Film">Motion Pictures and Film</option>
          <option value="Museums and Institutions">Museums and Institutions</option>
          <option value="Music">Music</option>
          <option value="Nanotechnology">Nanotechnology</option>
          <option value="Newspapers">Newspapers</option>
          <option value="Nonprofit Organization Management">Nonprofit Organization Management</option>
          <option value="Oil &amp; Energy">Oil &amp; Energy</option>
          <option value="Online Media">Online Media</option>
          <option value="Outsourcing/Offshoring">Outsourcing/Offshoring</option>
          <option value="Package/Freight Delivery">Package/Freight Delivery</option>
          <option value="Packaging and Containers">Packaging and Containers</option>
          <option value="Paper &amp; Forest Products">Paper &amp; Forest Products</option>
          <option value="Performing Arts">Performing Arts</option>
          <option value="Pharmaceuticals">Pharmaceuticals</option>
          <option value="Philanthropy">Philanthropy</option>
          <option value="Photography">Photography</option>
          <option value="Plastics">Plastics</option>
          <option value="Political Organization">Political Organization</option>
          <option value="Primary/Secondary Education">Primary/Secondary Education</option>
          <option value="Printing">Printing</option>
          <option value="Professional Training &amp; Coaching">Professional Training &amp; Coaching</option>
          <option value="Program Development">Program Development</option>
          <option value="Public Policy">Public Policy</option>
          <option value="Public Relations and Communications">Public Relations and Communications</option>
          <option value="Public Safety">Public Safety</option>
          <option value="Publishing">Publishing</option>
          <option value="Railroad Manufacture">Railroad Manufacture</option>
          <option value="Ranching">Ranching</option>
          <option value="Real Estate">Real Estate</option>
          <option value="Recreational Facilities and Services">Recreational Facilities and Services</option>
          <option value="Religious Institutions">Religious Institutions</option>
          <option value="Renewables &amp; Environment">Renewables &amp; Environment</option>
          <option value="Research">Research</option>
          <option value="Restaurants">Restaurants</option>
          <option value="Retail">Retail</option>
          <option value="Security and Investigations">Security and Investigations</option>
          <option value="Semiconductors">Semiconductors</option>
          <option value="Shipbuilding">Shipbuilding</option>
          <option value="Sporting Goods">Sporting Goods</option>
          <option value="Sports">Sports</option>
          <option value="Staffing and Recruiting">Staffing and Recruiting</option>
          <option value="Supermarkets">Supermarkets</option>
          <option value="Telecommunications">Telecommunications</option>
          <option value="Textiles">Textiles</option>
          <option value="Think Tanks">Think Tanks</option>
          <option value="Tobacco">Tobacco</option>
          <option value="Translation and Localization">Translation and Localization</option>
          <option value="Transportation/Trucking/Railroad">Transportation/Trucking/Railroad</option>
          <option value="Utilities">Utilities</option>
          <option value="Venture Capital &amp; Private Equity">Venture Capital &amp; Private Equity</option>
          <option value="Veterinary">Veterinary</option>
          <option value="Warehousing">Warehousing</option>
          <option value="Wholesale">Wholesale</option>
          <option value="Wine and Spirits">Wine and Spirits</option>
          <option value="Wireless">Wireless</option>
          <option value="Writing and Editing">Writing and Editing</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">In which countries are you looking for an internship/program/job? Please select all that apply. (Hold Control to select multiple options.)</p>
        <select name="countryChooser[]" id="countryChooser" style="height:10em;" multiple>
          <option value="United States">United States</option>
          <option value="Afghanistan (‫افغانستان‬‎)">Afghanistan (‫افغانستان‬‎)</option>
          <option value="Åland Islands (Åland)">Åland Islands (Åland)</option>
          <option value="Albania (Shqipëri)">Albania (Shqipëri)</option>
          <option value="Algeria">Algeria</option>
          <option value="American Samoa">American Samoa</option>
          <option value="Andorra">Andorra</option>
          <option value="Angola">Angola</option>
          <option value="Anguilla">Anguilla</option>
          <option value="Antarctica">Antarctica</option>
          <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
          <option value="Argentina">Argentina</option>
          <option value="Armenia (Հայաստան)">Armenia (Հայաստան)</option>
          <option value="Aruba">Aruba</option>
          <option value="Ascension Island">Ascension Island</option>
          <option value="Australia">Australia</option>
          <option value="Austria (Österreich)">Austria (Österreich)</option>
          <option value="Azerbaijan (Azərbaycan)">Azerbaijan (Azərbaycan)</option>
          <option value="Bahamas">Bahamas</option>
          <option value="Bahrain (‫البحرين‬‎)">Bahrain (‫البحرين‬‎)</option>
          <option value="Bangladesh (বাংলাদেশ)">Bangladesh (বাংলাদেশ)</option>
          <option value="Barbados">Barbados</option>
          <option value="Belarus (Беларусь)">Belarus (Беларусь)</option>
          <option value="Belgium">Belgium</option>
          <option value="Belize">Belize</option>
          <option value="Benin (Bénin)">Benin (Bénin)</option>
          <option value="Bermuda">Bermuda</option>
          <option value="Bhutan (འབྲུག)">Bhutan (འབྲུག)</option>
          <option value="Bolivia">Bolivia</option>
          <option value="Bosnia &amp; Herzegovina (Босна и Херцеговина)">Bosnia &amp; Herzegovina (Босна и Херцеговина)</option>
          <option value="Botswana">Botswana</option>
          <option value="Bouvet Island">Bouvet Island</option>
          <option value="Brazil (Brasil)">Brazil (Brasil)</option>
          <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
          <option value="British Virgin Islands">British Virgin Islands</option>
          <option value="Brunei">Brunei</option>
          <option value="Bulgaria (България)">Bulgaria (България)</option>
          <option value="Burkina Faso">Burkina Faso</option>
          <option value="Burundi (Uburundi)">Burundi (Uburundi)</option>
          <option value="Cambodia (កម្ពុជា)">Cambodia (កម្ពុជា)</option>
          <option value="Cameroon (Cameroun)">Cameroon (Cameroun)</option>
          <option value="Canada">Canada</option>
          <option value="Canary Islands (islas Canarias)">Canary Islands (islas Canarias)</option>
          <option value="Cape Verde (Kabu Verdi)">Cape Verde (Kabu Verdi)</option>
          <option value="Caribbean Netherlands">Caribbean Netherlands</option>
          <option value="Cayman Islands">Cayman Islands</option>
          <option value="Central African Republic (République centrafricaine)">Central African Republic (République centrafricaine)</option>
          <option value="Ceuta &amp; Melilla (Ceuta y Melilla)">Ceuta &amp; Melilla (Ceuta y Melilla)</option>
          <option value="Chad (Tchad)">Chad (Tchad)</option>
          <option value="Chile">Chile</option>
          <option value="China (中国)">China (中国)</option>
          <option value="Christmas Island">Christmas Island</option>
          <option value="Clipperton Island">Clipperton Island</option>
          <option value="Cocos (Keeling) Islands (Kepulauan Cocos (Keeling))">Cocos (Keeling) Islands (Kepulauan Cocos (Keeling))</option>
          <option value="Colombia">Colombia</option>
          <option value="Comoros (‫جزر القمر‬‎)">Comoros (‫جزر القمر‬‎)</option>
          <option value="Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)">Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)</option>
          <option value="Congo (Republic) (Congo-Brazzaville)">Congo (Republic) (Congo-Brazzaville)</option>
          <option value="Cook Islands">Cook Islands</option>
          <option value="Costa Rica">Costa Rica</option>
          <option value="Côte d’Ivoire">Côte d’Ivoire</option>
          <option value="Croatia (Hrvatska)">Croatia (Hrvatska)</option>
          <option value="Cuba">Cuba</option>
          <option value="Curaçao">Curaçao</option>
          <option value="Cyprus (Κύπρος)">Cyprus (Κύπρος)</option>
          <option value="Czech Republic (Česká republika)">Czech Republic (Česká republika)</option>
          <option value="Denmark (Danmark)">Denmark (Danmark)</option>
          <option value="Diego Garcia">Diego Garcia</option>
          <option value="Djibouti">Djibouti</option>
          <option value="Dominica">Dominica</option>
          <option value="Dominican Republic (República Dominicana)">Dominican Republic (República Dominicana)</option>
          <option value="Ecuador">Ecuador</option>
          <option value="Egypt (‫مصر‬‎)">Egypt (‫مصر‬‎)</option>
          <option value="El Salvador">El Salvador</option>
          <option value="Equatorial Guinea (Guinea Ecuatorial)">Equatorial Guinea (Guinea Ecuatorial)</option>
          <option value="Eritrea">Eritrea</option>
          <option value="Estonia (Eesti)">Estonia (Eesti)</option>
          <option value="Ethiopia">Ethiopia</option>
          <option value="Falkland Islands (Islas Malvinas)">Falkland Islands (Islas Malvinas)</option>
          <option value="Faroe Islands (Føroyar)">Faroe Islands (Føroyar)</option>
          <option value="Fiji">Fiji</option>
          <option value="Finland (Suomi)">Finland (Suomi)</option>
          <option value="France">France</option>
          <option value="French Guiana (Guyane française)">French Guiana (Guyane française)</option>
          <option value="French Polynesia (Polynésie française)">French Polynesia (Polynésie française)</option>
          <option value="French Southern Territories (Terres australes françaises)">French Southern Territories (Terres australes françaises)</option>
          <option value="Gabon">Gabon</option>
          <option value="Gambia">Gambia</option>
          <option value="Georgia (საქართველო)">Georgia (საქართველო)</option>
          <option value="Germany (Deutschland)">Germany (Deutschland)</option>
          <option value="Ghana (Gaana)">Ghana (Gaana)</option>
          <option value="Gibraltar">Gibraltar</option>
          <option value="Greece (Ελλάδα)">Greece (Ελλάδα)</option>
          <option value="Greenland (Kalaallit Nunaat)">Greenland (Kalaallit Nunaat)</option>
          <option value="Grenada">Grenada</option>
          <option value="Guadeloupe">Guadeloupe</option>
          <option value="Guam">Guam</option>
          <option value="Guatemala">Guatemala</option>
          <option value="Guernsey">Guernsey</option>
          <option value="Guinea (Guinée)">Guinea (Guinée)</option>
          <option value="Guinea-Bissau (Guiné-Bissau)">Guinea-Bissau (Guiné-Bissau)</option>
          <option value="Guyana">Guyana</option>
          <option value="Haiti">Haiti</option>
          <option value="Heard &amp; McDonald Islands">Heard &amp; McDonald Islands</option>
          <option value="Honduras">Honduras</option>
          <option value="Hong Kong (香港)">Hong Kong (香港)</option>
          <option value="Hungary (Magyarország)">Hungary (Magyarország)</option>
          <option value="Iceland (Ísland)">Iceland (Ísland)</option>
          <option value="India (भारत)">India (भारत)</option>
          <option value="Indonesia">Indonesia</option>
          <option value="Iran (‫ایران‬‎)">Iran (‫ایران‬‎)</option>
          <option value="Iraq (‫العراق‬‎)">Iraq (‫العراق‬‎)</option>
          <option value="Ireland">Ireland</option>
          <option value="Isle of Man">Isle of Man</option>
          <option value="Israel (‫ישראל‬‎)">Israel (‫ישראל‬‎)</option>
          <option value="Italy (Italia)">Italy (Italia)</option>
          <option value="Jamaica">Jamaica</option>
          <option value="Japan (日本)">Japan (日本)</option>
          <option value="Jersey">Jersey</option>
          <option value="Jordan (‫الأردن‬‎)">Jordan (‫الأردن‬‎)</option>
          <option value="Kazakhstan (Казахстан)">Kazakhstan (Казахстан)</option>
          <option value="Kenya">Kenya</option>
          <option value="Kiribati">Kiribati</option>
          <option value="Kosovo (Kosovë)">Kosovo (Kosovë)</option>
          <option value="Kuwait (‫الكويت‬‎)">Kuwait (‫الكويت‬‎)</option>
          <option value="Kyrgyzstan (Кыргызстан)">Kyrgyzstan (Кыргызстан)</option>
          <option value="Laos (ລາວ)">Laos (ລາວ)</option>
          <option value="Latvia (Latvija)">Latvia (Latvija)</option>
          <option value="Lebanon (‫لبنان‬‎)">Lebanon (‫لبنان‬‎)</option>
          <option value="Lesotho">Lesotho</option>
          <option value="Liberia">Liberia</option>
          <option value="Libya (‫ليبيا‬‎)">Libya (‫ليبيا‬‎)</option>
          <option value="Liechtenstein">Liechtenstein</option>
          <option value="Lithuania (Lietuva)">Lithuania (Lietuva)</option>
          <option value="Luxembourg">Luxembourg</option>
          <option value="Macau (澳門)">Macau (澳門)</option>
          <option value="Macedonia (FYROM) (Македонија)">Macedonia (FYROM) (Македонија)</option>
          <option value="Madagascar (Madagasikara)">Madagascar (Madagasikara)</option>
          <option value="Malawi">Malawi</option>
          <option value="Malaysia">Malaysia</option>
          <option value="Maldives">Maldives</option>
          <option value="Mali">Mali</option>
          <option value="Malta">Malta</option>
          <option value="Marshall Islands">Marshall Islands</option>
          <option value="Martinique">Martinique</option>
          <option value="Mauritania (‫موريتانيا‬‎)">Mauritania (‫موريتانيا‬‎)</option>
          <option value="Mauritius (Moris)">Mauritius (Moris)</option>
          <option value="Mayotte">Mayotte</option>
          <option value="Mexico (México)">Mexico (México)</option>
          <option value="Micronesia">Micronesia</option>
          <option value="Moldova (Republica Moldova)">Moldova (Republica Moldova)</option>
          <option value="Monaco">Monaco</option>
          <option value="Mongolia (Монгол)">Mongolia (Монгол)</option>
          <option value="Montenegro (Crna Gora)">Montenegro (Crna Gora)</option>
          <option value="Montserrat">Montserrat</option>
          <option value="Morocco">Morocco</option>
          <option value="Mozambique (Moçambique)">Mozambique (Moçambique)</option>
          <option value="Myanmar (Burma) (မြန်မာ)">Myanmar (Burma) (မြန်မာ)</option>
          <option value="Namibia (Namibië)">Namibia (Namibië)</option>
          <option value="Nauru">Nauru</option>
          <option value="Nepal (नेपाल)">Nepal (नेपाल)</option>
          <option value="Netherlands (Nederland)">Netherlands (Nederland)</option>
          <option value="New Caledonia (Nouvelle-Calédonie)">New Caledonia (Nouvelle-Calédonie)</option>
          <option value="New Zealand">New Zealand</option>
          <option value="Nicaragua">Nicaragua</option>
          <option value="Niger (Nijar)">Niger (Nijar)</option>
          <option value="Nigeria">Nigeria</option>
          <option value="Niue">Niue</option>
          <option value="Norfolk Island">Norfolk Island</option>
          <option value="Northern Mariana Islands">Northern Mariana Islands</option>
          <option value="North Korea (조선민주주의인민공화국)">North Korea (조선민주주의인민공화국)</option>
          <option value="Norway (Norge)">Norway (Norge)</option>
          <option value="Oman (‫عُمان‬‎)">Oman (‫عُمان‬‎)</option>
          <option value="Pakistan (‫پاکستان‬‎)">Pakistan (‫پاکستان‬‎)</option>
          <option value="Palau">Palau</option>
          <option value="Palestine (‫فلسطين‬‎)">Palestine (‫فلسطين‬‎)</option>
          <option value="Panama (Panamá)">Panama (Panamá)</option>
          <option value="Papua New Guinea">Papua New Guinea</option>
          <option value="Paraguay">Paraguay</option>
          <option value="Peru (Perú)">Peru (Perú)</option>
          <option value="Philippines">Philippines</option>
          <option value="Pitcairn Islands">Pitcairn Islands</option>
          <option value="Poland (Polska)">Poland (Polska)</option>
          <option value="Portugal">Portugal</option>
          <option value="Puerto Rico">Puerto Rico</option>
          <option value="Qatar (‫قطر‬‎)">Qatar (‫قطر‬‎)</option>
          <option value="Réunion (La Réunion)">Réunion (La Réunion)</option>
          <option value="Romania (România)">Romania (România)</option>
          <option value="Russia (Россия)">Russia (Россия)</option>
          <option value="Rwanda">Rwanda</option>
          <option value="Samoa">Samoa</option>
          <option value="San Marino">San Marino</option>
          <option value="São Tomé &amp; Príncipe (São Tomé e Príncipe)">São Tomé &amp; Príncipe (São Tomé e Príncipe)</option>
          <option value="Saudi Arabia (‫المملكة العربية السعودية‬‎)">Saudi Arabia (‫المملكة العربية السعودية‬‎)</option>
          <option value="Senegal">Senegal</option>
          <option value="Serbia (Србија)">Serbia (Србија)</option>
          <option value="Seychelles">Seychelles</option>
          <option value="Sierra Leone">Sierra Leone</option>
          <option value="Singapore">Singapore</option>
          <option value="Sint Maarten">Sint Maarten</option>
          <option value="Slovakia (Slovensko)">Slovakia (Slovensko)</option>
          <option value="Slovenia (Slovenija)">Slovenia (Slovenija)</option>
          <option value="Solomon Islands">Solomon Islands</option>
          <option value="Somalia (Soomaaliya)">Somalia (Soomaaliya)</option>
          <option value="South Africa">South Africa</option>
          <option value="South Georgia &amp; South Sandwich Islands">South Georgia &amp; South Sandwich Islands</option>
          <option value="South Korea (대한민국)">South Korea (대한민국)</option>
          <option value="South Sudan (‫جنوب السودان‬‎)">South Sudan (‫جنوب السودان‬‎)</option>
          <option value="Spain (España)">Spain (España)</option>
          <option value="Sri Lanka (ශ්‍රී ලංකාව)">Sri Lanka (ශ්‍රී ලංකාව)</option>
          <option value="St. Barthélemy (Saint-Barthélemy)">St. Barthélemy (Saint-Barthélemy)</option>
          <option value="St. Helena">St. Helena</option>
          <option value="St. Kitts &amp; Nevis">St. Kitts &amp; Nevis</option>
          <option value="St. Lucia">St. Lucia</option>
          <option value="St. Martin (Saint-Martin)">St. Martin (Saint-Martin)</option>
          <option value="St. Pierre &amp; Miquelon (Saint-Pierre-et-Miquelon)">St. Pierre &amp; Miquelon (Saint-Pierre-et-Miquelon)</option>
          <option value="St. Vincent &amp; Grenadines">St. Vincent &amp; Grenadines</option>
          <option value="Sudan (‫السودان‬‎)">Sudan (‫السودان‬‎)</option>
          <option value="Suriname">Suriname</option>
          <option value="Svalbard &amp; Jan Mayen (Svalbard og Jan Mayen)">Svalbard &amp; Jan Mayen (Svalbard og Jan Mayen)</option>
          <option value="Swaziland">Swaziland</option>
          <option value="Sweden (Sverige)">Sweden (Sverige)</option>
          <option value="Switzerland (Schweiz)">Switzerland (Schweiz)</option>
          <option value="Syria (‫سوريا‬‎)">Syria (‫سوريا‬‎)</option>
          <option value="Taiwan (台灣)">Taiwan (台灣)</option>
          <option value="Tajikistan">Tajikistan</option>
          <option value="Tanzania">Tanzania</option>
          <option value="Thailand (ไทย)">Thailand (ไทย)</option>
          <option value="Timor-Leste">Timor-Leste</option>
          <option value="Togo">Togo</option>
          <option value="Tokelau">Tokelau</option>
          <option value="Tonga">Tonga</option>
          <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
          <option value="Tristan da Cunha">Tristan da Cunha</option>
          <option value="Tunisia">Tunisia</option>
          <option value="Turkey (Türkiye)">Turkey (Türkiye)</option>
          <option value="Turkmenistan">Turkmenistan</option>
          <option value="Turks &amp; Caicos Islands">Turks &amp; Caicos Islands</option>
          <option value="Tuvalu">Tuvalu</option>
          <option value="U.S. Outlying Islands">U.S. Outlying Islands</option>
          <option value="U.S. Virgin Islands">U.S. Virgin Islands</option>
          <option value="Uganda">Uganda</option>
          <option value="Ukraine (Україна)">Ukraine (Україна)</option>
          <option value="United Arab Emirates (‫الإمارات العربية المتحدة‬‎)">United Arab Emirates (‫الإمارات العربية المتحدة‬‎)</option>
          <option value="United Kingdom">United Kingdom</option>
          <option value="Uruguay">Uruguay</option>
          <option value="Uzbekistan (Oʻzbekiston)">Uzbekistan (Oʻzbekiston)</option>
          <option value="Vanuatu">Vanuatu</option>
          <option value="Vatican City (Città del Vaticano)">Vatican City (Città del Vaticano)</option>
          <option value="Venezuela">Venezuela</option>
          <option value="Vietnam (Việt Nam)">Vietnam (Việt Nam)</option>
          <option value="Wallis &amp; Futuna">Wallis &amp; Futuna</option>
          <option value="Western Sahara (‫الصحراء الغربية‬‎)">Western Sahara (‫الصحراء الغربية‬‎)</option>
          <option value="Yemen (‫اليمن‬‎)">Yemen (‫اليمن‬‎)</option>
          <option value="Zambia">Zambia</option>
          <option value="Zimbabwe">Zimbabwe</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">If you selected the United States in the previous question, in which states are you looking for an internship/program/job? Please select all that apply. (Hold Control to select multiple options.)</p>
        <select name="stateChooser[]" id="stateChooser" style="height:10em;" multiple>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="DC">District Of Columbia</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
        </select>
      </div>

      <div class="internshipShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">During which period of the year would you like to have an internship/program? Please select all that apply.</p>
        <label for="looking">Winter 2016</label><input type="checkbox" id="internshipWinter2016" name="internshipWinter2016" value="<?php vecho('internshipWinter2016'); ?>" />
        <label for="looking">Spring 2016</label><input type="checkbox" id="internshipSpring2016" name="internshipSpring2016" value="<?php vecho('internshipSpring2016'); ?>" />
        <label for="looking">Summer 2016</label><input type="checkbox" id="internshipSummer2016" name="internshipSummer2016" value="<?php vecho('internshipSummer2016'); ?>" />
        <label for="looking">Fall 2016</label><input type="checkbox" id="internshipFall2016" name="internshipFall2016" value="<?php vecho('internshipFall2016'); ?>" />
        <label for="looking">Winter 2017</label><input type="checkbox" id="internshipWinter2017" name="internshipWinter2017" value="<?php vecho('internshipWinter2017'); ?>" />
        <label for="looking">Spring 2017</label><input type="checkbox" id="internshipSpring2017" name="internshipSpring2017" value="<?php vecho('internshipSpring2017'); ?>" />
        <br>
      </div>

      <div class="fulltimeShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">When would you like to start your full-time job? Please select all the times during which you can start a job.</p>
        <label for="looking">Winter 2016</label><input type="checkbox" id="fulltimeWinter2016" name="fulltimeWinter2016" value="<?php vecho('fulltimeWinter2016'); ?>" />
        <label for="looking">Spring 2016</label><input type="checkbox" id="fulltimeSpring2016" name="fulltimeSpring2016" value="<?php vecho('fulltimeSpring2016'); ?>" />
        <label for="looking">Summer 2016</label><input type="checkbox" id="fulltimeSummer2016" name="fulltimeSummer2016" value="<?php vecho('fulltimeSummer2016'); ?>" />
        <label for="looking">Fall 2016</label><input type="checkbox" id="fulltimeFall2016" name="fulltimeFall2016" value="<?php vecho('fulltimeFall2016'); ?>" />
        <label for="looking">Winter 2017</label><input type="checkbox" id="fulltimeWinter2017" name="fulltimeWinter2017" value="<?php vecho('fulltimeWinter2017'); ?>" />
        <label for="looking">Spring 2017</label><input type="checkbox" id="fulltimeSpring2017" name="fulltimeSpring2017" value="<?php vecho('fulltimeSpring2017'); ?>" />
        <br>
      </div>

      <div class="housingShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">During which period of the year would you like to have housing (sublet)? Please select all that apply.</p>
        <label for="looking">Winter 2016</label><input type="checkbox" id="housingWinter2016" name="housingWinter2016" value="<?php vecho('housingWinter2016'); ?>" />
        <label for="looking">Spring 2016</label><input type="checkbox" id="housingSpring2016" name="housingSpring2016" value="<?php vecho('housingSpring2016'); ?>" />
        <label for="looking">Summer 2016</label><input type="checkbox" id="housingSummer2016" name="housingSummer2016" value="<?php vecho('housingSummer2016'); ?>" />
        <label for="looking">Fall 2016</label><input type="checkbox" id="housingFall2016" name="housingFall2016" value="<?php vecho('housingFall2016'); ?>" />
        <label for="looking">Winter 2017</label><input type="checkbox" id="housingWinter2017" name="housingWinter2017" value="<?php vecho('housingWinter2017'); ?>" />
        <label for="looking">Spring 2017</label><input type="checkbox" id="housingSpring2017" name="housingSpring2017" value="<?php vecho('housingSpring2017'); ?>" />
        <br>
      </div>

      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>

<script type="text/javascript">
function educationValueChanged()
{
  if($('.undergraduate').is(":checked"))
    $(".undergraduateShow").show();
  else
    $(".undergraduateShow").hide();

  if($('.graduate').is(":checked"))
    $(".graduateShow").show();
  else
    $(".graduateShow").hide();
}

function lookingForValueChanged()
{
    if($('.internship').is(":checked") || $('.fulltime').is(":checked"))
      $(".jobShow").show();
    else
      $(".jobShow").hide();

    if($('.internship').is(":checked"))   
      $(".internshipShow").show();
    else
      $(".internshipShow").hide();

    if($('.fulltime').is(":checked"))   
      $(".fulltimeShow").show();
    else
      $(".fulltimeShow").hide();

    if($('.housing').is(":checked"))   
      $(".housingShow").show();
    else
      $(".housingShow").hide();
}
</script>