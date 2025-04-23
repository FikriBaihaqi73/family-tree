@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $family->name }}</h1>
        <p class="text-gray-600">{{ $family->description }}</p>
    </div>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('families.members.create', $family) }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Tambah Anggota
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 overflow-x-auto">
        <x-family-tree :members="$members" :family="$family" />
    </div>
</div>
@endsection
