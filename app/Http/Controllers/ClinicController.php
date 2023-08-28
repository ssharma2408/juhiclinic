<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;
use Hash;

class ClinicController extends Controller
{  
	
	public function dashboard()
    {
		return view('clinic.dashboard');
    }
	 
	 public function show()
    {
		$theUrl     = config('app.api_url').'v1/clinics/'.$_ENV['CLINIC_ID'];	   
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);
				
		$clinic = json_decode($response->body())->data;
		
		if(!empty($clinic->opening_hours)){
			$timing_arr = [];
			foreach($clinic->opening_hours as $timing){
				$timing_arr[$timing->day][] = $timing;
			}
			$clinic->opening_hours = $timing_arr;
		}
		
		$day_arr = array("Monday", "Tuseday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
		
		//get closed days
		$theUrl     = config('app.api_url').'v1/closed-timings/'.$clinic->clinic_admin->id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);
				
		$closed_days = json_decode($response->body())->data;		

        return view('clinic.show', compact('clinic', 'day_arr', 'closed_days'));
    }
	
	public function timings()
	{
		$theUrl     = config('app.api_url').'v1/clinic-timings/'.$_ENV['CLINIC_ID'];
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

		return view('clinic.timings', compact('details', 'day_arr'));
	}
	
	public function save(Request $request)
    {		
		$theUrl     = config('app.api_url').'v1/timings-save';
		
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $request->all());

		$response = json_decode($response->body());		
		$route = 'my-clinic.show';		
		
        return redirect()->route($route)->with('success', "Timings updated successfully");
    }
	
	public function closed_day(Request $request)
	{
		$theUrl     = config('app.api_url').'v1/closed-timings/'.$request->clinic_admin;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$details = json_decode($response->body())->data;
		
		$clinic_admin = $request->clinic_admin;

		return view('clinic.closedday', compact('details', 'clinic_admin'));
	}
	
	public function closed_day_save(Request $request)
    {		
		
		$theUrl     = config('app.api_url').'v1/closed-timings-save';
		
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $request->all());

		$response = json_decode($response->body());

        return redirect()->route('my-clinic.show')->with('success', "Timings updated successfully");
    }
	
	public function profile()
    {
		$theUrl     = config('app.api_url').'v1/profile/'.Session::get('user_details')->user_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->get($theUrl);

		$details = json_decode($response->body())->data;		

		return view('clinic.profile', compact('details'));
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

		return redirect()->route('clinic.admin.profile')->with('success', "Profile updated successfully");
	}
}
