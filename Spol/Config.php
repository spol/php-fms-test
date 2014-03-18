<?php

namespace Spol\FSM;

use InvalidArgumentException;

class Config
{

    protected $config;

    public function __construct($iniFileContents)
    {

        $conf = parse_ini_string($iniFileContents, true);

        if ($conf === false) {
            throw new RuntimeException("Unable to parse configuration file.");
        }

        $this->config = $conf;
    }

    public function get($path)
    {
        $segments = explode('.', $path);
        $conf = $this->config;

        while ($next = array_shift($segments)) {
            if (!isset($conf[$next])) {
                throw new InvalidArgumentException("Config property '{$path}' is not set.");
            }
            $conf = $conf[$next];
        }

        return $conf;
    }

    public static function fromFile($iniFilePath)
    {
        if (!file_exists($iniFilePath)) {
            throw new InvalidArgumentException("Specified config file does not exist: {$iniFilePath}");
        }

        return new static(file_get_contents($iniFilePath));
    }

    public static function fromString($iniFileContents)
    {
        return new static($iniFileContents);
    }
}
