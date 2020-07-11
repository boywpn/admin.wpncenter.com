@foreach($config['setup']['entity'] as $entity)
    @if($entity['type'] == 'settings')
        ['route' => '{{  strtolower($moduleName) }}.{{ strtolower($entity['name']) }}.index', 'label' => 'settings.{{ strtolower($entity['name']) }}'],
    @endif
@endforeach