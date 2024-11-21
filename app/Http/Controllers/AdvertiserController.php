<?php /** @noinspection PhpUnusedLocalVariableInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpIfWithCommonPartsInspection */

/** @noinspection PhpIfWithCommonPartsInspection */

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Upload;
use Illuminate\Http\Request;
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
        if ($request->has('sort_by') && in_array($request->sort_by, ['contract', 'business_name', 'updated_at'])) {
            $direction = $request->direction ?? 'asc';
            $query->orderBy($request->sort_by, $direction);
            $header = 'Advertiser Index - sorted by ' . $request->sort_by . ' (' . $direction . ')';
        } else {
            // Default sorting by updated_at if sort_by is not provided
            $query->orderBy('updated_at', 'desc');
            $header = 'Advertiser Index - sorted by last updated (desc)';
        }

        // Select advertisers with pagination
        $advertisers = $query->paginate(30);

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

        // Handle file uploads after saving the advertiser
        $this->handleFileUploads($advertiser, $request, []);

        return $this->redirectAfterSave();
    }

    public function update(Request $request, int $id)
    {
        $advertiser = Advertiser::findOrFail($id);
        $validatedData = $this->validateAdvertiser($request, false);

        // Retrieve existing file paths
        $existingFiles = [
            'banner' => $advertiser->banner,
            'button' => $advertiser->button,
            'mp4' => $advertiser->mp4,
        ];

        $this->fillAdvertiser($advertiser, $validatedData, $request);
        $advertiser->save();

        // Handle file uploads and update or delete existing records
        $this->handleFileUploads($advertiser, $request, $existingFiles);

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

        if ($isNew) {
            $rules['contract'] = 'required|unique:advertisers,contract';
            $rules['mp4'] = 'required|file|mimes:mp4';
        } else {
            $rules['contract'] = 'required|unique:advertisers,contract,' . $request->route('advertiser');
            $rules['mp4'] = 'nullable|file|mimes:mp4';
        }

        return $request->validate($rules);
    }

    private function fillAdvertiser(Advertiser $advertiser, array $validatedData, Request $request)
    {
        // Exclude file fields from the fill method
        $advertiser->fill(Arr::except($validatedData, ['banner', 'button', 'mp4']));

        // Set other attributes
        $advertiser->address_1 = $request->input('address_1');
        $advertiser->address_2 = $request->input('address_2');
        $advertiser->street = $request->input('street');
        $advertiser->city = $request->input('city');
        $advertiser->county = $request->input('county');
        $advertiser->postal_code = $request->input('postal_code');
        $advertiser->country = $request->input('country');
        $advertiser->phone = $request->input('phone');
        $advertiser->mobile = $request->input('mobile');
        $advertiser->email = $request->input('email');
        $advertiser->url = $request->input('url');
        $advertiser->social = $request->input('social');
        $advertiser->sort_order = $request->input('sort_order', 1);
        $advertiser->is_active = true;
        $advertiser->is_deleted = false;
        $advertiser->created_by = auth()->id();
        // save the advertiser
        $advertiser->save();
    }


    private function handleFileUploads(Advertiser $advertiser, Request $request, array $existingFiles)
    {
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

                // Check and update or delete existing records if the key exists
                if (isset($existingFiles[$field])) {
                    $this->updateOrDeleteExistingRecords($advertiser, $existingFiles[$field], $newFilename, $path);
                }

                // Check if an existing upload record exists
                $upload = Upload::where('advertiser_id', $advertiser->id)
                    ->where('resource_type', $abbreviation)
                    ->first();

                if ($upload) {
                    // Update the existing upload record
                    $upload->resource_filename = $newFilename;
                    $upload->resource_path = $path . '/' . $newFilename;
                    $upload->save();
                } else {
                    // Save the new upload record
                    $upload = new Upload();
                    $upload->advertiser_id = $advertiser->id;
                    $upload->resource_type = $abbreviation;
                    $upload->resource_filename = $newFilename;
                    $upload->resource_path = $path . '/' . $newFilename;
                    $upload->is_uploaded = true;
                    $upload->uploaded_by = auth()->id();
                    $upload->uploaded_at = now();
                    $upload->save();
                }

                // Set the file path on the advertiser model
                $advertiser->$field = $path . '/' . $newFilename;

                if (session()->has('schedule_id')) {
                    $scheduleItem = ScheduleItem::where('advertiser_id', $advertiser->id)
                        ->where('file', $existingFiles[$field] ?? '')
                        ->first();

                    if ($scheduleItem) {
                        // Update the existing schedule item record
                        $scheduleItem->file = $path . '/' . $newFilename;
                        $scheduleItem->save();
                    } else {
                        // Save the new schedule item record
                        $scheduleItem = new ScheduleItem();
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

        // Save the advertiser model again to store the file paths
        $advertiser->save();
    }

    private function updateOrDeleteExistingRecords(Advertiser $advertiser, $existingFile, $newFilename, $path)
    {
        if ($existingFile) {
            // Update existing upload record
            $upload = Upload::where('advertiser_id', $advertiser->id)
                ->where('resource_path', $existingFile)
                ->first();

            if ($upload) {
                $upload->resource_filename = $newFilename;
                $upload->resource_path = $path . '/' . $newFilename;
                $upload->save();
            }

            // Update existing schedule item record
            $scheduleItem = ScheduleItem::where('advertiser_id', $advertiser->id)
                ->where('file', $existingFile)
                ->first();

            if ($scheduleItem) {
                $scheduleItem->file = $path . '/' . $newFilename;
                $scheduleItem->save();
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

    public function selectExisting()
    {
        $advertisers = Advertiser::orderBy('updated_at', 'desc')->paginate(20);
        return view('advertisers.select', compact('advertisers'));
    }

}
