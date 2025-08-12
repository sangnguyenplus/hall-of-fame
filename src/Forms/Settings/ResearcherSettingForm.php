<?php

namespace Whozidis\HallOfFame\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Setting\Forms\SettingForm;
use Whozidis\HallOfFame\Http\Requests\Settings\ResearcherSettingRequest;

class ResearcherSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/hall-of-fame::settings.researchers.title'))
            ->setSectionDescription(trans('plugins/hall-of-fame::settings.researchers.description'))
            ->setValidatorClass(ResearcherSettingRequest::class)
            ->add('hall_of_fame_researcher_auto_approve', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.researchers.auto_approve'))
                ->helperText(trans('plugins/hall-of-fame::settings.researchers.auto_approve_helper'))
                ->value((int) setting('hall_of_fame_researcher_auto_approve', 0))
            )
            ->add('hall_of_fame_researcher_require_verification', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.researchers.require_verification'))
                ->helperText(trans('plugins/hall-of-fame::settings.researchers.require_verification_helper'))
                ->value((int) setting('hall_of_fame_researcher_require_verification', 1))
            )
            ->add('hall_of_fame_researcher_email_notifications', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.researchers.email_notifications'))
                ->helperText(trans('plugins/hall-of-fame::settings.researchers.email_notifications_helper'))
                ->value((int) setting('hall_of_fame_researcher_email_notifications', 1))
            )
            ->add('hall_of_fame_researcher_public_profiles', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.researchers.public_profiles'))
                ->helperText(trans('plugins/hall-of-fame::settings.researchers.public_profiles_helper'))
                ->value((int) setting('hall_of_fame_researcher_public_profiles', 1))
            )
            ->add('hall_of_fame_researcher_default_status', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.researchers.default_status'))
                ->helperText(trans('plugins/hall-of-fame::settings.researchers.default_status_helper'))
                ->choices([
                    'pending' => trans('plugins/hall-of-fame::researcher.status.pending'),
                    'active' => trans('plugins/hall-of-fame::researcher.status.active'),
                    'inactive' => trans('plugins/hall-of-fame::researcher.status.inactive'),
                ])
                ->selected(setting('hall_of_fame_researcher_default_status', 'pending'))
            );
    }
}
