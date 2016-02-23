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
    $jobidResult = $db->applications->distinct("jobid",array("status"=>0));

    //get unique recruiterids with unclaimed applications
    $recruiteridResult = $db->jobs->distinct("recruiter", array('$or' => wrapIdsInArrayOfMongoIds($jobidResult)));

    // query the recruiters collection for any recruiters with ids in $recruiteridResult, and also recruiters that have not been sent an email in a week or whose last_emailed field is NULL
    $currentTime = time();
    $interval = time() - 7 * 24 * 60 * 60; // 7 days before
    $mongoInterval = new MongoDate($interval); // MongoDate seven days in the past

    //final query
    $recruiterResult = $db->recruiters->find(
      array(
          '$and' => array(
              array(
                '$or' => array(
                  array(
                    "last_emailed" => array(
                      '$lt' => $mongoInterval
                    )
                  ),
                  array(
                    "last_emailed" => null
                  )
                )
              ),
              array(
                '$or' => wrapIdsInArrayOfMongoIds($recruiteridResult)
              )
            )
        )
      )->limit(MAX_EMAILS);

    if ($recruiterResult) {
      $recruitersEmailed = []; // keep track of emails of recruiters reminded using this script
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

      foreach ($recruiterResult as $recruiter) { // loop through all recruiters and send them personalized reminder emails
        $recruitersEmailed[] = $recruiter['email'];
        $mail->addAddress($recruiter['email']);
        $recruiterFullName = $recruiter['firstname'] . $recruiter['lastname'];
        $recruiterId = $recruiter['_id'];
        $linkDashboard = "http://sublite.net/employers/home"; //how to make a link to dashboard?

        $message = "
          Dear $recruiterFullName,
          <br />
          <br />You have unclaimed applications for some of your job listings! Please visit your <a href = '$linkDashboard'>recruiter homepage</a> and your invidual job listings pages to claim them!
          <br />
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
      }

      // send email to sublite reporting on details of cron job
      $mail->addAddress("eric.yu@yale.edu");
      $mail->addAddress("qingyang.chen@yale.edu");
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

  //Helper function - takes an array of MongoIds, and then stores them in structure that is easy to use in "or" Mongo queries
  //I use large "or" queries in order to avoid numerous small queries.
  function wrapIdsInArrayOfMongoIds($ids)
  {
    $finalArray = [];
    foreach ($ids as $id) {
      $finalArray[] = ["_id" => new MongoId($id)];
    }
    return $finalArray;
  }

?>
