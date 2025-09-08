<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoursResource;
use App\Models\Cours;
use Illuminate\Http\Request;

class CoursApiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Cours::class);
        $cours = Cours::with('instructeur')->actif()->orderBy('jour_semaine')->orderBy('heure_debut')->get();

        return CoursResource::collection($cours);
    }

    public function show(Cours $cours)
    {
        $this->authorize('view', $cours);
        $cours->load('instructeur');

        return new CoursResource($cours);
    }
}
