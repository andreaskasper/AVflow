<?php

class ffmpeg {

    public static function convert(File $file_in, File $file_out, string $cmd) {
        $file_tmp = new File("/tmp/".md5(microtime(true).rand(0,999999)).".".$file_out->extension());
        if ($file_tmp->exists()) throw new \Exception("tmp file gibt es noch");

        $cmd = str_replace("{{in}}", $file_in->fullname(), $cmd);
        $cmd = str_replace("{{out}}", $file_tmp->fullname(), $cmd);

        system($cmd);

        if ($file_tmp->exists()) {
            $file_tmp->move($file_out);
        }
    }


}