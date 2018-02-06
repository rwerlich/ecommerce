<?php

interface Session{
    
    public static function getMsgError();
    public static function setMsgError($msg);
    public static function clearMsgError();
}

