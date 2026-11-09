<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LanguageItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class LanguageItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LanguageItem');
    }

    public function view(AuthUser $authUser, LanguageItem $languageItem): bool
    {
        return $authUser->can('View:LanguageItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LanguageItem');
    }

    public function update(AuthUser $authUser, LanguageItem $languageItem): bool
    {
        return $authUser->can('Update:LanguageItem');
    }

    public function delete(AuthUser $authUser, LanguageItem $languageItem): bool
    {
        return $authUser->can('Delete:LanguageItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LanguageItem');
    }

    public function restore(AuthUser $authUser, LanguageItem $languageItem): bool
    {
        return $authUser->can('Restore:LanguageItem');
    }

    public function forceDelete(AuthUser $authUser, LanguageItem $languageItem): bool
    {
        return $authUser->can('ForceDelete:LanguageItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LanguageItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LanguageItem');
    }

    public function replicate(AuthUser $authUser, LanguageItem $languageItem): bool
    {
        return $authUser->can('Replicate:LanguageItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LanguageItem');
    }

}