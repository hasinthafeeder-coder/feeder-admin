<?php

namespace Tests\Support;

use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\Role;
use Feeder\Core\Services\UuidService;
use Illuminate\Support\Str;

trait SetsUpPortalRoles
{
    protected function ensurePortal(PortalCode $portalCode, string $name): Portal
    {
        return Portal::query()->firstOrCreate(
            ['code' => $portalCode->value],
            [
                'uuid' => UuidService::generate(),
                'name' => $name,
                'subdomain' => Str::lower($portalCode->value).'-'.Str::lower(Str::random(4)),
                'description' => $name,
                'is_active' => true,
            ]
        );
    }

    protected function ensureOwnerRole(PortalCode $portalCode): Role
    {
        $portal = $this->ensurePortal(
            $portalCode,
            match ($portalCode) {
                PortalCode::SUPPLIER => 'Supplier Portal',
                PortalCode::RESELLER => 'Reseller Portal',
                PortalCode::ADMIN => 'Admin Portal',
            }
        );

        return Role::query()->firstOrCreate(
            [
                'portal_id' => $portal->id,
                'slug' => 'owner',
            ],
            [
                'uuid' => UuidService::generate(),
                'company_id' => null,
                'name' => 'Owner',
                'description' => 'Company owner.',
                'is_system' => true,
            ]
        );
    }
}
