<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use Illuminate\Http\Request;

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
    public function create()
    {
        $advertisers = Advertiser::all();
        return view('advertisers.create', compact('advertisers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'contract' => 'required|unique:advertisers,contract',
            'business_name' => 'required',
        ]);
        $advertiser = new Advertiser();
        $advertiser->contract = $validatedData['contract'];
        $advertiser->business_name = $validatedData['business_name'];
        $advertiser->address_1 = $_REQUEST['address_1'];
        $advertiser->address_2 = $_REQUEST['address_2'];
        $advertiser->street = $_REQUEST['street'];
        $advertiser->city = $_REQUEST['city'];
        $advertiser->county = $_REQUEST['county'];
        $advertiser->postal_code = $_REQUEST['postal_code'];
        $advertiser->country = $_REQUEST['country'];
        $advertiser->phone = $_REQUEST['phone'];
        $advertiser->mobile = $_REQUEST['mobile'];
        $advertiser->email = $_REQUEST['email'];
        $advertiser->url = $_REQUEST['url'];
        $advertiser->social = $_REQUEST['social'];
        $advertiser->banner = $_REQUEST['banner'];
        $advertiser->social = $_REQUEST['social'];
        $advertiser->social = $_REQUEST['social'];


        // Set other fields from validated data
        $advertiser->save();

        return redirect()->route('advertisers.index')->with('success', 'Advertiser created successfully.');
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
