@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Anggota Keluarga</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('families.members.store', $family) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select name="gender" id="gender" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">Foto</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPG, PNG, GIF. Maksimal 1MB.</p>
                @error('photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('birth_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('birth_place')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="death_date" class="block text-sm font-medium text-gray-700">Tanggal Meninggal</label>
                <input type="date" name="death_date" id="death_date" value="{{ old('death_date') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('death_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="death_place" class="block text-sm font-medium text-gray-700">Tempat Meninggal</label>
                <input type="text" name="death_place" id="death_place" value="{{ old('death_place') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('death_place')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="occupation" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('occupation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bio" class="block text-sm font-medium text-gray-700">Biografi</label>
                <textarea name="bio" id="bio" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio') }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Orang Tua</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Orang Tua</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('parent_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="spouse_id" class="block text-sm font-medium text-gray-700">Pasangan</label>
                <select name="spouse_id" id="spouse_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Pasangan</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('spouse_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
                @error('spouse_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('families.show', $family) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
