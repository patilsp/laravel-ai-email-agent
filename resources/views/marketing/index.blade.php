@extends('layouts.app')

@section('content')
    <!-- Asymmetric Editorial Hero with Living Product Scene -->
    @include('marketing.partials.hero')

    <!-- Interactive Product Demonstration (Watch an inbox become a plan) -->
    @include('marketing.partials.interactive-demo')

    <!-- Art-Directed Feature Deep Dives (Prioritization, Context, Drafting, Human Control) -->
    @include('marketing.partials.features')

    <!-- Process & Architecture Workflow -->
    @include('marketing.partials.workflow')

    <!-- Frequently Asked Questions -->
    @include('marketing.partials.faq')

    <!-- Strong Closing Section with Early Access Enrollment -->
    @include('marketing.partials.final-cta')
@endsection
