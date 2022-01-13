<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nexmo;
use Twilio\Rest\Client;
use App\OtpConfiguration;
use App\User;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$users = User::all();
        return view('otp_systems.sms.index',compact('users'));
    }

    //send message to multiple users
    public function send(Request $request)
    {
        $sent_success = 0;
		$request->session()->flash('BulkSMSBody',$request->content);
        foreach ($request->user_phones as $key => $phone) {
            if ($phone!=null){
                sendSMS($phone, env('APP_NAME'), $request->content, $request->template_id);
                $sent_success = 1; }
        }

        if ($sent_success == 1) {
            flash(translate('SMS has been sent.'))->success(); }
        else{
            flash(translate('SMS failed to sent.'))->warning(); }

        return redirect()->route('sms.index');
    }
}
