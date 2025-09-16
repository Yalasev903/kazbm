<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SiteUserRequest;
use App\Models\Entities\MailEntity;
use App\Models\SiteUser;
use App\Services\MailService;
use App\Services\RecoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(SiteUserRequest $request, SiteUser $siteUser)
    {

        $siteUser->fill($request->all());
        $siteUser->password = Hash::make($request->post('password'));
        if ($siteUser->save()) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }

    public function login(LoginRequest $request)
    {

        $siteUser = SiteUser::query()
            ->where('email', $request->input('login'))
            ->first();

        if (!$siteUser || ($siteUser && !Hash::check($request->password, $siteUser->password))) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not exist or password mismatch'
            ], 422);
        }

        $this->guard()->login($siteUser);

        return response()->json(['status' => 'success']);
    }

    public function recovery(Request $request, RecoveryService $recoveryService)
    {

        $this->validate($request, ['email' => 'required|email']);

        if (!$recoveryService->hasUser($request->input('email'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'The user was not found'
            ], 422);
        }

        if (!($link = $recoveryService->getResetLink())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Try to do it in 60 seconds'
            ], 422);
        }

        $recoveryEntity = $recoveryService->getRecoveryEntity($link);
        (new MailService)->send($recoveryEntity);

        return response()->json(['status' => 'success', 'link' => $link]);
    }

    public function forgot(Request $request, RecoveryService $recoveryService)
    {

        if (!($email = $request->get('confirm'))
            || !$recoveryService->validateToken($email, $request->token))
            abort(419);

        $siteUser = SiteUser::query()
            ->where('email', $email)
            ->first();

        $this->guard()->login($siteUser);

        return response()->redirectTo('profile/index');
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['status' => 'success']);
    }

    protected function guard()
    {
        return Auth::guard('site');
    }
}
