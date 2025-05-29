<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Measurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MeasurementController extends BaseController
{
    /**
     * Display a listing of measurements.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Measurement::query()->where('user_id', request()->user()->id);
        
        // Apply date range filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        // Apply measurement type filter if provided
        if ($request->has('type') && in_array($request->type, ['glucose', 'weight', 'blood_pressure', 'other'])) {
            $query->where('type', $request->type);
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'date');
        $sortDirection = $request->get('sort_direction', 'desc');
        if (in_array($sortField, ['date', 'type', 'value', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $measurements = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse($measurements, 'Measurements retrieved successfully');
    }

    /**
     * Store a newly created measurement.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'type' => 'required|string|in:glucose,weight,blood_pressure,other',
            'value' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $measurement = Measurement::create([
            'user_id' => request()->user()->id,
            'date' => $request->date,
            'time' => $request->time,
            'type' => $request->type,
            'value' => $request->value,
            'unit' => $request->unit,
            'notes' => $request->notes,
        ]);

        return $this->sendResponse($measurement, 'Measurement created successfully');
    }

    /**
     * Display the specified measurement.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $measurement = Measurement::where('user_id', request()->user()->id)->find($id);

        if (is_null($measurement)) {
            return $this->sendError('Measurement not found.');
        }

        return $this->sendResponse($measurement, 'Measurement retrieved successfully');
    }

    /**
     * Update the specified measurement.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $measurement = Measurement::where('user_id', request()->user()->id)->find($id);

        if (is_null($measurement)) {
            return $this->sendError('Measurement not found.');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|date_format:H:i',
            'type' => 'sometimes|required|string|in:glucose,weight,blood_pressure,other',
            'value' => 'sometimes|required|numeric',
            'unit' => 'sometimes|required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $measurement->update($request->all());

        return $this->sendResponse($measurement, 'Measurement updated successfully');
    }

    /**
     * Remove the specified measurement.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $measurement = Measurement::where('user_id', request()->user()->id)->find($id);

        if (is_null($measurement)) {
            return $this->sendError('Measurement not found.');
        }

        $measurement->delete();

        return $this->sendResponse(null, 'Measurement deleted successfully');
    }

    /**
     * Get latest measurement of each type.
     *
     * @return JsonResponse
     */
    public function latest(): JsonResponse
    {
        $types = ['glucose', 'weight', 'blood_pressure', 'other'];
        $latest = [];
        
        foreach ($types as $type) {
            $measurement = Measurement::where('user_id', request()->user()->id)
                ->where('type', $type)
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->first();
                
            if ($measurement) {
                $latest[$type] = $measurement;
            }
        }

        return $this->sendResponse($latest, 'Latest measurements retrieved successfully');
    }

    /**
     * Get measurements by type.
     *
     * @param string $type
     * @param Request $request
     * @return JsonResponse
     */
    public function byType($type, Request $request): JsonResponse
    {
        if (!in_array($type, ['glucose', 'weight', 'blood_pressure', 'other'])) {
            return $this->sendError('Invalid measurement type.');
        }
        
        $query = Measurement::where('user_id', request()->user()->id)
            ->where('type', $type);
            
        // Apply date range filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
            
        $measurements = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->sendResponse($measurements, ucfirst($type) . ' measurements retrieved successfully');
    }
}
