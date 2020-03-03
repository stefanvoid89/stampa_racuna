@extends('master')
@section('content')
<warranty-insert-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}" :_id="{{$id}}">
</warranty-insert-component>
@endsection
