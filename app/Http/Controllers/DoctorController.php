<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Session;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Hash;

class DoctorController extends Controller
{
    public function index(){
		$theUrl     = config('app.api_url').'v1/doctors/'.$_ENV['CLINIC_ID'];
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$doctors = json_decode($response->body())->data[0]->doctors;

	   return view('doctors.index', compact('doctors'));
	}
	
	public function view(Request $request){
		$theUrl     = config('app.api_url').'v1/doctors/'.$_ENV['CLINIC_ID'].'/'.$request->doctor_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$details = json_decode($response->body())->data;
		
		if(!empty($details->opening_hours)){
			$timing_arr = [];
			foreach($details->opening_hours as $timing){
				$timing_arr[$timing->day][] = $timing;
			}
			$details->opening_hours = $timing_arr;
		}
		
		$day_arr = array("Monday", "Tuseday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

		return view('doctors.view', compact('details', 'day_arr'));
	   
	}
	
	public function dashboard()
    {
		return view('doctors.dashboard');
    }
	
	public function current_appointments()
    {
		$theUrl     = config('app.api_url').'v1/tokens/'.$_ENV['CLINIC_ID'].'/'.Session::get('user_details')->user_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$patients = json_decode($response->body())->data;
		
		$patient_arr = [];
		
		foreach($patients as $patient){
			$patient_arr[$patient->start_hour."-".$patient->end_hour]['patients'][] = $patient;
			$patient_arr[$patient->start_hour."-".$patient->end_hour]['is_started'] = $patient->is_started;
			$patient_arr[$patient->start_hour."-".$patient->end_hour]['slot_id'] = $patient->timing_id;
		}

		return view('doctors.current_appointments', compact('patient_arr'));
    }
	
	public function update_token(Request $request)
	{
		
		if($request->status == 0 || $request->status == 2){
			if($request->is_online){
				//process close token
				if ($request->hasFile('prescription')) {			

					//Upload file to S3 Bucket and set path to Prescription
					$extension  = request()->file('prescription')->getClientOriginalExtension();
					$image_name = time() .'_' . $request->patient_id . '.' . $extension;
					$path = $request->file('prescription')->storeAs(
						'patient_'.$request->patient_id,
						$image_name,
						's3'
					);
					$aws_path = Storage::disk('s3')->url($path);
					
					$theUrl     = config('app.api_url').'v1/update_token';

					$post_arr = [			
						'doctor_id'=>Session::get('user_details')->user_id,
						'patient_id'=>$request->patient_id,
						'slot_id'=>$request->slot_id,
						'status'=>$request->status,
						'clinic_id'=>$_ENV['CLINIC_ID'],
						'comment'=>$request->comment,
						'prescription'=>$aws_path,
						'is_online'=>$request->is_online,
						'next_visit_date'=>$request->next_visit_date,
						'time_taken'=>$request->time_taken,
					];

					$response   = Http ::withHeaders([
						'Authorization' => 'Bearer '.Session::get('user_details')->token 
					])->post($theUrl, $post_arr);		
					
					$msg = "Status updated successfully.";
					return response()->json(array('success'=>1, 'msg'=> $msg), 200);
				}else{
					//process hold
					$theUrl     = config('app.api_url').'v1/update_token';

					$post_arr = [			
						'doctor_id'=>Session::get('user_details')->user_id,
						'patient_id'=>$request->patient_id,
						'slot_id'=>$request->slot_id,
						'status'=>$request->status,
						'clinic_id'=>$_ENV['CLINIC_ID'],
						'comment'=>"",
						'prescription'=>"",
						'is_online'=>$request->is_online,
						'next_visit_date'=>"",
						'time_taken'=>$request->time_taken,
					];

					$response   = Http ::withHeaders([
						'Authorization' => 'Bearer '.Session::get('user_details')->token 
					])->post($theUrl, $post_arr);		
					
					$msg = "Status updated successfully.";
					return response()->json(array('success'=>1, 'msg'=> $msg), 200);
				}
			}else{
				$theUrl     = config('app.api_url').'v1/update_token';

				$post_arr = [			
					'doctor_id'=>Session::get('user_details')->user_id,
					'patient_id'=>$request->token_id,
					'slot_id'=>$request->slot_id,
					'status'=>$request->status,
					'clinic_id'=>$_ENV['CLINIC_ID'],
					'is_online'=>$request->is_online,
					'next_visit_date'=>"",
					'time_taken'=>$request->time_taken,
				];

				$response   = Http ::withHeaders([
					'Authorization' => 'Bearer '.Session::get('user_details')->token 
				])->post($theUrl, $post_arr);		
				
				$msg = "Status updated successfully.";
				return response()->json(array('success'=>1, 'msg'=> $msg), 200);
			}
		}
			
	}
	
	public function edit(Request $request)
	{
		$theUrl     = config('app.api_url').'v1/doctors/'.$_ENV['CLINIC_ID'].'/'.$request->doctor_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$details = json_decode($response->body())->data;
		
		if(!empty($details->opening_hours)){
			$timing_arr = [];
			foreach($details->opening_hours as $timing){
				$timing_arr[$timing->day][] = $timing;
			}
			$details->opening_hours = $timing_arr;
		}
		
		$day_arr = array("Monday", "Tuseday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

		return view('doctors.doctor_edit', compact('details', 'day_arr'));
	}
	public function save(Request $request)
    {		
		$theUrl     = config('app.api_url').'v1/timings-save';
		
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $request->all());

		$response = json_decode($response->body());		
		$route = 'clinicadmin.show';
		
		if($request->type =='doctor'){
			$route = 'doctors.index';
		}
        return redirect()->route($route)->with('success', "Timings updated successfully");
    }
	
	public function patients()
    {
		$theUrl     = config('app.api_url').'v1/patients/'.$_ENV['CLINIC_ID'].'/'.Session::get('user_details')->user_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);
		
		$patients = json_decode($response->body())->data;		
		
		$patient_arr = [];
		
		foreach($patients as $patient){
			$patient_arr[$patient->id]['name'] = $patient->name;
			$patient_arr[$patient->id]['visit_date'][] = array('visit_date' => $patient->visit_date, 'history_id' => $patient->history_id);
		}

		return view('doctors.patients', compact('patient_arr'));
    }
	
