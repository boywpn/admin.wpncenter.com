
@foreach($config['setup']['entity'] as $entity)

    @if($entity['type'] == 'main')


        @foreach($entity['properties'] as $section => $field)
            @foreach($field as $key => $prop)
                @if($prop['type'] == 'manyToOne')
                    use Modules\{{$config['setup']['module_name']}}\Entities\{{$prop['belongs_to']}};
                @endif
            @endforeach
        @endforeach

    @endif

@endforeach

