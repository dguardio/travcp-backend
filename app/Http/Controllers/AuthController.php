<?php

namespace App\Http\Controllers;

use App\Notifications\VerifyEmail;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\User as UserResource;
class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = request(['email', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->errorResponse(401, "You have provided an invalid login credentials", 'AuthenticationError');
            // return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id = auth()->user()->id;

        $user = User::findOrFail($id);
        $user->signed_in = true;
        $user->save();

        return $this->respondWithToken($token, new UserResource(auth()->user()));
    }
   
    public function register(){
        $this->validate(request(),[
            'email' => 'required|min:6|email|unique:users',
            'password' => 'required|min:6',
            'first_name' => 'required|min:3'
        ]);
        $data = request()->all();
        $data['password'] = \bcrypt(request()->password);
        $data['name'] = $data['first_name']." ".$data['surname'];
        $user = User::create($data);
        $credentials = request(['email', 'password']);
        // $credentials = ['email' => $user->email, 'password' => $user->password];

        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->errorResponse(401, "You have provided an invalid registration credentials", "RegistrationError");
            // return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->signed_in = true;
        $user->token = bin2hex(openssl_random_pseudo_bytes(100));
        $user->save();

        $this->sendVerificationMail($user);

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $id = auth()->user()->id;

        auth()->logout();

        $user = User::findOrFail($id);
        $user->signed_in = false;
        $user->save();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user=null)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Email Verification Methods
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    /**
     * send verification email to the user
     * @param User|null $user
     */
    private function sendVerificationMail(User $user = null){
        if(is_null($user) || !isset($user)){
            $user = auth()->user();
        }

        $token = $user->token;
        $user_id = $user->id;

        $user->notify(new VerifyEmail($token, $user_id));
    }

    /**
     * verify the user, return error if need be an persist to storage
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyUser(Request $request){
        $user_id = $request->input('user_id');
        $token = $request->input('token');

        $user = User::findOrFail($user_id);

        if($user->token == $token){

            $user->verified = true;
            $user->save();

            $response = ['congratulations! verification successful'];
            return response(['message'=> $response], 200);
        }

        $error = ['invalid token, resend verification mail ?'];
        return response(['error'=> $error], 500);
    }

    /**
     * if verification went wrong, resend verification mail
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendVerificationMail(Request $request){
        $user_id = $request->input('user_id');

        $user = User::findOrFail($user_id);
        $user->token = bin2hex(openssl_random_pseudo_bytes(100));
        $user->save();

        $this->sendVerificationMail($user);

        $response = ['verification email sent successfully'];
        return response(['message'=> $response], 200);
    }
}
