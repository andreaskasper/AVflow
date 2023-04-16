<?php

class Directory {

    private $_path = "/";

    public function __construct(string $path) {
        if (substr($path,-1,1) != "/") $path .= "/";
        $this->_path = $path;
    }




}