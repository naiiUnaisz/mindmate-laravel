<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = $request->user()->notes()->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    public function show(Request $request, $id)
    {
        $note = $request->user()->notes()->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $note
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note = $request->user()->notes()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan',
            'data' => $note
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $note = $request->user()->notes()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui',
            'data' => $note
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $note = $request->user()->notes()->findOrFail($id);
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil dihapus'
        ]);
    }
}
