<?php

namespace Whozidis\HallOfFame\Http\Requests\Settings;

use Botble\Setting\Http\Requests\SettingRequest as BaseSettingRequest;

class PGPSettingRequest extends BaseSettingRequest
{
    public function rules(): array
    {
        return [
            'hall_of_fame_pgp_enabled' => ['nullable', 'in:0,1'],
            'hall_of_fame_pgp_public_key' => ['nullable', 'string'],
            'hall_of_fame_pgp_private_key' => ['nullable', 'string'],
            'hall_of_fame_pgp_passphrase' => ['nullable', 'string', 'max:255'],
            'hall_of_fame_pgp_sign_pdf' => ['nullable', 'in:0,1'],
            'hall_of_fame_pgp_encrypt_pdf' => ['nullable', 'in:0,1'],
        ];
    }
}
