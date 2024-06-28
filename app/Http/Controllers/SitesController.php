<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;



class SitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     * @method static Builder orderBy(string $column, string $direction = 'asc')
     */

    public function index(Request $request)
    {
        $query = Site::query();

        // Filtering
        if ($request->has('filter')) {
            $query->where('site_name', 'like', '%' . $request->filter . '%')
                ->orWhere('site_ref', 'like', '%' . $request->filter . '%');
        }

        // Sorting
        if ($request->has('sort_by')) {
            $direction = $request->direction ?? 'asc';
            $query->orderBy($request->sort_by, $direction);
        }

        $sites = $query->paginate(10);

        $header = 'Sites Index';

        return view('sites.index', compact('sites', 'header' ));
    }

    // ToDo: Remove this function after testing
    /*public function index()
    {
        // show all sites
        $header = 'Sites';
        $sites = Site::orderBy('site_ref', 'asc')->paginate(10);
        return view('sites.index', ['header' => $header])->with('sites', $sites);
    } */

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $header = 'Create Site';
        return view('sites.create', ['header' => $header]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // validate form data
        $this->validateSite($request);

        // create site
        $site = new Site;

        // get all data except for the _token
        $requestData = $request->except('_token');

        // iterate through the request data
        foreach ($requestData as $key => $value) {
            // assign each value to the corresponding key in the Site model
            $site->$key = $value;
        }
        // set the status of site_active
        $site->site_active = $request->input('site_active');

        // save the site
        $site->save();

        // redirect to the sites index page with a success message
        return redirect()->route('sites.index')->with('success', 'Site created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
       $site = Site::find($id);
       $data = array(
           'header' => 'Site Details',
           'site' => $site);
        return view('sites.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        // find site by id
        $site = Site::find($id);
        $data = array(
            'header' => 'Edit Site',
            'site' => $site);
        return view('sites.edit')->with($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validate form data
        $this->validateSite($request);

        // create site
        $site = Site::find($id);

        // get all data except for the _token
        $requestData = $request->except('_token', '_method');

        // iterate through the request data
        foreach ($requestData as $key => $value) {
            // assign each value to the corresponding key in the Site model
            $site->$key = $value;
        }
        // set the status of site_active
        $site->site_active = $request->input('site_active');

        // save the site
        $site->save();

        // redirect to the sites index page with a success message
        return redirect()->route('sites.index')->with('success', 'Site updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $site = Site::find($id);
        $site->delete();

        return redirect()->route('sites.index')->with('success', 'Site deleted successfully');
    }

    private function validateSite($request) {
        $this->validate($request, [
            'site_ref' => 'required',
            'site_name' => 'required'
        ]);
    }
}
