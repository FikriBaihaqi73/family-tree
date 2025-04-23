@props(['members', 'family'])

<div class="family-tree-container overflow-x-auto py-8">
    <div class="family-tree">
        <div class="flex flex-col items-center">
            @foreach($members->whereNull('parent_id') as $root)
                @php
                    // Skip if this root member is someone's spouse to avoid duplication
                    $isSpouse = false;
                    foreach($members as $m) {
                        if($m->spouses && $m->spouses->contains('id', $root->id)) {
                            $isSpouse = true;
                            break;
                        }
                    }
                @endphp

                @if(!$isSpouse)
                    <div class="spouse-container flex items-center">
                        <!-- Root member -->
                        <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $root->id }})">
                            <img src="{{ $root->photo ? $root->photo : "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}"
                                alt="{{ $root->name }}"
                                class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                            <h3 class="text-lg font-semibold text-center">{{ $root->name }}</h3>
                            <div class="text-xs text-gray-600 text-center">
                                {{ $root->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </div>
                        </div>

                        <!-- Spouse (if any) -->
                        @if($root->spouses && $root->spouses->count() > 0)
                            <div class="spouse-connector w-8 h-0.5 bg-gray-300 mx-2"></div>
                            <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $root->spouses->first()->id }})">
                                <img src="{{ $root->spouses->first()->photo ? $root->spouses->first()->photo : "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}"
                                    alt="{{ $root->spouses->first()->name }}"
                                    class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                                <h3 class="text-lg font-semibold text-center">{{ $root->spouses->first()->name }}</h3>
                                <div class="text-xs text-gray-600 text-center">
                                    {{ $root->spouses->first()->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Children section -->
                    @php
                        // Collect all children from both the root and spouse to avoid duplication
                        $allChildren = collect([]);

                        // Add root's children
                        if($root->children->count() > 0) {
                            $allChildren = $allChildren->merge($root->children);
                        }

                        // Add spouse's children that aren't already included
                        if($root->spouses && $root->spouses->count() > 0) {
                            $spouseChildren = $root->spouses->first()->children;
                            foreach($spouseChildren as $spouseChild) {
                                if(!$allChildren->contains('id', $spouseChild->id)) {
                                    $allChildren->push($spouseChild);
                                }
                            }
                        }

                        // Filter out spouses who have their partners in the children list
                        $filteredChildren = collect([]);
                        foreach($allChildren as $child) {
                            $isChildSpouse = false;
                            foreach($allChildren as $potentialPartner) {
                                if($potentialPartner->spouses && $potentialPartner->spouses->contains('id', $child->id)) {
                                    $isChildSpouse = true;
                                    break;
                                }
                            }
                            if(!$isChildSpouse) {
                                $filteredChildren->push($child);
                            }
                        }

                        // Sort children by birth date if available
                        $filteredChildren = $filteredChildren->sortBy('birth_date');
                    @endphp

                    @if($filteredChildren->count() > 0)
                        <div class="vertical-line w-0.5 h-8 bg-gray-300"></div>
                        <div class="children-container flex flex-wrap justify-center">
                            @foreach($filteredChildren as $child)
                                <div class="child-branch mx-4">
                                    <div class="spouse-container flex items-center">
                                        <!-- Child -->
                                        <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $child->id }})">
                                            <img src="{{ $child->photo ? $child->photo : "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}"
                                                alt="{{ $child->name }}"
                                                class="w-20 h-20 rounded-full mx-auto mb-2 object-cover">
                                            <h3 class="text-md font-semibold text-center">{{ $child->name }}</h3>
                                            <div class="text-xs text-gray-600 text-center">
                                                {{ $child->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                            </div>
                                        </div>

                                        <!-- Child's Spouse (if any) -->
                                        @if($child->spouses && $child->spouses->count() > 0)
                                            <div class="spouse-connector w-8 h-0.5 bg-gray-300 mx-2"></div>
                                            <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $child->spouses->first()->id }})">
                                                <img src="{{ $child->spouses->first()->photo ? $child->spouses->first()->photo : "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}"
                                                    alt="{{ $child->spouses->first()->name }}"
                                                    class="w-20 h-20 rounded-full mx-auto mb-2 object-cover">
                                                <h3 class="text-md font-semibold text-center">{{ $child->spouses->first()->name }}</h3>
                                                <div class="text-xs text-gray-600 text-center">
                                                    {{ $child->spouses->first()->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Grandchildren -->
                                    @php
                                        // Collect all grandchildren from both child and their spouse
                                        $allGrandchildren = collect([]);

                                        // Add child's children
                                        if($child->children->count() > 0) {
                                            $allGrandchildren = $allGrandchildren->merge($child->children);
                                        }

                                        // Add child's spouse's children
                                        if($child->spouses && $child->spouses->count() > 0) {
                                            $spouseChildren = $child->spouses->first()->children;
                                            foreach($spouseChildren as $spouseChild) {
                                                if(!$allGrandchildren->contains('id', $spouseChild->id)) {
                                                    $allGrandchildren->push($spouseChild);
                                                }
                                            }
                                        }

                                        // Filter and sort grandchildren as we did with children
                                        $filteredGrandchildren = collect([]);
                                        foreach($allGrandchildren as $grandchild) {
                                            $isGrandchildSpouse = false;
                                            foreach($allGrandchildren as $potentialPartner) {
                                                if($potentialPartner->spouses && $potentialPartner->spouses->contains('id', $grandchild->id)) {
                                                    $isGrandchildSpouse = true;
                                                    break;
                                                }
                                            }
                                            if(!$isGrandchildSpouse) {
                                                $filteredGrandchildren->push($grandchild);
                                            }
                                        }

                                        $filteredGrandchildren = $filteredGrandchildren->sortBy('birth_date');
                                    @endphp

                                    @if($filteredGrandchildren->count() > 0)
                                        <div class="vertical-line w-0.5 h-8 bg-gray-300 mx-auto"></div>
                                        <div class="grandchildren-container flex flex-wrap justify-center">
                                            @foreach($filteredGrandchildren as $grandchild)
                                                <div class="grandchild-branch mx-2">
                                                    <div class="spouse-container flex items-center">
                                                        <!-- Grandchild -->
                                                        <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $grandchild->id }})">
                                                            <img src="{{ $grandchild->photo ? $grandchild->photo : "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}"
                                                                alt="{{ $grandchild->name }}"
                                                                class="w-16 h-16 rounded-full mx-auto mb-2 object-cover">
                                                            <h3 class="text-sm font-semibold text-center">{{ $grandchild->name }}</h3>
                                                            <div class="text-xs text-gray-600 text-center">
                                                                {{ $grandchild->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                                            </div>
                                                        </div>

                                                        <!-- Grandchild's Spouse (if any) -->
                                                        @if($grandchild->spouses && $grandchild->spouses->count() > 0)
                                                            <div class="spouse-connector w-8 h-0.5 bg-gray-300 mx-2"></div>
                                                            <div class="member-card bg-white rounded-lg shadow-md p-4 mb-4 cursor-pointer" onclick="showMemberModal({{ $grandchild->spouses->first()->id }})">
                                                                <img src="{{ $grandchild->spouses->first()->photo ? $grandchild->spouses->first()->photo : "https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png" }}"
                                                                    alt="{{ $grandchild->spouses->first()->name }}"
                                                                    class="w-16 h-16 rounded-full mx-auto mb-2 object-cover">
                                                                <h3 class="text-sm font-semibold text-center">{{ $grandchild->spouses->first()->name }}</h3>
                                                                <div class="text-xs text-gray-600 text-center">
                                                                    {{ $grandchild->spouses->first()->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
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
    let currentFamilyId = {{ isset($family) ? $family->id : 'null' }};

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
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Dasar</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Nama Lengkap</p>
                                    <p class="text-base">${data.name || '-'}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-700">Jenis Kelamin</p>
                                    <p class="text-base">${data.gender === 'male' ? 'Laki-laki' : data.gender === 'female' ? 'Perempuan' : 'Lainnya'}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tanggal Lahir</p>
                                    <p class="text-base">${data.birth_date ? new Date(data.birth_date).toLocaleDateString('id-ID') : '-'}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tempat Lahir</p>
                                    <p class="text-base">${data.birth_place || '-'}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Foto</p>
                                    <div class="mt-1">
                                        <img src="${data.photo ? data.photo : 'https://res.cloudinary.com/dhct2oudf/image/upload/v1745413780/default-avatar_m0w4f4.png'}"
                                             alt="${data.name}"
                                             class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-700">Pekerjaan</p>
                                    <p class="text-base">${data.occupation || '-'}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tanggal Meninggal <span class="text-xs text-gray-500"></span></p>
                                    <p class="text-base">${data.death_date ? new Date(data.death_date).toLocaleDateString('id-ID') : '-'}</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tempat Meninggal <span class="text-xs text-gray-500"></span></p>
                                    <p class="text-base">${data.death_place || '-'}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Biografi</h2>

                        <div>
                            <p class="text-sm font-medium text-gray-700">Cerita Hidup</p>
                            <p class="text-base">${data.bio || '-'}</p>
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
        if (currentMemberId && currentFamilyId) {
            window.location.href = `/families/${currentFamilyId}/members/${currentMemberId}/edit`;
        }
    });

    // Event listener untuk tombol Hapus
    document.getElementById('deleteMemberBtn').addEventListener('click', function() {
        if (currentMemberId && currentFamilyId && confirm('Apakah Anda yakin ingin menghapus anggota keluarga ini?')) {
            // Buat form untuk mengirim request DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/families/${currentFamilyId}/members/${currentMemberId}`;

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
