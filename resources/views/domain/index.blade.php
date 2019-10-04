@extends('layouts.main')

@section('content')
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
        @foreach($domains as $domain)
            <tr>
                <td>{{ $domain->id }}</td>
                <td>{{ $domain->name }}</td>
            </tr>
        @endforeach
    </table>

    {{ $domains->links() }}
@endsection