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
            <div class="form-group">
                <label for="{{ $field->getName() }}"
                    class="{{ $field->getLabelAttributes()['class'] }}">{{ $field->getLabel() }}</label>
                {!! Form::file($field->getName() . '[]', array_merge($field->getAttributes(), ['multiple' => true])) !!}
                <small class="form-text text-muted">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.attachments_description') }}
                </small>
                @if ($errors->has($field->getName()))
                    <div class="invalid-feedback">
                        {{ $errors->first($field->getName()) }}
                    </div>
                @endif
            </div>
        @else
            @if ($field->getType() == 'html')
                {!! $field->getRender() !!}
            @else
                <div class="form-group mb-3 @if ($errors->has($field->getName())) has-error @endif">
                    @if ($field->getType() == 'checkbox')
                        <div class="form-check">
                            {!! Form::checkbox($field->getName(), 1, $field->getValue() == 1, $field->getAttributes()) !!}
                            <label for="{{ $field->getName() }}" class="form-check-label">
                                {!! $field->getLabel() !!}
                                @if (in_array('required', $field->getValidateClass()))
                                    <span class="required">*</span>
                                @endif
                            </label>
                        </div>
                    @else
                        <label for="{{ $field->getName() }}" class="{{ $field->getLabelAttributes()['class'] }}">
                            {!! $field->getLabel() !!}
                            @if (in_array('required', $field->getValidateClass()))
                                <span class="required">*</span>
                            @endif
                        </label>
                        {!! $field->render() !!}
                    @endif
                    @if ($errors->has($field->getName()))
                        <div class="invalid-feedback">
                            <span>{{ $errors->first($field->getName()) }}</span>
                        </div>
                    @endif
                    @if ($field->getHelpText())
                        <span class="help-block">
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
