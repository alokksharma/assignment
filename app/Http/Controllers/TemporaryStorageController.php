<?php

namespace App\Http\Controllers;

use App\Storage\TemporaryStorage;
use Illuminate\Http\Resources\Json\JsonResource;

class TemporaryStorageController extends Controller
{
    public function show($id)
    {
        $url = TemporaryStorage::getUrlForId($id);

        return new JsonResource([
            'id' => $id,
            'status' => $url ? 'completed' : 'pending',
            'url' => $url,
        ]);
    }
}
