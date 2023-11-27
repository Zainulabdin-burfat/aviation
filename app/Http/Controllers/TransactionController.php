<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Services\StripeService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $transactions = Transaction::all();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function approve(Transaction $transaction)
    {
        // Perform logic to approve the transaction
    }

    public function decline(Transaction $transaction)
    {
        // Perform logic to decline the transaction
    }

    public function createPaymentIntent(Request $request)
    {
        try {

            return $this->stripeService->createPaymentIntent($request->input('price'));
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        try {
            
            return $this->stripeService->confirmPayment($request);
        } catch (\Exception $e) {
            // Handle Stripe API exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function logTransaction($userId, $transactionId, $logMessage)
    {
        TransactionLog::create([
            'user_id' => $userId,
            'transaction_id' => $transactionId,
            'log_message' => $logMessage,
        ]);
    }
	public function destroy()
	{
		//
	}

}
