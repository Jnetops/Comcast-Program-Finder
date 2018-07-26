<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Show Finder - Search Shows</title>

        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


        <script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ URL::asset('js/showfinder.js') }}"></script>

    </head>
    <body>
      <div class="container">
        <div class="row search-entry">
          <div class="col-md-12 text-center">
            <!-- <img src="{{asset('comcast-logo.png')}}" height: 50px width: 100%> -->
            <h2>Show Finder App</h2>
          </div>
          <div class="col-md-12 text-center">

            <h4>Customer Zipcode</h4>
            <input type="number" id="zipcode" />
            <button class="btn" id="submit-zip">Submit Zipcode</button>

          </div>

          <div class="col-md-12 service-id text-center">

            <h4>Customer Service ID</h4>
            <select id="id">
            </select>

          </div>

          <div class="col-md-12 search text-center">
            <div class="col-md-6 type-col">
              <label class="btn search">
                <input type="radio" id="search-radio"> Show Search
              </label>
              <!--<button class="btn search">Show Search</button>-->
            </div>
            <div class="col-md-6 type-col">
              <label class="btn channel">
                <input type="radio" id="channel-radio"> Channel Search
              </label>
              <!--<button class="btn channel">Channel Search</button>-->
            </div>
            <div class="col-md-6">
              <h4>Select Date/Time</h4>
              <div class="input-group date form_datetime text-center" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                <input class="form-control" size="16" type="text" value="" readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
              </div>
              <input type="hidden" id="dtp_input1" value="" /><br/>
            </div>
            <div class="col-md-6">
              <h4 id="search-title">Search Terms (Games/Shows/Movies)</h4>
              <h4 id="channel-title">Search Terms (Channel Name or Keywords)</h4>
              <div class="col-md-10 search-col">
                <input type="text" id="keywords" />
              </div>
              <div class="col-md-2 search-col">
                <button class="btn" id="submit-channel">Search</button>
                <button class="btn" id="submit-search">Search</button>
              </div>
            </div>

          </div>

        </div>

        <div class="row airing-row">
          <div class="col-md-12">
            <ul class="airing-ul">

            </ul>
          </div>
        </div>

      </div>
    </body>
    <div class="modal"></div>
    <script type="text/javascript" src="{{ URL::asset('datetime/jquery/jquery-1.8.3.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ URL::asset('datetime/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ URL::asset('js/moment.js') }}" charset="UTF-8"></script>
    <script type="text/javascript">
      var j = jQuery.noConflict();
      j( function() {
        j('.form_datetime').datetimepicker({
          minuteStep: 15,
          weekStart: 1,
          autoclose: 1,
          todayHighlight: 1,
          startDate: new Date(),
          startView: 2,
          forceParse: 0,
          showMeridian: 1,
        });
      });
    </script>
</html>
