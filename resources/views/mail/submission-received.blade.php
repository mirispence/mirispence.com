{{-- Plain-text mail: raw output is safe here, and escaping would corrupt apostrophes and ampersands. --}}
@php($commission = $submission->commissionRequest)
From: {!! $submission->name !!} <{!! $submission->email !!}>
Subject: {!! $submission->subject !!}
@if ($commission?->budget_range)
Budget Range: {!! $commission->budget_range !!}
@endif
@if ($commission?->desired_due_date)
Desired Due Date: {!! $commission->desired_due_date->format('F j, Y') !!}
@endif

{!! $submission->message !!}
