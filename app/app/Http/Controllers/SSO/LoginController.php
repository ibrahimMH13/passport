<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LoginController extends Controller
{
/*Client ID: 95dd5a60-12c8-4d33-8ef0-18fafd69fb7b
Client secret: g4gTCpVOMelzHivbLTh0mtYvUQsXhmI68NQhRUN5*/
    public function index(Request $request){
      $request->session()->put(['state' =>$state  = Str::random(40)]);
      $query = http_build_query([
         'client_id' =>'95dd5a60-12c8-4d33-8ef0-18fafd69fb7b',
         'redirect_uri' => 'http://localhost:8040/sso/oauth/callback',
         'response_type' =>'code',
         'scope' =>'view-user',
         'state' =>$state,
      ]);
        return redirect('http://localhost:8020/oauth/authorize?'.$query);
    }


    public function callback(Request $request)
    {
        $requestState = $request->get('state');
        $sessionState = $request->session()->pull('state');
      //  throw_unless(strlen($requestState) > 0 && $requestState == $sessionState, InvalidArgumentException::class);
        $response = Http::asForm()->post('http://localhost:8020/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => '95dd5a60-12c8-4d33-8ef0-18fafd69fb7b',
            'client_secret' => 'g4gTCpVOMelzHivbLTh0mtYvUQsXhmI68NQhRUN5',
            'redirect_uri' => 'http://localhost:8040/sso/oauth/callback',
            'code' => $request->code,
        ]);
        $request->session()->put($response->json());
        return redirect()->route('sso.user');
    }


    public function authUser(Request $request){
        $access_token = $request->session()->pull("access_token");
        $expires_in = $request->session()->pull("expires_in");
        $refresh_token = $request->session()->pull("refresh_token");
        $token_type = $request->session()->pull("token_type");
        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
        ])->get("http://localhost:8020/api/user");
        $userArray = $response->json();
     $user = User::firstOrCreate([
         'email' => $userArray['email']
      ],[
         'name'=>$userArray['name'],
         'password'=>Str::random('32'),
     ]);
     $user->tokens()->create([
         'token_type'=>$token_type,
         'expires_in'=>$expires_in,
         'access_token'=>$access_token,
         'refresh_token'=>$refresh_token,
     ]);
     Auth::login($user);
     return redirect()->route('home');
    }

    public function refresh(Request $request){
        $response = Http::asForm()->post('http://localhost:8020/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->user()->tokens->refresh_token,
            'client_id' => '95dd5a60-12c8-4d33-8ef0-18fafd69fb7b',
            'client_secret' => 'g4gTCpVOMelzHivbLTh0mtYvUQsXhmI68NQhRUN5',
            'scope' =>'view-user',
        ])->json();
         $request->user()->tokens()->update([
            'expires_in'=>$response['expires_in'],
            'access_token'=>$response['access_token'],
            'refresh_token'=>$response['refresh_token'],
        ]);
        return redirect()->back();
    }



}
