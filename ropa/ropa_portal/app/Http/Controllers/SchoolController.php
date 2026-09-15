<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * List school/business-function names belonging to a given college.
     * Used by the Select2 ajax source on step 1.
     *
     * GET /api/schools/{college}
     */
    public function index(College $college): JsonResponse
    {
        $names = $college->schools()
            ->orderBy('name')
            ->pluck('name');

        return response()->json($names);
    }

    /**
     * Create a new business function (school) under a college on the fly,
     * triggered when the user types a value that doesn't exist yet.
     *
     * POST /api/schools
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'name' => 'required|string|max:255',
        ]);

        // Avoid duplicate names under the same college (case-insensitive).
        $school = School::where('college_id', $validated['college_id'])
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($validated['name'])])
            ->first();

        if (! $school) {
            $school = School::create([
                'college_id' => $validated['college_id'],
                'name' => trim($validated['name']),
            ]);
        }

        return response()->json([
            'id' => $school->id,
            'name' => $school->name,
            'college_id' => $school->college_id,
        ], 201);
    }
}
