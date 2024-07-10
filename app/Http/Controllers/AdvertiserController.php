<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AdvertiserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $scheduleId = $request->schedule_id;
        $schedule = Schedule::find($scheduleId);// Get the schedule
        return view('advertisers.create', compact('schedule'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'contract' => 'required|unique:advertisers,contract',
                'business_name' => 'required',
                'banner' => 'file|mimes:png',
                'button' => 'file|mimes:png',
                'mp4' => 'required|file|mimes:mp4',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation errors: ', $e->validator->errors()->all());
            throw $e; // rethrow the exception so that Laravel can handle it and redirect back with errors
        }
            $advertiser = new Advertiser();
            $advertiser->contract = $validatedData['contract'];
            $advertiser->business_name = $validatedData['business_name'];
            $advertiser->address_1 = $request->input('address_1', null);
            $advertiser->address_2 = $request->input('address_2', null);
            $advertiser->street = $request->input('street', null);
            $advertiser->city = $request->input('city', null);
            $advertiser->county = $request->input('county', null);
            $advertiser->postal_code = $request->input('postal_code', null);
            $advertiser->country = $request->input('country', null);
            $advertiser->phone = $request->input('phone', null);
            $advertiser->mobile = $request->input('mobile', null);
            $advertiser->email = $request->input('email', null);
            $advertiser->url = $request->input('url', null);
            $advertiser->social = $request->input('social', null);
            $advertiser->sort_order = $request->input('sort_order', 1);
            $advertiser->is_active = true;
            $advertiser->is_deleted = true;
            $advertiser->created_by = auth()->id();

            // Check and store the file name for banner
            if ($request->hasFile('banner')) {
                $advertiser->banner = $request->file('banner')->getClientOriginalName();
            }

            // Check and store the file name for button
            if ($request->hasFile('button')) {
                $advertiser->button = $request->file('button')->getClientOriginalName();
            }

            // Check and store the file name for mp4
            if ($request->hasFile('mp4')) {
                $advertiser->mp4 = $request->file('mp4')->getClientOriginalName();
            }

            $advertiser->save();

        // Define the paths for each file type
        $filePaths = [
            'banner' => 'public/banner',
            'button' => 'public/button',
            'mp4' => 'public/mp4',
        ];

        // Process each file input
        foreach ($filePaths as $field => $path) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $abbreviation = $field === 'banner' ? 'ban' : ($field === 'button' ? 'btn' : 'mp4');
                $newFilename = $advertiser->contract . '_' . $abbreviation . '_' . $originalName;

                // Save the file to the determined path
                $storagePath = $file->storeAs($path, $newFilename);

                // Create an upload record
                $upload = new Upload();
                $upload->advertiser_id = $advertiser->id;
                $upload->resource_type = $abbreviation;
                $upload->resource_filename = $newFilename;
                $upload->resource_path = $storagePath;
                $upload->is_uploaded = true;
                $upload->uploaded_by = auth()->id();
                $upload->uploaded_at = now();
                $upload->save();

                // Create a ScheduleItem record for each file uploaded
                $scheduleItem = new \App\Models\ScheduleItem();
                $scheduleItem->schedule_id = $request->schedule_id;
                $scheduleItem->upload_id = $upload->id;
                $scheduleItem->advertiser_id = $advertiser->id;
                $scheduleItem->file = $storagePath;
                $scheduleItem->created_by = auth()->id();
                $scheduleItem->save();
            }
        }

        return redirect()->route('schedules.show', ['schedule' => $request->schedule_id])
            ->with('success', 'Advertiser created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
    }
}
