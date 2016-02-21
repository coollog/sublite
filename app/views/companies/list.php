<style>
  #search {
    margin-bottom: 2em;
  }

  list {
  }
  company {
    padding: 2em;
    display: inline-block;
    text-align: center;
    transition: 0.1s all ease-in-out;
    width: 200px;
    color: black !important;
  }
  company:hover {
    opacity: 0.5;
  }
  company photo {
    width: 200px;
    height: 200px;
  }
  company name {
    font-weight: bold;
    margin: 1em 0;
  }
  company industry {
    word-wrap: break-word;
  }
  company desc {
    text-align: left;
    margin-top: 1em;
  }

  showMore {
    display: block;
    padding: 1em;
    border: 2px solid #4D52E8;
    border-width: 2px 0;
    color: #4D52E8;
    font-size: 2em;
    cursor: pointer;
    margin-top: 2em;
  }
  showMore:hover {
    opacity: 0.5;
  }
</style>

<templates>
  <companytemplate>
    <a href="company?id={_id}">
      <company>
        <photo style="background-image: url('{logophoto}');"
               class="div imagecontain"></photo>
        <name class="div">{name}</name>
        <industry class="div">{industry}</industry>
        <desc class="div">{desc}</desc>
      </company>
    </a>
  </companytemplate>
</templates>

<script>
  function loadContent(id, data, callback) {
    var route = 'ajax/' + id;

    $.post(route, data, function (data) {
      console.log("'" + route + "' returned with:");
      console.log(data);
      data = JSON.parse(data);
      callback(data);
    });
  }

  function Companies() {
    this.finished = false;
  }
  Companies.loadMore = function (start, count, callback) {
    loadContent('loadcompanies', {
      start: start,
      count: count
    }, function (data) {
      if (typeof callback !== 'undefined') callback();
      if (start == 0) $('list').html('');
      Companies.setup(data);
    });
  }
  Companies.setup = function (data) {
    $('showMore').show();
    if (data.length < 10) {
      Companies.finish()
    }
    data.forEach(function (company) {
      company._id = company._id.$id;
      var html = Templates.use('companytemplate', company);
      $('list').append(html);
    });
    $('company desc').hide();
    // $('company').hover(function () {
    //   $(this).children('desc').slideDown(100, 'easeOutCubic');
    // }, function () {
    //   $(this).children('desc').slideUp(100, 'easeOutCubic');
    // });
    repositionFooter();
  }
  Companies.restart = function (loadCount) {
    this.finished = false;
    Companies.loadMore(0, loadCount);
  }
  Companies.finish = function () {
    this.finished = true;
    $('showMore').hide();
  }
  Companies.search = function (phrase, start, count, callback) {
    loadContent('loadcompanies', {
      start: start,
      count: count,
      search: phrase
    }, function (data) {
      if (typeof callback !== 'undefined') callback();
      if (start == 0) $('list').html('');
      Companies.setup(data);
    });
  }

  $(function () {
    Templates.init();

    var offset = 0;
    var loadCount = 10;
    Companies.restart(loadCount);

    $('showMore').click(function () {
      $(this).prop('disabled', true).html('Loading...');
      function finishedLoading() {
        $('showMore').prop('disabled', false).html('Show More');
      }

      offset += loadCount;
      if ($('#search').val()) {
        Companies.search(
          $('#search').val(), offset, loadCount, finishedLoading);
      } else {
        Companies.loadMore(offset, loadCount, finishedLoading);
      }
    });

    function searchAgain() {
      offset = 0;
      $('list').html('Loading...');
      if ($('#search').val()) {
        Companies.search($('#search').val(), offset, loadCount);
      } else {
        Companies.restart(loadCount);
      }
    }
    $('#search')
      .click(function () { $(this).val(''); searchAgain(); })
      .change(searchAgain);

    $('form').submit(function () { return false; });
  });
</script>

<panel>
  <div class="content">
    <headline>Companies</headline>

    <form>
      <div class="form-slider">
        <label for="search">Search for Companies:</label>
        <input type="text" id="search" name="search" />
      </div>
    </form>

    <list></list>

    <showMore>Show More</showMore>
  </div>
</panel>