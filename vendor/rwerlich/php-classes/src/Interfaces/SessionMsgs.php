<?php

namespace Werlich\Interfaces;

interface SessionMsgs{
    
    public static function getMsgError();
    public static function setMsgError($msg);
    public static function clearMsgError();
    public static function setMsgSuccess($msg);
    public static function getMsgSuccess();
    public static function clearMsgSuccess();
    
}

