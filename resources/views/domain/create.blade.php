@extends('layouts.main')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Pages Parser</h1>
        <hr class="my-4">
        <form action="{{ route('domains.store') }}" method="post">
            <label class="lead">
                Enter pages adress
            </label>
            @if($errors)
                @foreach($errors as $error)
                    <p style="color:red">{{ $error }}</p>
                @endforeach
            @endif
            <input type="text" required name="pagesAdress" class="lead" value=<?= $url ?? ''?>>
            <input type="submit" value="Enter" class="btn btn-primary btn-lg">
        </form>
    </div>
@endsection