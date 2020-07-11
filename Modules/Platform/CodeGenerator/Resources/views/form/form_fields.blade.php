@foreach($config['setup']['entity'] as $entity)

    @if($entity['type'] == 'main')

        @foreach($entity['properties'] as $section => $field)
            @foreach($field as $key => $prop)

                @if($prop['type'] == 'text')

                    $this->add('{{$key}}', 'textarea', [
                    'label' => trans('{{ strtolower($config['setup']['module_name'])  }}::{{ strtolower($config['setup']['module_name']) }}.form.{{$key}}'),
                    ]);
                @elseif($prop['type'] == 'boolean' && !isset($prop['hide']))

                    $this->add('{{$key}}', 'checkbox', [
                    'label' => trans('{{ strtolower($config['setup']['module_name'])  }}::{{ strtolower($config['setup']['module_name']) }}.form.{{$key}}'),
                    ]);
                @elseif(($prop['type'] == 'date' || $prop['type'] == 'datetime' ) && !isset($prop['hide']))

                    $this->add('{{$key}}', 'dateType', [
                    'label' => trans('{{ strtolower($config['setup']['module_name'])  }}::{{ strtolower($config['setup']['module_name']) }}.form.{{$key}}'),
                    ]);
                @elseif(($prop['type'] == 'decimal')  && !isset($prop['hide']))

                    $this->add('{{$key}}', 'number', [
                    'label' => trans('{{ strtolower($config['setup']['module_name'])  }}::{{ strtolower($config['setup']['module_name']) }}.form.{{$key}}'),
                    ]);
                @elseif(($prop['type'] == 'integer')  && !isset($prop['hide']))

                    $this->add('{{$key}}', 'number', [
                    'label' => trans('{{ strtolower($config['setup']['module_name'])  }}::{{ strtolower($config['setup']['module_name']) }}.form.{{$key}}'),
                    ]);
                @elseif(($prop['type'] == 'string')  && !isset($prop['hide']))

                    $this->add('{{$key}}', 'text', [
                    'label' => trans('{{ strtolower($config['setup']['module_name'])  }}::{{ strtolower($config['setup']['module_name']) }}.form.{{$key}}'),
                    ]);

                @elseif($prop['type'] == 'ownedBy')

                    $this->add('owned_by', 'select', [
                    'choices' => FormHelper::assignedToChoises(),
                    'attr' => ['class' => 'select2 pmd-select2 form-control'],
                    'label' => trans('core::core.form.assigned_to'),
                    'empty_value' => trans('core::core.empty_select'),
                    'selected' => FormHelper::assignSelectedFromModel($this->model)
                    ]);

                @elseif($prop['type'] == 'manyToOne')

                    $this->add('{{$key}}', 'select', [
                    'choices' => {{$prop['belongs_to']}}::all()->pluck('{{$prop['display_column']}}','id')->toArray(),
                    'attr' => ['class' => 'select2 pmd-select2 form-control'],
                    'label' => trans('{{strtolower($config['setup']['module_name'])}}::{{strtolower($config['setup']['module_name'])}}.form.{{$key}}'),
                    'empty_value' => trans('core::core.empty_select')
                    ]);

                @else

                @endif

            @endforeach
        @endforeach

    @endif

@endforeach

