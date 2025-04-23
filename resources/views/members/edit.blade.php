@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center">
        <h1 class="text-3xl font-bold text-gray-900">Edit Anggota Keluarga</h1>
        <a href="{{ route('families.show', $family) }}" class="ml-auto bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
        <form action="{{ route('families.members.update', [$family, $member]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="md:col-span-2">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Dasar</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" id="gender" required
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                            <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                            <option value="other" {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        @error('birth_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $member->birth_place) }}"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        @error('birth_place')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($member->photo)
                                    <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPG, PNG, GIF. Maksimal 1MB.</p>
                            </div>
                        </div>
                        @error('photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $member->occupation) }}"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        @error('occupation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="death_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Meninggal <span class="text-xs text-gray-500">(opsional)</span></label>
                        <input type="date" name="death_date" id="death_date" value="{{ old('death_date', $member->death_date ? $member->death_date->format('Y-m-d') : '') }}"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        @error('death_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="death_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat Meninggal <span class="text-xs text-gray-500">(opsional)</span></label>
                        <input type="text" name="death_place" id="death_place" value="{{ old('death_place', $member->death_place) }}"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        @error('death_place')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informasi Hubungan -->
                <div class="md:col-span-2 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Hubungan Keluarga</h2>
                </div>

                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Orang Tua/Mertua</label>
                    <select name="parent_id" id="parent_id"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        <option value="">Pilih Orang Tua</option>
                        @foreach($members as $potentialParent)
                            <option value="{{ $potentialParent->id }}" {{ old('parent_id', $member->parent_id) == $potentialParent->id ? 'selected' : '' }}>
                                {{ $potentialParent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="spouse_id" class="block text-sm font-medium text-gray-700 mb-1">Pasangan</label>
                    <select name="spouse_id" id="spouse_id"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">
                        <option value="">Pilih Pasangan</option>
                        @foreach($members as $potentialSpouse)
                            <option value="{{ $potentialSpouse->id }}" {{ old('spouse_id', $member->spouses->isNotEmpty() ? $member->spouses->first()->id : '') == $potentialSpouse->id ? 'selected' : '' }}>
                                {{ $potentialSpouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('spouse_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Biografi -->
                <div class="md:col-span-2 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Biografi</h2>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Cerita Hidup</label>
                        <textarea name="bio" id="bio" rows="5"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base">{{ old('bio', $member->bio) }}</textarea>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('families.show', $family) }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg mr-3 hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
