<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $recentDownloads = DB::table('schedule_site')
            ->join('schedules', 'schedule_site.schedule_id', '=', 'schedules.id')
            ->join('sites', 'schedule_site.site_id', '=', 'sites.id')
            ->where('schedule_site.downloaded', true)
            ->orderBy('schedule_site.downloaded_at', 'desc')
            ->select('schedule_site.id', 'schedules.title', 'sites.site_name', 'schedule_site.downloaded_at', 'schedule_site.schedule_id')
            ->limit(20)
            ->get();

        return view('dashboard', compact('recentDownloads'));
    }
}
