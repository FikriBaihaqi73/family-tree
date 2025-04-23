<?php
// app/Http/Controllers/MemberController.php
namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class MemberController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

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
            $file = $request->file('photo');
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                ['folder' => 'silsilah/members']
            );
            $validated['photo'] = $result['secure_url'];
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
            // Jika ada foto lama dan URL-nya berisi cloudinary, hapus foto lama
            if ($member->photo && strpos($member->photo, 'cloudinary.com') !== false) {
                // Ekstrak public_id dari URL
                $parts = explode('/', $member->photo);
                $filename = end($parts);
                $public_id = 'silsilah/members/' . pathinfo($filename, PATHINFO_FILENAME);

                // Hapus gambar dari Cloudinary
                $this->cloudinary->uploadApi()->destroy($public_id);
            }

            // Upload foto baru
            $file = $request->file('photo');
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                ['folder' => 'silsilah/members']
            );
            $validated['photo'] = $result['secure_url'];
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

        // Hapus foto dari Cloudinary jika ada
        if ($member->photo && strpos($member->photo, 'cloudinary.com') !== false) {
            // Ekstrak public_id dari URL
            $parts = explode('/', $member->photo);
            $filename = end($parts);
            $public_id = 'silsilah/members/' . pathinfo($filename, PATHINFO_FILENAME);

            // Hapus gambar dari Cloudinary
            $this->cloudinary->uploadApi()->destroy($public_id);
        }

        $member->delete();

        return redirect()->route('families.show', $family)->with('success', 'Anggota keluarga berhasil dihapus');
    }
}
