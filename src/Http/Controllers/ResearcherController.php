<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Whozidis\HallOfFame\Models\Researcher;
use Whozidis\HallOfFame\Forms\ResearcherForm;
use Whozidis\HallOfFame\Http\Requests\ResearcherRequest;
use Whozidis\HallOfFame\Tables\ResearchersTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Facades\PageTitle;
use Exception;

class ResearcherController extends BaseController
{
    protected $researcherRepository;

    public function __construct()
    {
        // Could inject a repository if you're using the repository pattern
    }

    public function index(ResearchersTable $table)
    {
        PageTitle::setTitle(trans('plugins/hall-of-fame::researcher.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('plugins/hall-of-fame::researcher.create'));

        return $formBuilder->create(ResearcherForm::class)->renderForm();
    }

    public function store(ResearcherRequest $request, BaseHttpResponse $response)
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $researcher = Researcher::create($data);

        event(new CreatedContentEvent('RESEARCHER_MODULE_SCREEN_NAME', $request, $researcher));

        return $response
            ->setPreviousUrl(route('researchers.index'))
            ->setNextUrl(route('researchers.edit', $researcher->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        $researcher = Researcher::findOrFail($id);

        event(new BeforeEditContentEvent($request, $researcher));

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $researcher->name]));

        return $formBuilder->create(ResearcherForm::class, ['model' => $researcher])->renderForm();
    }

    public function update(int $id, ResearcherRequest $request, BaseHttpResponse $response)
    {
        $researcher = Researcher::findOrFail($id);

        $data = $request->validated();

        // Only update password if it's provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $researcher->update($data);

        event(new UpdatedContentEvent('RESEARCHER_MODULE_SCREEN_NAME', $request, $researcher));

        return $response
            ->setPreviousUrl(route('researchers.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $researcher = Researcher::findOrFail($id);

            $researcher->delete();

            event(new DeletedContentEvent('RESEARCHER_MODULE_SCREEN_NAME', $request, $researcher));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
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
