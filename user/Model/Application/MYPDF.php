<?php

namespace App\User\Model\Application;

use TCPDF_FONTS;

class MYPDF extends \TCPDF
{
    protected $footerHTML;

    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-40);
        // Set font
        $fontnameBold = TCPDF_FONTS::addTTFfont(APP_PATH .'/public/assets/fonts/CalibriBold.TTF', 'TrueTypeUnicode', '', 96);
        $this->SetFont($fontnameBold, '', 14, '', false);
        // Page number
        $this->writeHTMLCell(0, 0, '', '', $this->footerHTML, 0, 1, 0, true, '', true);
    }

    public function setFooterHTML($html)
    {
        $this->footerHTML = $html;
    }

}