<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\CompanyTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\VendorProjectAssign;
use App\Models\Country;
use App\Models\Proof;
use App\Models\Survey;
use Illuminate\Support\Facades\Http;

class ShortUrlController extends Controller
{
    use CompanyTrait;


    public function s2c(Request $request)
    {
         $validator = Validator::make($request->all(), [
            '3ASPID'   => ['required'],
            'country'  => ['required'],
            'ccode'    => ['required'],
            'link'     => ['required', 'in:test,live'],
        ]);

        //Defining global variables
		$ua_is_bot=0;
		$useragent1 = $_SERVER['HTTP_USER_AGENT'] ?? '';
		$fraud_score = 0;
	    $fraud_reasons = [];
        $fraud_reason_text='';
		$country_mismatch =0;


        if ($validator->fails()) {
            return 'error: '. $validator->errors()->first();
        }

        //fetch vendor project data if not in survey database table

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

        //Check the survey is LIVE or not
        if($survey->status == "test"){
            return "Error: this is test mode";
        }

        if($request->link  == 'live'){
            $link = $survey->live_url;
        }else{
            $link = $survey->test_url;
        }

		  /* ================= BOT / SCRIPT CHECK ================= */
				$bot_signatures = [
									'bot','crawl','spider','curl','wget',
									'python','java','postman','httpclient','libwww'
								];
	  		foreach ($bot_signatures as $sig) {
				if (stripos($useragent1, $sig) !== false) {
					$ua_is_bot=1;
					$fraud_score += 30;
					$fraud_reasons[] = 'Bot user-agent';
				    $fraud_reason_text = implode(', ', $fraud_reasons);
					    				break;
				}
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
            $notes = 'Success';
            //  $ipAddress = file_get_contents('https://api.ipify.org');
            $ipAddress = config('marketxcel.ip_source') === 'request' ? file_get_contents('https://api.ipify.org') : request()->ip();
            $userAgent = request()->userAgent();
            $location = Http::get("http://ip-api.com/json/{$ipAddress}")->json();
            if($survey->country->name !== $location['country'] && $request->link == 'live' ){
                $country_mismatch = 1;
                return 'Error: Please check your invite email and copy the correct link as survey is not for eligible for the country';
            }

                //Panelist ccode converted into encode .. first encryption
            $encode = hash('sha256', $request->input('ccode'));

             //Encrypting the whole test/live link variables with encrytion value, which will be redirected to the client side
			  $send_able_link_hash = encrypt($link);

			  //Picking first 10 and last 10 values from original encrypted
			  $send_able_link_hash_short1 = substr($send_able_link_hash,0,5);
			  $send_able_link_hash_short3 = substr($send_able_link_hash,10,5);
			  $send_able_link_hash_short2 = substr($send_able_link_hash,30,5);

			  //Generating a new Encrypted value by concatenating first 10 and last 10 values as client redirect Panel ID
			  $send_able_link_hash_short = $send_able_link_hash_short1.$send_able_link_hash_short2.$send_able_link_hash_short3;


                //Replacing client indentifier with our encyrption code
                if($request->input('link') == 'test'){
                    $url = str_replace('[identifier]', $send_able_link_hash_short, $survey->test_url);
					$url = $url. "&3ASPID=" . request()->input('3ASPID') . "&ccode=" . $send_able_link_hash_short . "&link=" . request()->link;
                }else{
                    $url = str_replace('[identifier]', $send_able_link_hash_short, $survey->live_url);
		      	    $url = $url. "&3ASPID=" . request()->input('3ASPID') ."&ccode=" . $send_able_link_hash_short . "&link=" . request()->link;
                }

                //Entering the data into the Database
                $data = [
                    '3ASProjectID' =>  $request->input('3ASPID'),
                    'ClientProjectID' =>  $survey->project_id,
                    'ccode' =>  $request->input('ccode'),
                    'encode' =>  $encode,
                    'vendor_id' =>  $request->input('VendorID') ?  $vendorProjet?->vendor_id : null,
                    'link' =>  $request->input('link'),
                    'status' =>  0,
                    'clientsurveystatus' => $survey->status,
                    'client_url' => $url,
                    'device' =>  $device,
                    'useragent' =>  $userAgent,
                    'ip_address' =>  $ipAddress,
                    'cityname' =>   $location['city'],
                    'geolat' =>   $location['lat'],
                    'geolong' =>   $location['lon'],
                    'country_id' =>  $survey->country_id,
                    'notes' => $notes ?? 'Success',
                    'ua_is_bot' => $ua_is_bot,
					'country_mismatch' => $country_mismatch,
					'asr_hash' => $send_able_link_hash,
  	                'asr_hash_short' => $send_able_link_hash_short,
					'fraud_reason' => 'no reason',
					'fraud_score' => $fraud_score,
                ];

                //Check the ccode exists or not
                $proof = Proof::where('ccode',  $request->input('ccode'))->latest()->first();
                if($proof){
                    if($proof->status != 0){
                            return "Error: This participant has already submitted the survey";
                    }else{

                        $data['status'] = $request->input('status', 0);
                        $data['duplicate_pcode'] = 1;
                        $data['ip_attempts'] = ($proof->ip_attempts ?? 0) + 1;
                    }
                }

                Proof::create($data);

                return redirect()->to($url);

        }else{
             return 'error: device not exist';
        }

    }


