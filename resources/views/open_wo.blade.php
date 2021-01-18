@extends('master')
@section('content')
<page-open-wo-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}">
</page-open-wo-component>
@endsection