@extends('layouts.website')

@section('content')
    <section class="bg-lighter" style="direction: rtl">
        <div class="container">
            <div class="row">
        {!! $privacy->arabic_privacy !!}
            </div>
        </div>
    </section>
    <section class="bg-lighter" style="direction: ltr">
        <div class="container">
            <div class="row">
                {!! $privacy->english_privacy !!}
            </div>
        </div>
    </section>
@endsection
