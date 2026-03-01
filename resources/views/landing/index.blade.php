@extends('layouts.landing')

@section('content')
    <!-- Mengimpor section Hero -->
    @include('landing.sections.hero')

    <!-- Mengimpor section Fitur (AI & Payment) -->
    @include('landing.sections.features')

    <!-- Mengimpor section Call to Action (CTA) -->
    @include('landing.sections.cta')
@endsection
