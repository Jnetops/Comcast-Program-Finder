$(document).ready(function() {
    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }
    });

    $("#submit-zip").click(function() {

        $.ajax({url: "/zipcodes", type: "POST", data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'zipcode':$("#zipcode").val()}, success: function(result){

            if (typeof result['Failed'] !== 'undefined')
            {
                $(".col-md-12.service-id").hide();
                $(".col-md-12.search").hide();
                $(".alert-warning").remove();
                $(".container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">'+result["Failed"]["Errors"]+'</h4></div>');
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 10000);
            }
            else if (result['Success'] == "False")
            {
                $(".col-md-12.service-id").hide();
                $(".col-md-12.search").hide();
                $(".alert-warning").remove();
                $(".container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">No Results Found For This Zip Code</h4></div>');
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 10000);
            }
            else {

                $(".col-md-12.service-id").show();
                $(".col-md-12.search").show();
                $("#id").children().remove();
                $("#airing-ul").children().remove();
                $("#search-radio").prop('checked', true);
                $.each(result, function(key, value) {
                    if (key != "Success")
                    {
                        $('#id')
                            .append($("<option></option>")
                                    .attr("value",key)
                                    .text(value["Name"] + " - " + value["City"] + " - " + key));
                    }
                });
                $(".btn.search").trigger("click");
            }
        }});

    });

    $("#submit-search").click(function() {
        var date = moment($('#dtp_input1').val()).format('YYYY-MM-DD');
        var time = moment($('#dtp_input1').val()).format('HH:mm:ss');

        if (date == "Invalid date" || time == "Invalid date")
        {
            alert("Invalid Date/Time Entry");
            return;
        }

        $.ajax({url: "/search", type: "POST", data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'zipcode':$("#zipcode").val(), 'service_id': $('#id').find(":selected").val(), 'date':date, 'time':time, 'keywords':$("#keywords").val()}, success: function(result){

            if (typeof result['Failed'] !== 'undefined')
            {
                $(".col-md-12.service-id").hide();
                $(".alert-warning").remove();
                $(".container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">'+result["Failed"]["Errors"]+'</h4></div>');
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 10000);
            }
            else if (result['Success'] == "False")
            {
                $(".col-md-12.service-id").hide();
                $(".alert-warning").remove();
                $(".container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">No Results Found For Those Search Terms</h4></div>');
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 10000);
            }
            else {

                $(".row.airing-row").show();
                $(".airing-ul").children().remove();
                $.each(result, function(key, value) {
                    if (key != "Success")
                    {
                        $('.airing-ul')
                            .append($('<li class="airing-li search"><div class="row text-center"><div class="col-md-12 text-center"><div class="col-md-6 search-results-title text-center"><h4>Channel: ' + key + ' ' + value["ChannelName"] + '</h4></div><div class="col-md-6 search-results-title text-center"><h4> Title: ' + value["Title"] + '</h4></div></div><div class="col-md-12 text-center"><div class="col-md-6 search-results-time text-center"><h4>Air Time: ' + value["AiringTime"] + '</div><div class="col-md-6 search-results-time text-center"><h4>Duration: ' + value["Duration"] + '</div></div></div></li>'));
                    }
                });
            }
        }});

    });


    $("#submit-channel").click(function() {
        var date = moment($('#dtp_input1').val()).format('YYYY-MM-DD');
        var time = moment($('#dtp_input1').val()).format('HH:mm:ss');

        if (date == "Invalid date" || time == "Invalid date")
        {
            alert("Invalid Date/Time Entry");
            return;
        }

        if (!$("#keywords").val())
        {
          alert("Channel Keywords Required");
          return;
        }

        $.ajax({url: "/channel", type: "POST", data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'zipcode':$("#zipcode").val(), 'service_id': $('#id').find(":selected").val(), 'date':date, 'time':time, 'keywords':$("#keywords").val()}, success: function(result){

            if (typeof result['Failed'] !== 'undefined')
            {
                $(".col-md-12.service-id").hide();
                $(".alert-warning").remove();
                $(".container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">'+result["Failed"]["Errors"]+'</h4></div>');
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 10000);
            }
            else if (result['Success'] == "False")
            {
                $(".col-md-12.service-id").hide();
                $(".alert-warning").remove();
                $(".container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">No Results Found For Those Search Terms</h4></div>');
                setTimeout(function() {
                    $('.alert-warning').remove();
                }, 10000);
            }
            else {
                $(".row.airing-row").show();
                $(".airing-ul").children().remove();
                $.each(result, function(key1, value1) {
                    if (key1 != "Success")
                    {
                        $('.airing-ul').append($('<li class="airing-li channel" id="'+key1+'">'));
                        $('#' + key1).append($('<div class="col-md-12 text-center"><h3 id="channel-h3-title">Channel: ' + key1 + ' ' + value1[0]["ChannelName"] + '</h3></div>'));
                        $.each(value1, function(key2, value2) {
                            $('#' + key1).append($('<div class="col-md-12 text-center"><h4> Title: ' + value2["Title"] + '</h4></div><div class="col-md-12 text-center"><div class="col-md-6 airing text-center"><h4>Air Time: ' + value2["AiringTime"] + '</div><div class="col-md-6 airing text-center"><h4>Duration: ' + value2["Duration"] + '</div></div>'));
                        });
                        $('#' + key1).append($('</li>'));
                    }
                });
                //$('ul.airing-ul li:first-child').addClass('first');
                //$('.airing-li.first div:first').addClass('first');
            }
        }});

    });

    $(".btn.search").click(function() {
        $("#search-title").show();
        $("#submit-search").show();
        $("#channel-title").hide();
        $("#submit-channel").hide();
        $("#channel-radio").prop("checked", false);
    });
    $(".btn.channel").click(function() {
        $("#search-title").hide();
        $("#submit-search").hide();
        $("#channel-title").show();
        $("#submit-channel").show();
        $("#search-radio").prop("checked", false);
    });
});
