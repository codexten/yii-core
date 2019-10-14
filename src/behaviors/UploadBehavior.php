<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace codexten\yii\behaviors;

use Yii;

/**
 * Class UploadBehavior
 *
 * @package codexten\yii\behaviors
 * @author Jomon Johnson <jomon@entero.in>
 */
class UploadBehavior extends \trntv\filekit\behaviors\UploadBehavior
{
    public $baseUrlAttribute = false;

    /**
     * @param $file
     * @return mixed
     */
    protected function enrichFileData($file)
    {
        $fs = $this->getStorage()->getFilesystem();

//        if ($file['path'] && $fs->has($file['path'])) {
//            $data = [
//                'type' => $fs->getMimetype($file['path']),
//                'size' => $fs->getSize($file['path']),
//                'timestamp' => $fs->getTimestamp($file['path'])
//            ];
//            foreach ($data as $k => $v) {
//                if (!array_key_exists($k, $file) || !$file[$k]) {
//                    $file[$k] = $v;
//                }
//            }
//        }
        if ($file['path'] !== null && $file['base_url'] === null) {
            $file['base_url'] = $this->getStorage()->baseUrl;
        }
        if ($file['path']){
            $file['path']=ltrim($file['path'],'/');
        }

        return $file;
    }
}