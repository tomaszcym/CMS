<?php

use App\Models\ConstField;
use App\Models\SiteLang;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;

function renderImage($url, $width = null, $height = null, $objectFit = null)
{

    if (!is_dir(public_path('resized'))) {
        mkdir(public_path('resized'));
    }


    try {
        $storageImg = Storage::get($url);
    } catch (Exception $e) {
        $storageImg = public_path('image/utils/no-image.png');
        if (!$url) {
            $url = 'image/utils/no-image.png';
        }
    }


    $prefix = $width . 'x' . $height . '_' . $objectFit;
    $fileName = str_replace('/', '_', $url);
    $filePath = 'resized/' . $prefix . '_' . $fileName;

    if (!file_exists(public_path($filePath))) {
        $img = Image::make($storageImg);

        $extension = explode('.', $fileName);
        $extension = $extension[count($extension) - 1];

        if ($extension != 'svg') {
            switch ($objectFit) {
                case 'fit':
                {
                    $img->fit($width, $height);
                    break;
                }
                case 'resize':
                {
                    $img->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    break;
                }
            }
        }

        $img->save($filePath);
    }

    return asset($filePath);
}


function renderSmallCover($model)
{
    $gallery = $model->gallery;
    $url = '';
    if ($gallery) {
        $url = $gallery->coverUrl();
    }
    return renderImage($url, 50, 40, 'fit');
}


function getConstField($name)
{
    $value = '';
    $constField = ConstField::with([])->where('name', '=', $name)->locale()->first();
    if ($constField) {
        $value = $constField->value;
    }
    return $value;
}


function getLanguagesConfig()
{
    $host = env('DB_HOST');
    $dbName = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $connection = new Connection($pdo, $dbName);
    try {
        $result = $connection->select('SELECT name FROM site_lang WHERE active=1');
        $resultDefaultSiteLang = $connection->selectOne('SELECT name FROM site_lang WHERE default_site=1');
        $resultDefaultAdminLang = $connection->selectOne('SELECT name FROM site_lang WHERE default_admin=1');

        $langs = [];
        foreach ($result as $res) {
            $langs[] = $res->name;
        }

        return (object)[
            'langs' => $langs,
            'defaultSite' => $resultDefaultSiteLang->name,
            'defaultAdmin' => $resultDefaultAdminLang->name,
        ];
    } catch (Exception $e) {
        return (object)[
            'langs' => ['en', 'pl'],
            'defaultSite' => 'pl',
            'defaultAdmin' => 'pl',
        ];
    }
}


function getAdminLang()
{
    $lang = null;
    if (Schema::hasTable('site_lang')) {
        $lang = SiteLang::with([])
            ->where('default_site', '=', 1)
            ->where('active', '=', 1)
            ->first();
    }

    $default = 'en';
    if ($lang) {
        $default = $lang->name;
    }

    return session()->get('app_locale', $default);
}
