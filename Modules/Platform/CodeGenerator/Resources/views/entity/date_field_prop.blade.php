
@foreach($entity['properties'] as $section => $field)
    @foreach($field as $key => $prop)
        @if($prop['type'] == 'date')

            /**
            * Required to proper parse date provided in user date format
            * @param $value
            */
            public function set{{ \Modules\Platform\CodeGenerator\Lib\FieldsHelper::dateFieldName($key) }}Attribute($value)
            {
                $parsed = Carbon::parse($value);

                $this->attributes['{{$key}}'] = $parsed;
            }

        @endif
    @endforeach
@endforeach




