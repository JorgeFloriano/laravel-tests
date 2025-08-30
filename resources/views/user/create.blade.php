@extends('layout')

@section('content')

    <h2>Create User</h2>

    <form action="{{ route('user.store') }}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Your Name">
        <input type="email" name="email" placeholder="Your Email">
        <input type="password" name="password" placeholder="Your Password">
        <button type="submit" class="mt-2 btn btn-warning btn-sm">Create</button>
    </form>

@endsection