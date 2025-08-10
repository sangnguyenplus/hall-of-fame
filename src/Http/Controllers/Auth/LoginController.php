<?php

namespace Whozidis\HallOfFame\Http\Controllers\Auth;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    public function showLoginForm()
    {
        PageTitle::setTitle(trans('plugins/hall-of-fame::auth.login'));
        SeoHelper::setTitle(trans('plugins/hall-of-fame::auth.login'));

        return view('plugins/hall-of-fame::auth.login');
    }

    public function login(Request $request, BaseHttpResponse $response)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $response
                ->setNextUrl(route('public.hall-of-fame.auth.dashboard'))
                ->setMessage(trans('plugins/hall-of-fame::auth.login_success'));
        }

        return redirect()->back()
            ->withErrors([
                'email' => trans('plugins/hall-of-fame::auth.failed'),
            ])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.hall-of-fame');
    }
}
