<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidateInput
{
  public function zipcodeValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'zipcode' => 'required|integer'
    ]);
    return $validate;
  }

  public function searchValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'keywords' => 'nullable|string|max:50',
        'date' => 'required|string',
        'time' => 'required|string',
        'service_id' => 'required|integer',
        'zipcode' => 'required|integer'
    ]);
    return $validate;
  }

  public function searchChannelValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'keywords' => 'required|string|max:50',
        'date' => 'required|string',
        'time' => 'required|string',
        'service_id' => 'required|integer',
        'zipcode' => 'required|integer'
    ]);
    return $validate;
  }
}
