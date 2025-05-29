<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Meal;
use App\Models\Diary;
use App\Models\Measurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReportController extends BaseController
{
    /**
     * Get default reports data (daily view)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $today = Carbon::today()->format('Y-m-d');
        
        return $this->generateReportData($today, $today, 'daily');
    }

    /**
     * Get reports by period type (daily, weekly, monthly)
     *
     * @param string $period
     * @return JsonResponse
     */
    public function byPeriod($period): JsonResponse
    {
        $startDate = null;
        $endDate = null;
        
        switch ($period) {
            case 'daily':
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate = Carbon::today()->format('Y-m-d');
                break;
                
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
                
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
                
            default:
                return $this->sendError('Invalid period type. Use daily, weekly, or monthly.');
        }
        
        return $this->generateReportData($startDate, $endDate, $period);
    }

    /**
     * Get reports for a custom date range
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function customRange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        
        return $this->generateReportData($request->start_date, $request->end_date, 'custom');
    }
    
    /**
     * Generate report data for the specified date range and period type
     *
     * @param string $startDate
     * @param string $endDate
     * @param string $periodType
     * @return JsonResponse
     */
    private function generateReportData($startDate, $endDate, $periodType): JsonResponse
    {
        // Calculate number of days in the period
        $start = Carbon::createFromFormat('Y-m-d', $startDate);
        $end = Carbon::createFromFormat('Y-m-d', $endDate);
        $numberOfDays = $start->diffInDays($end) + 1;
        
        // Get meals data with food items
        $meals = Meal::with(['mealItems.food'])
            ->where('user_id', request()->user()->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        
        // Calculate nutritional totals
        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFat = 0;
        $totalFiber = 0;
        
        foreach ($meals as $meal) {
            foreach ($meal->mealItems as $mealItem) {
                $multiplier = $mealItem->quantity / 100; // Assuming nutrition facts are per 100g
                $totalCalories += ($mealItem->food->calories ?? 0) * $multiplier;
                $totalProtein += ($mealItem->food->protein ?? 0) * $multiplier;
                $totalCarbs += ($mealItem->food->carbohydrate ?? 0) * $multiplier;
                $totalFat += ($mealItem->food->fat ?? 0) * $multiplier;
                $totalFiber += ($mealItem->food->fiber ?? 0) * $multiplier;
            }
        }
        
        // Calculate daily averages for periods longer than a day
        $avgCalories = $numberOfDays > 1 ? $totalCalories / $numberOfDays : $totalCalories;
        $avgProtein = $numberOfDays > 1 ? $totalProtein / $numberOfDays : $totalProtein;
        $avgCarbs = $numberOfDays > 1 ? $totalCarbs / $numberOfDays : $totalCarbs;
        $avgFat = $numberOfDays > 1 ? $totalFat / $numberOfDays : $totalFat;
        $avgFiber = $numberOfDays > 1 ? $totalFiber / $numberOfDays : $totalFiber;
        
        // Get macronutrient percentages
        $totalMacros = $totalProtein + $totalCarbs + $totalFat;
        $proteinPercentage = $totalMacros > 0 ? round(($totalProtein / $totalMacros) * 100) : 0;
        $carbsPercentage = $totalMacros > 0 ? round(($totalCarbs / $totalMacros) * 100) : 0;
        $fatPercentage = $totalMacros > 0 ? round(($totalFat / $totalMacros) * 100) : 0;
        
        // Get water consumption data
        $diaryEntries = Diary::where('user_id', request()->user()->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        
        $totalWater = $diaryEntries->sum('water');
        $avgWater = $numberOfDays > 1 ? $totalWater / $numberOfDays : $totalWater;
        
        // Water consumption status (based on 2L daily goal)
        $waterStatus = 'regular';
        $waterPercentage = 0;
        $dailyGoal = 2000; // 2L = 2000ml
        
        if ($periodType === 'daily') {
            $waterPercentage = $dailyGoal > 0 ? min(100, round(($totalWater / $dailyGoal) * 100)) : 0;
            
            if ($waterPercentage >= 85) {
                $waterStatus = 'excellent';
            } elseif ($waterPercentage >= 70) {
                $waterStatus = 'good';
            } elseif ($waterPercentage >= 50) {
                $waterStatus = 'regular';
            } else {
                $waterStatus = 'low';
            }
        } else {
            $waterPercentage = $dailyGoal > 0 ? min(100, round(($avgWater / $dailyGoal) * 100)) : 0;
            
            if ($avgWater >= 1700) {
                $waterStatus = 'excellent';
            } elseif ($avgWater >= 1400) {
                $waterStatus = 'good';
            } elseif ($avgWater >= 1000) {
                $waterStatus = 'regular';
            } else {
                $waterStatus = 'low';
            }
        }
        
        // Get glucose measurements
        $glucoseMeasurements = Measurement::where('user_id', request()->user()->id)
            ->where('type', 'glucose')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('time')
            ->get();
        
        // Prepare the response data
        $responseData = [
            'period_type' => $periodType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'number_of_days' => $numberOfDays,
            'period_display' => $this->getPeriodDisplayText($startDate, $endDate, $periodType),
            'nutrition' => [
                'calories' => [
                    'total' => round($totalCalories, 1),
                    'average' => round($avgCalories, 1),
                ],
                'macros' => [
                    'protein' => [
                        'total' => round($totalProtein, 1),
                        'average' => round($avgProtein, 1),
                        'percentage' => $proteinPercentage,
                    ],
                    'carbohydrates' => [
                        'total' => round($totalCarbs, 1),
                        'average' => round($avgCarbs, 1),
                        'percentage' => $carbsPercentage,
                    ],
                    'fat' => [
                        'total' => round($totalFat, 1),
                        'average' => round($avgFat, 1),
                        'percentage' => $fatPercentage,
                    ],
                    'fiber' => [
                        'total' => round($totalFiber, 1),
                        'average' => round($avgFiber, 1),
                    ],
                ],
            ],
            'water' => [
                'total' => $totalWater,
                'average' => round($avgWater, 1),
                'percentage' => $waterPercentage,
                'status' => $waterStatus,
                'label' => $this->getWaterConsumptionLabel($periodType),
            ],
            'glucose' => [
                'measurements' => $glucoseMeasurements,
                'count' => $glucoseMeasurements->count(),
                'average' => $glucoseMeasurements->count() > 0 ? round($glucoseMeasurements->avg('value'), 1) : 0,
                'min' => $glucoseMeasurements->count() > 0 ? $glucoseMeasurements->min('value') : 0,
                'max' => $glucoseMeasurements->count() > 0 ? $glucoseMeasurements->max('value') : 0,
            ],
        ];
        
        return $this->sendResponse($responseData, 'Reports data retrieved successfully');
    }
    
    /**
     * Get a display text for the current period
     * 
     * @param string $startDate
     * @param string $endDate
     * @param string $periodType
     * @return string
     */
    private function getPeriodDisplayText($startDate, $endDate, $periodType): string
    {
        $start = Carbon::createFromFormat('Y-m-d', $startDate);
        $end = Carbon::createFromFormat('Y-m-d', $endDate);
        
        switch ($periodType) {
            case 'daily':
                return $start->format('d/m/Y');
                
            case 'weekly':
                return 'Semana de ' . $start->format('d/m/Y') . ' até ' . $end->format('d/m/Y');
                
            case 'monthly':
                return $start->format('F Y');
                
            case 'custom':
                return $start->format('d/m/Y') . ' até ' . $end->format('d/m/Y');
                
            default:
                return '';
        }
    }
    
    /**
     * Get appropriate label for water consumption based on period type
     * 
     * @param string $periodType
     * @return string
     */
    private function getWaterConsumptionLabel($periodType): string
    {
        switch ($periodType) {
            case 'daily':
                return 'Consumo de água hoje';
                
            case 'weekly':
                return 'Média diária de água (semana)';
                
            case 'monthly':
                return 'Média diária de água (mês)';
                
            case 'custom':
                return 'Média diária de água';
                
            default:
                return 'Consumo de água';
        }
    }
}
