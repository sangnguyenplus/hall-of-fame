<?php

namespace Whozidis\HallOfFame\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Setting\Forms\SettingForm;
use Whozidis\HallOfFame\Http\Requests\Settings\PGPSettingRequest;

class PGPSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/hall-of-fame::settings.pgp.title'))
            ->setSectionDescription(trans('plugins/hall-of-fame::settings.pgp.description'))
            ->setValidatorClass(PGPSettingRequest::class)
            ->add('hall_of_fame_pgp_enabled', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.pgp.enable'))
                ->value((int) setting('hall_of_fame_pgp_enabled', 0))
            )
            ->add('hall_of_fame_pgp_uploaders', HtmlField::class, HtmlFieldOption::make()->view('plugins/hall-of-fame::settings.upload-buttons'))
            ->add('hall_of_fame_pgp_public_key', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.pgp.public_key'))
                ->rows(6)
                ->value(setting('hall_of_fame_pgp_public_key'))
                ->placeholder('-----BEGIN PGP PUBLIC KEY BLOCK-----\n...\n-----END PGP PUBLIC KEY BLOCK-----')
            )
            ->add('hall_of_fame_pgp_private_key', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.pgp.private_key'))
                ->rows(10)
                ->value(setting('hall_of_fame_pgp_private_key'))
                ->placeholder('-----BEGIN PGP PRIVATE KEY BLOCK-----\n...\n-----END PGP PRIVATE KEY BLOCK-----')
                ->helperText(trans('plugins/hall-of-fame::settings.pgp.private_key_helper'))
            )
            ->add('hall_of_fame_pgp_passphrase', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.pgp.passphrase'))
                ->value($this->getDecryptedPassphrase())
                ->attributes(['type' => 'password'])
            )
            ->add('hall_of_fame_pgp_sign_pdf', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.pgp.sign_pdf'))
                ->value((int) setting('hall_of_fame_pgp_sign_pdf', 0))
            )
            ->add('hall_of_fame_pgp_encrypt_pdf', OnOffField::class, OnOffFieldOption::make()
                ->label(trans('plugins/hall-of-fame::settings.pgp.encrypt_pdf'))
                ->value((int) setting('hall_of_fame_pgp_encrypt_pdf', 0))
            );
    }

    protected function getDecryptedPassphrase(): ?string
    {
        $stored = setting('hall_of_fame_pgp_passphrase');
        if (! $stored) {
            return null;
        }
        try {
            return decrypt($stored);
        } catch (\Throwable $e) {
            return $stored;
        }
    }
}
