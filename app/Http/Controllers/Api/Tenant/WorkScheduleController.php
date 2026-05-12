<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(WorkSchedule::with('days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'expected_daily_hours' => ['required'],
            'days' => ['required', 'array'],

            'days.*.weekday' => ['required', 'string'],
            'days.*.start_time' => ['required'],
            'days.*.end_time' => ['required'],
            'days.*.break_minutes' => ['required', 'integer'],
        ]);

        DB::beginTransaction();

        try {

            $workSchedule = WorkSchedule::create([
                'name' => $validated['name'],
                'expected_daily_hours' => $validated['expected_daily_hours'],
            ]);

            foreach ($validated['days'] as $day) {

                $workSchedule->days()->create([
                    'weekday' => $day['weekday'],
                    'start_time' => $day['start_time'],
                    'end_time' => $day['end_time'],
                    'break_minutes' => $day['break_minutes'],
                ]);
            }

            DB::commit();

            return response()->json($workSchedule->load('days'), 201);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json(['message' => 'Erro ao criar escala.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSchedule $workSchedule)
    {
        return response()->json($workSchedule->load('days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'expected_daily_hours' => ['required'],
            'days' => ['required', 'array'],

            'days.*.weekday' => ['required', 'string'],
            'days.*.start_time' => ['required'],
            'days.*.end_time' => ['required'],
            'days.*.break_minutes' => ['required', 'integer'],
        ]);

        DB::beginTransaction();

        try {

            $workSchedule->update([
                'name' => $validated['name'],
                'expected_daily_hours' => $validated['expected_daily_hours'],
            ]);

            $workSchedule->days()->delete();

            foreach ($validated['days'] as $day) {

                $workSchedule->days()->create([
                    'weekday' => $day['weekday'],
                    'start_time' => $day['start_time'],
                    'end_time' => $day['end_time'],
                    'break_minutes' => $day['break_minutes'],
                ]);
            }

            DB::commit();

            return response()->json($workSchedule->load('days'));
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json(['message' => 'Erro ao atualizar escala.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSchedule $workSchedule)
    {
        if ($workSchedule->employees()->exists()) {
            $workSchedule->delete();

            return response()->noContent();
        }

        return response()->json(['message' => 'Não foi possível deletar a escala. Escala está em uso.'], 500);
    }
}
