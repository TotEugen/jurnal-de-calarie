<?php

namespace App\Services;

use App\Enums\AffiliationStatus;
use App\Enums\CenterApplicationStatus;
use App\Models\CenterApplication;
use App\Models\ProfessionalApplication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ApplicationReviewService
{
    public function reviewCenter(CenterApplication $application, string $decision, User $reviewer, ?string $notes = null): void
    {
        $this->validateNotes($decision, $notes);

        DB::transaction(function () use ($application, $decision, $reviewer, $notes): void {
            $fromStatus = $application->status;
            $toStatus = $this->statusFor($decision);

            $application->update([
                'status' => $toStatus,
                'reviewed_by' => $reviewer->id,
                'review_notes' => $notes,
                'reviewed_at' => $this->isFinal($toStatus) ? now() : null,
            ]);

            $application->statusHistory()->create([
                'changed_by' => $reviewer->id,
                'from_status' => $fromStatus->value,
                'to_status' => $toStatus->value,
                'notes' => $notes,
            ]);

            if ($toStatus === CenterApplicationStatus::Approved) {
                $application->center->update([
                    'affiliation_status' => AffiliationStatus::Active,
                    'affiliated_at' => now(),
                    'is_public' => true,
                    'published_at' => now(),
                ]);
            }
        });
    }

    public function reviewProfessional(ProfessionalApplication $application, string $decision, User $reviewer, ?string $notes = null): void
    {
        $this->validateNotes($decision, $notes);

        DB::transaction(function () use ($application, $decision, $reviewer, $notes): void {
            $fromStatus = $application->status;
            $toStatus = $this->statusFor($decision);

            $application->update([
                'status' => $toStatus,
                'reviewed_by' => $reviewer->id,
                'review_notes' => $notes,
                'reviewed_at' => $this->isFinal($toStatus) ? now() : null,
            ]);

            $application->statusHistory()->create([
                'changed_by' => $reviewer->id,
                'from_status' => $fromStatus->value,
                'to_status' => $toStatus->value,
                'notes' => $notes,
            ]);

            if ($toStatus === CenterApplicationStatus::Approved) {
                $application->professional->update([
                    'federation_status' => 'active',
                    'qualification_grade' => $application->qualification_grade,
                    'qualification_identifier' => $application->qualification_identifier,
                    'passport_number' => $application->passport_number,
                    'qualification_obtained_at' => $application->qualification_obtained_at,
                    'issuing_authority' => $application->issuing_authority,
                    'is_evaluator' => $application->requests_evaluator_authorization,
                    'authorized_at' => now(),
                    'evaluator_authorized_at' => $application->requests_evaluator_authorization ? now() : null,
                ]);

                $monitorRole = Role::query()->firstOrCreate(['code' => 'monitor'], ['name' => 'Monitor']);
                $application->professional->user->roles()->syncWithoutDetaching($monitorRole);
            }
        });
    }

    private function statusFor(string $decision): CenterApplicationStatus
    {
        return match ($decision) {
            'review' => CenterApplicationStatus::UnderReview,
            'changes' => CenterApplicationStatus::ChangesRequested,
            'approve' => CenterApplicationStatus::Approved,
            'reject' => CenterApplicationStatus::Rejected,
            default => throw new InvalidArgumentException('Decizie necunoscută.'),
        };
    }

    private function validateNotes(string $decision, ?string $notes): void
    {
        if (in_array($decision, ['changes', 'reject'], true) && blank($notes)) {
            throw new InvalidArgumentException('Observațiile sunt obligatorii pentru această decizie.');
        }
    }

    private function isFinal(CenterApplicationStatus $status): bool
    {
        return in_array($status, [CenterApplicationStatus::Approved, CenterApplicationStatus::Rejected], true);
    }
}
