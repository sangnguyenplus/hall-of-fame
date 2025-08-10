<?php

namespace Whozidis\HallOfFame\Forms;

use Botble\Base\Forms\FormAbstract;
use Whozidis\HallOfFame\Models\Researcher;

class ResearcherForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new Researcher)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('plugins/hall-of-fame::researcher.form.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/hall-of-fame::researcher.form.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'email', [
                'label' => trans('plugins/hall-of-fame::researcher.form.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/hall-of-fame::researcher.form.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('website', 'url', [
                'label' => trans('plugins/hall-of-fame::researcher.form.website'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/hall-of-fame::researcher.form.website_placeholder'),
                    'data-counter' => 255,
                ],
            ])
            ->add('twitter', 'text', [
                'label' => trans('plugins/hall-of-fame::researcher.form.twitter'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/hall-of-fame::researcher.form.twitter_placeholder'),
                    'data-counter' => 255,
                ],
            ])
            ->add('github', 'text', [
                'label' => trans('plugins/hall-of-fame::researcher.form.github'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/hall-of-fame::researcher.form.github_placeholder'),
                    'data-counter' => 255,
                ],
            ])
            ->add('bio', 'textarea', [
                'label' => trans('plugins/hall-of-fame::researcher.form.bio'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/hall-of-fame::researcher.form.bio_placeholder'),
                    'data-counter' => 400,
                ],
            ]);
    }
}
