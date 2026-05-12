<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\TimeEntryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TimeEntry::with(['employee',]);

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date) {
            $query->whereDate('recorded_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('recorded_at', '<=', $request->end_date);
        }

        return response()->json($query->orderBy('recorded_at', 'desc')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type' => ['required', 'string'],
            'recorded_at' => ['required', 'date'],
            'source' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $timeEntry = TimeEntry::create($validated);

        TimeEntryLog::create([
            'time_entry_id' => $timeEntry->id,
            'action' => 'created',
            'old_value' => null,
            'new_value' => $timeEntry->toArray(),
            'changed_by' => Auth::id(),
        ]);

        return response()->json($timeEntry, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeEntry $timeEntry)
    {
        return response()->json($timeEntry->load(['employee', 'logs']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeEntry $timeEntry)
    {
        $validated = $request->validate([
            'type' => ['sometimes', 'string'],
            'recorded_at' => ['sometimes', 'date'],
            'source' => ['sometimes', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $oldValue = $timeEntry->toArray();

        $timeEntry->update($validated);

        TimeEntryLog::create([
            'time_entry_id' => $timeEntry->id,
            'action' => 'updated',
            'old_value' => $oldValue,
            'new_value' => $timeEntry->fresh()->toArray(),
            'changed_by' => Auth::id(),
        ]);

        return response()->json($timeEntry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeEntry $timeEntry)
    {
        $oldValue = $timeEntry->toArray();

        $timeEntry->delete();

        TimeEntryLog::create([
            'time_entry_id' => $timeEntry->id,
            'action' => 'deleted',
            'old_value' => $oldValue,
            'new_value' => null,
            'changed_by' => Auth::id(),
        ]);

        return response()->noContent();
    }
}
