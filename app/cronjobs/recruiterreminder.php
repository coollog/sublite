#!/usr/local/bin/php

<?php

  define("MAX_EMAILS", 200); // max number of emails per batch
  require('../pass.php');
  //to use with cron: "* 2 * * sun /usr/bin/php ~/Sites/sublite/app/cronjobs/recruiterreminder.php"
  // runs job at 2am on sundays

  require_once('PHPMailer/PHPMailerAutoload.php');

  //connect to database
  try {
    $dbname = 'subliteinternships';

    switch ($env) { // test development environment to determine which database to connect to
      case 'dev':
        $dbhost = 'localhost:27017';
        break;
      default:
        $dbhost = 'ds051980.mongolab.com:51980';
    }
    $uri = "mongodb://$dbuser:$dbpass@$dbhost/$dbname";
    $m = new MongoClient($uri); // connect to database

    $db = $m->$dbname;
    //query the subliteinternships db -> applications collection for unique jobids where application(s) have not been unlocked yet

    //get unique jobids

    // array to store list of recruiters to remind about unclaimed applications
    $reminders = [];

    // jobs with unclaimed applications
    $jobidResult = $db->applications->distinct("jobid",array("status"=>0));
    if ($jobidResult) {
      // calculate a day 7 days in the past
      $currentTime = time();
      $interval = time() - 7 * 24 * 60 * 60; // 7 days before
      $mongoInterval = new MongoDate($interval); // MongoDate seven days in the past

      // loop through distinct job ids and find number of applicants unclaimed and recruiter info
      foreach ($jobidResult as $jobid) {
        $numUnclaimed;
        $jobName;
        $recruiterId;
        $recruiterName;
        $recruiterEmail;
        $recruiterCredits;
        // count number of unclaimed per job
        $numUnclaimed = count($db->applications->find([
          '$and' => [
            ['jobid' => $jobid],
            ['status' => 0]
          ]
        ]));
        // query jobs collection
        $recruiterIdResult = $db->jobs->findOne(["_id" => $jobid], ["recruiter", "title"]);
        // get recruiter Id and jobname
        $recruiterId = $recruiterIdResult ? $recruiterIdResult['recruiter'] : "";
        $jobName = $recruiterIdResult ? $recruiterIdResult['title'] : "";
        // find recruiter with recruiterId and emailed longer than 7 days ago
        // query recruiters collection
        $recruiterResult = $db->recruiters->findOne([
          '$and' => [
            ['_id' => $recruiterId],
            ['$or' => [
              ['last_emailed' => [
                '$lt' => $mongoInterval
                ]
              ],
              ['last_emailed' => null]
              ]
            ]
            ]
          ], ["email"]);
        // get email of recruiter
        $recruiterEmail = $recruiterResult ? $recruiterResult['email'] : "";
        $recruiterName = $recruiterResult ? $recruiterResult['firstname'] . ' ' . $recruiterResult['lastname'] : "";
        //$recruiterCredits = $recruiterResult ? $recruiterResult['credits'] : "";
        $recruiterCredits = 9001;
        $recruiterId = $recruiterResult ? $recruiterResult['_id'] : "";
        // add recruiter and data to list of reminders
        $reminders[] = [
          'email' => $recruiterEmail,
          'name' => $recruiterName,
          'numUnclaimed' => $numUnclaimed,
          'jobname' => $jobName,
          'credits' => $recruiterCredits,
          'id' => $recruiterId;
        ]

      }

      //set up phpmailer
      $mail = new PHPMailer;
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'info@sublite.net';                 // SMTP username
      $mail->Password = $gmailpass;
      $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
      $mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
      $mail->setFrom("info@sublite.net");
      $mail->addReplyTo("info@sublite.net");
      $mail->WordWrap = 80;
      $mail->isHTML(true);
      $subject = "SubLite Unclaimed Applications";
      $mail->Subject = $subject;

      // loop through recruiters and email them, stop if MAX_EMAILS is reached
      $numEmailed = 0;
      $recruitersEmailed = []; // keep track of emails of recruiters reminded using this script
      foreach ($reminders as $reminder) {
        if ($numEmailed > MAX_EMAILS) break;

        $recruitersEmailed[] = $reminder['email'];
        $mail->addAddress($reminder['email']);
        $recruiterId = $reminder['id'];
        $recruiterFullName = $reminder['name'];
        $numUnclaimed = $reminder['numUnclaimed'];
        $jobName = $reminder['jobname'];
        $credits = $reminder['credits'];
        $message = "
          Dear $recruiterFullName,
          <br />
          You have $numUnclaimed applicants waiting to hear back from you on your job listing for $jobName!
          You may view their names and universities before using your credits to unlock their applications.
          You have $credits free credits left, and you receive another free credit for every work opportunity posted on SubLite.
          In addition, you can purchase credits for 8 dollars. All purchases in quantities of 10 credits and over are 5 dollars, and unused credits can be refunded at the rate they were purchased at.
          <br />
          If you have any questions, feel free to reach out to our recruitment director Dean at dean.li@yale.edu! Thank you so much for using the SubLite Platform.
          <br /
          Team SubLite
        ";

        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        // send email
        if (!$mail->send()) {
           return $mail->ErrorInfo;
        }

        // update 'last_emailed' field in db after email
        $updateLastEmail = $db->recruiters->update(
          array('_id' => new MongoId($recruiterId)),
          array(
            '$set' => array(
              'last_emailed' => new MongoDate(time())
            )
          )
        );

         // clear recipients for next recruiter's email
        $mail->ClearAllRecipients();
        $numEmailed++;
      }

      // send email to sublite reporting on details of cron job
      $mail->addAddress("eric.yu@yale.edu");
      $mail->addAddress("qingyang.chen@yale.edu");
      $mail->addAddress("info@sublite.net");
      $mail->addAddress("tony.jiang@yale.edu");
      $message = "Recruiters emailed by cron job recruiterreminder.php: " . implode(',', $recruitersEmailed);
      $mail->Body    = $message;
      $mail->AltBody = strip_tags($message);

      // send email
      if (!$mail->send()) {
         return $mail->ErrorInfo;
      }
    } else { // no results
      echo "no results";
    }

  } catch (MongoConnectionException $e) {
    trigger_error('Mongodb not available');
  }

?>
