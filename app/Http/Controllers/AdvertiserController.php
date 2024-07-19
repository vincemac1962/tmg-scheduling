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
    public function index(Request $request)

    {

        $query = Advertiser::query();

        // Filtering
        if ($request->has('filter')) {
            $query->where('business_name', 'like', '%' . $request->filter . '%')
                ->orWhere('contract', 'like', '%' . $request->filter . '%');
        }

        // Sorting
        if ($request->has('sort_by')) {
            $direction = $request->direction ?? 'asc';
            $query->orderBy($request->sort_by, $direction);
            $header = 'Advertiser Index - sorted by ' . $request->sort_by;
        } else {
            // Default sorting by updated_at if sort_by is not provided
            $query->orderBy('updated_at', 'desc');
            $header = 'Advertiser Index - sorted by last updated';
        }
        // select advertisers most recently added
        $advertisers = $query->paginate(20);
        //$advertisers = Advertiser::orderBy('created_at', 'desc')->paginate(20);
        return view('advertisers.index', compact('advertisers', 'header'));
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
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $advertiser = Advertiser::find($id);
        $data = array(
            'header' => 'Advertiser Details',
            'advertiser' => $advertiser);
        return view('advertisers.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        // find advertiser by id
        $advertiser = Advertiser::find($id);
        // get schedule id from session if it exists
        $scheduleId = session('schedule_id');
        $data = array(
            'header' => 'Edit Advertiser',
            'scheduleId' => $scheduleId,
            'advertiser' => $advertiser);
        return view('advertisers.edit')->with($data);
    }

    public function store(Request $request)
    {
        $advertiser = $this->saveAdvertiser($request);
        $scheduleId = session('schedule_id');
        $this->handleFileUploads($request, $advertiser);
        if (session()->has('schedule_id')) {
            return redirect()->route('schedules.show', ['schedule' => session('schedule_id')])
                ->with('success', 'Advertiser created successfully.');
        } else {
            return redirect()->route('advertisers.index')
                ->with('success', 'Advertiser created successfully.');
        }
    }

    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        try {
            $advertiser = Advertiser::findOrFail($id);
            $this->saveAdvertiser($request, $advertiser);
        } catch (\Exception $e) {
            // ToDo: remove next line when testing is finished
            //Log::error('Error updating advertiser: ' . $e->getMessage());
            throw ValidationException::withMessages(['error' => 'Error updating advertiser.']);
        }

        // ToDo: remove next line when testing is finished
        //Log::debug('Session Data', session()->all());
        if (session()->has('schedule_id')) {
            return redirect()->route('schedules.show', ['schedule' => session('schedule_id')])
                ->with('success', 'Advertiser updated successfully.');
        } else {
            return redirect()->route('advertisers.index')
                ->with('success', 'Advertiser updated successfully.');
        }
    }



    /**
     * Stores a record.
     * @throws ValidationException
     */
    private function saveAdvertiser(Request $request, Advertiser $advertiser = null)
    {
        $isNew = false;
        if (!$advertiser) {
            $advertiser = new Advertiser();
            $isNew = true;
        }

        try {
            $rules = [
                'business_name' => 'required',
                'banner' => 'file|mimes:png',
                'button' => 'file|mimes:png',
            ];

            // Modify the contract validation rule based on whether it's a new or existing record
            $rules['contract'] = $isNew ? 'required|unique:advertisers,contract' : 'required|unique:advertisers,contract,' . $advertiser->id;

            // Modify the mp4 validation rule based on whether it's a new or existing record
            if ($isNew) {
                $rules['mp4'] = 'required|file|mimes:mp4';
            } else {
                $rules['mp4'] = 'nullable|file|mimes:mp4';
            }

            $validatedData = $request->validate($rules);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation errors: ', $e->validator->errors()->all());
            throw $e; // rethrow the exception so that Laravel can handle it and redirect back with errors
        }

        // fill the advertiser model with validated data
        $advertiser->fill($validatedData);
        // fill the model with additional data
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
        $advertiser->is_deleted = false;
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

        // File handling logic (omitted for brevity)

        return $advertiser;
    }

    private function handleFileUploads(Request $request, Advertiser $advertiser)
    {
        $filePaths = [
            'banner' => 'public/banner',
            'button' => 'public/button',
            'mp4' => 'public/mp4',
        ];

        foreach ($filePaths as $field => $path) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $abbreviation = $field === 'banner' ? 'ban' : ($field === 'button' ? 'btn' : 'mp4');
                $newFilename = $advertiser->contract . '_' . $abbreviation . '_' . $originalName;

                $storagePath = $file->storeAs($path, $newFilename);

                $upload = new Upload();
                $upload->advertiser_id = $advertiser->id;
                $upload->resource_type = $abbreviation;
                $upload->resource_filename = $newFilename;
                $upload->resource_path = $storagePath;
                $upload->is_uploaded = true;
                $upload->uploaded_by = auth()->id();
                $upload->uploaded_at = now();
                $upload->save();
                $specificVariable = session('schedule_id', 'Schedule ID not found');
                \Log::info('Specific session variable:', ['schedule_id' => $specificVariable]);
                if (session()->has('schedule_id')) {
                    $scheduleItem = new \App\Models\ScheduleItem();
                    $scheduleItem->schedule_id = $request->schedule_id;
                    $scheduleItem->upload_id = $upload->id;
                    $scheduleItem->advertiser_id = $advertiser->id;
                    $scheduleItem->file = $storagePath;
                    $scheduleItem->created_by = auth()->id();
                    $scheduleItem->save();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $advertiser = Advertiser::find($id);
        $advertiser->delete();
        return redirect()->route('advertisers.index');
    }

    public function selectExisting(Request $request)
    {
        $advertisers = Advertiser::orderBy('updated_at', 'desc')->paginate(20);
        return view('advertisers.select', compact('advertisers'));
    }

}
