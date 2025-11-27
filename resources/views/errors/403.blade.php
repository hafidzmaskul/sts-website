@extends('errors.layout')

@section('title', __('You do not have access'))
@section('code', '403')
@section('message', __('This request is not allowed. Make sure you have the right permissions or contact an administrator.'))
