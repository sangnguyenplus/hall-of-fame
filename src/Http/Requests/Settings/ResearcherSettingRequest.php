<?php

namespace Whozidis\HallOfFame\Http\Requests\Settings;

use Botble\Setting\Http\Requests\SettingRequest as BaseSettingRequest;

class ResearcherSettingRequest extends BaseSettingRequest
{
    public function rules(): array
    {
        return [
            'hall_of_fame_researcher_auto_approve' => ['nullable', 'in:0,1'],
            'hall_of_fame_researcher_require_verification' => ['nullable', 'in:0,1'],
            'hall_of_fame_researcher_email_notifications' => ['nullable', 'in:0,1'],
            'hall_of_fame_researcher_public_profiles' => ['nullable', 'in:0,1'],
            'hall_of_fame_researcher_default_status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
