@extends('master')
@section('content')
<page-print-warranty-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}">
</page-print-warranty-component>
@endsection
