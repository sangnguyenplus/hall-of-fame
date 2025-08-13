<?php

namespace Whozidis\HallOfFame\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use Whozidis\HallOfFame\Forms\Settings\PGPSettingForm;
use Whozidis\HallOfFame\Http\Requests\Settings\PGPSettingRequest;

class PGPSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/hall-of-fame::settings.pgp.title'));

        return PGPSettingForm::create()->renderForm();
    }

    public function update(PGPSettingRequest $request)
    {
        $data = $request->validated();

        if (! empty($data['hall_of_fame_pgp_passphrase'])) {
            try {
                $data['hall_of_fame_pgp_passphrase'] = encrypt($data['hall_of_fame_pgp_passphrase']);
            } catch (\Throwable $e) {
                // fallback to raw if encryption fails
            }
        }

        return $this->performUpdate($data);
    }
}
