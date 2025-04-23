@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}</p>
    </div>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('families.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
            Buat Silsilah Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($families as $family)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $family->name }}</h2>
                <p class="text-gray-600 mb-4">{{ $family->description ?? 'Tidak ada deskripsi' }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">{{ $family->members->count() }} anggota</span>
                    <div class="space-x-2">
                        <a href="{{ route('families.show', $family) }}"
                           class="text-indigo-600 hover:text-indigo-800">
                            Lihat Detail
                        </a>
                        <a href="{{ route('families.edit', $family) }}"
                           class="text-gray-600 hover:text-gray-800">
                            Edit
                        </a>
                        <form action="{{ route('families.destroy', $family) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus silsilah ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-600 mb-4">Anda belum memiliki silsilah keluarga</p>
                <a href="{{ route('families.create') }}"
                   class="text-indigo-600 hover:text-indigo-800 font-medium">
                    Buat silsilah pertama Anda
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
