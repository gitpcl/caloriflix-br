<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends BaseController
{
    /**
     * Display a listing of recipes.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Recipe::query()->where('user_id', request()->user()->id);
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        if (in_array($sortField, ['name', 'preparation_time', 'cooking_time', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $recipes = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse($recipes, 'Recipes retrieved successfully');
    }

    /**
     * Store a newly created recipe.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'preparation_time' => 'nullable|integer|min:0',
            'cooking_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'protein' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'carbohydrate' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'calories' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $recipe = Recipe::create(array_merge(
            $request->all(),
            ['user_id' => request()->user()->id]
        ));

        return $this->sendResponse($recipe, 'Recipe created successfully');
    }

    /**
     * Display the specified recipe.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $recipe = Recipe::where('user_id', request()->user()->id)->find($id);

        if (is_null($recipe)) {
            return $this->sendError('Recipe not found.');
        }

        return $this->sendResponse($recipe, 'Recipe retrieved successfully');
    }

    /**
     * Update the specified recipe.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $recipe = Recipe::where('user_id', request()->user()->id)->find($id);

        if (is_null($recipe)) {
            return $this->sendError('Recipe not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'ingredients' => 'sometimes|required|string',
            'instructions' => 'sometimes|required|string',
            'preparation_time' => 'nullable|integer|min:0',
            'cooking_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'protein' => 'sometimes|required|numeric|min:0',
            'fat' => 'sometimes|required|numeric|min:0',
            'carbohydrate' => 'sometimes|required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'calories' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $recipe->update($request->all());

        return $this->sendResponse($recipe, 'Recipe updated successfully');
    }

    /**
     * Remove the specified recipe.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $recipe = Recipe::where('user_id', request()->user()->id)->find($id);

        if (is_null($recipe)) {
            return $this->sendError('Recipe not found.');
        }

        $recipe->delete();

        return $this->sendResponse(null, 'Recipe deleted successfully');
    }
}
