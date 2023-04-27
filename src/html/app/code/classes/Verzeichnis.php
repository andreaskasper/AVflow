<?php

class Verzeichnis {

    private $_path = "/";

    public function __construct(string $path) {
        if (substr($path,-1,1) != "/") $path .= "/";
        $this->_path = $path;
    }
    
    public function createsub(string $new_foldername) : Verzeichnis { $v = new Verzeichnis($this->path().$new_foldername."/"); mkdir($v->path()); return $v; }
    public function exists() : bool { return file_exists($this->_path); }
    public function modified() : DateTime { return new DateTime("@".filemtime($this->_path)); }
    public function path() : string { return $this->_path; }


    public function is_writable() : bool { return is_writable($this->_path); }
    public function is_writeable() : bool { return $this->is_writable(); }


}