<?php

namespace App\Repository\SharpSpring;

use Illuminate\Support\Facades\Storage;

interface SharpSpringRepositoryInterface
{
    public function getCredentials(): array;
    public function store(string $data);
    public function getListMemberships(string $email): array;
    public function getListMembers(int $id): array;
    public function addListMember(int $leadId, int $listID): bool;
    public function addListMemberEmailAddress(string $emailAddress, int $listID): bool;
    public function getLead(?int $id = null, ?string $email = null): array;
    public function getActiveList(int $id): mixed;
    public function getAllMembersOfList(int $listId): mixed;
    public function getFields(): mixed;
    public function getLeads(array $listOfIds = []): array;
}
