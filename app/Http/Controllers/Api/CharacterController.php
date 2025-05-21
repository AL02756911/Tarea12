<?php

namespace App\Http\Controllers\Api;

use App\Models\Character;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    public function index()
    {
        return Character::with('media')->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'exists:media,id'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('characters', 'public');
            $data['image_path'] = $path;
        }

        $character = Character::create($data);
        if (!empty($data['media_ids'])) {
            $character->media()->sync($data['media_ids']);
        }

        return response()->json($character->load('media'), 201);
    }

    public function show(Character $character)
    {
        return $character->load('media');
    }

    public function update(Request $request, Character $character)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'exists:media,id'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($character->image_path);
            $path = $request->file('image')->store('characters', 'public');
            $data['image_path'] = $path;
        }

        $character->update($data);
        if (isset($data['media_ids'])) {
            $character->media()->sync($data['media_ids']);
        }

        return $character->load('media');
    }

    public function destroy(Character $character)
    {
        Storage::disk('public')->delete($character->image_path);
        $character->delete();
        return response()->noContent();
    }
}
