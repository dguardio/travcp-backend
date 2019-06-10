<?php

namespace App\Http\Controllers;

use App\Notifications\VerifyEmail;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\User as UserResource;
class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verifyUser', 'resendVerificationMail', "createReferralToken"]]);
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

    /**
     * register a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(){
        $this->validate(request(),[
            'email' => 'required|min:6|email|unique:users',
            'password' => 'required|min:6',
            'first_name' => 'required|min:3'
        ]);
        $data = request()->all();
        $data['password'] = \bcrypt(request()->password);


        if(isset($data["referrer_token"])){
            try{
                $referrer = User::where('referral_token', $data["referrer_token"])->firstOrFail();
                $data['referrer_id'] = $referrer->id;

                unset($data["referrer_token"]);
            }catch (ModelNotFoundException $e){}
        }

        $user = User::create($data);
        $credentials = request(['email', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->errorResponse(401, "You have provided an invalid registration credentials", "RegistrationError");
        }


        $user->referral_token = User::generateReferralId();
        $user->signed_in = true;
        $user->verify_token = bin2hex(openssl_random_pseudo_bytes(50));
        $user->save();

        $this->sendVerificationMail($user);

        return $this->respondWithToken($token, new UserResource(User::find($user->id)));
    }

    /**
     * return user with newly created referral token
     * @return UserResource|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function createReferralToken(){
        $id = \request()->user_id;

        try{
            $user = User::findOrFail($id);
            if(is_null($user->referral_token)){
                $user->referral_token = User::generateReferralId();
            }
            $user->save();
        }catch (ModelNotFoundException $e){
            $errors = ["user with id ".$id." not found"];
            return response(['errors'=> $errors], 404);
        }

        return new UserResource($user);
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

        $token = $user->verify_token;
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

        if($user->verify_token == $token){

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
        $user->verify_token = bin2hex(openssl_random_pseudo_bytes(100));
        $user->save();

        $this->sendVerificationMail($user);

        $response = ['verification email sent successfully'];
        return response(['message'=> $response], 200);
    }
}
