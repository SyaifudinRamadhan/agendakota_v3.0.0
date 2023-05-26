@extends('layouts.homepage')

@section('title', 'FAQ')
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
        <a class="d-flex justify-content-center mt-5" href="/"><img src="{{ asset('images/logo.png') }}" class="lebar-50"></a>
        <h2 class="pt-5 mt-5 mb-5 text-center">Frequently Asked Questions (FAQ)</h2>        

        <div id="accordion">
            @foreach ($faq as $faq)
                <div class="card mt-3">
                    <div class="card-header" id="heading-{{ $faq->id }}" data-toggle="collapse"
                        data-target="#collapse-{{ $faq->id }}" aria-expanded="false"
                        aria-controls="collapse-{{ $faq->id }}">
                        <h5 class="mb-0">                           
                                {{ $faq->pertanyaan }}
                        </h5>
                    </div>
                    <div id="collapse-{{ $faq->id }}" class="collapse"
                        aria-labelledby="heading-{{ $faq->id }}" data-parent="#accordion">
                        <div class="card-body">
                            {{ $faq->jawaban }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

@endsection

@section('javascript')
<script>
</script>

@endsection
