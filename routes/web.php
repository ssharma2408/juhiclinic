<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('clinic/', [TestController::class, 'getClinic']); */

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
	/* Route::get('/', function () {
		return view('home');
	}); */
	
	Route::get('/', 'HomeController@index')->name('clinic.home');
	Route::get('/c/{code}', 'HomeController@shortenLink')->name('shorten.link');
	
	Route::group(['middleware' => ['guest']], function() {

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::get('/patient_login', 'LoginController@patient_login')->name('patient.login.show');
        Route::post('/patient_login', 'LoginController@patient_gen_otp')->name('patient.gen.otp');
		Route::get('/patient_verification', 'LoginController@patient_verify')->name('patient.verify.show');
		Route::post('/patient_verification', 'LoginController@patient_verify_process')->name('patient.login.perform');
        Route::get('/patient_register', 'LoginController@patient_register')->name('patient.register.show');
        Route::post('/patient_register', 'LoginController@patient_register_save')->name('patient.register.save');
        Route::post('/login', 'LoginController@login')->name('login.perform');
        Route::get('/logout', 'LoginController@logout')->name('login.logout');        

    });	
	
	Route::group(['prefix' => 'ca_dashboard', 'middleware' => ['clinicAdminAccess']], function() {
		Route::get('/', 'ClinicController@dashboard')->name('clinic.admin.dashboard');
		Route::get('/my-clinic', 'ClinicController@show')->name('my-clinic.show');
		Route::resource('staffs', 'StaffController');
		Route::get('doctors', 'DoctorController@index')->name('doctors.index');
		Route::get('doctor-view', 'DoctorController@view')->name('doctor.view');
		Route::get('doctor', 'DoctorController@edit')->name('doctor.edit');
		Route::post('/clinic-doctor-timings-save', 'DoctorController@save')->name('clinic.timings.save');
		Route::get('/clinic-timings', 'ClinicController@timings')->name('clinic.timings.edit');
		Route::post('/clinic-timings-save', 'ClinicController@save')->name('clinicadmin.timings.save');
		Route::get('/clinicadmin-closed', 'ClinicController@closed_day')->name('clinic.closed.edit');
		Route::post('/clinicadmin-closed-save', 'ClinicController@closed_day_save')->name('clinicadmin.closed.save');
		Route::get('/profile', 'ClinicController@profile')->name('clinic.admin.profile');
		Route::post('/profile', 'ClinicController@profile_update')->name('clinicadmin.profile.update');
	 
	});
	
	Route::group(['prefix' => 'doctor_dashboard', 'middleware' => ['doctorAdminAccess']], function() {
		Route::get('/', 'DoctorController@dashboard')->name('doctor.dashboard');
		Route::get('/current_appointments', 'DoctorController@current_appointments')->name('doctor.current.appointments');
		Route::get('/patients', 'DoctorController@patients')->name('doctor.patients');
		Route::get('/get_history/{id}', 'DoctorController@get_history')->name('doctor.get_history');
		Route::get('/search_patients/{search_term}', 'DoctorController@search_patients')->name('doctor.search_patients');
		Route::resource('timings', 'TimingController');
		Route::post('timings-save', 'TimingController@save')->name('timings.save');
		Route::post('/update-token', 'DoctorController@update_token')->name('doctor.update_token');
		Route::get('/profile', 'DoctorController@profile')->name('doctor.profile');
		Route::post('/profile', 'DoctorController@profile_update')->name('doctor.profile.update');
		Route::get('/start-slot/{slot_id}/{status}', 'DoctorController@start_slot')->name('doctor.start.slot');
	});
	
	Route::group(['prefix' => 'staff_dashboard', 'middleware' => ['staffAccess']], function() {
		Route::get('/', 'StaffController@dashboard')->name('staff.dashboard');
		Route::get('/my-clinic', 'StaffController@clinic')->name('clinic.show');
		Route::get('/clinic-timings', 'StaffController@timings')->name('staff.timings.edit');
		Route::get('/clinic-closed', 'StaffController@closed_day')->name('closed.edit');		
		Route::post('/staff-timings-save', 'StaffController@save')->name('staff.timings.save');
		Route::get('doctors', 'StaffController@doctors')->name('clinic.doctors');
		Route::get('staff-doctor-view', 'StaffController@view')->name('staff.doctor.view');
		Route::get('staff-doctor', 'StaffController@doctor_timing_edit')->name('staff.doctor.edit');
		Route::get('tokens', 'StaffController@token_status')->name('staff.token.status');
		Route::get('creat_token/{doctor_id}/{slot_id}', 'StaffController@create_token')->name('staff.create.show');
		Route::get('refresh_token/{doctor_id}/{slot_id}', 'StaffController@refresh_token')->name('staff.refresh.token');
		Route::post('/clinic-closed-save', 'StaffController@closed_day_save')->name('clinic.closed.save');
		Route::post('/process_token', 'StaffController@process_token')->name('staff.create.token');
		Route::get('/profile', 'StaffController@profile')->name('staff.profile');
		Route::post('/profile', 'StaffController@profile_update')->name('staff.profile.update');
		
	});
	
	Route::group(['prefix' => 'user_dashboard', 'middleware' => ['patientAccess']], function() {
		Route::get('/', 'PatientController@dashboard')->name('patient.dashboard');
		Route::post('family/sendsms', 'FamilyController@sendsms')->name('family.sendsms');
		Route::resource('family', 'FamilyController');
		Route::get('/booking/{doctor_id}/{slot_id}', 'PatientController@booking')->name('patient.booking');
		Route::get('/book-appointment/{doctor_id}/{slot_id}/{patient_id}', 'PatientController@book_appointment')->name('patient.book_appointment');
		Route::get('/refresh-status/{doctor_id}/{slot_id}/{patient_id}', 'PatientController@refresh_status')->name('patient.refresh_status');
		Route::get('/page/{id}', 'PatientController@page')->name('patient.page');
		Route::get('/profile', 'PatientController@profile')->name('patient.profile');
		Route::post('/profile', 'PatientController@profile_update')->name('patient.profile.update');
	});
	
});
