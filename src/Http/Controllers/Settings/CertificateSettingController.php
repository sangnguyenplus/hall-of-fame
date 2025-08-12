<?php

namespace Whozidis\HallOfFame\Http\Controllers\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Facades\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateSettingController extends BaseController
{
    public function edit()
    {
        $settings = [
            'certificate_template_style' => Setting::get('hall_of_fame_certificate_template_style', 'professional'),
            'certificate_language_default' => Setting::get('hall_of_fame_certificate_language_default', 'en'),
            'certificate_auto_generate' => Setting::get('hall_of_fame_certificate_auto_generate', false),
            'certificate_include_qr_code' => Setting::get('hall_of_fame_certificate_include_qr_code', true),
            'certificate_watermark_opacity' => Setting::get('hall_of_fame_certificate_watermark_opacity', 0.1),
            'certificate_signature_required' => Setting::get('hall_of_fame_certificate_signature_required', true),
            'certificate_export_formats' => Setting::get('hall_of_fame_certificate_export_formats', ['pdf']),
            'certificate_email_delivery' => Setting::get('hall_of_fame_certificate_email_delivery', false),
            'certificate_public_verification' => Setting::get('hall_of_fame_certificate_public_verification', true),
            'certificate_batch_processing' => Setting::get('hall_of_fame_certificate_batch_processing', true),
        ];

        return view('plugins/hall-of-fame::settings.certificates.edit', compact('settings'));
    }

    public function update(Request $request, BaseHttpResponse $response)
    {
        $validator = Validator::make($request->all(), [
            'certificate_template_style' => 'required|in:professional,modern,classic',
            'certificate_language_default' => 'required|in:en,ar',
            'certificate_auto_generate' => 'boolean',
            'certificate_include_qr_code' => 'boolean',
            'certificate_watermark_opacity' => 'numeric|min:0|max:1',
            'certificate_signature_required' => 'boolean',
            'certificate_export_formats' => 'array',
            'certificate_export_formats.*' => 'in:pdf,png,jpg',
            'certificate_email_delivery' => 'boolean',
            'certificate_public_verification' => 'boolean',
            'certificate_batch_processing' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'hall_of_fame_certificate_template_style' => $request->get('certificate_template_style'),
            'hall_of_fame_certificate_language_default' => $request->get('certificate_language_default'),
            'hall_of_fame_certificate_auto_generate' => $request->boolean('certificate_auto_generate'),
            'hall_of_fame_certificate_include_qr_code' => $request->boolean('certificate_include_qr_code'),
            'hall_of_fame_certificate_watermark_opacity' => (float) $request->get('certificate_watermark_opacity', 0.1),
            'hall_of_fame_certificate_signature_required' => $request->boolean('certificate_signature_required'),
            'hall_of_fame_certificate_export_formats' => $request->get('certificate_export_formats', ['pdf']),
            'hall_of_fame_certificate_email_delivery' => $request->boolean('certificate_email_delivery'),
            'hall_of_fame_certificate_public_verification' => $request->boolean('certificate_public_verification'),
            'hall_of_fame_certificate_batch_processing' => $request->boolean('certificate_batch_processing'),
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::save();

        return redirect()
            ->route('hall-of-fame.settings.certificates.edit')
            ->with('success', 'Certificate settings updated successfully!');
    }
}
