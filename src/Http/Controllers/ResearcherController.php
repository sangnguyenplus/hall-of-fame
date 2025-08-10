<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Whozidis\HallOfFame\Models\Researcher;
use Whozidis\HallOfFame\Forms\ResearcherForm;
use Whozidis\HallOfFame\Http\Requests\ResearcherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Exception;

class ResearcherController extends BaseController
{
    public function showRegistrationForm(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/hall-of-fame::researcher.register'));

        return $formBuilder->create(ResearcherForm::class)->renderForm();
    }

    public function register(ResearcherRequest $request, BaseHttpResponse $response)
    {
        try {
            $data = $request->validated();
            
            if (!isset($data['password']) || !isset($data['name']) || !isset($data['email'])) {
                return $response
                    ->setError()
                    ->setCode(422)
                    ->setMessage('Required fields are missing');
            }

            $password = $data['password'];
            unset($data['password']);
            unset($data['password_confirmation']);
            
            $researcher = new Researcher();
            $researcher->fill($data);
            $researcher->password = Hash::make($password);
            
            if (!$researcher->save()) {
                throw new Exception('Failed to save researcher');
            }

            return $response
                ->setNextUrl(route('researchers.login'))
                ->setMessage(trans('plugins/hall-of-fame::researcher.register_success'));
        } catch (Exception $exception) {
            Log::error('Researcher registration failed: ' . $exception->getMessage(), [
                'exception' => $exception,
                'request_data' => array_diff_key($request->validated(), array_flip(['password', 'password_confirmation']))
            ]);
            
            return $response
                ->setError()
                ->setCode(500)
                ->setMessage('An error occurred while processing your registration. Please try again.');
        }
    }

    public function showLoginForm()
    {
        page_title()->setTitle(trans('plugins/hall-of-fame::researcher.login_title'));
        
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::researcher.login_title'), route('researchers.login'));

        $currentTheme = \Botble\Theme\Facades\Theme::getThemeName() ?: 'default';
        $viewPath = 'plugins/hall-of-fame::auth.login';
        
        if (! \Botble\Theme\Facades\Theme::uses()) {
            return view($viewPath);
        }

        return \Botble\Theme\Facades\Theme::scope($viewPath)->render();
    }

    public function login(Request $request, BaseHttpResponse $response)
    {
        $credentials = $request->only('email', 'password');
        $researcher = Researcher::where('email', $credentials['email'])->first();

        if (!$researcher || !Hash::check($credentials['password'], $researcher->password)) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/hall-of-fame::researcher.login_failed'));
        }

        // Generate a signed URL for the my-reports page
        $url = URL::signedRoute('public.vulnerability-reports.my-reports', ['researcher' => $researcher->id]);

        return $response
            ->setNextUrl($url)
            ->setMessage(trans('plugins/hall-of-fame::researcher.login_success'));
    }

    public function logout(Request $request, BaseHttpResponse $response)
    {
        $request->session()->forget('researcher_id');

        return $response
            ->setNextUrl(route('public.hall-of-fame'))
            ->setMessage(trans('plugins/hall-of-fame::researcher.logout_success'));
    }
}
