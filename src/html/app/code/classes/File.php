<?php

class File {

    private $_filename = null;

    public function __construct(string $filename) {
        $this->_filename = $filename;
    }

    public function directory() : Directory { return new Directory($this->info()["dirname"].PATH_SEPARATOR); }
    public function delete() { unlink($this->_filename); }
    public function exists() : bool { return file_exists($this->_filename); }
    public function extension() : string  { return $this->fileinfo()["extension"]; }
    public function fullname() : string  { return $this->_filename; }
    public function info() : Array|null { return pathinfo($this->_filename); }
    public function name() : string  { return $this->fileinfo()["basename"]; }
    public function md5() : int { return md5_file($this->_filename); }
    public function size() : int { return filesize($this->_filename); }
    public function sha256() : string { return hash_file("sha256", $this->_filename); }
    
    
    public function is_writable() : bool { return is_writable($this->_filename); }
    public function is_writeable() : bool { return is_writable($this->_filename); }

}