<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VcardController extends Controller
{
    public function __invoke(Request $request)
    {
        $format_name = utf8_encode($_GET['name']);
        $format_email = utf8_encode($_GET['email']);
        $format_tel = utf8_encode($_GET['tel']);
        $format_fax = utf8_encode('');
        $format_www = utf8_encode('');
        $format_address = utf8_encode('');

        $content = "BEGIN:VCARD\r\n";
        $content .= "VERSION:3.0\r\n";
        $content .= "CLASS:PUBLIC\r\n";
        $content .= "FN:".utf8_encode($_GET['name'])."\r\n";
        $content .= "N:;".utf8_encode($_GET['name'])." ;;;\r\n";
        $content .= "TITLE:".iconv('UTF-8', 'ISO-8859-1', $_GET['cargo'])."\r\n";
        $content .= "ORG:".utf8_encode($_GET['empresa'])."\r\n";
        // $content .= "ADR;TYPE=work:;;21 W. 20th St.;Broadview ;IL;60559;\r\n";
        $content .= "EMAIL;TYPE=internet,pref:".utf8_encode($_GET['email'])."\r\n";
        $content .= "TEL;TYPE=work,voice:".utf8_encode($_GET['tel'])."\r\n";
        $content .= "TEL;TYPE=HOME,voice:".utf8_encode($_GET['tel'])."\r\n";
        // $content .= "URL:http://www.wegnerdesign.com\r\n";
        $content .= "END:VCARD\r\n";

        // $content = 'BEGIN%3AVCARD%0D%0AVERSION%3A4.0%0D%0AN%3A%3B'.$format_name.'%3B%3B%3B%0D%0AFN%3A'.$format_name.'%0D%0AEMAIL%3A'.$format_email.'%0D%0AORG%3A'.$format_name.'%0D%0ATEL%3A'.$format_tel.'%0D%0ATEL%3Btype%3DFAX%3A'.$format_fax.'%0D%0AURL%3Btype%3Dpref%3A'.$format_www.'%0D%0AADR%3A%3B'.$format_address.'%3B%3B%3B%3B%3BSpain%0D%0AEND%3AVCARD';

        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $format_name . '.vcf"');
        header('Content-Length: '.strlen($content)); 

        echo $content;
    }
}
