<?php /** @noinspection PhpUndefinedFieldInspection */


namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\ScheduleItem;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScheduleItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     * @method static Builder orderBy(string $column, string $direction = 'asc')
     */
    // index method
    public function index(Request $request)
    {
        $query = ScheduleItem::query();
        // Filtering
        if ($request->has('filter')) {
            $query->where('schedule_id', 'like', '%' . $request->filter . '%')
                ->orWhere('file', 'like', '%' . $request->filter . '%');
        }
        // Sorting
        if ($request->has('sort_by')) {
            $direction = $request->direction ?? 'asc';
            $query->orderBy($request->sort_by, $direction);
        }
        $scheduleItems = $query->paginate(10);
        $header = 'Schedule Items Index';
        return view('schedule_items.index', compact('scheduleItems', 'header'));
    }

    // create method
    public function create()
    {
        // get user id
        $user_id = Auth::id();
        // create data array to pass to the view
        $data = array(
            'header' => 'Create Schedule Item',
            'user_id' => $user_id,
            'schedule_id' => $_GET['schedule_id']
        );
        // return the view
        return view('schedule_items.create')->with($data);
    }

    // store method
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'resource_filename' => 'required|mimes:mp4',
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create a new record in the uploads table
            $file = $request->file('resource_filename');
            $filename = $file->getClientOriginalName();

            $upload = new Upload();
            $upload->resource_type = $request->input('resource_type');
            $upload->resource_filename = $filename;
            $upload->resource_path = 'mp4s/' . $filename;
            $upload->is_uploaded = false;
            $upload->uploaded_by = Auth::id();
            $upload->save();

            // Move the uploaded file to the specified directory
            try {
                // Ensure the directory exists
                Storage::disk('public')->makeDirectory('mp4s');

                // Attempt to move the uploaded file to the specified directory
                $file->storeAs('public/mp4s', $filename);
            } catch (Exception) {
                // An error occurred while moving the file; handle this error
                return back()->withInput()->withErrors(['resource_filename' => 'The resource filename failed to upload.']);
            }
            $upload->is_uploaded = true;
            $upload->save();

            // Create a new record in the schedule_items table
            $scheduleItem = new ScheduleItem();
            $scheduleItem->schedule_id = $request->input('schedule_id');
            $scheduleItem->upload_id = $upload->id;
            $scheduleItem->title = $request->input('title');
            $scheduleItem->start_date = $request->input('start_date');
            $scheduleItem->end_date = $request->input('end_date');
            $scheduleItem->file = $upload->resource_path;
            $scheduleItem->created_by = Auth::id();
            $scheduleItem->save();

            // Commit the transaction
            DB::commit();

            // Redirect the user to the schedule items index page with a success message
            return redirect()->route('schedules.index')->with('success', 'Schedule item created successfully.');
        } catch (Exception $e) {
            // An error occurred; rollback the transaction...
            DB::rollback();

            // Log the exception message
            Log::error($e->getMessage());

            // and return the user to the form with an error message
            return back()->withInput()->withErrors(['error' => 'An error occurred while creating the schedule item. Please try again.']);
        } catch (Throwable) {
            echo 'Errors generated';
        }
    }



    public function show(int $id)
    {
        $scheduleItem = ScheduleItem::with(['upload', 'creator'])->find($id);

        if ($scheduleItem === null) {
            abort(404);
        }

        $data = [
            'header' => 'Schedule Item Details',
            'scheduleItem' => $scheduleItem
        ];

        return view('schedule_items.show')->with($data);
    }
    // destroy method
    public function destroy($id)
    {
        $scheduleItem = ScheduleItem::findOrFail($id);
        $scheduleId = $scheduleItem->schedule_id;
        $scheduleItem->delete();

        // Redirect with the required `schedule` parameter
        return redirect()->route('schedules.show', ['schedule' => $scheduleId])
            ->with('success', 'Schedule item deleted successfully.');
    }



}
