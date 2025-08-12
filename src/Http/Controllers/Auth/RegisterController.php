<?php

namespace Whozidis\HallOfFame\Http\Controllers\Auth;

use Botble\ACL\Models\User;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends BaseController
{
    public function showRegistrationForm()
    {
        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Register', route('public.hall-of-fame.auth.register'));

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::auth.register')->render();
    }

    public function register(Request $request, BaseHttpResponse $response)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->fill([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        $user->save();

        Auth::login($user);

        return $response
            ->setNextUrl(route('public.hall-of-fame.auth.dashboard'))
            ->setMessage(trans('plugins/hall-of-fame::auth.register_success'));
    }
}
