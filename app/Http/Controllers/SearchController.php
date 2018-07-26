<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Search;
use App\Traits\ValidateInput;

class SearchController extends Controller
{
    use ValidateInput;
    public function zipcode(Request $request)
    {
      $validateResults = $this->zipcodeValidate($request);

      if($validateResults->passes()) {

        $zip = new Search();
        $gatherZip = $zip->zip_code($request);

        return $gatherZip;

      } else {
        return array("Failed" => "User Input Invalid");
        //return redirect()->route('Home')->with('Failed', array('Errors' => $validateInput->errors(), 'Stage' => 'zipcode'));
      }
    }

    public function search(Request $request)
    {
      $validateResults = $this->searchValidate($request);

      if($validateResults->passes()) {

        $results = new Search();
        $gatherResults = $results->search_keywords($request);

        return $gatherResults;

      } else {
        return array("Failed" => "User Input Invalid");
        //return redirect()->route('Home')->with('Failed', array('Errors' => $validateResults->errors(), 'Stage' => 'search'));
      }
    }

    public function channel(Request $request)
    {
      $validateResults = $this->searchChannelValidate($request);

      if($validateResults->passes()) {

        $results = new Search();
        $gatherResults = $results->channel_keywords($request);

        return $gatherResults;

      } else {
        return array("Failed" => "User Input Invalid");
        //return redirect()->route('Home')->with('Failed', array('Errors' => $validateResults->errors(), 'Stage' => 'search'));
      }
    }
}
