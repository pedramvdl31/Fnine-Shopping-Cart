@extends('layouts.emails.template')

@section('title', 'Confirm Account Deletion')

@section('header', 'Confirm Account Deletion')

@section('content')
<p>Hi {{ $user->name }},</p>
<p>You have requested to delete your account. Please confirm by clicking the link below:</p>
<a href="{{ $deleteLink }}" class="button">Confirm Account Deletion</a>
<p>If you did not make this request, you can safely ignore this email.</p>
@endsection
