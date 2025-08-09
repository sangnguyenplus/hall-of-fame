<?php

namespace Whozidis\HallOfFame;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function activated()
    {
        // Run when plugin is activated
    }

    public static function deactivated()
    {
        // Run when plugin is deactivated
    }

    public static function remove()
    {
        // Clean up after plugin is removed
        Schema::dropIfExists('vulnerability_reports');
        Schema::dropIfExists('vulnerability_attachments');
    }
}
