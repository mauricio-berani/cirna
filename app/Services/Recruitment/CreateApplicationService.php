<?php

namespace App\Services\Recruitment;

use App\Contracts\Recruitment\CreatesApplications;
use App\Mail\ApplicationReceivedMail;
use App\Models\Common\Setting;
use App\Models\Recruitment\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CreateApplicationService implements CreatesApplications
{
    /**
     * @param  array{name:string,email:string,phone:?string,area:?string,resume_path:string}  $payload
     */
    public function handle(array $payload): Application
    {
        $application = DB::transaction(function () use ($payload) {
            $application = new Application;

            $application->forceFill([
                Application::FIELD_NAME => $payload['name'],
                Application::FIELD_EMAIL => $payload['email'],
                Application::FIELD_PHONE => $payload['phone'] ?? null,
                Application::FIELD_AREA => $payload['area'] ?? null,
                Application::FIELD_RESUME_PATH => $payload['resume_path'],
            ])->save();

            return $application;
        });

        Mail::to(Setting::careersEmail())
            ->send(new ApplicationReceivedMail($application));

        return $application;
    }
}
