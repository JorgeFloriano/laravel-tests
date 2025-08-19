@extends('layout')

@section('content')

    <h2>Login Form Test</h2>

    <form action="{{ route('login.store') }}" method="post">
        @csrf
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>

@endsection