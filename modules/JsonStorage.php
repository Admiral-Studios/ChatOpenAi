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
            $fileData = json_decode(file_get_contents(self::$filename), true);

            if (count($fileData) == count($fileData, COUNT_RECURSIVE)) {
                $resultArray = [];
                $resultArray[] = $fileData;
                $resultArray[] = $data;

                self::store($resultArray);
            } else {
                $fileData[] = $data;

                self::store($fileData);
            }
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
            $json = json_decode(file_get_contents(self::$filename));

            if (is_object($json))
                return [$json];

            return $json;
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