<?php

namespace Whozidis\HallOfFame\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use Whozidis\HallOfFame\Http\Requests\Settings\ResearcherSettingRequest;
use Whozidis\HallOfFame\Forms\Settings\ResearcherSettingForm;

class ResearcherSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/hall-of-fame::settings.researchers.title'));

        return ResearcherSettingForm::create()->renderForm();
    }

    public function update(ResearcherSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
