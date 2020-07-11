@foreach($config['setup']['entity'] as $entity)
    @if($entity['type'] == 'main')
        @foreach($entity['properties'] as $section => $field)

            '{{$section}}' => [
                @foreach($field as $fieldKey => $value)

                    @if(!isset($value['hide']))
                    '{{$fieldKey}}' => [
                        'type' => '{{ \Modules\Platform\CodeGenerator\Lib\FieldsHelper::getType($value) }}',
                        @if($value['type'] == 'manyToOne')
                         'relation' => '{{$value['relation']}}',
                         'column' => '{{$value['display_column']}}'
                         @endif
                    ],
                    @endif

                @endforeach
            ],

        @endforeach
    @endif
@endforeach