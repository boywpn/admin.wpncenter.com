$table->increments('id');

@foreach($entity['properties'] as $section => $field)
    @foreach($field as $key => $prop)

        $table->{!! \Modules\Platform\CodeGenerator\Lib\FieldsHelper::migrationFiled($key,$prop) !!};

    @endforeach
@endforeach




$table->timestamps();
$table->softDeletes();