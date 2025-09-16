<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteUserRequest;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\SiteUser;
use Illuminate\Support\Facades\Hash;

class ProfileController extends AuthController
{

    public function index()
    {
        $user = $this->guard()->user();
        return view('pages.profile.index', compact('user'));
    }

    public function history()
    {

        $user = $this->guard()->user();
        $orderIds = OrderHistory::query()
            ->where('user_id', $user->id)
            ->pluck('order_id')
            ->toArray();

        $orders = Order::query()
            ->with(['orderInvoice'])
            ->whereIn('id', $orderIds)
            ->get();

        return view('pages.profile.history', compact('user', 'orders'));
    }

    public function settings(SiteUserRequest $request)
    {

        /** @var SiteUser $siteUser */
        $siteUser = $this->guard()->user();
        $siteUser->setRawAttributes($request->validated());
        $siteUser->surname = $request->post('surname');
        $siteUser->patronymic = $request->post('patronymic');
        $siteUser->password = Hash::make($request->post('password'));
        if (!$siteUser->save()) {
            return back()->with('error', 'Произошла ошибка!');
        }

        return back()->with('message', 'Успешно выполнено!');
    }
}
