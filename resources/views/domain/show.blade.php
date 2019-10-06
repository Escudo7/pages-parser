@extends('layouts.main')

@section('content')
    <table class='table table-bordered table-hover table-sm'>
        <thead class='thead-dark'>
            <tr>
            <th>ID</th>
                <th>Name</th>
                <th>Content-length</th>
                <th>Status code</th>
            </tr>
        </thead>
        <tr class='table-info'>
            <td>{{ $domain->id }}</td>
            <td><a href="{{ $domain->name }}">{{ $domain->name }}</a></td>
            <td>{{ $domain->content_length }}</td>
            <td>{{ $domain->status_code }}</td>
        </tr>
    </table>
@endsection