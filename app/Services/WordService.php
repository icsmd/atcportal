<?php

namespace App\Services;

use App\Models\Application;
use PhpOffice\PhpWord\PhpWord;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Class WordService.
 */
class WordService extends BaseService
{
    /**
     * WordService constructor.
     *
     * @param  PhpWord  $word
     */
    public function __construct(PhpWord $word)
    {
        $this->model = $word;
    }

    /**
     * @param  Application  $application
     * @return string
     */
    public function generateResolution(Application $application)
    {
        $filename = 'resolution-'.$application->control_number.'.docx';
        $path = base_path('storage/app/'.$filename);

        $qrLink = config('atc.access.qr_url').$application->control_number;
        if ($application->is_extension) {
            $docPath = base_path(config('atc.access.resolution_10_days'));
        } else {
            $docPath = base_path(config('atc.access.resolution_14_days'));
        }

        $oldApplication = null;
        if ($application->is_extension) {
            $oldApplication = Application::where('control_number', $application->control_number)
                                        ->where('is_extension', false)->first();
        }

        $document = $this->model->loadTemplate($docPath);

        $document->setValue('current_year', now()->format('Y'));
        $document->setValue('arrested_person', $application->arrested_fullname);
        $document->setValue('name', $application->name);
        $document->setValue('unit', $application->unit);
        $document->setValue('application_date', $application->created_at->format('F d, Y'));
        $document->setValue('arrested_date_time', $application->when->format('F d, Y h:i A'));
        $document->setValue('date_final_resolution', $oldApplication ? $oldApplication->final_resolution->format('F d, Y h:i A') : '');
        $document->setValue('where', $application->where);
        $document->setValue('what', $application->what);
        $document->setValue('brief_narrative', strip_tags($application->reason_narration));
        $document->setValue('date_evaluated', $application->posted_date);
        $document->setValue('office_address', $application->office_address);
        $document->setImageValue('qr', [
            'path' => $this->uploadQR($application, QrCode::format('png')->generate($qrLink)),
            'width' => 50,
            'height' => 50,
        ]);

        $document->saveAs($path);

        $urlPath = $this->uploadResolution($application, $path, 'resolution_draft');

        return $urlPath;
    }

    /**
     * @param  Application  $application
     * @param  QrCode  $qr
     * @return string
     */
    protected function uploadQR(Application $application, $qr)
    {
        $application->addMediaFromStream($qr)
                    ->usingFileName('qr.png')
                    ->toMediaCollection('qr');

        return $application->getFileUrl('qr');
    }

    /**
     * @param  Application  $application
     * @param  mixed  $content
     * @param  string  $collection
     * @return string
     */
    protected function uploadResolution(Application $application, $content, $collection)
    {
        return $this->upload($application, $content, $collection);
    }

    /**
     * @param  Application  $application
     * @param  mixed  $qr
     * @param  string  $collection
     * @return string
     */
    protected function upload(Application $application, $content, $collection)
    {
        $application->addMedia($content)
            ->toMediaCollection($collection);

        return $application->getFilePath($collection);
    }
}
