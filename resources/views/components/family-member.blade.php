@props(['member'])

<div class="member-branch flex flex-col items-center">
    <div class="spouse-container flex items-center">
        <!-- Primary member card -->
        <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer hover:shadow-lg" onclick="showMemberModal({{ $member->id }})">
            <img src="{{ $member->photo ?: "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}" alt="{{ $member->name }}"
                 class="w-20 h-20 rounded-full mx-auto mb-2 object-cover border-2 border-gray-200">
            <h4 class="text-md font-semibold text-center">{{ $member->name }}</h4>
            <div class="text-xs text-gray-600 text-center">
                {{ $member->gender == 'male' ? 'Laki-laki' : ($member->gender == 'female' ? 'Perempuan' : 'Lainnya') }}
            </div>
            @if($member->birth_date)
                <div class="text-xs text-center">{{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}</div>
            @endif
            @if($member->death_date)
                <div class="text-xs text-gray-500 text-center">† {{ \Carbon\Carbon::parse($member->death_date)->format('d M Y') }}</div>
            @endif
        </div>

        <!-- Spouse member card (if exists) -->
        @php
            $spouse = $member->spouses->first();
        @endphp

        @if($spouse)
            <div class="spouse-connector w-8 h-0.5 bg-gray-300 mx-2"></div>
            <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer hover:shadow-lg" onclick="showMemberModal({{ $spouse->id }})">
                <img src="{{ $spouse->photo ? Storage::url($spouse->photo) : asset('images/default-avatar.png') }}"
                     alt="{{ $spouse->name }}"
                     class="w-20 h-20 rounded-full mx-auto mb-2 object-cover border-2 border-gray-200">
                <h4 class="text-md font-semibold text-center">{{ $spouse->name }}</h4>
                <div class="text-xs text-gray-600 text-center">
                    {{ $spouse->gender == 'male' ? 'Laki-laki' : ($spouse->gender == 'female' ? 'Perempuan' : 'Lainnya') }}
                </div>
                @if($spouse->birth_date)
                    <div class="text-xs text-center">{{ \Carbon\Carbon::parse($spouse->birth_date)->format('d M Y') }}</div>
                @endif
                @if($spouse->death_date)
                    <div class="text-xs text-gray-500 text-center">† {{ \Carbon\Carbon::parse($spouse->death_date)->format('d M Y') }}</div>
                @endif
            </div>
        @endif
    </div>

    <!-- Children section -->
    @php
        // Collect all unique children from both member and spouse
        $allChildren = collect([]);

        // Add member's children
        if($member->children->count() > 0) {
            $allChildren = $allChildren->merge($member->children);
        }

        // Add spouse's children that aren't already included
        if($spouse) {
            $spouseChildren = $spouse->children;
            foreach($spouseChildren as $spouseChild) {
                if(!$allChildren->contains('id', $spouseChild->id)) {
                    $allChildren->push($spouseChild);
                }
            }
        }

        // Sort children by birth date
        $allChildren = $allChildren->sortBy('birth_date');
    @endphp

    @if($allChildren->count() > 0)
        <div class="vertical-line w-0.5 h-8 bg-gray-300"></div>
        <div class="children-container flex flex-wrap justify-center">
            @foreach($allChildren as $child)
                @include('components.family-member', ['member' => $child])
            @endforeach
        </div>
    @endif
</div>
