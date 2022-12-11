<?php

class DbAdapter
{
    private $nativeDb;

    public function __construct($nativeDb)
    {
        $this->nativeDb = $nativeDb;
    }
}

?>