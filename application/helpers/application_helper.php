<?php

if (!function_exists('indo_date'))
{
    function indoDate($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = '')
    {
        if (trim($timestamp) == '') {
            $timestamp = time();
        } elseif (!ctype_digit($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace("/S/", "", $date_format);
        $pattern = array(
            '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
            '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
            '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
            '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
            '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
            '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
            '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
            '/November/', '/December/',
        );
        $replace = array(
            'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
            'Jan ', 'Feb ', 'Mar ', 'Apr ', 'Mei ', 'Jun ', 'Jul ', 'Ags ', 'Sep ', 'Okt', 'Nov ', 'Des ',
            'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember',
        );
        $date = date($date_format, $timestamp);
        $date = preg_replace($pattern, $replace, $date);
        $date = "{$date} {$suffix}";
        return $date;
    }
}

if (!function_exists('terbilang')) 
{
    function terbilang($number, $suffix = 'rupiah')
    {
        $search = ['bilyun', 'milyar', 'juts'];
        $replace = ['triliun', 'miliar', 'juta'];
        $formatNumber = new NumberFormatter("id", NumberFormatter::SPELLOUT);
        $format = $formatNumber->format($number);

        return str_replace($search, $replace, $format) . ' ' . $suffix;
    }
}

if (!function_exists('dbprefix')) 
{
    function dbPrefix($table)
    {
        return get_instance()->db->dbprefix($table);
    }
}

if (!function_exists('bgExec'))
{
    function bgExec($command)
    {
        if (substr(php_uname(), 0, 7) == "Windows")
        {
            pclose(popen("start /B " . $command, "r"));
        }
        else {
            exec($command . " > /dev/null &");
        }
    }
}