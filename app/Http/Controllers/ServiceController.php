<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::where('deleted_at',NULL)->get();
        return view(
            'content.home.services.list',
            compact('services')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.home.services.form_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $service = new Service ;
            if ($request->hasFile('image')) {

                if ($service->image) {
                    Storage::delete('public/images/service/' . $service->image);
                    Storage::delete('public/images/service/' . $service->image_webp);
                }

                $file = $request->file('image');
                $originalFileName = time() . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/service', $originalFileName);
                $service->image = $originalFileName;

                $webpFileName = time() . '.webp';
                $webpPath = public_path('storage/images/service/' . $webpFileName);

                $image = Image::make($file->getRealPath())->encode('webp', 100);
                $image->save($webpPath);

                $service->image_webp = $webpFileName;
            }
            if ($request->hasFile('icon')) {

                if ($service->icon) {
                    Storage::delete('public/images/service/' . $service->icon);
                    Storage::delete('public/images/service/' . $service->icon_webp);
                }

                $file = $request->file('icon');
                $originalFileName = 'icon'.time() . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/service', $originalFileName);
                $service->image = $originalFileName;

                $webpFileName = 'icon'.time() . '.webp';
                $webpPath = public_path('storage/images/service/' . $webpFileName);

                $image = Image::make($file->getRealPath())->encode('webp', 100);
                $image->save($webpPath);

                $service->icon_webp = $webpFileName;
            }

            $active_count = Service::where('status', '1')->where('deleted_at', NULL)->count();
            if ($active_count > 8) {
                $service->status = '0';
            } else {
                $service->status = '1';
            }

            $sort_order = Service::orderBy('sort_order', 'DESC')->first();

            if ($sort_order) {
                $sort_number = ($sort_order->sort_order + 1);
            } else {
                $sort_number = 1;
            }
            $service->title = $request->title ?? '';
            $service->image_attribute = $request->image_attribute ?? '';
            $service->icon_attribute = $request->icon_attribute ?? '';
            $service->description = $request->description ?? '';
            $service->sort_order = $sort_number;
            // dd($service);
            $service->save();

            DB::commit();
            return redirect()->back()->with(["status" => true, "message" => "Service added successfully..!"]);
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with(["status" => false, "message" => "Service not added. Please try again later..!"]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {

       $service = $service;
       // $slider = $slider->original;
       if ($service) {
           return view(
               'content.home.services.form',
               compact('service')
           );
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreServiceRequest $request, Service $service)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('image')) {

                if ($service->image) {
                    Storage::delete('public/images/service/' . $service->image);
                    Storage::delete('public/images/service/' . $service->image_webp);
                }

                $file = $request->file('image');
                $originalFileName = time() . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/service', $originalFileName);
                $service->image = $originalFileName;

                $webpFileName = time() . '.webp';
                $webpPath = public_path('storage/images/service/' . $webpFileName);

                $image = Image::make($file->getRealPath())->encode('webp', 100);
                $image->save($webpPath);

                $service->image_webp = $webpFileName;
            }
            if ($request->hasFile('icon')) {

                if ($service->icon) {
                    Storage::delete('public/images/service/' . $service->icon);
                    Storage::delete('public/images/service/' . $service->icon_webp);
                }

                $file = $request->file('icon');
                $originalFileName = 'icon'.time() . '.' . $file->getClientOriginalExtension();

                $file->storeAs('public/images/service', $originalFileName);
                $service->image = $originalFileName;

                $webpFileName = 'icon'.time() . '.webp';
                $webpPath = public_path('storage/images/service/' . $webpFileName);

                $image = Image::make($file->getRealPath())->encode('webp', 100);
                $image->save($webpPath);

                $service->icon_webp = $webpFileName;
            }

            $service->title = $request->title ?? '';
            $service->image_attribute = $request->image_attribute ?? '';
            $service->icon_attribute = $request->icon_attribute ?? '';
            $service->description = $request->description ?? '';
            // dd($service);
            $service->save();

            DB::commit();
            return redirect()->back()->with(["status" => true, "message" => "Service Updated successfully..!"]);
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with(["status" => false, "message" => "Service not Updated. Please try again later..!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            DB::beginTransaction();
            if ($service->image) {
                Storage::delete('public/images/service/' . $service->image);
                Storage::delete('public/images/service/' . $service->image_webp);
            }
            if ($service->icon) {
                Storage::delete('public/images/service/' . $service->icon);
                Storage::delete('public/images/service/' . $service->icon_webp);
            }
            $service->delete();
            DB::commit();
            return response()->json(["status" => true, "message" => "Key Feature deleted successfully..!"]);
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            return response()->json(["status" => false, "message" => "Key Feature not deleted. Please try again later..!"]);
        }

    }
}
