<panel class="form">
  <div class="content">
    <headline>Register as a Student!</headline>
    <subheadline><?php View::echof('email'); ?></subheadline>
    <form method="post">
      <?php View::notice(); ?>

      <div class="form-slider"><label for="name">Full Name*</label><input type="text" id="name" name="name" value="<?php View::echof('name'); ?>" maxlength="100" required /></div>

      <div class="form-slider"><label for="pass">Password (6 chars min)*</label><input type="password" id="pass" name="pass" required pattern=".{6,}" /></div>
      <div class="form-slider"><label for="pass2">Confirm Password*</label><input type="password" id="pass2" name="pass2" required pattern=".{6,}" /></div>

      <div class="form-slider"><label for="class">Class Year*</label><input type="text" id="class" name="class" value="<?php View::echof('class'); ?>" required /></div>
      <div class="form-slider"><label for="school">School*</label><input type="text" id="name" name="school" value="<?php View::echof('school'); ?>" required /></div>


      <p style="text-align:left;margin-bottom:0px;margin-top:0px">What is your gender?*</p>
      <select id="gender" name="gender" required>
        <?php View::echof('gender', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); ?>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>

      <p style="text-align:left;margin-bottom:0px">Are you an undergraduate or graduate student? If you have graduated, select your most recent degree.</p>
      <label><input class="undergraduate" onchange="educationValueChanged()" type="radio" name="education" id="undergraduate" value="undergraduate" checked> Undergraduate</label>
      <label><input class="graduate" onchange="educationValueChanged()" type="radio" name="education" id="graduate" value="graduate" <?php View::checked('education', 'graduate'); ?> > Graduate</label>

      <div class="undergraduateShow">
        <p style="text-align:left;margin-bottom:0px">What degree are you pursuing or have already attained?*</p>
        <select class="undergraduateChoosers" name="undergraduateDegree" id="uDegreeChooser" type="singleselect" required>
          <?php if(View::get('education') == 'undergraduate') View::echof('degree', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
          <option value="Bachelor of Arts">Bachelor of Arts</option>
          <option value="Bachelor of Science">Bachelor of Science</option>
          <option value="Associate Degree">Associate Degree</option>
          <option value="Other">Other</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What year are you?*</p>
        <select class="undergraduateChoosers" name="undergraduateYear" id="uYearChooser" type="singleselect" required>
          <?php if(View::get('education') == 'undergraduate') View::echof('year', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
          <option value="Freshman">Freshman</option>
          <option value="Sophomore">Sophomore</option>
          <option value="Junior">Junior</option>
          <option value="Senior">Senior</option>
          <option value="I have already graduated">I have already graduated</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What is the month and year of your (projected) graduation?*</p>
        <select class="undergraduateChoosers" name="undergraduateGraduationMonth" id="uGraduationMonthChooser" type="singleselect" required>
          <?php if(View::get('education') == 'undergraduate') View::echof('graduationMonth', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
          <option value="January">January</option>
          <option value="February">February</option>
          <option value="March">March</option>
          <option value="April">April</option>
          <option value="May">May</option>
          <option value="June">June</option>
          <option value="July">July</option>
          <option value="August">August</option>
          <option value="September">September</option>
          <option value="October">October</option>
          <option value="November">November</option>
          <option value="December">December</option>
        </select>
        <select class="undergraduateChoosers" name="undergraduateGraduationYear" id="uGraduationYearChooser" type="singleselect" required>
          <?php if(View::get('education') == 'undergraduate') View::echof('graduationYear', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
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
        <p style="text-align:left;margin-bottom:0px">What degree are you pursuing or have already attained?*</p>
        <select class="graduateChoosers" name="graduateDegree" id="gDegreeChooser" type="singleselect" required>
          <?php if(View::get('education') == 'graduate') View::echof('degree', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
          <option value="Masters (M.A)">Masters (M.A)</option>
          <option value="Masters (M.S)">Masters (M.S)</option>
          <option value="PhD">PhD</option>
          <option value="Law (J.D)">Law (J.D)</option>
          <option value="Doctor (M.D)">Doctor (M.D)</option>
          <option value="Business (M.B.A)">Business (M.B.A)</option>
          <option value="Other">Other</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What year are you in your program?*</p>
        <select class="graduateChoosers" name="graduateYear" id="gYearChooser" type="singleselect" required>
          <?php if(View::get('education') == 'graduate') View::echof('year', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
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

        <p style="text-align:left;margin-bottom:0px">What is the month and year of your (projected) graduation?*</p>
        <select class="graduateChoosers" name="graduateGraduationMonth" id="gGraduationMonthChooser" type="singleselect" required>
          <?php if(View::get('education') == 'graduate') View::echof('graduationMonth', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
          <option value="January">January</option>
          <option value="February">February</option>
          <option value="March">March</option>
          <option value="April">April</option>
          <option value="May">May</option>
          <option value="June">June</option>
          <option value="July">July</option>
          <option value="August">August</option>
          <option value="September">September</option>
          <option value="October">October</option>
          <option value="November">November</option>
          <option value="December">December</option>
        </select>
        <select class="graduateChoosers" name="graduateGraduationYear" id="gGraduationYearChooser" type="singleselect" required>
          <?php if(View::get('education') == 'graduate') View::echof('graduationYear', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); else echo '<option value="">Please Select...</option>'; ?>
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

      <p style="text-align:left;margin-bottom:0px">What are you looking for? Please select at least one.* <br> Your answers to the following will help us find better opportunities for you.</p>
      <label><input class="internship opportunities" data-related-item="internshipShow" onchange="lookingForValueChanged()" type="checkbox" id="internship" name="internship" value="<?php View::echof('internship'); ?>" <?php View::checked('lookingFor', 'internship'); ?> />Internship </label>
      <label><input class="fulltime opportunities" data-related-item="fulltimeShow" onchange="lookingForValueChanged()" type="checkbox" id="fulltime" name="fulltime" value="<?php View::echof('fulltime'); ?>" <?php View::checked('lookingFor', 'fulltime'); ?> />Full-Time </label>
      <label><input class="housing opportunities" data-related-item="housingShow" onchange="lookingForValueChanged()" type="checkbox" id="housing" name="housing" value="<?php View::echof('housing'); ?>" <?php View::checked('lookingFor', 'housing'); ?> />Housing </label>
      <br>

      <div class="jobShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">What is your (intended) major?</p>
        <select name="majorChooser" id="majorChooser" type="singleselect">
          <?php View::echof('major', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); ?>
          <option value ="Accounting">Accounting</option>
          <option value ="Acupuncture">Acupuncture</option>
          <option value ="Administrative Assistant">Administrative Assistant</option>
          <option value ="Advertising and Marketing">Advertising and Marketing</option>
          <option value ="Agriculture">Agriculture</option>
          <option value ="Air Traffic Controller">Air Traffic Controller</option>
          <option value ="Aircraft Mechanic">Aircraft Mechanic</option>
          <option value ="Animal Science">Animal Science</option>
          <option value ="Animation">Animation</option>
          <option value ="Anthropology">Anthropology</option>
          <option value ="Archaeology">Archaeology</option>
          <option value ="Architecture">Architecture</option>
          <option value ="Art History">Art History</option>
          <option value ="Art Therapy">Art Therapy</option>
          <option value ="Astronomy">Astronomy</option>
          <option value ="Astrophysics">Astrophysics</option>
          <option value ="Athletic Training">Athletic Training</option>
          <option value ="Audio and Video Production">Audio and Video Production</option>
          <option value ="Audiology and Speech Pathology">Audiology and Speech Pathology</option>
          <option value ="Auto Body">Auto Body</option>
          <option value ="Auto Mechanic">Auto Mechanic</option>
          <option value ="Automotive Engineering">Automotive Engineering</option>
          <option value ="Aviation">Aviation</option>
          <option value ="Baking And Pastry">Baking And Pastry</option>
          <option value ="Behavioral Science">Behavioral Science</option>
          <option value ="Biochemistry">Biochemistry</option>
          <option value ="Biology">Biology</option>
          <option value ="Biomedical Engineering">Biomedical Engineering</option>
          <option value ="Biomedical Science">Biomedical Science</option>
          <option value ="Bookkeeping">Bookkeeping</option>
          <option value ="Botany">Botany</option>
          <option value ="Bus and Truck Driver">Bus and Truck Driver</option>
          <option value ="Business Administration">Business Administration</option>
          <option value ="Carpentry">Carpentry</option>
          <option value ="Chemical Engineering">Chemical Engineering</option>
          <option value ="Chemistry">Chemistry</option>
          <option value ="Child Care">Child Care</option>
          <option value ="Child Development">Child Development</option>
          <option value ="Chiropractic">Chiropractic</option>
          <option value ="Christian Counseling">Christian Counseling</option>
          <option value ="Cinematography And Film">Cinematography And Film</option>
          <option value ="Civil Engineering">Civil Engineering</option>
          <option value ="Clinical Psychology">Clinical Psychology</option>
          <option value ="Communications">Communications</option>
          <option value ="Computer Aided Design (CAD)">Computer Aided Design (CAD)</option>
          <option value ="Computer Graphics">Computer Graphics</option>
          <option value ="Computer Networking">Computer Networking</option>
          <option value ="Computer Programming">Computer Programming</option>
          <option value ="Computer Science">Computer Science</option>
          <option value ="Construction Management">Construction Management</option>
          <option value ="Cosmetology">Cosmetology</option>
          <option value ="Counseling Psychology">Counseling Psychology</option>
          <option value ="Court Reporting">Court Reporting</option>
          <option value ="Creative Writing">Creative Writing</option>
          <option value ="Criminal Justice">Criminal Justice</option>
          <option value ="Criminology">Criminology</option>
          <option value ="Culinary Arts">Culinary Arts</option>
          <option value ="Dance">Dance</option>
          <option value ="Dental Assistant">Dental Assistant</option>
          <option value ="Dental Hygienist">Dental Hygienist</option>
          <option value ="Dentistry">Dentistry</option>
          <option value ="Developmental And Child Psychology">Developmental And Child Psychology</option>
          <option value ="Diesel Mechanic">Diesel Mechanic</option>
          <option value ="Dietetics">Dietetics</option>
          <option value ="Early Childhood Education">Early Childhood Education</option>
          <option value ="Economics">Economics</option>
          <option value ="Education">Education</option>
          <option value ="Educational Leadership and Administration">Educational Leadership and Administration</option>
          <option value ="Electrical Engineering">Electrical Engineering</option>
          <option value ="Electrician">Electrician</option>
          <option value ="Elementary Education">Elementary Education</option>
          <option value ="Engineering">Engineering</option>
          <option value ="Engineering Management">Engineering Management</option>
          <option value ="English">English</option>
          <option value ="Environmental Health">Environmental Health</option>
          <option value ="Environmental Science">Environmental Science</option>
          <option value ="Environmental and Wildlife Management">Environmental and Wildlife Management</option>
          <option value ="Equine Studies">Equine Studies</option>
          <option value ="Esthetician">Esthetician</option>
          <option value ="Exercise Physiology">Exercise Physiology</option>
          <option value ="Fashion Design">Fashion Design</option>
          <option value ="Fashion Merchandising">Fashion Merchandising</option>
          <option value ="Finance">Finance</option>
          <option value ="Fire Science">Fire Science</option>
          <option value ="Food Science">Food Science</option>
          <option value ="Forensic Psychology">Forensic Psychology</option>
          <option value ="Forensic Science">Forensic Science</option>
          <option value ="Forestry">Forestry</option>
          <option value ="Game Design">Game Design</option>
          <option value ="General Studies">General Studies</option>
          <option value ="Geography">Geography</option>
          <option value ="Geology">Geology</option>
          <option value ="Graphic Design">Graphic Design</option>
          <option value ="Guidance Counselor">Guidance Counselor</option>
          <option value ="Gunsmithing">Gunsmithing</option>
          <option value ="Health Informatics">Health Informatics</option>
          <option value ="Healthcare Administration">Healthcare Administration</option>
          <option value ="Heating and Air Conditioning (HVAC)">Heating and Air Conditioning (HVAC)</option>
          <option value ="History">History</option>
          <option value ="Holistic Health and Nutrition">Holistic Health and Nutrition</option>
          <option value ="Horticulture">Horticulture</option>
          <option value ="Hotel and Hospitality Management">Hotel and Hospitality Management</option>
          <option value ="Human Resources">Human Resources</option>
          <option value ="Human Services">Human Services</option>
          <option value ="Illustration">Illustration</option>
          <option value ="Industrial Design">Industrial Design</option>
          <option value ="Information Systems">Information Systems</option>
          <option value ="Information Technology">Information Technology</option>
          <option value ="Interior Design">Interior Design</option>
          <option value ="International Business">International Business</option>
          <option value ="International Relations">International Relations</option>
          <option value ="Jewelry Design">Jewelry Design</option>
          <option value ="Journalism">Journalism</option>
          <option value ="Kinesiology And Exercise Science">Kinesiology And Exercise Science</option>
          <option value ="Law">Law</option>
          <option value ="Law and Justice Administration">Law and Justice Administration</option>
          <option value ="Legal Studies">Legal Studies</option>
          <option value ="Liberal Arts">Liberal Arts</option>
          <option value ="Licensed Practical Nurse (LPN)">Licensed Practical Nurse (LPN)</option>
          <option value ="Linguistics">Linguistics</option>
          <option value ="Logistics and Supply Chain Management">Logistics and Supply Chain Management</option>
          <option value ="Makeup Artist">Makeup Artist</option>
          <option value ="Marine Biology">Marine Biology</option>
          <option value ="Marriage and Family Therapy">Marriage and Family Therapy</option>
          <option value ="Massage Therapy">Massage Therapy</option>
          <option value ="Math">Math</option>
          <option value ="Mechanical Engineering">Mechanical Engineering</option>
          <option value ="Medical Assistant">Medical Assistant</option>
          <option value ="Medical Transcription">Medical Transcription</option>
          <option value ="Medicine">Medicine</option>
          <option value ="Mental Health Counseling">Mental Health Counseling</option>
          <option value ="Microbiology">Microbiology</option>
          <option value ="Ministry">Ministry</option>
          <option value ="Molecular Biology">Molecular Biology</option>
          <option value ="Museum Studies">Museum Studies</option>
          <option value ="Music">Music</option>
          <option value ="Music Management">Music Management</option>
          <option value ="Music Therapy">Music Therapy</option>
          <option value ="Nail Technician">Nail Technician</option>
          <option value ="Neuroscience">Neuroscience</option>
          <option value ="Nursing">Nursing</option>
          <option value ="Nursing Assistant">Nursing Assistant</option>
          <option value ="Nutrition">Nutrition</option>
          <option value ="Occupational Therapy">Occupational Therapy</option>
          <option value ="Occupational Therapy Assistant (OTA)">Occupational Therapy Assistant (OTA)</option>
          <option value ="Operations Management">Operations Management</option>
          <option value ="Optometry">Optometry</option>
          <option value ="Organizational Psychology">Organizational Psychology</option>
          <option value ="Organizational and Nonprofit Management">Organizational and Nonprofit Management</option>
          <option value ="Paralegal">Paralegal</option>
          <option value ="Parks and Recreation Management">Parks and Recreation Management</option>
          <option value ="Petroleum Engineering">Petroleum Engineering</option>
          <option value ="Pharmacy">Pharmacy</option>
          <option value ="Pharmacy Technician">Pharmacy Technician</option>
          <option value ="Philosophy">Philosophy</option>
          <option value ="Phlebotomy">Phlebotomy</option>
          <option value ="Photography">Photography</option>
          <option value ="Physical Education">Physical Education</option>
          <option value ="Physical Therapist Assistant">Physical Therapist Assistant</option>
          <option value ="Physical Therapy">Physical Therapy</option>
          <option value ="Physician Assistant">Physician Assistant</option>
          <option value ="Physics">Physics</option>
          <option value ="Podiatry">Podiatry</option>
          <option value ="Political Science">Political Science</option>
          <option value ="Property Management">Property Management</option>
          <option value ="Psychology">Psychology</option>
          <option value ="Public Administration">Public Administration</option>
          <option value ="Public Health">Public Health</option>
          <option value ="Public Policy">Public Policy</option>
          <option value ="Public Relations">Public Relations</option>
          <option value ="Radio And Television Broadcasting">Radio And Television Broadcasting</option>
          <option value ="Radiology Technician">Radiology Technician</option>
          <option value ="Real Estate">Real Estate</option>
          <option value ="Religious Studies">Religious Studies</option>
          <option value ="Respiratory Therapy">Respiratory Therapy</option>
          <option value ="Risk Management and Insurance">Risk Management and Insurance</option>
          <option value ="School Psychology">School Psychology</option>
          <option value ="Secondary Education">Secondary Education</option>
          <option value ="Sign Language">Sign Language</option>
          <option value ="Social Work">Social Work</option>
          <option value ="Sociology">Sociology</option>
          <option value ="Software Engineering">Software Engineering</option>
          <option value ="Special Education">Special Education</option>
          <option value ="Sports Management">Sports Management</option>
          <option value ="Sports Medicine">Sports Medicine</option>
          <option value ="Statistics">Statistics</option>
          <option value ="Structural Engineering">Structural Engineering</option>
          <option value ="Substance Abuse and Addiction Counseling">Substance Abuse and Addiction Counseling</option>
          <option value ="Surgical Technologist">Surgical Technologist</option>
          <option value ="Systems Engineering">Systems Engineering</option>
          <option value ="Theology">Theology</option>
          <option value ="Turf Management">Turf Management</option>
          <option value ="Ultrasound Technician">Ultrasound Technician</option>
          <option value ="Urban Planning">Urban Planning</option>
          <option value ="Veterinary Assistant">Veterinary Assistant</option>
          <option value ="Veterinary Medicine">Veterinary Medicine</option>
          <option value ="Web Design">Web Design</option>
          <option value ="Welding">Welding</option>
          <option value ="Wildlife Biology">Wildlife Biology</option>
          <option value ="Zoology">Zoology</option>
          <option value ="Other">Other</option>
          <option value ="Undecided">Undecided</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What is your GPA range?</p>
        <select name="gpaChooser" id="gpaChooser" type="singleselect">
          <?php View::echof('gpa', '<option selected="selected">{var}</option>', '<option value="">Please Select...</option>'); ?>
          <option value ="3.51-4.00">3.51-4.00</option>
          <option value ="3.01-3.50">3.01-3.50</option>
          <option value ="2.51-3.00">2.51-3.00</option>
          <option value ="2.01-2.50">2.01-2.50</option>
          <option value ="Below 2">Below 2</option>
          <option value ="I prefer not to say">I prefer not to say</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">What industries are you interested in? Please select all that apply. (Hold Ctrl/Cmd to select multiple options.)</p>
        <select name="industryChooser[]" id="industryChooser" style="height:10em;" multiple>
          <option value="Accounting">Accounting</option>
          <option value="Airlines/Aviation">Airlines/Aviation</option>
          <option value="Alternative Dispute Resolution">Alternative Dispute Resolution</option>
          <option value="Alternative Medicine">Alternative Medicine</option>
          <option value="Animation">Animation</option>
          <option value="Apparel and Fashion">Apparel and Fashion</option>
          <option value="Architecture and Planning">Architecture and Planning</option>
          <option value="Arts and Crafts">Arts and Crafts</option>
          <option value="Automotive">Automotive</option>
          <option value="Aviation and Aerospace">Aviation and Aerospace</option>
          <option value="Banking">Banking</option>
          <option value="Biotechnology">Biotechnology</option>
          <option value="Broadcast Media">Broadcast Media</option>
          <option value="Building Materials">Building Materials</option>
          <option value="Business Supplies and Equipment">Business Supplies and Equipment</option>
          <option value="Capital Markets">Capital Markets</option>
          <option value="Chemicals">Chemicals</option>
          <option value="Civic and Social Organization">Civic and Social Organization</option>
          <option value="Civil Engineering">Civil Engineering</option>
          <option value="Commercial Real Estate">Commercial Real Estate</option>
          <option value="Computer and Network Security">Computer and Network Security</option>
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
          <option value="Defense and Space">Defense and Space</option>
          <option value="Design">Design</option>
          <option value="Education Management">Education Management</option>
          <option value="E-Learning">E-Learning</option>
          <option value="Electrical/Electronic Manufacturing">Electrical/Electronic Manufacturing</option>
          <option value="Entertainment">Entertainment</option>
          <option value="Entrepreneurship">Entrepreneurship</option>
          <option value="Environmental Services">Environmental Services</option>
          <option value="Events Services">Events Services</option>
          <option value="Executive Office">Executive Office</option>
          <option value="Facilities Services">Facilities Services</option>
          <option value="Farming">Farming</option>
          <option value="Financial Services">Financial Services</option>
          <option value="Fine Art">Fine Art</option>
          <option value="Fishery">Fishery</option>
          <option value="Food and Beverages">Food and Beverages</option>
          <option value="Food Production">Food Production</option>
          <option value="Fund-Raising">Fund-Raising</option>
          <option value="Furniture">Furniture</option>
          <option value="Gambling and Casinos">Gambling and Casinos</option>
          <option value="Glass, Ceramics and Concrete">Glass, Ceramics and Concrete</option>
          <option value="Government Administration">Government Administration</option>
          <option value="Government Relations">Government Relations</option>
          <option value="Graphic Design">Graphic Design</option>
          <option value="Health, Wellness and Fitness">Health, Wellness and Fitness</option>
          <option value="Higher Education">Higher Education</option>
          <option value="Hospital and Health Care">Hospital and Health Care</option>
          <option value="Hospitality">Hospitality</option>
          <option value="Human Resources">Human Resources</option>
          <option value="Import and Export">Import and Export</option>
          <option value="Individual and Family Services">Individual and Family Services</option>
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
          <option value="Leisure, Travel and Tourism">Leisure, Travel and Tourism</option>
          <option value="Libraries">Libraries</option>
          <option value="Logistics and Supply Chain">Logistics and Supply Chain</option>
          <option value="Luxury Goods and Jewelry">Luxury Goods and Jewelry</option>
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
          <option value="Mining and Metals">Mining and Metals</option>
          <option value="Motion Pictures and Film">Motion Pictures and Film</option>
          <option value="Museums and Institutions">Museums and Institutions</option>
          <option value="Music">Music</option>
          <option value="Nanotechnology">Nanotechnology</option>
          <option value="Newspapers">Newspapers</option>
          <option value="Nonprofit Organization Management">Nonprofit Organization Management</option>
          <option value="Oil and Energy">Oil and Energy</option>
          <option value="Online Media">Online Media</option>
          <option value="Outsourcing/Offshoring">Outsourcing/Offshoring</option>
          <option value="Package/Freight Delivery">Package/Freight Delivery</option>
          <option value="Packaging and Containers">Packaging and Containers</option>
          <option value="Paper and Forest Products">Paper and Forest Products</option>
          <option value="Performing Arts">Performing Arts</option>
          <option value="Pharmaceuticals">Pharmaceuticals</option>
          <option value="Philanthropy">Philanthropy</option>
          <option value="Photography">Photography</option>
          <option value="Plastics">Plastics</option>
          <option value="Political Organization">Political Organization</option>
          <option value="Primary/Secondary Education">Primary/Secondary Education</option>
          <option value="Printing">Printing</option>
          <option value="Professional Training and Coaching">Professional Training and Coaching</option>
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
          <option value="Renewables and Environment">Renewables and Environment</option>
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
          <option value="Venture Capital and Private Equity">Venture Capital and Private Equity</option>
          <option value="Veterinary">Veterinary</option>
          <option value="Warehousing">Warehousing</option>
          <option value="Wholesale">Wholesale</option>
          <option value="Wine and Spirits">Wine and Spirits</option>
          <option value="Wireless">Wireless</option>
          <option value="Writing and Editing">Writing and Editing</option>
        </select>

        <p style="text-align:left;margin-bottom:0px">In which cities are you looking for an internship/program/job? Please select all that apply. (Hold Ctrl/Cmd to select multiple options.)</p>
        <select name="citiesChooser[]" id="citiesChooser" style="height:10em;" multiple>
          <option value="Atlanta, GA">Atlanta, GA</option>
          <option value="Austin, TX">Austin, TX</option>
          <option value="Boston, MA">Boston, MA</option>
          <option value="Chicago, IL">Chicago, IL</option>
          <option value="Columbus, OH">Columbus, OH</option>
          <option value="Dallas, TX">Dallas, TX</option>
          <option value="Denver, CO">Denver, CO</option>
          <option value="Houston, TX">Houston, TX</option>
          <option value="Los Angeles, CA">Los Angeles, CA</option>
          <option value="Minneapolis, MN">Minneapolis, MN</option>
          <option value="New York, NY">New York, NY</option>
          <option value="Orlando, FL">Orlando, FL</option>
          <option value="Philadelphia, PA">Philadelphia, PA</option>
          <option value="Phoenix, AZ">Phoenix, AZ</option>
          <option value="Portland, OR">Portland, OR</option>
          <option value="Raleigh, NC">Raleigh, NC</option>
          <option value="Riverside, CA">Riverside, CA</option>
          <option value="San Antonio, TX">San Antonio, TX</option>
          <option value="San Diego, CA">San Diego, CA</option>
          <option value="San Francisco, CA">San Francisco, CA</option>
          <option value="San Jose, CA">San Jose, CA</option>
          <option value="Seattle, WA">Seattle, WA</option>
          <option value="Tampa, FL">Tampa, FL</option>
          <option value="Washington, D.C.">Washington, D.C.</option>
        </select>
      </div>

      <?php
        $seasons = array('Winter 2016', 'Spring 2016', 'Summer 2016', 'Fall 2016', 'Winter 2017', 'Spring 2017');
        $internshipSeasons = array_map(function ($season) {return 'internship' . str_replace(' ', '', $season);}, $seasons);
        $fulltimeSeasons = array_map(function ($season) {return 'fulltime' . str_replace(' ', '', $season);}, $seasons);
        $housingSeasons = array_map(function ($season) {return 'housing' . str_replace(' ', '', $season);}, $seasons);
      ?>

      <div class="internshipShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">During which period of the year would you like to have an internship/program? Please select all that apply.</p>
        <?php
          for($i = 0; $i < count($seasons); $i++) {
            ob_start();
            View::checked('internshipTimes', $seasons[$i]);
            $checked = ob_get_contents();
            ob_end_clean();

            echo "<label><input type=\"checkbox\" id=\"" . $internshipSeasons[$i] . "\" name=\"" . $internshipSeasons[$i] . "\" " . " " . $checked . " />" . $seasons[$i] . " </label>";
          }
        ?>
        <br>
      </div>

      <div class="fulltimeShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">When would you like to start your full-time job? Please select all the times during which you can start a job.</p>
        <?php
          for($i = 0; $i < count($seasons); $i++) {
            ob_start();
            View::checked('fulltimeTimes', $seasons[$i]);
            $checked = ob_get_contents();
            ob_end_clean();

            echo "<label><input type=\"checkbox\" id=\"" . $fulltimeSeasons[$i] . "\" name=\"" . $fulltimeSeasons[$i] . "\" " . " " . $checked . " />" . $seasons[$i] . " </label>";
          }
        ?>
        <br>
      </div>

      <div class="housingShow" style="display:none">
        <p style="text-align:left;margin-bottom:0px">During which period of the year would you like to have housing (sublet)? Please select all that apply.</p>
        <?php
          for($i = 0; $i < count($seasons); $i++) {
            ob_start();
            View::checked('housingTimes', $seasons[$i]);
            $checked = ob_get_contents();
            ob_end_clean();

            echo "<label><input type=\"checkbox\" id=\"" . $housingSeasons[$i] . "\" name=\"" . $housingSeasons[$i] . "\" " . " " . $checked . " />" . $seasons[$i] . " </label>";
          }
        ?>
        <br>
      </div>

      <?php
        View::partial('s3single', array(
          's3name' => 'photo',
          's3title' => 'Upload a profile picture *: ',
          's3link' => View::get('photo')
        ));
      ?>

      <?php View::notice(); ?>

      <input type="submit" name="register" value="Register" />
    </form>
  </div>
</panel>

<script type="text/javascript">
function educationValueChanged() {
  if($('.undergraduate').is(":checked"))
  {
    $(".undergraduateChoosers").prop('required', true);
    $(".graduateChoosers").prop('required', false);
    $(".undergraduateShow").show();
    $(".graduateShow").hide();
  }
  else
  {
    $(".undergraduateChoosers").prop('required', false);
    $(".graduateChoosers").prop('required', true);
    $(".graduateShow").show();
    $(".undergraduateShow").hide();
  }
}

function lookingForValueChanged() {
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

educationValueChanged();
lookingForValueChanged();

// Restore previously selected items
<?php
  if(!is_null(View::get('industry'))) {
    echo "var industry = " . toJSON(View::get('industry')) . ";";
    echo "$( \"#industryChooser\" ).val(industry);";
  }
  if(!is_null(View::get('cities'))) {
    echo "var cities = " . toJSON(View::get('cities')) . ";";
    echo "$( \"#citiesChooser\" ).val(cities);";
  }
?>
</script>