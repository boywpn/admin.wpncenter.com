

@foreach($entity['properties'] as $section => $field)
    @foreach($field as $key => $prop)
        @if($prop['type'] == 'manyToOne')

            /**
            * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
            */
            public function {{$prop['relation']}}()
            {
            return $this->belongsTo({{$prop['belongs_to']}}::class);
            }

        @endif
    @endforeach
@endforeach




