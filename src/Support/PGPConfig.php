<?php

namespace Whozidis\HallOfFame\Support;

class PGPConfig
{
    public static function enabled(): bool
    {
        return (bool) setting('hall_of_fame_pgp_enabled', config('plugins.hall-of-fame.general.pgp.enabled'));
    }

    public static function publicKey(): ?string
    {
        return setting('hall_of_fame_pgp_public_key') ?: config('plugins.hall-of-fame.general.pgp.public_key');
    }

    public static function privateKey(): ?string
    {
        return setting('hall_of_fame_pgp_private_key') ?: config('plugins.hall-of-fame.general.pgp.private_key');
    }

    public static function passphrase(): ?string
    {
        return setting('hall_of_fame_pgp_passphrase') ?: config('plugins.hall-of-fame.general.pgp.passphrase');
    }

    public static function shouldSignPdf(): bool
    {
        return (bool) setting('hall_of_fame_pgp_sign_pdf', config('plugins.hall-of-fame.general.pgp.sign_pdf'));
    }

    public static function shouldEncryptPdf(): bool
    {
        return (bool) setting('hall_of_fame_pgp_encrypt_pdf', config('plugins.hall-of-fame.general.pgp.encrypt_pdf'));
    }
}
