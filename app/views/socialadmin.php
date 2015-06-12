<style>
  * {
    box-sizing: border-box;
  }
  .content {
    text-align: left;
  }
  #map-canvas {
    width: 100%;
    height: 300px;
  }
  .error, .success {
    display: none;
  }
</style>

<panel class="studentmap">
  <div class="content">
    <headline>Where are people at this summer?</headline>
    <div id="map-canvas"></div>
  </div>
</panel>
<panel class="hubs">
  <div class="content">
    <headline>Existing Hubs</headline>
    <div class="thehubs"></div>
  </div>
</panel>
<panel class="addhub">
  <div class="content">
    <form>
      <headline>Create a Hub</headline>

      <div class="error"></div>
      <div class="success"></div>

      Name of Hub:
      <input type="text" name="name" />
      Location of Hub:
      <input type="text" name="location" />
      Upload a banner:
      <?php
        vpartial('s3single', array(
          's3name' => 'banner', 
          's3title' => 'What would you like your banner image to be?*'
        ));
      ?>

      <div class="error"></div>
      <div class="success"></div>

      <input type="submit" value="Create Hub" />
    </form>
    <script>
      $('.addhub form').submit(function() {
        var json = formJSON(this),
            form = this;

        Comm.emit('create hub', json, function (err, data) {
          if (err) { $(form).children('.error').show().html(err); return; }

          formReset(form);
          $(form).children('.success').show().html(data);
        });

        return false;
      });
    </script>
  </div>
</panel>
<panel class="addstudent">
  <div class="content">
    <headline>Select students to add to hubs!</headline>
    <form>
      <?php 
        global $MStudent, $MSocial;
        $students = iterator_to_array($MStudent->find(array(
          'hubs' => array('$exists' => true)
        )));

        function sortStudents($a, $b) {
          return strcasecmp($a['hubs']['city'], $b['hubs']['city']);
        }
        usort($students, "sortStudents");

        foreach($students as $student) {
          echo '<input type="checkbox" name="students[]" value="' . $student['_id']
              . '">' . $student['name'] . ' at <b>' . $student['hubs']['city'] .
              '</b>. Current hubs: ' .
              implode(', ', $MSocial->getHubs($student['_id'])) . '<br>';
        }
      ?>
      <br><b>Add these students to hub:</b><br>
      <?php
        global $MSocial;
        $hubs = $MSocial->getAll();
        foreach ($hubs as $hub) {
          echo '<input type="radio" name="hub" value="' . $hub['_id'] . '">' .
          $hub['name'] . "<br>";
        }
      ?>
      <input type="submit" value="Add to Hub" />
    </form>
  </div>
</panel>
<script>
  $('.addstudent form').submit(function() {
    var json = formJSON(this);console.log(json);

    var emitdata = {
      students: json.students,
      hub: json.hub
    };
    Comm.emit('add students to hub', emitdata, function (err, data) {
      if (err) { alert(err); return; }

      alert('added students!');
      // location.reload();
    });

    return false;
  });
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDORLARDVNHaHBSLZ0UG-1EGABk-IH2uq0&sensor=false"></script>
<script>
  function initialize(locations) {
    var searchzone = new google.maps.LatLng(37.09024, -95.712891);

    var mapOptions = {
      center: searchzone,
      /*adjust number to change starting zoom size*/
      zoom: 4
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    // set icon for marker
    var houseicon = '<?php echo $GLOBALS['dirpre']; ?>assets/gfx/map/marker.png';

    // create the markers and infowindows based on array location
    for (var i = 0; i < locations.length; i++) {
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i].latitude, locations[i].longitude),
        map: map,
        icon: houseicon
      });

      var infowindow = new google.maps.InfoWindow();

      // creates infowindow when marker is clicked
      google.maps.event.addListener(marker, 'click', (function (marker, i) {
        return function() {
          var students = '';
          locations[i].students.forEach(function (student) {
            students += 
              '<p>' +
                student.name + ' - ' + student.email + ' - ' + student.city
              '</p>';
          });

          infowindow.setContent(
            '<div class="iwcontent">' +
              '<h3 class="iwfirstHeading" class="firstHeading">' +
                locations[i].students.length + 
              '</h3>' +
              '<div class="iwbodyContent">' +
                students + 
              '</div>' +
            '</div>');

          infowindow.open(map, marker);
        }
      })(marker, i));
    }

    //clicking map will close opened infowindow
    google.maps.event.addListener(map, 'click', function() {
      infowindow.close(map, marker);
    });
  }
</script>

<?php vpartial('hubs/controllers/comm'); ?>

<script>
  Comm.apiuri = '<?php echo $GLOBALS['dirpre']; ?>../hubs/adminapi.php';

  Comm.emit('load students', {}, function (err, data) {
    if (err) { alert(err); return; }

    var locations = [];
    data.forEach(function (student) {
      var latitude = student.hubs.geocode.latitude,
          longitude = student.hubs.geocode.longitude,
          student = {
            name: student.name,
            email: student.email,
            photo: student.photo,
            city: student.hubs.city
          };

      var exists = false;
      locations.forEach(function (location, i) {
        if (!exists &&
          location.latitude == latitude && location.longitude == longitude) {
          locations[i].students.push(student);
          exists = true;
        }
      });

      if (!exists) {
        locations.push({
          latitude: latitude,
          longitude: longitude,
          students: [student]
        });
      }
    });

    initialize(locations);
  });

  Comm.emit('load hubs', {}, function (err, data) {
    if (err) { alert(err); return; }

    $('.thehubs').html('');
    data.forEach(function (hub) {
      $('.thehubs').append(
        '<a href="hub.php?id='+hub._id.$id+'">' +
          '<input type="button" value="'+hub.name+' ('+hub.members.length+')" />' +
        '</a><br />'
      );
    });

  });
</script>