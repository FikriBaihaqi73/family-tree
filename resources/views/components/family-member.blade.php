@props(['member'])

<div class="member-branch flex flex-col items-center">
    <div class="spouse-container flex items-center">
        <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $member->id }})">
            <img src="{{ $member->photo ? Storage::url($member->photo) : asset('images/default-avatar.png') }}"
                 alt="{{ $member->name }}"
                 class="w-20 h-20 rounded-full mx-auto mb-2 object-cover">
            <h4 class="text-md font-semibold text-center">{{ $member->name }}</h4>
            <div class="text-xs text-gray-600 text-center">
                {{ $member->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
            </div>
            @if($member->birth_date)
                <div class="text-xs text-center">Lahir: {{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}</div>
            @endif
        </div>

        @if($member->spouses && $member->spouses->count() > 0)
            <div class="spouse-connector w-8 h-0.5 bg-gray-300 mx-2"></div>
            <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $member->spouses->first()->id }})">
                <img src="{{ $member->spouses->first()->photo ? Storage::url($member->spouses->first()->photo) : asset('images/default-avatar.png') }}"
                     alt="{{ $member->spouses->first()->name }}"
                     class="w-20 h-20 rounded-full mx-auto mb-2 object-cover">
                <h4 class="text-md font-semibold text-center">{{ $member->spouses->first()->name }}</h4>
                <div class="text-xs text-gray-600 text-center">
                    {{ $member->spouses->first()->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                </div>
                @if($member->spouses->first()->birth_date)
                    <div class="text-xs text-center">Lahir: {{ \Carbon\Carbon::parse($member->spouses->first()->birth_date)->format('d M Y') }}</div>
                @endif
            </div>
        @endif
    </div>

    @if($member->children->count() > 0 || ($member->spouses && $member->spouses->first() && $member->spouses->first()->children->where('parent_id', '!=', $member->id)->count() > 0))
        <div class="vertical-line w-0.5 h-8 bg-gray-300"></div>
        <div class="children-container flex justify-center space-x-4">
            @foreach($member->children as $child)
                @include('components.family-member', ['member' => $child])
            @endforeach

            @if($member->spouses && $member->spouses->first())
                @foreach($member->spouses->first()->children->where('parent_id', '!=', $member->id) as $spouseChild)
                    @include('components.family-member', ['member' => $spouseChild])
                @endforeach
            @endif
        </div>
    @endif
</div>
