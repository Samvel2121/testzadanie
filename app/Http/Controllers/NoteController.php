<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $notes = Note::query()
            ->where('user_id', $request->user()->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('updated_at')
            ->paginate(9)
            ->withQueryString();

        return view('notes.index', compact('notes', 'search'));
    }

    public function create(): View
    {
        return view('notes.create');
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $request->user()->notes()->create($request->validated());

        return redirect()
            ->route('notes.index')
            ->with('success', 'Заметка создана.');
    }

    public function edit(Note $note): View
    {
        abort_if($note->user_id !== auth()->id(), Response::HTTP_FORBIDDEN);

        return view('notes.edit', compact('note'));
    }

    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        abort_if($note->user_id !== $request->user()->id, Response::HTTP_FORBIDDEN);

        $note->update($request->validated());

        return redirect()
            ->route('notes.index')
            ->with('success', 'Заметка обновлена.');
    }

    public function destroy(Request $request, Note $note): RedirectResponse
    {
        abort_if($note->user_id !== $request->user()->id, Response::HTTP_FORBIDDEN);

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with('success', 'Заметка удалена.');
    }

    public function togglePin(Request $request, Note $note): array
    {
        abort_if($note->user_id !== $request->user()->id, Response::HTTP_FORBIDDEN);

        $note->update([
            'is_pinned' => ! $note->is_pinned,
        ]);

        return [
            'is_pinned' => $note->is_pinned,
        ];
    }
}