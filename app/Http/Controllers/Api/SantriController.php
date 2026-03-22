<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SantriResource;
use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Santri::query()
            ->forWebList($request, $user)
            ->orderByDesc('created_at');

        $perPage = min(max((int) $request->get('per_page', 25), 1), 100);

        return SantriResource::collection($query->paginate($perPage));
    }
}
