<?php

namespace bots;

class converter {

    public static $json_index = [];

    public static function start() {
        if (!file_exists("/in/")) die("[ERROR] /in/ Verzeichnis fehlt!".PHP_EOL);
        if (!file_exists("/data/")) mkdir("/data/");
        if (!file_exists("/out/")) mkdir("/out/");

        if (!file_exists("/data/index.json")) file_put_contents("/data/index.json","[]");
        self::$json_index = json_decode(file_get_contents("/data/index.json"),true);

        foreach (self::$json_index as $row) {
            $file_in = "/in".$row["filename"];

            $file_out = "/out/".$row["md5"].".1080p.mp4";
            if (!file_exists($file_out)) {
                $cmd = 'ffmpeg -i "'.$file_in.'" -vf scale=-2:1080 -threads 0 -movflags +faststart "'.$file_out.'"';
                system($cmd);
            }

            $file_out = "/out/".$row["md5"].".480p.mp4";
            if (!file_exists($file_out)) {
                $cmd = 'ffmpeg -i "'.$file_in.'" -vf scale=-2:480 -threads 0 -movflags +faststart "'.$file_out.'"';
                system($cmd);
            }
        }
        echo("[*] ".count(self::$json_index)."files checked...".PHP_EOL);

    }

}