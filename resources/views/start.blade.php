@extends('layouts.main')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Pages Parser</h1>
        <hr class="my-4">
        <form action="/domains" method="post">
            <label class="lead">
                Enter pages adress
            </label>
            <input type="text" required name="pagesAdress" class="lead">
            <input type="submit" value="Enter" class="btn btn-primary btn-lg">
        </form>
    </div>
@endsection