<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'birth_date',
        'birth_place',
        'death_date',
        'death_place',
        'occupation',
        'bio',
        'gender',
        'family_id',
        'parent_id', // Self-reference for parent relationship
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    // Tambahkan event deleting untuk menghapus foto saat model dihapus
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($member) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
        });
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function parent()
    {
        return $this->belongsTo(Member::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'parent_id');
    }

    public function spouses()
    {
        return $this->belongsToMany(Member::class, 'member_relationships', 'member_id', 'related_member_id')
            ->withPivot('relationship_type')
            ->wherePivot('relationship_type', 'spouse');
    }
}

