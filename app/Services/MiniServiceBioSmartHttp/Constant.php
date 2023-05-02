<?php
namespace App\Services\MiniServiceBioSmartHttp;

class Constant
{
    const biosmartHttpIp = '94.180.249.117';
    const biosmartHttpPort = '60002';

    static function biosmartHttp($slash)
    {
        if ($slash == '/')
            return 'http://' . self::biosmartHttpIp . ':' . self::biosmartHttpPort . '/';
        else
            return 'http://' . self::biosmartHttpIp . ':' . self::biosmartHttpPort;
    }

	const devNumber = '79600756509';

    private static $socketIoServer;

    public static function get_socketIoServer(){
        if (is_null(self::$socketIoServer) || empty(self::$socketIoServer)) {
            self::$socketIoServer = CSettings::getValue('socket_server_address');
        }
        return self::$socketIoServer;
    }
}
