@extends('layouts.app')

@section('content')
    <div class="container">
        <p class="text-5xl mb-5">Schedule Details</p>

        <!-- Section (a) -->
        <div>
            <p class="mb-2 text-2xl"><strong>ID:</strong> {{ $schedule->id ?? 'N/A' }}</p>
            <p><strong>Title:</strong> {{ $schedule->title ?? 'N/A' }}</p>
            <p><strong>Created By:</strong> {{ $schedule->creator->name ?? 'N/A' }}</p>
            <p><strong>Created At:</strong> {{ $schedule->created_at ? $schedule->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
            <p><strong>Updated At:</strong> {{ $schedule->updated_at ? $schedule->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
        </div>

        <!-- Section (b) -->
        <p class="text-2xl mt-5">Schedule Sites</p>
        <table class="table-auto" style="border-collapse: collapse;">
            <thead>
            <tr>
                <th style="padding: 4px;">Site ID</th>
                <th style="padding: 4px;">Site Name</th>
                <th style="padding: 4px;">Download At</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($schedule->sites as $site)
                <tr>
                    <td style="padding: 4px;">{{ $site->id ?? 'N/A' }}</td>
                    <td style="padding: 4px;">{{ $site->site_name ?? 'N/A' }}</td>
                    <td style="padding: 4px;">{{ $site->pivot->download_at ?? 'Not downloaded' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Section (c) -->
        <p class="text-2xl mt-5">Schedule Items</p>
        <table class="table-auto" style="border-collapse: collapse;">
            <thead>
            <tr>
                <th style="padding: 4px;">Advertiser ID</th>
                <th style="padding: 4px;">Title</th>
                <th style="padding: 4px;">File</th>
                <th style="padding: 4px;">Start Date</th>
                <th style="padding: 4px;">End Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($schedule->items as $item)
                <tr>
                    <td style="padding: 4px;">{{ $item->advertiser_id ?? 'N/A' }}</td>
                    <td style="padding: 4px;">{{ $item->title ?? 'N/A' }}</td>
                    <td style="padding: 4px;">{{ $item->file ?? 'N/A' }}</td>
                    <td style="padding: 4px;">{{ $item->start_date ?? 'N/A' }}</td>
                    <td style="padding: 4px;">{{ $item->end_date ?? 'N/A' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
