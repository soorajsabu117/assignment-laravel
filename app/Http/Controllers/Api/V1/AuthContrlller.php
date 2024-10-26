<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator,Hash;

class AuthContrlller extends Controller
{
    //

    public function signup(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=>'required|min:8'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user = new User();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->password     = Hash::make($request->password);   
            $user->created_at   = gmdate('Y-m-d H:i:s');
            $user->updated_at   = gmdate('Y-m-d H:i:s');
            $user->save();

            $token = $user->createToken('api_token');
            $user->access_token = $token->plainTextToken;
            $user->save();

            if($user->id > 0){
                $status  = "1";
                $message = "Registration completed successfully";
                $user = User::find($user->id);
                $o_data  = $user->toArray();
            }else{
                $message = "Faild to register";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }

    public function login(REQUEST $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'email'=> 'required|email',
            'password'=>'required'
        ]);
        
        if ($validator->fails()) {
            $status = "0";
            $message = "Kindly fill all the mandatory fields";
            $errors = $validator->messages();
            
        }else{
            $user_check = User::where(['email'=>strtolower($request->email)])->get();
            if($user_check->count() > 0){
                $user=$user_check->first();
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('api_token');
                    $user->access_token = $token->plainTextToken;
                    $user->updated_at   = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $user_data = User::find($user->id);
                    $o_data    = $user_data->toArray();
                    $message   = "You have successfully loged in";
                    $status    = "1";
                }else{
                    $message = "invalid password provided";
                }
            }else{
                $message = "invalid email id provided";
            }
        }
        return response()->json(['status' => $status, 'message' => $message,'oData'=>(object)$o_data,'errors'=>(object)$errors]);
    }
}
