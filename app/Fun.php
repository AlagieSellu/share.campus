<?php

namespace App;

class Fun
{
    public static function canPlay($file)
    {
        $entension = $file->extension();
        foreach (config('sys.can_play_extensions') as $ext){
            if($entension == $ext){
                return true;
            }
        }
        return false;
    }
    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1000; $i++) {
            $bytes /= 1000;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function bytes_gigs($bytes)
    {
        return $bytes/1000000000;
    }

    public static function gigs_bytes($bytes)
    {
        return $bytes*1000000000;
    }

    public static function bytes_kilos($bytes)
    {
        return $bytes/1000;
    }
}
