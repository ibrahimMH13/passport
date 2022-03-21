<?php

namespace App\Http\Controllers\Auth\OTP;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
#use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA;

class Google2faController extends Controller
{
    public function index(Google2FA $google2FA){
        $user = Auth::user();
        $secretKey  =$google2FA->generateSecretKey();
        $user->update([
           'google2fa_secret'=> $secretKey
        ]);
        $user->refresh();

        $qrCodeUrl = $google2FA->getQRCodeInline(
            'MH7',
            $user->email,
            $secretKey
        );
        return  view('qr')->with([
            'qr' => $qrCodeUrl
        ]);
    }

    public function store(Request $request,Google2FA $google2FA){
        $user = Auth::user();
        $valid = $google2FA->verifyKey($user->google2fa_secret, $request->get('key'));
        dd($valid);
    }
}
