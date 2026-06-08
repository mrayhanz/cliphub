<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isBrand() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Submission $submission): bool
    {
        // Brand can only view submissions for their own campaigns
        if ($user->isBrand()) {
            return $submission->campaign->user_id === $user->id;
        }

        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Kreator can view their own submissions
        if ($user->isKreator()) {
            return $submission->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isKreator();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Submission $submission): bool
    {
        // Brand can only update submissions for their own campaigns
        if ($user->isBrand()) {
            return $submission->campaign->user_id === $user->id;
        }

        // Admin can update all
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Submission $submission): bool
    {
        // Only admin can delete
        if ($user->isAdmin()) {
            return true;
        }

        // Kreator can delete their own pending submissions
        if ($user->isKreator() && $submission->user_id === $user->id) {
            return $submission->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Submission $submission): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Submission $submission): bool
    {
        return $user->isAdmin();
    }
}
