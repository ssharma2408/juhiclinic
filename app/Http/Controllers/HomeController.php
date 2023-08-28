<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Session;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
		$theUrl     = config('app.api_url').'clinic-close-status/'.$_ENV['CLINIC_ID'];

		$response   = Http ::get($theUrl);

		$close_status = json_decode($response->body())->data->is_clinic_closed;

		if($close_status){
			Session::put('close_status', true);			
		}else{
			session()->forget('close_status');			
		}
		
		$theUrl     = config('app.api_url').'token_status/'.$_ENV['CLINIC_ID'];
		$response   = Http::get($theUrl);

		$doctors = json_decode($response->body())->data;
		
		$doctor_arr = [];		
		
		foreach($doctors as $doctor){			
			$doctor_arr[$doctor->id]['id'] = $doctor->id;
			$doctor_arr[$doctor->id]['name'] = $doctor->name;
			$doctor_arr[$doctor->id]['timings'][$doctor->day][] = array('start_hour'=>$doctor->start_hour, 'end_hour'=>$doctor->end_hour, 'slot_id'=>$doctor->slot_id, 'total_token'=>$doctor->total_token, 'current_token'=>(!$doctor->is_started) ? "Not Started" : $doctor->current_token);
		}
		
		$theUrl     = config('app.api_url').'announcements/'.$_ENV['CLINIC_ID'];
		$response   = Http::get($theUrl);
		
		$announcements = json_decode($response->body())->data;		

	   return view('home', compact('doctor_arr', 'announcements'));
	}
	
	public function shortenLink($code){		 
		
		$theUrl     = config('app.api_url').'code/'.$code;
		$response   = Http ::get($theUrl);
				
		$status = json_decode($response->body())->data->status;
		
		if($status){
			return redirect()->route('clinic.home')->with('success', "You have joined the family successfully");		
		}else{
			return redirect()->route('clinic.home')->with('success', "You have already joined the family");	
		}	   
	}	

}
