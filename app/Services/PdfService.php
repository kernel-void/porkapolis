<?php

namespace App\Services;

use Illuminate\Http\Response;
use Mpdf\Mpdf;

class PdfService
{
    public function inline(string $view, array $data, string $filename): Response
    {
        $html = view($view, $data)->render();

        $mpdf = new Mpdf([
            'mode'        => 'utf-8',
            'format'      => 'A4',
            'orientation' => 'P',
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
