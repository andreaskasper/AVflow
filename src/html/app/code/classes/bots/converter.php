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
            $file_in = new \File("/in".$row["filename"]);

            if (empty($_ENV["CONVERTS"])) $_ENV["CONVERTS"] = "1080p.mp4,480p.mp4,240p.mp4";

            
            foreach (explode(",", $_ENV["CONVERTS"]) as $c) {
                switch (trim($c)) {
                    default:
                        case "1080p.mp4":
                            $file_out = new \File("/out/".$row["md5"].".1080p.mp4");
                            if (!$file_out->exists()) {
                                \ffmpeg::convert($file_in, $file_out, 'ffmpeg -i "{{in}}" -vf scale=-2:1080 -movflags +faststart "{{out}}"');
                            }
                            break;
                        case "480p.mp4":
                            $file_out = new \File("/out/".$row["md5"].".480p.mp4");
                            if (!$file_out->exists()) {
                                \ffmpeg::convert($file_in, $file_out, 'ffmpeg -i "{{in}}" -vf scale=-2:480 -movflags +faststart "{{out}}"');
                            }
                            break;
                        case "240p.mp4":
                            $file_out = new \File("/out/".$row["md5"].".240p.mp4");
                            if (!$file_out->exists()) {
                                \ffmpeg::convert($file_in, $file_out, 'ffmpeg -i "{{in}}" -vf scale=-2:240 -movflags +faststart "{{out}}"');
                            }
                            break;
                        case "poster.half.jpg":
                            $file_out = new \File("/out/".$row["md5"].".poster.half.jpg");
                            if (!$file_out->exists()) {
                                $sec = $file_in->video_duration();
                                if ($sec == 0) break;
                                \ffmpeg::convert($file_in, $file_out, 'ffmpeg -i "{{in}}" -ss '.($sec / 2).' -frames:v 1 "{{out}}"');
                            }
                            break;
                        echo("[ERR] unknown Converter: ".$c.PHP_EOL);
                        break;
                }
            }
        }
        echo("[*] ".count(self::$json_index)."files checked...".PHP_EOL);
    }
}