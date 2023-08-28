<?php

namespace App\Http\Controllers;
use Session;

use Illuminate\Http\Request;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Rules\ReCaptcha;

class LoginController extends Controller
{
    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('login');
    }	
	
	/**
     * Display register page for patient.
     * 
     * @return Renderable
     */
    public function patient_register()
    {
        return view('patient_register');
    }	

	/**
     * Display register page for patient.
     * 
     * @return Renderable
     */
    public function patient_register_save(Request $request)
    {
        
		$request->validate([
            'name' => 'required',            
            'mobile_no' => 'required|digits:10|numeric',
            'gender' => 'required',            
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
		
		$post_arr = [
			'name'=>$request['name'],
			'mobile_number'=>'+91'.$request['mobile_no'],
			'gender'=>$request['gender'],
			'dob'=>$request['dob'],
			'domain'=>$_ENV['DOMAIN'],
			'clinic_id'=>$_ENV['CLINIC_ID'],
		];		

		$response = Http::post(config('app.api_url').'patient_register', $post_arr);
		$result = json_decode($response->body());
		
		if(isset($result->success)){
			if($result->success && $result->data->token){
				
				$patient = $result->data;
				Session::put('user_details', $patient);

				return $this->authenticated($request, $patient);
				
			}else{
				
				return redirect()->to('/')
					->withErrors($result->data->error);
			}
		}else{
			return redirect()->to('patient_login')
					->withErrors("There is a technical error. Please try after some time");
		}
		
    }
	
	/**
     * Display login page for patient.
     * 
     * @return Renderable
     */
    public function patient_login()
    {
        return view('patient_login');
    }

	/**
     * Display OTP page for patient.
     * 
     * @return Renderable
     */
    public function patient_gen_otp(Request $request)
    {
        
		$post_arr = [			
			'mobile_number'=>'+91'.$request['mobile_no'],
			'domain'=>$_ENV['DOMAIN'],
			'clinic_id'=>$_ENV['CLINIC_ID'],
		];		

		$response = Http::withHeaders([
            'Accept' => 'application/json' 
        ])->post(config('app.api_url').'patientlogin', $post_arr);
		
		
		$result = json_decode($response->body());		
		
		if(isset($result->success)){
			if($result->success && isset($result->data->patient_id)){
				
				$patient_id = $result->data->patient_id;
				
				return redirect()->to('patient_verification')
					->with(['success'=> $result->message, 'patient_id'=>$patient_id]);			
				
			}else{
				
				return redirect()->to('/')
					->withErrors($result->message);
			}
		}else{
			if(isset($result->errors)){
				return redirect()->to('patient_login')
					->withErrors("Entered mobile number is not registered");
			}
			return redirect()->to('patient_login')
					->withErrors("There is a technical error. Please try after some time");
		}
    }	

	/**
     * Display OTP page for patient.
     * 
     * @return Renderable
     */
    public function patient_verify()
    {
        return view('otpVerification');
    }

	
	/**
     * Display OTP page for patient.
     * 
     * @return Renderable
     */
    public function patient_verify_process(Request $request)
    {
        $post_arr = [			
			'patient_id'=>$request['patient_id'],
			'otp'=>$request['otp'],
			'domain'=>$_ENV['DOMAIN'],
			'clinic_id'=>$_ENV['CLINIC_ID'],
		];		

		$response = Http::withHeaders([
            'Accept' => 'application/json' 
        ])->post(config('app.api_url').'patientloginwithotp', $post_arr);
		
		
		$result = json_decode($response->body());
		
		if($result->success){
			
			$patient = $result->data;
			Session::put('user_details', $patient);

			return $this->authenticated($request, $patient);
			
		}else{
			
			return redirect()->to('patient_login')
                ->withErrors($result->data->error);
		}
    }
	
	/**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();		

		$response = Http::post(config('app.api_url').'login', [
			'email'=>isset($credentials['email']) ? $credentials['email'] : $credentials['username'],
			'password'=>$credentials['password'],
			'domain'=>$_ENV['DOMAIN'],
			'clinic_id'=>$_ENV['CLINIC_ID'],
		]);		
		$result = json_decode($response->body());		
		
		if(isset($result->success)){
			if($result->success && $result->data->token){
				
				$user = $result->data;
				Session::put('user_details', $user);
				
				//Auth::login($user);

				return $this->authenticated($request, $user);
				
			}else{
				
				return redirect()->to('login')
					->withErrors($result->data->error);
			}
		}else{
			return redirect()->to('login')
					->withErrors("There is a technical error. Please try after some time");
		}
    }
	
	/**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        $dashboard = "";
		
		switch($user->role){
			case "Clinic Admin":
				$dashboard = "ca_dashboard";
				break;
			case "Doctor":
				$dashboard = "doctor_dashboard";
				break;
			case "Staff":
				$dashboard = "staff_dashboard";
				break;
			default :
				$dashboard = "user_dashboard";
				break;				
		}
		return redirect()->intended($dashboard);
    }
	
	/**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
       $path = Session::get('user_details')->role == "Patient" ? "/" : "login";
	   
	   $request->session()->flush();
	   return redirect()->to($path)
			->with('success', 'Logout successfully');
    }
}
