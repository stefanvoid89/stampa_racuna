@extends('master')
@section('content')
<page-warranty-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}">
</page-warranty-component>
@endsection

