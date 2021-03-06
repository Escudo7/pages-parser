@extends('layouts.main')

@section('content')
    <table class='table table-bordered table-hover table-sm'>
        <thead class='thead-dark'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Content-length</th>
                <th>Status code</th>
                <th>Heading</th>
                <th>Keywords</th>
                <th>Description</th>
            </tr>
        </thead>
        <tr>
            <td>{{ $domain->id }}</td>
            <td><a href="{{ $domain->name }}">{{ $domain->name }}</a></td>
            <td>{{ $domain->content_length }}</td>
            <td>{{ $domain->status_code }}</td>
            <td>{{ $domain->heading }}</td>
            <td>{{ $domain->keywords }}</td>
            <td>{{ $domain->description }}</td>
        </tr>
    </table>
@endsection