<?php

namespace App\Traits;

use File;
use Carbon\Carbon;

trait ZipcodeTimezone
{
  public function getTimeOffsets($zipcode)
  {
    $array = array();
    $file = fopen(public_path() . "\zipcode\zipcode.csv", 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
      //$line is an array of the csv elements
      $array[] = $line;
    }
    fclose($file);

    $returnArray;
    foreach ($array as $top)
    {
      if (in_array($zipcode, $top))
      {
        $returnArray["Offset"] = $top[5];
        $returnArray["Daylight"] = $top[6];
      }
    }
    return $returnArray;
  }

  public function getDaylightTime($daylightOffset)
  {
    //check for daylight savings time
    $now = Carbon::now();
    $month = $now->format('m');
    $day = $now->format('d');

    if ($month <= 11 && $month >= 3)
    {
      if ($month == 11)
      {
        if ($day > 4)
        {
          $datetime->addHours($daylightOffset);
        }
      }
      elseif ($month == 3)
      {
        if ($day < 12)
        {
          $datetime->addHours($daylightOffset);
        }
      }
      else {
        $datetime->addHours($daylightOffset);
      }
    }
  }

  public function gatherResultsTimeOffset($offsetArray)
  {
      $offset;
      foreach ($offsetArray as $key => $value)
      {
        if ($key == "Offset")
        {
          $offset = $value["Offset"];
        }
      }
      return $offset;
  }

  public function gatherResults($response, $offsetHours)
  {
    $lineupArray = array();
    if (isset($response["LinearScheduleResult"]["Schedule"]["Airings"]))
    {
      foreach($response["LinearScheduleResult"]["Schedule"]["Airings"] as $airing)
      {
        if (!array_key_exists ( $airing["Channel"] , $lineupArray ))
        {
          $dateExplode = explode("T", $airing["AiringTime"]);
          $timeExplode = explode("Z", $dateExplode[1]);
          $dateTime = $dateExplode[0] . " " . $timeExplode[0];

          $airdate = Carbon::parse($dateTime);
          if ($offsetHours[0] == "+")
          {
            $airdate->addHours($offsetHours[1]);
          } else {
            $airdate->subHours($offsetHours[1]);
          }

          if ((int)$airing["Duration"] < 60)
          {
            $duration = $airing["Duration"] . " Minutes";
          } else {
            if (fmod((int)$airing["Duration"], 60) != 0)
            {
              $duration = (int)((int)$airing["Duration"] / 60) . " Hours " . fmod((int)$airing["Duration"], 60) . " Minutes";
            }
            else {
              $duration = (int)((int)$airing["Duration"] / 60) . " Hours";
            }
          }

          $lineupArray[$airing["Channel"]] = array("Title" => $airing["Title"], "ChannelName" => $airing["SourceLongName"], "AiringTime" => $airdate->format("g:i A"), "Duration" => $duration);
        }
      }
      $lineupArray["Success"] = "True";
    }
    else {
      $lineupArray["Success"] = "False";
    }
    return $lineupArray;
  }

  public function gatherResultsKeywords($response, $offsetHours, $keywords)
  {
    $lineupArray = array();
    if (isset($response["LinearScheduleResult"]["Schedule"]["Airings"]))
    {
      foreach($response["LinearScheduleResult"]["Schedule"]["Airings"] as $airing)
      {
        if (!array_key_exists ( $airing["Channel"] , $lineupArray ))
        {
          if (stripos($airing["Title"], $keywords) !== false)
          {
            $dateExplode = explode("T", $airing["AiringTime"]);
            $timeExplode = explode("Z", $dateExplode[1]);
            $dateTime = $dateExplode[0] . " " . $timeExplode[0];

            $airdate = Carbon::parse($dateTime);
            if ($offsetHours[0] == "+")
            {
              $airdate->addHours($offsetHours[1]);
            } else {
              $airdate->subHours($offsetHours[1]);
            }

            if ((int)$airing["Duration"] < 60)
            {
              $duration = $airing["Duration"] . " Minutes";
            } else {
              if (fmod((int)$airing["Duration"], 60) != 0)
              {
                $duration = (int)((int)$airing["Duration"] / 60) . " Hours " . fmod((int)$airing["Duration"], 60) . " Minutes";
              }
              else {
                $duration = (int)((int)$airing["Duration"] / 60) . " Hours";
              }
            }

            $lineupArray[$airing["Channel"]] = array("Title" => $airing["Title"], "ChannelName" => $airing["SourceLongName"], "AiringTime" => $airdate->format("g:i A"), "Duration" => $duration);
          }
        }
      }
      $lineupArray["Success"] = "True";
    }
    else {
      $lineupArray["Success"] = "False";
    }
    return $lineupArray;
  }

  public function gatherChannelKeywords($response, $offsetHours, $keywords)
  {
    $lineupArray = array();
    if (isset($response["LinearScheduleResult"]["Schedule"]["Airings"]))
    {
      foreach($response["LinearScheduleResult"]["Schedule"]["Airings"] as $airing)
      {
        if (stripos($airing["SourceLongName"], $keywords) !== false)
        {
          $dateExplode = explode("T", $airing["AiringTime"]);
          $timeExplode = explode("Z", $dateExplode[1]);
          $dateTime = $dateExplode[0] . " " . $timeExplode[0];

          $airdate = Carbon::parse($dateTime);
          if ($offsetHours[0] == "+")
          {
            $airdate->addHours($offsetHours[1]);
          } else {
            $airdate->subHours($offsetHours[1]);
          }

          if ((int)$airing["Duration"] < 60)
          {
            $duration = $airing["Duration"] . " Minutes";
          } else {
            if (fmod((int)$airing["Duration"], 60) != 0)
            {
              $duration = (int)((int)$airing["Duration"] / 60) . " Hours " . fmod((int)$airing["Duration"], 60) . " Minutes";
            }
            else {
              $duration = (int)((int)$airing["Duration"] / 60) . " Hours";
            }
          }
          $lineupArray[$airing["Channel"]][] = array("Title" => $airing["Title"], "ChannelName" => $airing["SourceLongName"], "AiringTime" => $airdate->format("g:i A"), "Duration" => $duration);
        }
      }
      $lineupArray["Success"] = "True";
    }
    else {
      $lineupArray["Success"] = "False";
    }
    return $lineupArray;
  }
}
