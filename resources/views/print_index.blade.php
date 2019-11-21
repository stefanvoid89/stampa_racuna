@extends('master')
@section('content')
<page-print-index-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}">
</page-print-index-component>
@endsection
