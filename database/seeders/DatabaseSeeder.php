<?php

namespace Database\Seeders;

use App\Enums\CertificateBatchStatus;
use App\Enums\CertificateStatus;
use App\Enums\DeliveryAttemptStatus;
use App\Enums\DeliveryStatus;
use App\Models\Certificate;
use App\Models\CertificateBatch;
use App\Models\CertificateDelivery;
use App\Models\CertificateDeliveryAttempt;
use App\Models\CertificateFile;
use App\Models\CertificateNumberCounter;
use App\Models\Import;
use App\Models\Participant;
use App\Models\Template;
use App\Models\TemplateField;
use App\Models\TemplateVersion;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $hr = User::factory()->create([
            'name' => 'HR Admin',
            'email' => 'hr@example.com',
        ]);

        // --- Template + version + fields ---
        $template = Template::factory()->for($hr, 'createdBy')->create([
            'name' => 'Seminar Completion Certificate',
        ]);

        $version = TemplateVersion::factory()->for($template)->create();

        foreach (['name' => 'Participant Name', 'event_date' => 'Event Date', 'certificate_number' => 'Certificate Number'] as $key => $label) {
            TemplateField::factory()->for($version, 'templateVersion')->create([
                'field_key' => $key,
                'label' => $label,
            ]);
        }

        // --- Certificate number counter, current year ---
        $counter = CertificateNumberCounter::create(['year' => now()->year, 'last_number' => 0]);

        // --- Two imports, each with real participants ---
        foreach (range(1, 2) as $i) {
            $import = Import::factory()->for($hr, 'importedBy')->create([
                'original_filename' => "seminar-batch-{$i}.xlsx",
            ]);

            $participants = Participant::factory()
                ->count(25)
                ->for($import)
                ->create();

            $import->update([
                'total_rows' => $participants->count(),
                'valid_rows' => $participants->count(),
            ]);

            // --- One certificate batch per import, against the active template version ---
            $batch = CertificateBatch::factory()
                ->for($import)
                ->for($version, 'templateVersion')
                ->for($hr, 'createdBy')
                ->create([
                    'status' => CertificateBatchStatus::Completed,
                    'total_count' => $participants->count(),
                ]);

            $generated = 0;
            $failed = 0;

            foreach ($participants as $index => $participant) {
                // Small deliberate failure rate so status-filtered queries have real variety to hit.
                $isFailed = $index === 0 && $i === 1;

                $counter->increment('last_number');
                $number = sprintf('CERT-%d-%06d', $counter->year, $counter->last_number);

                $certificate = Certificate::factory()
                    ->for($batch, 'certificateBatch')
                    ->for($participant)
                    ->for($version, 'templateVersion')
                    ->create([
                        'certificate_number' => $number,
                        'status' => $isFailed ? CertificateStatus::Failed : CertificateStatus::Generated,
                        'error_message' => $isFailed ? 'PDF rendering timed out.' : null,
                        'generated_at' => $isFailed ? null : now(),
                        'rendered_data' => $isFailed ? null : [
                            'name' => $participant->name,
                            'event_date' => optional($participant->event_date)->toDateString(),
                            'certificate_number' => $number,
                        ],
                    ]);

                $isFailed ? $failed++ : $generated++;

                if ($isFailed) {
                    continue;
                }

                CertificateFile::factory()->for($certificate)->create();

                // Deliberate delivery-status variety: mostly sent, one pending, one bounced.
                $deliveryStatus = match (true) {
                    $index === 1 && $i === 1 => DeliveryStatus::Pending,
                    $index === 2 && $i === 1 => DeliveryStatus::Bounced,
                    default => DeliveryStatus::Sent,
                };

                $delivery = CertificateDelivery::factory()->for($certificate)->create([
                    'recipient_email' => $participant->email,
                    'status' => $deliveryStatus,
                    'sent_at' => $deliveryStatus === DeliveryStatus::Sent ? now() : null,
                    'retry_count' => $deliveryStatus === DeliveryStatus::Bounced ? 2 : 0,
                    'failure_reason' => $deliveryStatus === DeliveryStatus::Bounced ? 'Mailbox does not exist.' : null,
                ]);

                if ($deliveryStatus !== DeliveryStatus::Pending) {
                    CertificateDeliveryAttempt::factory()->for($delivery, 'delivery')->create([
                        'status' => $deliveryStatus === DeliveryStatus::Sent
                            ? DeliveryAttemptStatus::Sent
                            : DeliveryAttemptStatus::Failed,
                    ]);
                }
            }

            $batch->update(['generated_count' => $generated, 'failed_count' => $failed]);
        }
    }
}