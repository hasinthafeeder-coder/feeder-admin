<?php

namespace Tests\Feature\Team;

use App\Models\User;
use App\Services\Team\TeamTreeService;
use Feeder\Core\Authorization\Services\PermissionService;
use Mockery;
use Tests\TestCase;

class TeamTreeControllerTest extends TestCase
{
    public function test_guest_cannot_access_team_tree_page(): void
    {
        $response = $this->get(route('team.structure'));

        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_user_without_permission_gets_forbidden(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('team.structure'));

        $response->assertForbidden();
    }

    public function test_authorized_user_can_load_root_endpoint(): void
    {
        $this->allowTeamStructurePermission();

        $service = Mockery::mock(TeamTreeService::class);
        $service->shouldReceive('getRootTreeData')
            ->once()
            ->andReturn([
                'root' => [
                    'user_id' => 1,
                    'user_uuid' => 'root-uuid',
                    'user_label' => '#1',
                    'company_name' => 'Master Co',
                    'portal_code' => 'RESELLER',
                    'is_master_reseller' => true,
                    'total_referrals' => 4,
                    'direct_referrals' => 2,
                    'has_children' => true,
                ],
                'children' => [],
            ]);

        $this->app->instance(TeamTreeService::class, $service);

        $response = $this
            ->actingAs($this->makeUser())
            ->getJson(route('team.structure.root'));

        $response
            ->assertOk()
            ->assertJsonPath('root.user_uuid', 'root-uuid')
            ->assertJsonPath('root.total_referrals', 4);
    }

    public function test_authorized_user_can_search_team_members(): void
    {
        $this->allowTeamStructurePermission();

        $service = Mockery::mock(TeamTreeService::class);
        $service->shouldReceive('searchUsers')
            ->once()
            ->with('abc', 10)
            ->andReturn([
                [
                    'user_id' => 42,
                    'user_uuid' => 'user-42',
                    'label' => '#42 — ABC Trading',
                ],
            ]);

        $this->app->instance(TeamTreeService::class, $service);

        $response = $this
            ->actingAs($this->makeUser())
            ->getJson(route('team.structure.search', ['q' => 'abc']));

        $response
            ->assertOk()
            ->assertJsonPath('results.0.user_uuid', 'user-42');
    }

    private function makeUser(): User
    {
        $user = new User();
        $user->forceFill([
            'id' => 1001,
            'uuid' => 'auth-user-uuid',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        return $user;
    }

    private function allowTeamStructurePermission(): void
    {
        $permissionService = Mockery::mock(PermissionService::class);
        $permissionService->shouldReceive('hasPermission')
            ->andReturnUsing(function (User $user, string $permission): bool {
                return $permission === 'team.structure.view';
            });

        $this->app->instance(PermissionService::class, $permissionService);
    }
}
