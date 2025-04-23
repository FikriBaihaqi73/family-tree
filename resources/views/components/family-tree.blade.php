@props(['members', 'family'])

<div class="family-tree p-4">
    <div class="flex flex-col items-center">
        @foreach($members->whereNull('parent_id') as $root)
            <div class="spouse-container flex items-center">
                <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $root->id }})">
                    <img src="{{ $root->photo ? Storage::url($root->photo) : asset('images/default-avatar.png') }}"
                         alt="{{ $root->name }}"
                         class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                    <h3 class="text-lg font-semibold text-center">{{ $root->name }}</h3>
                    <div class="text-xs text-gray-600 text-center">
                        {{ $root->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                    </div>
                    @if($root->birth_date)
                        <div class="text-xs text-center">Lahir: {{ \Carbon\Carbon::parse($root->birth_date)->format('d M Y') }}</div>
                    @endif
                </div>

                @if($root->spouses && $root->spouses->count() > 0)
                    <div class="spouse-connector w-8 h-0.5 bg-gray-300 mx-2"></div>
                    <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $root->spouses->first()->id }})">
                        <img src="{{ $root->spouses->first()->photo ? Storage::url($root->spouses->first()->photo) : asset('images/default-avatar.png') }}"
                             alt="{{ $root->spouses->first()->name }}"
                             class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                        <h3 class="text-lg font-semibold text-center">{{ $root->spouses->first()->name }}</h3>
                        <div class="text-xs text-gray-600 text-center">
                            {{ $root->spouses->first()->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                        @if($root->spouses->first()->birth_date)
                            <div class="text-xs text-center">Lahir: {{ \Carbon\Carbon::parse($root->spouses->first()->birth_date)->format('d M Y') }}</div>
                        @endif
                    </div>
                @endif
            </div>

            @if($root->children->count() > 0 || ($root->spouses && $root->spouses->first() && $root->spouses->first()->children->where('parent_id', '!=', $root->id)->count() > 0))
                <div class="vertical-line w-0.5 h-8 bg-gray-300"></div>
                <div class="children-container flex justify-center space-x-4">
                    @foreach($root->children as $child)
                        @include('components.family-member', ['member' => $child])
                    @endforeach

                    @if($root->spouses && $root->spouses->first())
                        @foreach($root->spouses->first()->children->where('parent_id', '!=', $root->id) as $spouseChild)
                            @include('components.family-member', ['member' => $spouseChild])
                        @endforeach
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>

<!-- Modal untuk detail anggota keluarga -->
<div id="memberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <div id="memberModalContent">
            <!-- Konten modal akan diisi melalui JavaScript -->
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-2">Memuat...</p>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <div class="flex space-x-2">
                <button id="editMemberBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Edit
                </button>
                <button id="deleteMemberBtn" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Hapus
                </button>
            </div>
            <button onclick="closeMemberModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    let currentMemberId = null;

    function showMemberModal(memberId) {
        // Simpan ID anggota yang sedang ditampilkan
        currentMemberId = memberId;

        // Tampilkan modal
        document.getElementById('memberModal').classList.remove('hidden');

        // Ambil data anggota keluarga melalui AJAX
        fetch(`/members/${memberId}`)
            .then(response => response.json())
            .then(data => {
                // Isi konten modal dengan data anggota
                document.getElementById('memberModalContent').innerHTML = `
                    <div class="text-center mb-4">
                        <img src="${data.photo ? data.photo : '/images/default-avatar.png'}"
                             alt="${data.name}"
                             class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                        <h3 class="text-xl font-bold">${data.name}</h3>
                        <p class="text-gray-600">${data.gender === 'male' ? 'Laki-laki' : data.gender === 'female' ? 'Perempuan' : 'Lainnya'}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Lahir</p>
                            <p>${data.birth_date ? new Date(data.birth_date).toLocaleDateString('id-ID') : '-'}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tempat Lahir</p>
                            <p>${data.birth_place || '-'}</p>
                        </div>
                        ${data.death_date ? `
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Meninggal</p>
                            <p>${new Date(data.death_date).toLocaleDateString('id-ID')}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tempat Meninggal</p>
                            <p>${data.death_place || '-'}</p>
                        </div>
                        ` : ''}
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-500">Pekerjaan</p>
                            <p>${data.occupation || '-'}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-500">Biografi</p>
                            <p>${data.bio || '-'}</p>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                document.getElementById('memberModalContent').innerHTML = `
                    <div class="text-center text-red-600">
                        <p>Terjadi kesalahan saat memuat data.</p>
                    </div>
                `;
                console.error('Error:', error);
            });
    }

    function closeMemberModal() {
        document.getElementById('memberModal').classList.add('hidden');
        currentMemberId = null;
    }

    // Event listener untuk tombol Edit
    document.getElementById('editMemberBtn').addEventListener('click', function() {
        if (currentMemberId) {
            window.location.href = `/families/{{ isset($family) ? $family->id : '' }}/members/${currentMemberId}/edit`;
        }
    });

    // Event listener untuk tombol Hapus
    document.getElementById('deleteMemberBtn').addEventListener('click', function() {
        if (currentMemberId && confirm('Apakah Anda yakin ingin menghapus anggota keluarga ini?')) {
            // Buat form untuk mengirim request DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/families/members/${currentMemberId}`;

            // Tambahkan CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Tambahkan method DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Tambahkan form ke body dan submit
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
