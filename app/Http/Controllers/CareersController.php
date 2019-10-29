<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CareersMail;
class CareersController extends Controller
{
    public function sendMail(Request $request) {
        $feedback = $request->all();
        
        Mail::to('kelanik8@gmail.com')->send(new CareersMail($feedback));

        return response([
            'data'=> null,
            'message' => 'Message Sent succesfully',
            'status'=> 'success'
        ], 200);
    }
}
