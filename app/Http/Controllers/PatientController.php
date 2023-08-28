<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Session;

use Illuminate\Http\Request;

class PatientController extends Controller
{
	public function dashboard()
    {
		$theUrl     = config('app.api_url').'v1/doctors_timing/'.$_ENV['CLINIC_ID'];
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->get($theUrl);

		$doctors = json_decode($response->body())->data;
		
		$doctor_arr = [];
		$day_arr = array("Monday", "Tuseday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		
		foreach($doctors as $doctor){			
			$doctor_arr[$doctor->id]['id'] = $doctor->id;
			$doctor_arr[$doctor->id]['name'] = $doctor->name;
			$doctor_arr[$doctor->id]['timings'][$doctor->day][] = array('start_hour'=>$doctor->start_hour, 'end_hour'=>$doctor->end_hour, 'slot_id'=>$doctor->slot_id);
		}
		
		$theUrl     = config('app.api_url').'v1/pages/'.$_ENV['CLINIC_ID'];
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->get($theUrl);

		$pages = json_decode($response->body())->data;

		return view('patients.dashboard', compact('doctor_arr', 'day_arr', 'pages'));
    }
	
	public function book_appointment(Request $request)
    {
		date_default_timezone_set("Asia/Kolkata");
		
		$theUrl     = config('app.api_url').'v1/tokens';
		
		$post_arr = [			
			'doctor_id'=>$request->doctor_id,
			'slot_id'=>$request->slot_id,
			'patient_id'=>$request->patient_id,
			'clinic_id'=>$_ENV['CLINIC_ID'],
		];

		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $post_arr);
		
		$token = json_decode($response->body())->data;

		if(isset($token->token_number)){
			$msg = "Appointment booked successfully.";
			$token->estimated_time = date('h:i a', $token->estimated_time);
			return response()->json(array('success'=>1, 'msg'=> $msg, 'token'=>$token), 200);
		}else{
			$msg = "There is a technical error, please try after sometime";
			return response()->json(array('success'=>0,'msg'=> $msg, 'token'=>""), 200);
		}
    }
	
	public function refresh_status(Request $request)
    {
		date_default_timezone_set("Asia/Kolkata");
		
		$theUrl     = config('app.api_url').'v1/refresh_status/'.$_ENV['CLINIC_ID'].'/'.$request->doctor_id.'/'.$request->slot_id.'/'.$request->patient_id;

		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->get($theUrl);


		$status = json_decode($response->body());

		if(isset($status->data)){

			$status->data->estimated_time = date('h:i a', $status->data->estimated_time);
			return response()->json(array('success'=>1, 'token'=>$status->data), 200);
		}else{
			$msg = "No records found or there is a technical error, please try after sometime";
			return response()->json(array('success'=>0,'msg'=> $msg, 'token'=>""), 200);
		}
    }
	
	public function booking($doctor_id, $slot_id){
		
		date_default_timezone_set("Asia/Kolkata");
		
		$theUrl     = config('app.api_url').'v1/patient_family/'.Session::get('user_details')->family_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->get($theUrl);

		$members = json_decode($response->body())->data;

		$theUrl     = config('app.api_url').'v1/doctors/'.$_ENV['CLINIC_ID'].'/'.$doctor_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$doctor = json_decode($response->body())->data->doctor;		
		
		$is_booked = [];

		foreach($members->members as $index => $patient){

			$theUrl     = config('app.api_url').'v1/refresh_status/'.$_ENV['CLINIC_ID'].'/'.$doctor_id.'/'.$slot_id.'/'.$patient->id;
			$response   = Http ::withHeaders([
				'Authorization' => 'Bearer '.Session::get('user_details')->token
			])->get($theUrl);
			$res = json_decode($response->body());			

			if(!empty($res)){
				$res->data->estimated_time = date('h:i a', $res->data->estimated_time);
				$is_booked[$res->data->patient_id] = (array)$res->data;
			}		
		}

		return view('patients.booking', compact('members', 'doctor', 'slot_id', 'is_booked'));
	}

	public function profile()
    {
		$theUrl     = config('app.api_url').'v1/patient_profile/'.Session::get('user_details')->id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$details = json_decode($response->body())->data;		

		return view('patients.profile', compact('details'));
    }
	
	public function profile_update(Request $request)
	{
		$post_arr = [
			'name'=>$request['name'],
			'gender'=>$request['gender'],
			'mobile_number'=>'+91'.$request['mobile_number'],
			'dob'=>$request['dob'],
			'id'=>$request['user_id'],
		];
		
		if(trim($request['password']) != ""){
			$post_arr['password'] = Hash::make(trim($request['password']));
		}

		$theUrl     = config('app.api_url').'v1/patient_update_profile';
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $post_arr);
		
		$status = json_decode($response->body());

		return redirect()->route('patient.profile')->with('success', "Profile updated successfully");
	}
}
