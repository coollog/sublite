<?php
  require_once($GLOBALS['dirpre'].'controllers/Controller.php');

  interface StudentControllerAJAXInterface {
    public static function dashboardSublets();
    public static function dashboardApplications();
    public static function dashboardMessages();
  }

  class StudentControllerAJAX extends StudentController
                              implements StudentControllerAJAXInterface {
    public static function dashboardSublets() {
      StudentController::requireLogin();

      $sublets = SubletModel::getByStudent($_SESSION['_id']);
      $data = [];

      foreach ($sublets as $sublet) {
        $sublet['_id'] = $sublet['_id']->{'$id'};
        if (count($sublet['photos']) == 0) {
          $sublet['photo'] =
            "$GLOBALS[dirpreFromRoute]assets/gfx/subletnophoto.png";
        } else {
          $sublet['photo'] = $sublet['photos'][0];
        }
        if ($sublet['city'] != '') $sublet['address'] .= $sublet['city'];
        if ($sublet['state'] != '') $sublet['address'] .= $sublet['state'];
        $data[] = $sublet;
      }

      echo toJSON([ 'sublets' => $data ]);
    }

    public static function dashboardApplications() {
      StudentController::requireLogin();

      $applications = ApplicationStudent::getByStudent($_SESSION['_id']);

      $data = [];
      foreach ($applications as $application) {
        $jobId = $application->getJobId();
        $job = JobModel::getById($jobId);
        if (is_null($job)) continue;
        $companyId = $job['company'];
        $company = CompanyModel::getByIdMinimal($companyId);
        $data[] = [
          'title' => $job['title'],
          'location' => $job['location'],
          'deadline' => $job['deadline'],
          'logo' => $company['logophoto'],
          'desc' => $job['desc'],
          'company' => $company['name'],
          'jobId' => $application->getJobId()->{'$id'},
          'submitted' => $application->isSubmitted()
        ];
      }

      echo toJSON([ 'applications' => $data ]);
    }

    public static function dashboardMessages() {
      StudentController::requireLogin();

      $data = [];

      $messages = array_reverse(iterator_to_array(
        MessageModel::findByParticipant($_SESSION['_id']->{'$id'})));

      foreach ($messages as $m) {
        $reply = array_pop($m['replies']);
        $reply['_id'] = $m['_id'];

        $from = $reply['from'];

        MessageController::setFromNamePic($reply, $from);

        $reply['time'] = timeAgo($reply['time']);

        if (strlen($reply['msg']) > 25) {
          $reply['msg'] = substr($reply['msg'], 0, 22) . '...';
        }
        if (strlen($reply['fromname']) > 8) {
          $reply['fromname'] = substr($reply['fromname'], 0, 5) . '...';
        }

        array_push($data, $reply);
      }

      echo toJSON([
        'messages' => $data,
        'unread' => MessageModel::getNumUnread($_SESSION['_id'])
      ]);
    }
  }
?>