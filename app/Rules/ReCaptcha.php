<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements Rule
{
    
	/**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
          
    }
	
	/**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    /* public function validate(string $attribute, mixed $value, Closure $fail)
    {
        $response = Http::get("https://www.google.com/recaptcha/api/siteverify",[
            'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
            'response' => $value
        ]);
          
        return $response->json()["success"];
    } */
	
	public function passes($attribute, $value)
    {
        $response = Http::get("https://www.google.com/recaptcha/api/siteverify",[
            'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
            'response' => $value
        ]);
          
        return $response->json()["success"];
    }
	
	/**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The google recaptcha is required.';
    }
}
