<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Whozidis\HallOfFame\Models\Researcher;
use Whozidis\HallOfFame\Models\VulnerabilityReport;
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
use Botble\Base\Facades\BaseHelper;
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
            ->setPreviousUrl(BaseHelper::getAdminPrefix() . '/researchers')
            ->setNextUrl(BaseHelper::getAdminPrefix() . '/researchers/edit/' . $researcher->getKey())
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
            ->setPreviousUrl(BaseHelper::getAdminPrefix() . '/researchers')
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
        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add(trans('plugins/hall-of-fame::researcher.register'), route('public.hall-of-fame.auth.register'));

        page_title()->setTitle(trans('plugins/hall-of-fame::researcher.register'));

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::auth.register')->render();
    }

    public function register(ResearcherRequest $request, BaseHttpResponse $response)
    {
        try {
            $data = $request->validated();

            if (!isset($data['password']) || !isset($data['name']) || !isset($data['email'])) {
                return $response
                    ->setError()
                    ->setCode(422)
                    ->setMessage(trans('plugins/hall-of-fame::researcher.required_fields_missing'));
            }

            $password = $data['password'];
            unset($data['password']);
            unset($data['password_confirmation']);

            $researcher = new Researcher();
            $researcher->fill($data);
            $researcher->password = Hash::make($password);

            if (!$researcher->save()) {
                throw new Exception(trans('plugins/hall-of-fame::researcher.failed_to_save'));
            }

            return $response
                ->setNextUrl(route('public.hall-of-fame.auth.login'))
                ->setMessage(trans('plugins/hall-of-fame::researcher.register_success'));
        } catch (Exception $exception) {
            Log::error('Researcher registration failed: ' . $exception->getMessage(), [
                'exception' => $exception,
                'request_data' => array_diff_key($request->validated(), array_flip(['password', 'password_confirmation']))
            ]);

            return $response
                ->setError()
                ->setCode(500)
                ->setMessage(trans('plugins/hall-of-fame::researcher.registration_error'));
        }
    }

    public function showLoginForm()
    {
        // Set the Hall of Fame theme layout
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        
        page_title()->setTitle(trans('plugins/hall-of-fame::researcher.login_title'));

        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::researcher.login_title'), route('public.hall-of-fame.auth.login'));

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::auth.login')->render();
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

        // Store researcher in session for Hall of Fame authentication
        session(['hof_researcher' => $researcher->id]);
        session(['hof_researcher_data' => $researcher->toArray()]);

        return $response
            ->setNextUrl(route('public.hall-of-fame.dashboard.index'))
            ->setMessage(trans('plugins/hall-of-fame::researcher.login_success'));
    }

    public function logout(Request $request, BaseHttpResponse $response)
    {
        // Clear Hall of Fame researcher session
        $request->session()->forget('hof_researcher');
        $request->session()->forget('hof_researcher_data');

        return $response
            ->setMessage(trans('plugins/hall-of-fame::researcher.logout_success'))
            ->setNextUrl(route('public.hall-of-fame.index'));
    }

    public function dashboard(Request $request)
    {
        $researcher = $request->hof_researcher;
        
        // Get researcher's reports for the dashboard stats
        $reports = VulnerabilityReport::where('researcher_email', $researcher->email)->get();
        
        // Set the Hall of Fame theme layout
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        
        page_title()->setTitle(trans('plugins/hall-of-fame::auth.dashboard'));

        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::auth.dashboard'), '');

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::auth.dashboard', compact('researcher', 'reports'))->render();
    }
}
