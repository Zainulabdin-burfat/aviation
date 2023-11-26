@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Messages</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <!-- Table headers -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>From</th>
                    <th>Message</th>
                    <th>Chat</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through your messages and display them -->
                @foreach ($messages as $message)
                <tr>
                    <td>{{ $message->id }}</td>
                    <td>{{ $message->sender->first_name }}</td>
                    <td>{{ $message->content }}</td>
                    <td>
                        <a href="{{ route('messages.chat', ['user' => $message->from_user_id]) }}" class="btn btn-primary btn-sm">Chat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection