<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Session;

class FamilyController extends Controller
{
    public function index()
	{
		$theUrl     = config('app.api_url').'v1/patient_family/'.Session::get('user_details')->family_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->get($theUrl);

		$member_arr = json_decode($response->body())->data;

		$type = ["type_0"=>"Self, Spouse or Parents", "type_1"=>"Children"];

		$members = [];
		foreach($member_arr->members as $member){
			$members["type_".$member->is_dependent][] = $member;
		}

		$owner_id = $member_arr->owner_id;

		return view('patients.family.index', compact('members', 'type', 'owner_id'));
	}
	
	public function create()
    {
        return view('patients.family.create');
    }
	
	public function store(Request $request)
    {		
		
		$theUrl     = config('app.api_url').'v1/patients';		
		
		$post_arr = [
			'name'=>$request['name'],			
			'mobile_number'=>isset($request['has_mobile']) ? '+91'.$request['mobile_number'] : "",
			'clinic_id'=>$_ENV['CLINIC_ID'],
			'gender'=>$request['gender'],
			'dob'=>$request['dob'],
			'family_id'=>Session::get('user_details')->family_id,
			'user_mobile_number'=> Session::get('user_details')->mobile_number,
			'is_dependent'=> isset($request['has_mobile']) ? 0 : 1,
			'added_by'=> Session::get('user_details')->id,
		];

		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->post($theUrl, $post_arr);
		
		$member = json_decode($response->body());

		if(isset($member->data->id)){
			return redirect()->route('family.index')->with('success', "Member added successfully");
		}else{
			$member = ['name'=>$request['name'], 'mobile_number'=>$request['mobile_number']];
			
			return view('patients.family.sendsms')->with('member', $member)->with('success', "Member is already exist in the system.");
		}
    }	
	
	public function edit(){
		
		$url_arr = explode("/", url()->full());

		$patient_id = $url_arr[count($url_arr)-2];
		
		$theUrl     = config('app.api_url').'v1/patients/'.$patient_id;
		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->get($theUrl);		

		$member = json_decode($response->body())->data;

		return view('patients.family.edit', compact('member'));
	}
	
	public function update(Request $request)
    {		
		
		$url_arr = explode("/", url()->full());

		$patient_id = $url_arr[count($url_arr)-1];
		
		$theUrl     = config('app.api_url').'v1/patients/'.$patient_id;
	
		$post_arr = [
			'id'=> $request['id'],
			'name'=>$request['name'],			
			'gender'=>$request['gender'],
			'dob'=>$request['dob'],
			'family_id'=>Session::get('user_details')->family_id,
			'clinic_id'=>$_ENV['CLINIC_ID']
		];

		if(isset($request['mobile_number']) && $request['mobile_number'] != ""){
			$post_arr['mobile_number'] = '+91'.$request['mobile_number'];
			$post_arr['is_dependent'] = 0;
		}else{
			$post_arr['mobile_number'] = Session::get('user_details')->mobile_number."-".rand(pow(10, 3-1), pow(10, 3)-1);
			$post_arr['is_dependent'] = 1;
		}

		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token 
        ])->put($theUrl, $post_arr);

		$member = json_decode($response->body());
		
		if(isset($member->data->id)){
			return redirect()->route('family.index')->with('success', "Member updated successfully");
		}else{
			return redirect()->route('family.index')->with('success', "There is a technical error");
		}
    }
	
	public function destroy(Request $request)
	{
		$url_arr = explode("/", url()->full());

		$member_id = $url_arr[count($url_arr)-1];
		
		$theUrl     = config('app.api_url').'v1/patients/'.$member_id;

		$response   = Http ::withHeaders([
            'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->delete($theUrl);

		if(isset(json_decode($response->body())->data) && $member_id == Session::get('user_details')->id){
			
			$user_details = Session::get('user_details');
			$user_details->family_id = json_decode($response->body())->data->family_id;		
			Session::put('user_details', $user_details);			
			
			return redirect()->route('family.index')
			->with('success', 'Exited the family successfully');
		}

		return redirect()->route('family.index')->with('success', "Member removed successfully");
		
	}
	
	public function sendsms(Request $request)
    {
		$post_arr = [			
			'family_id'=>Session::get('user_details')->family_id,
			'name'=>$request['name'],
			'mobile_number'=>'+91'.$request['mobile_number'],
			'user_mobile_number'=> Session::get('user_details')->mobile_number,
			'user_name'=> Session::get('user_details')->name,
			'domain'=>$_ENV['DOMAIN'],
			'clinic_id'=>$_ENV['CLINIC_ID'],
			'site_url'=>$_ENV['APP_URL'],			
		];		

		$response = Http::withHeaders([
            'Accept' => 'application/json',
			'Authorization' => 'Bearer '.Session::get('user_details')->token
        ])->post(config('app.api_url').'v1/sendsms', $post_arr);
		
		$status = json_decode($response->body())->data->status;
		$message = json_decode($response->body())->data->message;

		if($status){
			return redirect()->route('family.index')->with('success', "SMS sent successfully ".$message);
		}else{
			return redirect()->route('family.index')->with('success', "There is a technical error");	
		}
	}
}
