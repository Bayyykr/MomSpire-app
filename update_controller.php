<?php
$file = 'app/Http/Controllers/DataKiaController.php';
$content = file_get_contents($file);

$search = <<<EOD
        \$pdf = new \setasign\Fpdi\Fpdi();
        \$pdf->setSourceFile(\$convertedPath);

        // Import halaman 2 (Identitas)
        \$templateId = \$pdf->importPage(2);
        \$size = \$pdf->getTemplateSize(\$templateId);
        \$pdf->AddPage(\$size['orientation'], [\$size['width'], \$size['height']]);
        \$pdf->useTemplate(\$templateId);

        // Set font
        \$pdf->SetFont('Arial', '', 10);
        \$pdf->SetTextColor(0, 0, 0);

        // Mapping data ke koordinat Halaman 2
        \$pdf->SetXY(55, 68);  \$pdf->Write(0, \$dataKia->nama_ibu   ?? '-');
        \$pdf->SetXY(55, 75);  \$pdf->Write(0, \$dataKia->nik        ?? '-');
        \$pdf->SetXY(145, 68); \$pdf->Write(0, \$dataKia->nama_suami ?? '-');
        \$pdf->SetXY(145, 75); \$pdf->Write(0, \$dataKia->nik_suami  ?? '-');
        \$pdf->SetXY(55, 125); \$pdf->MultiCell(90, 5, \$dataKia->alamat ?? '-', 0, 'L');
EOD;

$replace = <<<EOD
        \$pdf = new \setasign\Fpdi\Fpdi();
        \$pageCount = \$pdf->setSourceFile(\$convertedPath);

        for (\$pageNo = 1; \$pageNo <= \$pageCount; \$pageNo++) {
            \$templateId = \$pdf->importPage(\$pageNo);
            \$size = \$pdf->getTemplateSize(\$templateId);
            \$pdf->AddPage(\$size['orientation'], [\$size['width'], \$size['height']]);
            \$pdf->useTemplate(\$templateId);

            if (\$pageNo === 2) {
                \$pdf->SetFont('Arial', '', 10);
                \$pdf->SetTextColor(0, 0, 0);

                // Saya turunkan Y sedikit ke 70 dan 77 sebagai percobaan
                \$pdf->SetXY(55, 70);  \$pdf->Write(0, \$dataKia->nama_ibu   ?? '-');
                \$pdf->SetXY(55, 77);  \$pdf->Write(0, \$dataKia->nik        ?? '-');
                \$pdf->SetXY(145, 70); \$pdf->Write(0, \$dataKia->nama_suami ?? '-');
                \$pdf->SetXY(145, 77); \$pdf->Write(0, \$dataKia->nik_suami  ?? '-');
                \$pdf->SetXY(55, 127); \$pdf->MultiCell(90, 5, \$dataKia->alamat ?? '-', 0, 'L');
            }
        }
EOD;

$search = str_replace("\r\n", "\n", $search);
$replace = str_replace("\r\n", "\n", $replace);
$content = str_replace("\r\n", "\n", $content);

$content = str_replace($search, $replace, $content);
file_put_contents($file, $content);
echo "Done";
