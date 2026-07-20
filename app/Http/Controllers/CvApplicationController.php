<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCvApplicationRequest;
use App\Models\CvApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CvApplicationController extends Controller
{
    public function create(): View
    {
        $nrcData = [];
        $path = base_path('nrcdb.json');

        if (File::exists($path)) {
            $decoded = json_decode(File::get($path), true);
            $nrcData = is_array($decoded) ? $decoded : [];
        }

        return view('cv.create', [
            'nrcStates' => array_keys($nrcData),
            'nrcTownships' => $nrcData,
        ]);
    }

    public function store(StoreCvApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $application = DB::transaction(function () use ($data, $request) {
            $application = new CvApplication();
            $application->reference                  = $this->generateReference();
            $application->name                       = $data['name'];
            $application->position_applied           = $data['position_applied'];
            $application->date_of_birth              = $data['date_of_birth'];
            $application->education_level            = $data['education_level'];
            $application->current_employer           = $data['current_employer'] ?? null;
            $application->current_job_title          = $data['current_job_title'] ?? null;
            $application->expected_salary            = $data['expected_salary'] ?? null;
            $application->nrc                        = $data['nrc'];
            $application->address                    = $data['address'];
            $application->email                      = $data['email'];
            $application->phone                      = $data['phone'];
            $application->work_experience            = $data['work_experience'] ?? null;
            $application->skills                     = $data['skills'] ?? null;
            $application->languages                  = $data['languages'] ?? null;
            $application->education                  = $data['education'] ?? null;
            $application->start_date                 = $data['start_date'] ?? null;
            $application->emergency_contact_name     = $data['emergency_contact_name'];
            $application->emergency_contact_relationship = $data['emergency_contact_relationship'];
            $application->emergency_contact_phone    = $data['emergency_contact_phone'];
            $application->portfolio_url              = $data['portfolio_url'] ?? null;
            $application->references                 = $data['references'] ?? null;
            $application->why_join_wta               = $data['why_join_wta'];
            $application->status                     = CvApplication::STATUS_PENDING;

            if ($request->hasFile('photo')) {
                $application->photo_path = $request->file('photo')
                    ->store('cvs/photo', 'public');
            }

            if ($request->hasFile('nrc_file')) {
                $application->nrc_file_path = $request->file('nrc_file')
                    ->store('cvs/nrc', 'public');
            }

            $application->save();

            return $application;
        });

        return redirect()
            ->route('cv.thank-you', ['reference' => $application->reference])
            ->with('applicant_name', $application->name);
    }

    public function thankYou(string $reference): View
    {
        $application = CvApplication::where('reference', $reference)->firstOrFail();

        return view('cv.thank-you', [
            'application' => $application,
        ]);
    }

    protected function generateReference(): string
    {
        $year = now()->format('Y');
        $count = CvApplication::whereYear('created_at', $year)->count() + 1;

        do {
            $reference = sprintf('WTA-%s-%04d', $year, $count);
            $count++;
        } while (CvApplication::where('reference', $reference)->exists());

        return $reference;
    }
}
