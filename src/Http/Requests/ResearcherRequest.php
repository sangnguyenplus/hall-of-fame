<?php

namespace Whozidis\HallOfFame\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Support\Facades\Route;

class ResearcherRequest extends Request
{
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|max:255',
            'github' => 'nullable|max:255',
            'bio' => 'nullable|max:400',
        ];

        // Make email unique except for the current record
        $id = request()->segment(count(request()->segments()));
        if (is_numeric($id)) {
            $rules['email'] .= '|unique:hof_researchers,email,' . $id;

            // Password is optional for existing records
            if (!empty(request()->password)) {
                $rules['password'] = 'nullable|min:8|confirmed';
                $rules['password_confirmation'] = 'nullable|min:8';
            }
        } else {
            $rules['email'] .= '|unique:hof_researchers,email';
            $rules['password'] = 'required|min:8|confirmed';
            $rules['password_confirmation'] = 'required|min:8';
        }

        return $rules;
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
