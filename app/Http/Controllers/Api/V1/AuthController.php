<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Post(
     *   path="/login",
     *   summary="login Api",
     *
     *
     *     @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email.",
     *     required=true,
     *     type="string"
     *   ),
     *     @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="password.",
     *     required=true,
     *     type="string"
     *   ),
     *
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Post(
     *   path="/register",
     *   summary="Register Api",
     *
     *
     *     @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email.",
     *     required=true,
     *     type="string"
     *   ),
     *
     *     @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="Name .",
     *     required=true,
     *     type="string"
     *   ),
     *     @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="password.",
     *     required=true,
     *     type="string"
     *   ),
     *
     *     @SWG\Parameter(
     *     name="c_password",
     *     in="formData",
     *     description="Re password.",
     *     required=true,
     *     type="string"
     *   ),
     *
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Post(
     *   path="/profile",
     *   summary="My Profile Api",
     *
     *
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="use Bearer . Token",
     *     required=true,
     *     type="string"
     *   ),
     *
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function profile()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
