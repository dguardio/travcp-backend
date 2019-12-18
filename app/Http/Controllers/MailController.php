<?php

namespace App\Http\Controllers;

use App\Mails;
use App\User;
use Mail;
use App\Mail\Newsletter;
use Illuminate\Http\Request;

class MailController extends Controller
{
    
    public function index()
    {
        $mails = Mails::all();
        return view('vendor.voyager.newsletter.edit-list', compact('mails'));
    }

   
    public function create()
    {
        $users = User::all();
        return view('vendor.voyager.newsletter.edit-add', compact('users'));
    }


    public function store(Request $request)
    {
        $this -> validate($request, [
            'from' => 'required',
            'subject' => 'required',
            'body' => 'required'
        ]);

        $mail = new Mails;
        
        $mail->from = $request->from;
        $mail->subject = $request->subject;
        $mail->body = $request->body;
        
        $mail->save();

        // return $mail;

        $users = User::where('subscribed_to_newsletter', 1)->orderBy('created_at', 'desc')->get();

        foreach ($users as $user) {
            Mail::to($user->email, $user->first_name)->send(new Newsletter($mail));
        }

        return redirect()->route('mail.index')->with('message','Newsletter created and sent successfully');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $mail = Mails::where('id', $id)->first();
        return view('vendor.voyager.newsletter.edit', compact('mail'));
    }

    public function update(Request $request, $id)
    {
        $this -> validate($request, [
            'from' => 'required',
            'subject' => 'required',
            'body' => 'required'
        ]);

        $mail = Mails::find($id);
        
        $mail->from = $request->from;
        $mail->subject = $request->subject;
        $mail->body = $request->body;

        $mail -> save();

        return redirect(route('mail.index'))->with('message','Newsletter updated successfully');
    }

    public function destroy($id)
    {
        Mails::where('id',$id)->delete();
        return redirect()->back()->with('message', 'Newsletter deleted successfully');
    }
}
