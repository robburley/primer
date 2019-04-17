<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Leads\FileUpload;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvalidLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FileUpload $fileUpload
     * @return StreamedResponse
     */
    public function show(FileUpload $fileUpload)
    {
        set_time_limit(0);

        $response = new StreamedResponse(function () use ($fileUpload) {
            $handle = fopen('php://output', 'w');

            $headers = $fileUpload->headings->pluck('name')->push('Errors')->toArray();

            fputcsv($handle, $headers);

            $fileUpload->invalidLeads()
                       ->chunk(500, function ($leads) use ($handle) {
                           foreach ($leads as $lead) {
                               $data = collect(json_decode($lead->data))->push($lead->validation_errors)->toArray();

                               fputcsv($handle, $data);
                           }
                       });

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ]);

        return $response;
    }
}
