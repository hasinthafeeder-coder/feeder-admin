<?php

namespace App\Services\Team;

use Feeder\Core\Models\User;
use Feeder\Core\Services\Referral\ReferralService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamTreeService
{
    public function __construct(
        private readonly ReferralService $referralService,
    ) {}

    public function getRootTreeData(int $childrenLimit = 60): array
    {
        $rootUser = $this->referralService->getTreeRootUser();

        if ($rootUser === null) {
            return [
                'root' => null,
                'children' => [],
            ];
        }

        $rootNode = $this->referralService->getNodeData($rootUser);

        return [
            'root' => $rootNode,
            'children' => $this->referralService->getChildrenNodeData($rootUser, $childrenLimit),
        ];
    }

    public function getChildren(User $user, int $limit = 60): array
    {
        return $this->referralService->getChildrenNodeData($user, $limit);
    }

    public function searchUsers(string $query, int $limit = 10): array
    {
        return $this->referralService->searchTreeUsers($query, $limit);
    }

    public function getPathToUser(User $targetUser): array
    {
        $rootUser = $this->referralService->getTreeRootUser();

        if ($rootUser === null) {
            throw new NotFoundHttpException('The team hierarchy root user could not be found.');
        }

        $path = $this->referralService->getPathNodeData($targetUser, $rootUser);

        if ($path === []) {
            throw new NotFoundHttpException('No referral path exists from the root user to the selected user.');
        }

        return $path;
    }
}
