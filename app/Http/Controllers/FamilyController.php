<?php
// app/Http/Controllers/FamilyController.php
namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $families = Family::where('user_id', $user->id)->get();
        return view('families.index', compact('families'));
    }

    public function create()
    {
        return view('families.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $family = new Family($validated);
        $family->user_id = $request->user()->id;
        $family->save();

        return redirect()->route('families.show', $family)->with('success', 'Silsilah keluarga berhasil dibuat');
    }

    public function show(Request $request, Family $family)
    {
        $this->authorize('view', $family);

        $members = $family->members;
        return view('families.show', compact('family', 'members'));
    }

    public function edit(Family $family)
    {
        $this->authorize('update', $family);

        return view('families.edit', compact('family'));
    }

    public function update(Request $request, Family $family)
    {
        $this->authorize('update', $family);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $family->update($validated);

        return redirect()->route('families.show', $family)->with('success', 'Silsilah keluarga berhasil diperbarui');
    }

    public function destroy(Family $family)
    {
        $this->authorize('delete', $family);

        $family->delete();

        return redirect()->route('families.index')->with('success', 'Silsilah keluarga berhasil dihapus');
    }
}
