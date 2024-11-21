<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class ApiScheduleItemController extends Controller
{

    public function logItemUpload(Request $request)
    {
        // Validate the request to ensure 'id' is provided and is an integer
        $request->validate([
            'id' => 'required|integer'
        ]);

        // Retrieve the id from the request
        $id = $request->input('id');

        // Update the download_at column with the current date and time
        $updated = DB::table('schedule_items')
            ->where('id', $id)
            ->update(['downloaded_at' => Carbon::now()]);

        // Check if the update was successful
        if ($updated) {
            return response()->json(['success' => 'Download time and date updated successfully.']);
        } else {
            return response()->json(['error' => 'Failed to update download time and date.'], 500);
        }
    }
}