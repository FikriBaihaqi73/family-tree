<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Family;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create a family for the test user
        $family = Family::factory()->create([
            'name' => 'Smith Family',
            'description' => 'Family tree of the Smith family',
            'user_id' => $user->id,
        ]);

        // Create grandparents (first generation)
        $grandfather = Member::factory()->create([
            'name' => 'Peter Smith',
            'gender' => 'male',
            'birth_date' => '1940-05-15',
            'birth_place' => 'Boston',
            'occupation' => 'Engineer',
            'family_id' => $family->id,
        ]);

        $grandmother = Member::factory()->create([
            'name' => 'Jessica Smith',
            'gender' => 'female',
            'birth_date' => '1942-11-28',
            'birth_place' => 'Chicago',
            'occupation' => 'Teacher',
            'family_id' => $family->id,
        ]);

        // Create parents (second generation)
        $father = Member::factory()->create([
            'name' => 'David Smith',
            'gender' => 'male',
            'birth_date' => '1968-07-23',
            'birth_place' => 'New York',
            'occupation' => 'Doctor',
            'family_id' => $family->id,
            'parent_id' => $grandfather->id,
        ]);

        $mother = Member::factory()->create([
            'name' => 'Sheila Smith',
            'gender' => 'female',
            'birth_date' => '1970-03-12',
            'birth_place' => 'Philadelphia',
            'occupation' => 'Lawyer',
            'family_id' => $family->id,
            'parent_id' => $grandfather->id,
        ]);

        $uncle = Member::factory()->create([
            'name' => 'Ron Smith',
            'gender' => 'male',
            'birth_date' => '1965-09-08',
            'birth_place' => 'Boston',
            'occupation' => 'Businessman',
            'family_id' => $family->id,
            'parent_id' => $grandfather->id,
        ]);

        $aunt1 = Member::factory()->create([
            'name' => 'Eva Johnson',
            'gender' => 'female',
            'birth_date' => '1972-01-18',
            'birth_place' => 'Los Angeles',
            'occupation' => 'Artist',
            'family_id' => $family->id,
            'parent_id' => $grandfather->id,
        ]);

        $uncle2 = Member::factory()->create([
            'name' => 'Robert Johnson',
            'gender' => 'male',
            'birth_date' => '1970-05-30',
            'birth_place' => 'San Francisco',
            'occupation' => 'Architect',
            'family_id' => $family->id,
            'parent_id' => $grandfather->id,
        ]);

        // Create children (third generation)
        $child1 = Member::factory()->create([
            'name' => 'Steven Smith',
            'gender' => 'male',
            'birth_date' => '1995-12-10',
            'birth_place' => 'Chicago',
            'occupation' => 'Software Developer',
            'family_id' => $family->id,
            'parent_id' => $father->id,
        ]);

        $child2 = Member::factory()->create([
            'name' => 'Sandra Smith',
            'gender' => 'female',
            'birth_date' => '1997-08-20',
            'birth_place' => 'New York',
            'occupation' => 'Medical Student',
            'family_id' => $family->id,
            'parent_id' => $father->id,
        ]);

        $cousin1 = Member::factory()->create([
            'name' => 'Donald Wilson',
            'gender' => 'male',
            'birth_date' => '1992-02-15',
            'birth_place' => 'Miami',
            'occupation' => 'Chef',
            'family_id' => $family->id,
            'parent_id' => $uncle->id,
        ]);

        $cousin2 = Member::factory()->create([
            'name' => 'Cynthia Wilson',
            'gender' => 'female',
            'birth_date' => '1994-04-25',
            'birth_place' => 'Dallas',
            'occupation' => 'Journalist',
            'family_id' => $family->id,
            'parent_id' => $uncle->id,
        ]);

        $cousin3 = Member::factory()->create([
            'name' => 'Francisco Johnson',
            'gender' => 'male',
            'birth_date' => '1998-07-14',
            'birth_place' => 'Portland',
            'occupation' => 'Student',
            'family_id' => $family->id,
            'parent_id' => $uncle2->id,
        ]);

        $cousin4 = Member::factory()->create([
            'name' => 'Amanda Johnson',
            'gender' => 'female',
            'birth_date' => '2000-11-03',
            'birth_place' => 'Seattle',
            'occupation' => 'Student',
            'family_id' => $family->id,
            'parent_id' => $uncle2->id,
        ]);

        // Create grandchildren (fourth generation)
        $grandchild1 = Member::factory()->create([
            'name' => 'Mark Wilson',
            'gender' => 'male',
            'birth_date' => '2018-05-12',
            'birth_place' => 'Boston',
            'family_id' => $family->id,
            'parent_id' => $cousin1->id,
        ]);

        $grandchild2 = Member::factory()->create([
            'name' => 'Lula Wilson',
            'gender' => 'female',
            'birth_date' => '2020-07-30',
            'birth_place' => 'Boston',
            'family_id' => $family->id,
            'parent_id' => $cousin1->id,
        ]);

        $grandchild3 = Member::factory()->create([
            'name' => 'Perdo Smith',
            'gender' => 'male',
            'birth_date' => '2021-03-18',
            'birth_place' => 'New York',
            'family_id' => $family->id,
            'parent_id' => $child1->id,
        ]);

        $grandchild4 = Member::factory()->create([
            'name' => 'Zula Johnson',
            'gender' => 'female',
            'birth_date' => '2019-12-05',
            'birth_place' => 'Portland',
            'family_id' => $family->id,
            'parent_id' => $cousin3->id,
        ]);

        $grandchild5 = Member::factory()->create([
            'name' => 'Cleveland Johnson',
            'gender' => 'male',
            'birth_date' => '2022-01-22',
            'birth_place' => 'Seattle',
            'family_id' => $family->id,
            'parent_id' => $cousin3->id,
        ]);

        // Create spouse relationships
        DB::table('member_relationships')->insert([
            ['member_id' => $grandfather->id, 'related_member_id' => $grandmother->id, 'relationship_type' => 'spouse', 'created_at' => now(), 'updated_at' => now()],
            ['member_id' => $grandmother->id, 'related_member_id' => $grandfather->id, 'relationship_type' => 'spouse', 'created_at' => now(), 'updated_at' => now()],
            ['member_id' => $father->id, 'related_member_id' => $mother->id, 'relationship_type' => 'spouse', 'created_at' => now(), 'updated_at' => now()],
            ['member_id' => $mother->id, 'related_member_id' => $father->id, 'relationship_type' => 'spouse', 'created_at' => now(), 'updated_at' => now()],
            ['member_id' => $uncle2->id, 'related_member_id' => $aunt1->id, 'relationship_type' => 'spouse', 'created_at' => now(), 'updated_at' => now()],
            ['member_id' => $aunt1->id, 'related_member_id' => $uncle2->id, 'relationship_type' => 'spouse', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
