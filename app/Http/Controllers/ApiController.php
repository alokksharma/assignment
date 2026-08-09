<?php

namespace App\Http\Controllers;

 use Illuminate\Http\Request;
use App\Http\Requests\S2CRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\VendorProjectAssign;
use App\Models\Country;
use App\Models\Proof;
use App\Models\Survey;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{

    public function s2c(Request $request)
    {
         $validator = Validator::make($request->all(), [
            '3ASPID'   => ['required'],
            'country'  => ['required'],
            'ccode'    => ['required'],
            'link'     => ['required', 'in:test,live'],
        ]);

        if ($validator->fails()) {
            return 'error: '. $validator->errors()->first();
        }
        //fetch vendor project data if not htan survey data
         if( $request->VendorID){
            $vendorProjet =  VendorProjectAssign::query()->with('survey');
            $vendorProjet->where('project_vendor_id', $request->VendorID);
            $vendorProjet = $vendorProjet->where('three_as_project_id', $request->input('3ASPID'))->first();
            $survey = $vendorProjet?->survey;
         }else{
            $surveyId = (int) last(explode('-', $request->input('3ASPID')));
            $survey = Survey::query()->find( $surveyId);
         }
        if(!$survey){
            return 'Error: Project not found with Project ID ' .
            $request->input('3ASPID') .
            ($request->input('VendorID') ? " and Vendor ID {$request->input('VendorID')}" : '');
        }

        if($request->link  == 'live'){
            $link = $survey->live_url;
        }else{
            $link = $survey->test_url;
        }
        //condition check for device type
        $userAgent = request()->header('User-Agent');
        if (preg_match('/mobile/i', $userAgent)) {
            $device = 'Mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            $device = 'Tablet';
        } else {
            $device = 'Desktop';
        }
        if (
            $survey?->device_type &&
            in_array(
                $device,
                array_map('trim', explode(',', $survey->device_type))
            )
        ) {
                  $ipAddress = file_get_contents('https://api.ipify.org');
              //  $ipAddress = request()->ip();
                $userAgent = request()->userAgent();
                $location = Http::get("http://ip-api.com/json/{$ipAddress}")->json();
                // if($survey->country->name !== $location['country'] && $request->link == 'live' ){
                //     return 'Error: Country not match from geo location';
                // }
                $encode = hash('sha256', $request->input('ccode'));
                if($request->input('link') == 'test'){
                    $url = str_replace('[identifier]', $encode, $survey->test_url);
                }else{
                    $url = str_replace('[identifier]', $encode, $survey->live_url);
                }
                $data = [
                    '3ASProjectID' =>  $request->input('3ASPID'),
                    'ClientProjectID' =>  $survey->project_id,
                    'ccode' =>  $request->input('ccode'),
                    'encode' =>  $encode,
                    'vendor_id' =>  $request->input('VendorID') ?  $vendorProjet?->vendor_id : null,
                    'link' =>  $request->input('link'),
                    'status' =>  0,
                    'clientsurveystatus' =>   $survey->status,
                    'client_url' => $url,
                    'device' =>  $device,
                    'useragent' =>  $userAgent,
                    'ip_address' =>  $ipAddress,
                    'cityname' =>   $location['city'],
                    'geolat' =>   $location['lat'],
                    'geolong' =>   $location['lon'],
                    'country_id' =>  $survey->country_id,
                    'notes' =>  $location['query'],
                    'ua_is_bot' => true,
                ];
                Proof::create($data);

                return redirect()->to($url);

        }else{
             return 'error: device not exist';
        }

    }
}
