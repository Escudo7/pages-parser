@extends('layouts.main')

@section('content')
    <table class='table table-bordered table-hover table-sm'>
        <thead class='thead-dark'>
            <tr>
                <th>ID</td>
                <th>Name</td>
            </tr>
        </thead>
        <tr class='table-info'>
            <td>{{ $domain->id }}</td>
            <td>{{ $domain->name }}</td>
        </tr>
    </table>
@endsection