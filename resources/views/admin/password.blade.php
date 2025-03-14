@extends('admin.layout')

@section('content')
    <div class="container">
        <h3>Enter Password to Access {{ $user->name }}'s Files</h3>
        <form action="{{ route('admin.verifyPassword', $user->id) }}" method="POST">
            @csrf
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>
@endsection


