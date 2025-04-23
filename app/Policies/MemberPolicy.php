<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the member.
     */
    public function view(User $user, Member $member)
    {
        // Pengguna dapat melihat anggota jika mereka memiliki akses ke keluarga
        return $user->id === $member->family->user_id;
    }

    /**
     * Determine whether the user can create members.
     */
    public function create(User $user)
    {
        // Semua pengguna yang terautentikasi dapat membuat anggota
        return true;
    }

    /**
     * Determine whether the user can update the member.
     */
    public function update(User $user, Member $member)
    {
        // Pengguna dapat memperbarui anggota jika mereka memiliki akses ke keluarga
        return $user->id === $member->family->user_id;
    }

    /**
     * Determine whether the user can delete the member.
     */
    public function delete(User $user, Member $member)
    {
        // Pengguna dapat menghapus anggota jika mereka memiliki akses ke keluarga
        return $user->id === $member->family->user_id;
    }
}
