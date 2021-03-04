@extends('master')
@section('content')
<page-regular-maint-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}">
</page-regular-maint-component>
@endsection