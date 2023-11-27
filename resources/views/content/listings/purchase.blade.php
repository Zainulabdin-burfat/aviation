@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Purchase Listing</h5>
        </div>
        <div class="card-body">
            <p>Listing: {{ $listing->aircraft_model }} - ${{ $listing->price }}</p>

            <!-- Stripe Checkout button -->
            <div id="checkout-button" class="btn btn-primary">Purchase Now</div>
        </div>
    </div>

    <!-- Include Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
    var stripe = Stripe('{{ config('services.stripe.key') }}');
    var checkoutButton = document.getElementById('checkout-button');

    checkoutButton.addEventListener('click', function () {
        // Create a PaymentIntent on the server
        fetch('/create-payment-intent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ price: {{ $listing->price }} }),
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (session) {
            // Redirect to Stripe Checkout
            return stripe.redirectToCheckout({
                sessionId: session.id,
                successUrl: '{{ url('/confirm-payment?paymentIntentId={CHECKOUT_SESSION_ID}') }}',
                cancelUrl: '{{ route('listings.show', $listing) }}',
            });
        })
        .then(function (result) {
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
        });
    });
</script>

@endsection
