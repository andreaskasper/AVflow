<?php

class File {

    private $_filename = null;

    public function __construct(string $filename) {
        $this->_filename = $filename;
    }

    public function directory() : Directory { return new Directory($this->info()["dirname"].PATH_SEPARATOR); }
    public function delete() { unlink($this->_filename); }
    public function exists() : bool { return file_exists($this->_filename); }
    public function extension() : string  { return $this->info()["extension"]; }
    public function fullname() : string  { return $this->_filename; }
    public function fullname_as_id(): string { $a = $this->fullname(); if (substr($a,0,4) == "/in/") $a = substr($a,3,99999); return $a; }
    public function info() : Array|null { return pathinfo($this->_filename); }
    public function name() : string  { return $this->info()["basename"]; }
    public function md5() : string { return md5_file($this->_filename); }
    public function size() : int { return filesize($this->_filename); }
    public function sha256() : string { return hash_file("sha256", $this->_filename); }
    public function modified() : DateTime { return new DateTime("@".filemtime($this->_filename)); }
    public function move(File $new_file) { if (!$new_file->exists()) rename($this->fullname(), $new_file->fullname()); }
    public function rename(File $new_file) { if (!$new_file->exists()) rename($this->fullname(), $new_file->fullname()); }
    
    
    
    public function is_writable() : bool { return is_writable($this->_filename); }
    public function is_writeable() : bool { return is_writable($this->_filename); }
    public function is_video() : bool { if (strtolower($arr["extension"] ?? "") == "mp4") return true; return false; }

}