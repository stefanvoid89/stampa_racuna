@extends('master')
@section('content')
<page-crm-index-component :prop_data="{{ $prop_data->toJson() }}" title="{{$title}}">
</page-crm-index-component>
@endsection

