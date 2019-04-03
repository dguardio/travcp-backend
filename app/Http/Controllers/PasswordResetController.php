<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

Illuminate\Foundation\Auth\SendsPasswordResetEmails;
Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordResetController extends Controller
{
    use SendsPasswordResetEmails;
    use ResetsPassword;

    public function forgot(Request $request){
        return $this->sendResetLinkEmail($request);
    }

    protected function sendResetLinkResponse($response){
        return response()->json(['message' => "Password reset email sent successfully"]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response){
        return $this->errorResponse(405, "Email could not be sent to the specified email address");
    }

    public function doReset(Request $request){
        return $this->reset($request);
    }

    public function resetPassword($user, $password){
        $user->password = Hash::make($password);
        $user->save();

        event(new PasswordReset($user));
    }

    protected function sendResetResponse($response){
        return response()->json(['message' => "Password reset successful"]);
    }

    protected function sendResetFailedResponse(Request $request, $response){
        return $this->errorResponse(401, "Unauthorized");
    }
}
