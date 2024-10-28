<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

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
     * Show the form for creating a new resource without a schedule id.
     */
    public function createNoScheduleId()
    {
        // unset the schedule_id session variable
        session()->forget('schedule_id');
        // return the create view
        return view('advertisers.create');
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
        $validatedData = $this->validateAdvertiser($request);
        $advertiser = new Advertiser();
        $this->fillAdvertiser($advertiser, $validatedData, $request);
        $advertiser->save();
        //$this->handleFileUploads($request, $advertiser, true);

        return $this->redirectAfterSave();
    }

    public function update(Request $request, int $id)
    {
        $advertiser = Advertiser::findOrFail($id);
        $validatedData = $this->validateAdvertiser($request, false);
        $this->fillAdvertiser($advertiser, $validatedData, $request);
        $advertiser->save();
        //$this->handleFileUploads($request, $advertiser, false);

        return $this->redirectAfterSave();
    }

    private function redirectAfterSave()
    {
        if (session()->has('schedule_id')) {
            return redirect()->route('schedules.show', ['schedule' => session('schedule_id')])
                ->with('success', 'Advertiser saved successfully.');
        } else {
            return redirect()->route('advertisers.index')
                ->with('success', 'Advertiser saved successfully.');
        }
    }

    private function validateAdvertiser(Request $request, $isNew = true)
    {
        $rules = [
            'business_name' => 'required',
            'banner' => 'file|mimes:png',
            'button' => 'file|mimes:png',
        ];

        $rules['contract'] = $isNew ? 'required|unique:advertisers,contract' : 'required|unique:advertisers,contract,' . $request->id;

        if ($isNew) {
            $rules['mp4'] = 'required|file|mimes:mp4';
        } else {
            $rules['mp4'] = 'nullable|file|mimes:mp4';
        }

        return $request->validate($rules);
    }

    private function fillAdvertiser(Advertiser $advertiser, array $validatedData, Request $request)
    {
        // Exclude file fields from the fill method
        $advertiser->fill(Arr::except($validatedData, ['banner', 'button', 'mp4']));

        // Set other attributes
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

        // Handle file uploads
        $filePaths = [
            'banner' => 'banners',
            'button' => 'buttons',
            'mp4' => 'mp4s',
        ];

        foreach ($filePaths as $field => $path) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $abbreviation = $field === 'banner' ? 'ban' : ($field === 'button' ? 'btn' : 'mp4');
                $newFilename = $advertiser->contract . '_' . $abbreviation . '_' . $originalName;

                $storagePath = $file->storeAs('public/' . $path, $newFilename);

                $upload = new Upload();
                $upload->advertiser_id = $advertiser->id;
                $upload->resource_type = $abbreviation;
                $upload->resource_filename = $newFilename;
                $upload->resource_path = $path . '/' . $newFilename;
                $upload->is_uploaded = true;
                $upload->uploaded_by = auth()->id();
                $upload->uploaded_at = now();
                $upload->save();

                // Set the file path on the advertiser model
                $advertiser->$field = $path . '/' . $newFilename;

                if (session()->has('schedule_id')) {
                    $scheduleItem = new \App\Models\ScheduleItem();
                    $scheduleItem->schedule_id = session('schedule_id');
                    $scheduleItem->upload_id = $upload->id;
                    $scheduleItem->advertiser_id = $advertiser->id;
                    $scheduleItem->file = $path . '/' . $newFilename;
                    $scheduleItem->created_by = auth()->id();
                    $scheduleItem->save();
                }
            }
        }
    }

    /*private function handleFileUploads(Request $request, Advertiser $advertiser, $isNew = true)
    {
        $filePaths = [
            'banner' => 'banners',
            'button' => 'buttons',
            'mp4' => 'mp4s',
        ];

        foreach ($filePaths as $field => $path) {
            if ($request->hasFile($field) && ($isNew || $request->file($field)->getClientOriginalName() !== $advertiser->$field)) {
                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $abbreviation = $field === 'banner' ? 'ban' : ($field === 'button' ? 'btn' : 'mp4');
                $newFilename = $advertiser->contract . '_' . $abbreviation . '_' . $originalName;

                $storagePath = $file->storeAs('public/' . $path, $newFilename);

                $upload = new Upload();
                $upload->advertiser_id = $advertiser->id;
                $upload->resource_type = $abbreviation;
                $upload->resource_filename = $newFilename;
                $upload->resource_path = $path . '/' . $newFilename;
                $upload->is_uploaded = true;
                $upload->uploaded_by = auth()->id();
                $upload->uploaded_at = now();
                $upload->save();

                // Set the file path on the advertiser model
                $advertiser->$field = $path . '/' . $newFilename;

                if (session()->has('schedule_id')) {
                    $scheduleItem = new \App\Models\ScheduleItem();
                    $scheduleItem->schedule_id = session('schedule_id');
                    $scheduleItem->upload_id = $upload->id;
                    $scheduleItem->advertiser_id = $advertiser->id;
                    $scheduleItem->file = $path . '/' . $newFilename;
                    $scheduleItem->created_by = auth()->id();
                    $scheduleItem->save();
                }
            }
        }
    } */

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
