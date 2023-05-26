@extends('layouts.homepage')

@section('title', $page->title)
<style>
    .container {
        border-style: solid;
        border-width: 1px;
        border-color: #e5214f; /*#eb597b*/
    }

    .card-header {
        background-color: #e5214f !important; /*#eb597b*/
        color: white;
    }

    .body {
        font-family: 'Inter';
    }
</style>
@section('content')
<div class="col-md-10 mx-auto mt-5 p-5 container">
    <div class="form-group">        
        <h2 class="pt-5 mt-5 mb-5 text-center">{{$page->title}}</h2>        

        <div id="container">
            {!! $page->body !!}
        </div>

    </div>
</div>

@endsection

@section('javascript')
<script>
</script>

@endsection
