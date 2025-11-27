@extends('errors.layout')

@section('title', __('Too many requests'))
@section('code', '429')
@section('message', __('You have made too many requests in a short time. Please wait a moment before retrying.'))
