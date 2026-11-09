<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SceneModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class SceneModelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SceneModel');
    }

    public function view(AuthUser $authUser, SceneModel $sceneModel): bool
    {
        return $authUser->can('View:SceneModel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SceneModel');
    }

    public function update(AuthUser $authUser, SceneModel $sceneModel): bool
    {
        return $authUser->can('Update:SceneModel');
    }

    public function delete(AuthUser $authUser, SceneModel $sceneModel): bool
    {
        return $authUser->can('Delete:SceneModel');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SceneModel');
    }

    public function restore(AuthUser $authUser, SceneModel $sceneModel): bool
    {
        return $authUser->can('Restore:SceneModel');
    }

    public function forceDelete(AuthUser $authUser, SceneModel $sceneModel): bool
    {
        return $authUser->can('ForceDelete:SceneModel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SceneModel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SceneModel');
    }

    public function replicate(AuthUser $authUser, SceneModel $sceneModel): bool
    {
        return $authUser->can('Replicate:SceneModel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SceneModel');
    }

}