@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center">
        <h1 class="text-3xl font-bold text-gray-900">Buat Silsilah Keluarga Baru</h1>
        <a href="{{ route('dashboard') }}" class="ml-auto bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
        <form action="{{ route('families.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Informasi Silsilah -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Silsilah</h2>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Silsilah</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base"
                        placeholder="Contoh: Keluarga Besar Soeharto">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Silsilah</label>
                    <textarea name="description" id="description" rows="4"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-base"
                        placeholder="Jelaskan secara singkat tentang silsilah keluarga ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg mr-3 hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Buat Silsilah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
