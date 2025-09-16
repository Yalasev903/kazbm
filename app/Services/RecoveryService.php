<?php

namespace App\Services;

use App\Models\Entities\MailEntity;
use App\Models\SiteUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecoveryService
{

    private $_user;

    public function hasUser(string $email): bool
    {

        $siteUser = SiteUser::query()
            ->where('email', $email)
            ->first();
        $this->_user = $siteUser;

        return $siteUser ? true : false;
    }

    public function getResetLink(): ?string
    {

        $email = $this->_user->email;
        $record = $this->query()->where('email', $email)->first();

        if (($record && $this->tokenExpired($record->created_at)) || !$record) {

            $this->query()->where('email', $email)->delete();
            $token = $this->createNewToken();
            $this->query()->insert([
                'email' => $email, 'token' => Hash::make($token), 'created_at' => new Carbon
            ]);

            return route('user.forgot_password', $token) ."?confirm=$email";
        }

        return null;
    }

    public function validateToken(string $email, string $token)
    {

        $record = $this->query()->where('email', $email)->first();
        if ($record && Hash::check($token, $record->token)) {
            return !Carbon::parse($record->created_at)->addMinutes(15)->isPast();
        }

        return false;
    }

    public function getRecoveryEntity($link): MailEntity
    {
        $entity = new MailEntity();
        $entity->subject =  'kazbm.kz: Восстановления пароля';
        $entity->sendTo = $this->_user->email;
        $entity->message = view('emails.recovery_password', compact('link'))->render();
        return $entity;
    }

    protected function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds(60)->isPast();
    }

    private function query()
    {
        return DB::table('password_reset_tokens');
    }
}
