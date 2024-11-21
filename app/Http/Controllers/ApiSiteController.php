<?php

// app/Http/Controllers/ApiSiteController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiSiteController extends Controller
{
    public function registerSite(Request $request)
    {
        // Enable query logging
        DB::enableQueryLog();

        // Validate the request to ensure all required parameters are provided
        $request->validate([
            'site_group_id' => 'required|integer',
            'site_ref' => 'required|string',
            'site_name' => 'required|string',
            'site_address' => 'required|string',
            'site_postcode' => 'required|string',
            'site_country' => 'required|string',
            'site_email' => 'required|email'
        ]);

        // Retrieve the form parameters
        $siteGroupId = $request->input('site_group_id');
        $siteRef = $request->input('site_ref');
        $siteName = $request->input('site_name');
        $siteAddress = $request->input('site_address');
        $sitePostcode = $request->input('site_postcode');
        $siteCountry = $request->input('site_country');
        $siteEmail = $request->input('site_email');

        // Check if a record with the given site_ref already exists
        $site = DB::table('sites')->where('site_ref', $siteRef)->first();

        if ($site) {
            // Update the existing record
            DB::table('sites')
                ->where('site_ref', $siteRef)
                ->update([
                    'site_group_id' => $siteGroupId,
                    'site_name' => $siteName,
                    'site_address' => $siteAddress,
                    'site_postcode' => $sitePostcode,
                    'site_country' => $siteCountry,
                    'site_email' => $siteEmail,
                    'updated_at' => now()
                ]);

            // Return a JSON response indicating the record was updated
            return response()->json(['message' => 'Site record updated successfully.', 'id' => $site->id]);
        } else {
            // Insert a new record
            $insertedId =DB::table('sites')->insertGetId([
                'site_group_id' => $siteGroupId,
                'site_ref' => $siteRef,
                'site_name' => $siteName,
                'site_address' => $siteAddress,
                'site_postcode' => $sitePostcode,
                'site_country' => $siteCountry,
                'site_email' => $siteEmail,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log the executed query
            Log::info(print_r(DB::getQueryLog(), true));

            // Return a JSON response indicating the record was inserted
            return response()->json(['message' => 'Site record inserted successfully.', 'id' => $insertedId]);
        }
    }

    // log site update
    public function logSiteUpdate(Request $request)
    {
        // Validate the request
        $request->validate([
            'site_id' => 'required|integer',
        ]);

        // Retrieve the site_id from the request
        $siteId = $request->input('site_id');

        // Update the site_last_contact and site_last_updated columns
        DB::table('sites')
            ->where('id', $siteId)
            ->update([
                'site_last_contact' => now(),
                'site_last_updated' => now(),
            ]);

        return response()->json(['message' => 'Site updated successfully']);
    }

    // update site table site_last_contact
    public function updateSiteLastContact(Request $request)
    {
        // Validate the request
        $request->validate([
            'site_id' => 'required|integer',
        ]);

        // Retrieve the site_id from the request
        $siteId = $request->input('site_id');

        // Update the site_last_contact column
        DB::table('sites')
            ->where('id', $siteId)
            ->update([
                'site_last_contact' => now(),
            ]);

        return response()->json(['message' => 'Site last contact updated successfully']);
    }

}