<?php
// app/Http/Controllers/MemberController.php
namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function create(Family $family)
    {
        $this->authorize('update', $family);

        $members = $family->members;
        return view('members.create', compact('family', 'members'));
    }

    public function store(Request $request, Family $family)
    {
        $this->authorize('update', $family);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:1024',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'death_date' => 'nullable|date',
            'death_place' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'parent_id' => 'nullable|exists:members,id',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        $member = new Member($validated);
        $member->family_id = $family->id;
        $member->save();

        if ($request->spouse_id) {
            $member->spouses()->attach($request->spouse_id, ['relationship_type' => 'spouse']);
        }

        return redirect()->route('families.show', $family)->with('success', 'Anggota keluarga berhasil ditambahkan');
    }

    public function show(Family $family, Member $member)
    {
        $this->authorize('view', $family);

        return view('members.show', compact('family', 'member'));
    }

    public function edit(Family $family, Member $member)
    {
        $this->authorize('update', $family);

        $members = $family->members->where('id', '!=', $member->id);
        return view('members.edit', compact('family', 'member', 'members'));
    }

    public function update(Request $request, Family $family, Member $member)
    {
        $this->authorize('update', $family);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:1024',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'death_date' => 'nullable|date',
            'death_place' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'gender' => 'required|in:male,female,other',
            'parent_id' => 'nullable|exists:members,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $path = $request->file('photo')->store('members', 'public');
            $validated['photo'] = $path;
        }

        $member->update($validated);

        if ($request->spouse_id) {
            $member->spouses()->sync([$request->spouse_id => ['relationship_type' => 'spouse']]);
        }

        return redirect()->route('families.show', $family)->with('success', 'Anggota keluarga berhasil diperbarui');
    }

    public function destroy(Family $family, Member $member)
    {
        $this->authorize('update', $family);

        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('families.show', $family)->with('success', 'Anggota keluarga berhasil dihapus');
    }
}