    public function c2s(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ccode'    => ['required'],
            'status'     => ['required'],
        ]);


        $proof = Proof::query()->with(['vendorProjectAssign','vendor'])->where('asr_hash_short', $request->input('ccode'))->latest()->first();
        if ($proof) {
            if ($proof->clientsurveystatus != "Live")
            {
                return "Error: Survey is closed";
            }
            $proof->update([
                'status' => (int)$request->status
            ]);

            if($proof->vendorProjectAssign){
                 $url = match ($proof->status) {
                    1 =>  $proof->vendorProjectAssign->temp_complete ?? $proof->vendor->complete,
                    2 =>  $proof->vendorProjectAssign->temp_terminate ?? $proof->vendor->terminate,
                    3 =>  $proof->vendorProjectAssign->temp_quotafull ?? $proof->vendor->quotafull,
                    4 =>  $proof->vendorProjectAssign->temp_speedster ?? $proof->vendor->speedster,
                    default =>null,
                };

                if (! $url) {
                    return 'Unknown project status.';
                }
                    $url = str_replace('[#ccode#]', $proof->ccode, $url);
                    return redirect()->away($url);

            }else{
               return match ($proof->status) {
                    1 => 'The project is complete.',
                    2 => 'The project is terminated.',
                    3 => 'The project is quota full.',
                    4 => 'The project is quality.',
                    default => 'Unknown project status.',
                };
            }
        }
        else
        {
             return "Error: PanelistID is not correct";
        }

    }


    public function dataReconcile(Request $request)
    {
        $survey_id =  (int) last(explode('-', $request->input('search')));
        $threeAsProjects = Proof::query()
            ->select('3ASProjectID')
            ->distinct()
            ->get();

        return view('companies.vendors.data-reconncile', compact('threeAsProjects'));
    }

    public function search(Request $request)
{
    $search = $request->search;

    $vendors = Vendor::where('vendor_id', 'like', "%{$search}%")
        ->limit(10)
        ->get();

    return response()->json($vendors);
}

    public function dataReconcileStore(Request $request){
        $request->validate([
            'vendor_id' => ['required', 'string'],
            'asr_hash_short' => ['required', 'string'],
            'redirect_status' => ['required', 'integer', 'in:1,2,3,4'],
        ]);

       $asr_hash_short = array_map('trim', explode(',', $request->asr_hash_short));
       $proof = Proof::where('3ASProjectID', $request->input('vendor_id'))
       ->whereIn('asr_hash_short', $asr_hash_short)
        ->update([
            'reconcile_status' => (int) $request->input('redirect_status'),
        ]);
       if ($proof) {
            return redirect()->back()->with('success', "($proof) Status updated successfully.");
        }else{
            return redirect()->back()->with('success', 'data not found.');

        }
    }

    public function show(ShortUrl $shortUrl)
    {
        return view('short_urls.show', compact('shortUrl'));
    }

    //short url list
    public function index()
    {
        $shortUrls = $this->shortUrlList();
        return view('short_urls.index', compact('shortUrls'));
    }


}
