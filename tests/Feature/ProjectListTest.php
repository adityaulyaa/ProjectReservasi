<?php

namespace Tests\Feature;

use App\Models\ProjectList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectListTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_list_and_becomes_owner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('lists.store'), [
            'name' => 'Project Alpha',
            'description' => 'Test project description',
        ]);

        $list = ProjectList::where('name', 'Project Alpha')->first();

        $this->assertNotNull($list);
        $this->assertEquals($user->id, $list->owner_id);
        $response->assertRedirect(route('lists.show', $list));
    }

    public function test_user_can_view_owned_and_collaboration_lists(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $ownedList = ProjectList::create([
            'owner_id' => $owner->id,
            'name' => 'Owner List',
            'description' => 'Desc',
        ]);

        $collabList = ProjectList::create([
            'owner_id' => $member->id,
            'name' => 'Collab List',
            'description' => 'Desc',
        ]);
        $collabList->members()->attach($owner->id);

        $response = $this->actingAs($owner)->get(route('lists.index'));

        $response->assertStatus(200);
        $response->assertSee('Owner List');
        $response->assertSee('Collab List');
    }

    public function test_only_owner_can_update_list(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $list = ProjectList::create([
            'owner_id' => $owner->id,
            'name' => 'Original Name',
            'description' => 'Original Desc',
        ]);

        // Non-owner attempts update -> 403
        $this->actingAs($otherUser)
            ->put(route('lists.update', $list), ['name' => 'Hacked Name'])
            ->assertStatus(403);

        // Owner updates successfully
        $this->actingAs($owner)
            ->put(route('lists.update', $list), ['name' => 'Updated Name'])
            ->assertRedirect(route('lists.show', $list));

        $this->assertDatabaseHas('lists', ['id' => $list->id, 'name' => 'Updated Name']);
    }

    public function test_only_owner_can_delete_list(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $list = ProjectList::create([
            'owner_id' => $owner->id,
            'name' => 'To Delete',
        ]);

        // Non-owner attempts delete -> 403
        $this->actingAs($otherUser)
            ->delete(route('lists.destroy', $list))
            ->assertStatus(403);

        // Owner deletes successfully
        $this->actingAs($owner)
            ->delete(route('lists.destroy', $list))
            ->assertRedirect(route('lists.index'));

        $this->assertDatabaseMissing('lists', ['id' => $list->id]);
    }

    public function test_owner_can_add_member_by_email_or_username(): void
    {
        $owner = User::factory()->create();
        $targetMember = User::factory()->create([
            'name' => 'johndoe',
            'email' => 'john@example.com',
        ]);

        $list = ProjectList::create([
            'owner_id' => $owner->id,
            'name' => 'Team List',
        ]);

        $response = $this->actingAs($owner)->post(route('lists.members.add', $list), [
            'identifier' => 'john@example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue($list->members()->where('user_id', $targetMember->id)->exists());
    }

    public function test_owner_can_remove_member_from_list(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $list = ProjectList::create([
            'owner_id' => $owner->id,
            'name' => 'Team List',
        ]);
        $list->members()->attach($member->id);

        $response = $this->actingAs($owner)->delete(route('lists.members.remove', [$list, $member]));

        $response->assertSessionHas('success');
        $this->assertFalse($list->members()->where('user_id', $member->id)->exists());
    }
}
