@include('plugins/hall-of-fame::partials.hof-master')

@if ($showStart)
    {!! Form::open([
        'url' => $url,
        'method' => $method,
        'class' => $formClass,
        'id' => $formId,
        'enctype' => $enctype,
    ]) !!}
@endif

@if ($showFields)
    @foreach ($fields as $field)
        @if ($field->getName() == 'attachments')
            <div class="hof-form-group">
                <label for="{{ $field->getName() }}"
                    class="{{ $field->getLabelAttributes()['class'] }} hof-form-label">{{ $field->getLabel() }}</label>
                {!! Form::file(
                    $field->getName() . '[]',
                    array_merge($field->getAttributes(), ['multiple' => true, 'class' => 'hof-form-control']),
                ) !!}
                <small class="hof-form-text">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.attachments_description') }}
                </small>
                @if ($errors->has($field->getName()))
                    <div class="hof-form-error">
                        {{ $errors->first($field->getName()) }}
                    </div>
                @endif
            </div>
        @else
            @if ($field->getType() == 'html')
                {!! $field->getRender() !!}
            @else
                <div class="hof-form-group @if ($errors->has($field->getName())) hof-form-error-state @endif">
                    @if ($field->getType() == 'checkbox')
                        <div class="hof-form-check">
                            {!! Form::checkbox(
                                $field->getName(),
                                1,
                                $field->getValue() == 1,
                                array_merge($field->getAttributes(), ['class' => 'hof-form-check-input']),
                            ) !!}
                            <label for="{{ $field->getName() }}" class="hof-form-check-label">
                                {!! $field->getLabel() !!}
                                @if (in_array('required', $field->getValidateClass()))
                                    <span class="hof-required">*</span>
                                @endif
                            </label>
                        </div>
                    @else
                        <label for="{{ $field->getName() }}" class="hof-form-label">
                            {!! $field->getLabel() !!}
                            @if (in_array('required', $field->getValidateClass()))
                                <span class="hof-required">*</span>
                            @endif
                        </label>
                        {!! $field->render() !!}
                    @endif
                    @if ($errors->has($field->getName()))
                        <div class="hof-form-error">
                            <span>{{ $errors->first($field->getName()) }}</span>
                        </div>
                    @endif
                    @if ($field->getHelpText())
                        <span class="hof-help-text">
                            <small>{!! $field->getHelpText() !!}</small>
                        </span>
                    @endif
                </div>
            @endif
        @endif
    @endforeach
@endif

@if ($showEnd)
    {!! Form::close() !!}
@endif
@endif