	public function get_history(Request $request)
	{
		$theUrl     = config('app.api_url').'v1/patient-histories/'.$request->id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);
			
		$history = json_decode($response->body())->data;
		
		return response()->json(array('success'=>1, 'history'=>$history), 200);
	}
	
	public function search_patients($term)
	{
		$theUrl     = config('app.api_url').'v1/search_patients/'.$_ENV['CLINIC_ID'].'/'.Session::get('user_details')->user_id.'/'.$term;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);		
		
		$patients = json_decode($response->body());
		
		if(isset($patients->data)){
			$patient_arr = [];
			
			if(!empty($patients->data)){
				foreach($patients->data as $patient){
					$patient_arr[$patient->id]['name'] = $patient->name;
					$patient_arr[$patient->id]['visit_date'][] = array('visit_date' => $patient->visit_date, 'history_id' => $patient->history_id);
				}
			}

			return response()->json(array('success'=>1, 'patient_arr'=>$patient_arr), 200);

		}else{
			return response()->json(array('success'=>0), 200);
		}		
	}

	public function profile()
    {
		$theUrl     = config('app.api_url').'v1/profile/'.Session::get('user_details')->user_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$details = json_decode($response->body())->data;		

		return view('doctors.profile', compact('details'));
    }
	
	public function profile_update(Request $request)
	{
		$post_arr = [
			'name'=>$request['name'],
			'email'=>$request['email'],
			'mobile_number'=>$request['mobile_number'],						
			'id'=>$request['user_id'],
		];
		
		if(trim($request['password']) != ""){
			$post_arr['password'] = Hash::make(trim($request['password']));
		}

		$theUrl     = config('app.api_url').'v1/update_profile';
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $post_arr);
		
		$status = json_decode($response->body());

		return redirect()->route('doctor.profile')->with('success', "Profile updated successfully");
	}
	
	public function start_slot($slot_id, $status)
	{
		date_default_timezone_set("Asia/Kolkata");

		$theUrl     = config('app.api_url').'v1/work_status/'.$slot_id.'/'.$status.'/'.strtotime("now");

		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);
		
		return response()->json(array('success'=>1), 200);
	}
}
