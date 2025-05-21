<?php

namespace App\Http\Controllers\Api;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function index()
    {
        return Media::with('characters')->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'genre' => 'required',
            'release_date' => 'required|date',
            'review' => 'nullable|string',
            'season' => 'nullable|integer',
        ]);

        $media = Media::create($data);
        if ($request->has('character_ids')) {
            $media->characters()->sync($request->input('character_ids'));
        }

        return response()->json($media->load('characters'), 201);
    }

    public function show(Media $media)
    {
        return $media->load('characters');
    }

    public function update(Request $request, Media $media)
    {
        $media->update($request->all());
        if ($request->has('character_ids')) {
            $media->characters()->sync($request->input('character_ids'));
        }
        return $media->load('characters');
    }

    public function destroy(Media $media)
    {
        $media->delete();
        return response()->noContent();
    }
}
