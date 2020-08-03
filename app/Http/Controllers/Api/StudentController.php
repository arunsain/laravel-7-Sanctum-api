<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentController extends APIController
{


	public function login(Request $request){


		$validation = Validator::make($request->all(), [
			'email'    => 'required|email',
			'password' => 'required|min:4',
           // 'device_token' => 'required',
		]);

		if ($validation->fails()) {
			return $this->throwValidation($validation->messages()->all());
		}



		try {
			$user = Student::where('email', $request->email)->first();

			if (! $user || ! Hash::check($request->password, $user->password)) {
				throw ValidationException::withMessages([
					'email' => ['The provided credentials are incorrect.'],
				]);
			}

			$user->token =  $user->createToken('my-device')->plainTextToken;

			return $this->respond([
				'status'      => true,
				'message'   => trans('Student Login Successfully'),
				'data'      => $user
			]);


		} catch (\Exception $e) {

			return $this->respondInternalError($e->getMessage());
		}

	}

	public function register(Request $request){
 
		$rules = [
			'name'                     =>  'required|string|max:255',
			'email'                          =>  'required|string|max:255|unique:students',
			'password'                       =>  'required',
		];

		$messages = [];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
         	//$day =180;
         	 //echo $current = Carbon::now()->format('Y-m-d');
         	// echo $last_date =  date_create($current)->modify($day . 'days')->format('Y-m-d');
			return $this->throwValidation($validator->messages()->all());
		}else{

			$student                       = new Student();
			$student->name           = $request->name;
			$student->email            = $request->email;
			$student->password        = Hash::make($request->password);
			if($student->save()){


				return  $this->login($request);
			}else{
				return $this->respond([
				'status'      => false,
				'message'   => "some things went wrong",
				
			]);

			}


		} 

	}

	public function studentDetail(Request $request){

		// if($this->middleware('auth:sanctum')){
	return	auth()->user();
			//return $user = $request->user();
		//print_r($user); 

		//}
		
	}

}
