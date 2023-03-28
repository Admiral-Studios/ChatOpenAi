<?php

namespace App\Modules;

class JsonStorage
{
    private static string $filename;

    private function __construct() {}

    /**
     * Store file.
     *
     * @param array $data
     * @return void
     */
    public static function store(array $data): void
    {
        file_put_contents(self::$filename, json_encode($data));
    }

    /**
     * Append new section into JSON.
     *
     * @param array $data
     * @return void
     */
    public static function append(array $data): void
    {
        if (file_exists(self::$filename)) {
            $fileData = json_decode(file_get_contents(self::$filename));

            $resultData = [];
            $resultData[] = $fileData;

            self::store($resultData);
        }
    }

    /**
     * Read full JSON.
     *
     * @return array
     */
    public static function read(): array
    {
        if (file_exists(self::$filename)) {
            return json_decode(file_get_contents(self::$filename));
        }

        return [];
    }

    /**
     * Set current filename for working.
     *
     * @param string $filename
     * @return void
     */
    public static function setFilename(string $filename): void
    {
        self::$filename = $filename;
    }
}