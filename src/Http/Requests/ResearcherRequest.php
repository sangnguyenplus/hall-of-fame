<?php

namespace Whozidis\HallOfFame\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ResearcherRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:hof_researchers,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('plugins/hall-of-fame::researcher.name_required'),
            'email.required' => trans('plugins/hall-of-fame::researcher.email_required'),
            'email.email' => trans('plugins/hall-of-fame::researcher.email_invalid'),
            'email.unique' => trans('plugins/hall-of-fame::researcher.email_unique'),
            'password.required' => trans('plugins/hall-of-fame::researcher.password_required'),
            'password.min' => trans('plugins/hall-of-fame::researcher.password_min'),
            'password.confirmed' => trans('plugins/hall-of-fame::researcher.password_confirmation_match'),
        ];
    }
}
