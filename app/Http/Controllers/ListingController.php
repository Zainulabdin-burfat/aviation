<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Transaction;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Models\ListingImage;
use App\Models\TransactionLog;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index(Request $request)
    {
        $query = Listing::query();

        if ($request->has('aircraft_model')) {
            $query->where('aircraft_model', 'like', '%' . $request->input('aircraft_model') . '%');
        }

        if ($request->has('year')) {
            $query->where('year', 'like', '%' . $request->input('year') . '%');
        }

        if ($request->has('condition')) {
            $query->where('condition', 'like', '%' . $request->input('condition') . '%');
        }
        $listings = $query->get();

        return view('content.listings.index', compact('listings'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.listings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingRequest $request)
    {
        try {

            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('listing_images', 'public');
            } else {
                $imagePath = null;
            }

            $listing = Listing::create([
                'user_id' => auth()->user()->id,
                'aircraft_model' => $request->input('aircraft_model'),
                'year' => $request->input('year'),
                'condition' => $request->input('condition'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
            ]);

            if ($imagePath) {
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $imagePath,
                ]);
            }

            $this->stripeService->createProductOnStripe($listing);

            DB::commit();

            return redirect()->back()->with(['status' => true, 'message' => 'Listing created successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return redirect()->back()->with(['status' => false, 'message' => 'Listing not created. Please try again later.']);
        }
    }

    public function purchase(Listing $listing)
    {
        $transaction = Transaction::create([
            'buyer_id' => auth()->user()->id,
            'seller_id' => $listing->user_id,
            'listing_id' => $listing->id,
            'amount' => $listing->price,
            'status' => 'pending',
        ]);

        TransactionLog::create([
            'user_id' => auth()->user()->id,
            'transaction_id' => $transaction->id,
            'log_message' => 'Purchase initiated for listing ID ' . $listing->id,
        ]);

        $paymentIntent = $this->stripeService->createPaymentIntent($listing->price);
        $paymentIntent = json_decode(json_encode($paymentIntent), true);
        // Pass client secret to the view
        return view('content.listings.purchase', [
            'listing' => $listing,
            'clientSecret' => $paymentIntent['original']['clientSecret'],
        ]);

        // return redirect()->route('listings.show', $listing)->with(['status' => true, 'message' => 'Listing purchased successfully!']);
    }


    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        return view('content.listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        return view('content.listings.edit', compact('listing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        try {
            DB::beginTransaction();

            // Update the listing details
            $listing->update([
                'aircraft_model' => $request->input('aircraft_model'),
                'year' => $request->input('year'),
                'condition' => $request->input('condition'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
            ]);

            if ($request->hasFile('image')) {
                if ($listing->image) {
                    Storage::disk('public')->delete($listing->image->image_path);
                    $listing->image->delete();
                }

                $imagePath = $request->file('image')->store('listing_images', 'public');

                // Update or create the listing image record
                $listing->image()->updateOrCreate([], ['image_path' => $imagePath]);
            }

            DB::commit();

            return redirect()->back()->with(['status' => true, 'message' => 'Listing updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            return redirect()->back()->with(['status' => false, 'message' => 'Error updating listing.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        //
    }
}
