@extends('layouts.main')

@section('content')
    <table>
        <tr>
            <th>ID</td>
            <th>Name</td>
        </tr>
        <tr>
            <td>{{ $domain->id }}</td>
            <td>{{ $domain->name }}</td>
        </tr>
    </table>
@endsection