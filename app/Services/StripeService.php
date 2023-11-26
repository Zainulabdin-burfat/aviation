<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\Product;
use Stripe\Transfer;

class StripeService
{
    protected $stripeSecretKey;

    public function __construct()
    {
        $this->stripeSecretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    public function transferFunds($amount, $destinationAccountId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        Transfer::create([
            'amount' => $amount,
            'currency' => 'usd',
            'destination' => $destinationAccountId,
        ]);
    }

    // public function createPaymentIntent(Request $request)
    // {
    //     try {
    //         $paymentIntent = PaymentIntent::create([
    //             'amount' => $request->input('amount'),
    //             'currency' => 'usd', // Adjust as needed
    //         ]);

    //         return ['clientSecret' => $paymentIntent->client_secret];
    //     } catch (\Exception $e) {
    //         // Handle Stripe API exception
    //         return ['error' => $e->getMessage()];
    //     }
    // }

    // public function confirmPayment(Request $request)
    // {
    //     try {
    //         $paymentIntent = PaymentIntent::retrieve($request->input('paymentIntentId'));
    //         $paymentIntent->confirm();

    //         // Perform actions after successful payment confirmation
    //         // ...

    //         return ['message' => 'Payment confirmed successfully'];
    //     } catch (\Exception $e) {
    //         // Handle Stripe API exception
    //         return ['error' => $e->getMessage()];
    //     }
    // }
    public function confirmPayment(Request $request)
    {
        try {

            $paymentIntent = PaymentIntent::retrieve($request->input('paymentIntentId'));
            $paymentIntent->confirm();

            $transaction = Transaction::where('id', $paymentIntent->metadata->transaction_id)->firstOrFail();
            $transaction->update(['status' => 'completed']);

            TransactionLog::create([
                'user_id' => auth()->user()->id,
                'transaction_id' => $transaction->id,
                'log_message' => 'Payment confirmed for listing ID ' . $transaction->listing_id,
            ]);

            return response()->json(['message' => 'Payment confirmed successfully']);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a product on Stripe.
     */
    public function createProductOnStripe(Listing $listing)
    {
        try {
            $product = Product::create([
                'name' => $listing->aircraft_model,
                'type' => 'service',
            ]);

            $price = Price::create([
                'product' => $product->id,
                'unit_amount' => $listing->price * 100,
                'currency' => 'usd',
            ]);

            $listing->update([
                'stripe_product_id' => $product->id,
                'stripe_price_id' => $price->id,
            ]);
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    public function createPaymentIntent($price)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $price * 100,
                'currency' => 'usd',
            ]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            // Handle Stripe API exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
