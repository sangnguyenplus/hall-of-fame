<?php

// Dev-only stubs for GNUPG functions to satisfy static analyzers when ext-gnupg is not installed.
// These stubs are defined only if the GNUPG extension is not loaded.
// Runtime code should always guard with extension_loaded('gnupg') before using these.

if (! extension_loaded('gnupg')) {
    if (! function_exists('gnupg_init')) {
        function gnupg_init()
        {
            throw new RuntimeException('gnupg extension is not installed');
        }
    }

    if (! function_exists('gnupg_sethome')) {
        function gnupg_sethome($res, string $dir): void
        {
            // no-op in stub
        }
    }

    if (! function_exists('gnupg_import')) {
        function gnupg_import($res, string $key)
        {
            throw new RuntimeException('gnupg_import not available');
        }
    }

    if (! function_exists('gnupg_clearsignkeys')) {
        function gnupg_clearsignkeys($res): void
        {
            // no-op
        }
    }

    if (! function_exists('gnupg_addsignkey')) {
        function gnupg_addsignkey($res, string $keyId, ?string $passphrase = null): void
        {
            // no-op
        }
    }

    if (! function_exists('gnupg_sign')) {
        function gnupg_sign($res, string $text)
        {
            throw new RuntimeException('gnupg_sign not available');
        }
    }

    if (! function_exists('gnupg_verify')) {
        function gnupg_verify($res, string $text, string $signature)
        {
            throw new RuntimeException('gnupg_verify not available');
        }
    }

    if (! function_exists('gnupg_clearencryptkeys')) {
        function gnupg_clearencryptkeys($res): void
        {
            // no-op
        }
    }

    if (! function_exists('gnupg_addencryptkey')) {
        function gnupg_addencryptkey($res, string $keyId): void
        {
            // no-op
        }
    }

    if (! function_exists('gnupg_encrypt')) {
        function gnupg_encrypt($res, string $text)
        {
            throw new RuntimeException('gnupg_encrypt not available');
        }
    }
}
