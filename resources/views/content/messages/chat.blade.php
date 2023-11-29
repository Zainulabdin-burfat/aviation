@extends('layouts/layoutMaster')

@section('title', 'User Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
<script>
    const getPermissions = @json(auth()->user()->roles);
    const getDirectPermissions = @json(auth()->user()->permissions);
    const userRole = getDirectPermissions.length ? 'super-admin' : getPermissions[0].name;
    const userPermissions = getDirectPermissions.length ? [] : getPermissions[0].permissions;

    function hasPermission(permissionName) {
        if (userRole === 'super-admin') {
            return true;
        }

        return userPermissions.some(permission => permission.name === permissionName);
    }


</script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/users.js') }}"></script>
    <script>
        $(function() {
            @if (session('message'))
                @if (session('status'))
                    toastr.success("{{ session('message') }}");
                @else
                    toastr.error("{{ session('message') }}");
                @endif
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
@endsection

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
