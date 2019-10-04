@extends('layouts.main')

@section('content')
    <table class='table table-bordered table-hover table-sm'>
        <thead class='thead-dark'>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        @foreach($domains as $domain)
            <tr class='table-info'>
                <td>{{ $domain->id }}</td>
                <td><a href="{{ $domain->name }}">{{ $domain->name }}</a></td>
            </tr>
        @endforeach
    </table>

    {{ $domains->links() }}
@endsection