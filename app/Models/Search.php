<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Redis;
use App\Traits\ZipcodeTimezone;
use Carbon\Carbon;

class Search extends Model
{
    use ZipcodeTimezone;

    public function zip_code($request)
    {
      $api_key = "empty";
      $url = "http://api.rovicorp.com/TVlistings/v9/listings/services/postalcode/". $request->input('zipcode') ."/info?locale=en-US&countrycode=US&format=json&apikey=" . $api_key;
      $response = json_decode(file_get_contents($url), true);
      $serviceArray = array();
      if (isset($response["ServicesResult"]["Services"]["Service"]))
      {
        foreach ($response["ServicesResult"]["Services"]["Service"] as $service)
        {
          if ($service["SystemName"] == "Comcast")
          {
            $serviceArray[$service["ServiceId"]] = array("Name" => $service["Name"], "City" => $service["City"]);
          }
        }
      }

      if (!empty($serviceArray))
      {
        $serviceArray["Success"] = "True";
      }
      else {
        $serviceArray["Success"] = "False";
      }

      return $serviceArray;
    }

    public function search_keywords($request)
    {
      $service_id = $request->input('service_id');
      $date = $request->input('date');
      $time = $request->input('time');
      $keywords = $request->input('keywords');
      $zipcode = $request->input('zipcode');

      $timezoneOffsets = $this->getTimeOffsets($zipcode);
      //print_r($timezoneOffsets);
      $datetime = Carbon::parse($date . ' ' . $time);
      $offsetHours = explode('-', $timezoneOffsets["Offset"]);
      if ($offsetHours[0] == "+")
      {
        $datetime->subHours($offsetHours[1]);
      } else {
        $datetime->addHours($offsetHours[1]);
      }

      // check for daylight savings time
      //$daylightTime = daylightTime($datetime, $timezoneOffsets["Daylight"]);

      $date = $datetime->format('Y-m-d');
      $time = $datetime->format("H:i:s");
      $response;

      if (Redis::exists($service_id . ':' . $date . ':' . $time))
      {
        $cache = Redis::get($service_id . ':' . $date . ':' . $time);
        $response = json_decode($cache, true);

      } else {
         $api_key = "vgmjw4fbbcf6kcp7f53u2296";
         $url = "http://api.rovicorp.com/TVlistings/v9/listings/linearschedule/". $service_id ."/info?locale=en-US&duration=15&startdate=". $date . "T" . $time . "Z" ."&inprogress=true&oneairingpersourceid=false&format=json&apikey=" . $api_key;

         $cache = file_get_contents($url);
         Redis::set($service_id . ':' . $date . ':' . $time, $cache);
         Redis::expire($service_id . ':' . $date . ':' . $time, 900);
         $response = json_decode($cache, true);
      }

      $offsetArray = $response["LinearScheduleResult"]["Schedule"]["TimeZones"];


      $offset = $this->gatherResultsTimeOffset($offsetArray);

      if ($request->input('keywords') == "")
      {
        $lineupArray = $this->gatherResults($response, $offsetHours);
      }
      else {
        $lineupArray = $this->gatherResultsKeywords($response, $offsetHours, $keywords);
      }

      return $lineupArray;
    }

    public function channel_keywords($request)
    {
      $service_id = $request->input('service_id');
      $date = $request->input('date');
      $time = $request->input('time');
      $keywords = $request->input('keywords');
      $zipcode = $request->input('zipcode');

      $timezoneOffsets = $this->getTimeOffsets($zipcode);
      //print_r($timezoneOffsets);
      $datetime = Carbon::parse($date . ' ' . $time);
      $offsetHours = explode('-', $timezoneOffsets["Offset"]);
      if ($offsetHours[0] == "+")
      {
        $datetime->subHours($offsetHours[1]);
      } else {
        $datetime->addHours($offsetHours[1]);
      }

      // check for daylight savings time
      //$daylightTime = daylightTime($datetime, $timezoneOffsets["Daylight"]);

      // //file_put_contents('C:\Users\domecide\Documents\filename.txt', $datetime->format('%H'));

      $date = $datetime->format('Y-m-d');
      $time = $datetime->format("H:i:s");
      $response;

      if (Redis::exists($service_id . ':channel:' . $date . ':' . $time))
      {
        $cache = Redis::get($service_id . ':channel:' . $date . ':' . $time);
        $response = json_decode($cache, true);

      } else {
         $api_key = "vgmjw4fbbcf6kcp7f53u2296";
         $url = "http://api.rovicorp.com/TVlistings/v9/listings/linearschedule/". $service_id ."/info?locale=en-US&duration=240&startdate=". $date . "T" . $time . "Z" ."&inprogress=true&oneairingpersourceid=false&format=json&apikey=" . $api_key;

         $cache = file_get_contents($url);
         Redis::set($service_id . ':channel:' . $date . ':' . $time, $cache);
         Redis::expire($service_id . ':channel:' . $date . ':' . $time, 14400);
         $response = json_decode($cache, true);
      }

      $offsetArray = $response["LinearScheduleResult"]["Schedule"]["TimeZones"];


      $offset = $this->gatherResultsTimeOffset($offsetArray);

      $lineupArray = $this->gatherChannelKeywords($response, $offsetHours, $keywords);

      return $lineupArray;
    }
}
