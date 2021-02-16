<?php

class Connect
{
    public static function getDSN($dbname,$host)
    {
        return 'mysql:dbname='.$dbname.';host='.$host;
    }
}