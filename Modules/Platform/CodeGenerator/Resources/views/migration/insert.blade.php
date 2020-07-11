@if(isset($entity['insert_data']))

    $dictValues = [
    @foreach($entity['insert_data'] as $key => $value)
        ['id' => {{$loop->iteration}}, 'name' => '{{$value}}'],
    @endforeach
    ];

    DB::table('{{ $entity['table'] }}')->insert($dictValues);

@endif