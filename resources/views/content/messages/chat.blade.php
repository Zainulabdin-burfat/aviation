@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="/style.css">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Chat with {{ $receiver->first_name }}</h5>
        </div>
        <div class="card-body">
            <div class="chat">
                <!-- Header -->
                <div class="top">
                    <div>
                        <p>{{auth()->user()->first_name}}</p>
                        <small>Online</small>
                    </div>
                </div>
                <!-- End Header -->

                <!-- Chat -->
                <div class="messages">
                    @foreach($messages as $message)
                        @include('receive', ['message' => $message])
                    @endforeach
                </div>
                <!-- End Chat -->

                <!-- Footer -->
                <div class="bottom">
                    <form>
                        <input type="text" id="message" name="message" placeholder="Enter message..."
                               autocomplete="off">
                        <button type="submit"></button>
                    </form>
                </div>
                <!-- End Footer -->

            </div>
        </div>
    </div>

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <!-- jQuery JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <script>
        let userId = parseInt('{{ auth()->id() }}');
        let receiverId = parseInt('{{ $receiver->id }}');

        let channelName = `messages.${userId}.${receiverId}`;

        const pusher = new Pusher('{{config('
        broadcasting.connections.pusher.key ')}}', {
            cluster: 'eu'
        });

        const channel = pusher.subscribe(channelName);

        //Receive messages
        channel.bind('chat', function (data) {
            $.post("/receive", {
                _token: '{{csrf_token()}}',
                message: data.message,
            })
                .done(function (res) {
                    console.log(res);
                    $(".messages > .message").last().after(res);
                    $(document).scrollTop($(document).height());
                });
        });

        //Broadcast messages
        $("form").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: `{{ route('messages.send', ['receiver_id' => ':receiverId']) }}`.replace(':receiverId', receiverId),
                method: 'POST',
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: '{{csrf_token()}}',
                    content: $("form #message").val(),
                }
            }).done(function (res) {
                $(".messages > .message").last().after(res);
                $("form #message").val('');
                $(document).scrollTop($(document).height());
            });
        });
    </script>

@endsection