@extends('errors.layout')

@section('title', __('Session expired'))
@section('code', '419')
@section('message', __('Your session has expired. Refresh the page and try again.'))
