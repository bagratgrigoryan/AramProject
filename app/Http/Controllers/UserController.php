<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use function React\Promise\all;
use Illuminate\Support\Facades\Auth;
use function React\Promise\reduce;


class UserController extends Controller
{
    public function CreateUser(Request $request)
    {
        $validator = validator($request->all(), [
            'first_name' => 'required|min:3|max:30',
            'last_name' => 'required|min:3|max:30',
            'email' => 'required|email|unique:users|min:8|max:30',
            'phone' => 'required|unique:users|min:9|max:9',
            'password' => 'required|min:3|max:30',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 400);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->save();
        return response()->json(["status" => "success", "message" => "user created successfully"]);
    }

    public function Index()
    {
        return User::all();
    }

    public function UpdateUser(Request $request)
    {
        $validator = validator($request->all(), [
            'first_name' => 'min:3|max:30',
            'last_name' => 'min:3|max:30',
            'email' => 'email|unique:users|min:8|max:30',
            'phone' => 'unique:users|min:9|max:9',
            'password' => 'min:3|max:30',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 400);
        }
        $user = $request->user();
        if ($request->first_name) {
            $user->first_name = $request->first_name;
        }
        if ($request->last_name) {
            $user->last_name = $request->last_name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->phone) {
            $user->phone = $request->phone;
        }
        if ($request->password) {
            $user->password = $request->password;
        }
        $user->save();

        return response()->json(["status" => "success", "message" => "User updated Successfully", "user" => $user]);
    }

    public function Delete(Request $request)
    {
        $request->user()->delete();
        return response()->json(["status"=>"success", "message"=>"User Deleted"]);
    }
    public function Login(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|min:8|max:30',
            'password' => 'required|min:3|max:30',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 400);
        }
        if (!Auth::attempt(["email" => $request->email, 'password' => $request->password])) {
            return response()->json([
                "message" => "Invalid Login or/ Password"
            ], 400);
        }
        $user = $request->user();
        $token = $user->createToken('Token')->accessToken;
        return response()->json(["token" => $token, "type" => "Bearer"]);
    }

    public function GetUser(Request $request)
    {dd(100);
        $user = $request->user();
        return response()->json(["user" => $user]);
    }

    public function LogOut(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(["status" => "success", "message" => "User Loges Out"]);
    }

}
