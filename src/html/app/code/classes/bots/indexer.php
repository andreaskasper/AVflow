<?php

namespace bots;

class indexer {

    public static $json_index = [];

    public static function start() {
        if (!file_exists("/in/")) die("[ERROR] /in/ Verzeichnis fehlt!".PHP_EOL);
        if (!file_exists("/data/")) mkdir("/data/");
        if (!file_exists("/out/")) mkdir("/out/");

        if (!file_exists("/data/index.json")) file_put_contents("/data/index.json","[]");
        self::$json_index = json_decode(file_get_contents("/data/index.json"),true);

        $dirs = array("/in/");
        $i = 0;
        while (isset($dirs[$i])) {
            $dir = $dirs[$i];
            $i++;
            $data_modified = false;
            $files = scandir($dir);
            foreach ($files as $file) {
                $file = new File($dir.$file);
                if (substr($file->name(),0,1) == ".") continue;
                if (!$file->is_writable()) continue;
                if (is_dir($file->fullname())) { $dirs[] = $file->fullname()."/"; continue; }
                echo("[💾] ".$file->fullname().PHP_EOL);
                if ($file->is_video()) continue;

                $file2 = $file->fullname_as_id();
                $row = self::indexdata_by_filename($file2);
                if ($row == false) {
                    self::$json_index[] = array(
                        "filename" => $file2,
                        "filesize" => $file->size(),
                        "modified" => $file->modified()->getTimestamp(),
                        "md5" => $file->md5()
                    );
                    $data_modified = true;
                }
                if ($row[0]["filesize"] != $file->size()) {
                    self::$json_index[$row[1]] = array(
                        "filename" => $file2,
                        "filesize" => $file->size(),
                        "modified" => $file->modified()->getTimestamp(),
                        "md5" => $file->md5()
                    );
                    $data_modified = true;
                }
            }
            if ($data_modified) file_put_contents("/data/index.json", json_encode(self::$json_index));
        }
    }

    public static function indexdata_by_filename($filename) : Array|false {
        for ($i = 0; $i < count(self::$json_index); $i++) {
            $row = self::$json_index[$i];
            if ($row["filename"] == $filename) return array($row, $i);
        }
        return false;
    }

}