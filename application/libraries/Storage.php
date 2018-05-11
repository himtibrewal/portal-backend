<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Cloud\Storage\StorageClient;

/**
 * Created by PhpStorm.
 * User: manish
 * Email: raj.manishkr@gmail.com
 * Date: 26/2/18
 */
class Storage {

    private $storage;
    private $bucket;

    function __construct() {
        $ci =& get_instance();
        $ci->config->load('google_storage');
        $data = $ci->config->item('google_storage');
        $this->storage = new StorageClient(array(
            'keyFile' => $data['keyFile']
        ));

        $this->bucket = $this->storage->bucket($data['bucket']);
    }

    function upload($name, $data) {
        $this->bucket->upload(
            $data,
            array('name' => $name)
        );

        return $this->exists($name);
    }

    function all($prefix = false) {
        $options = array();
        if ($prefix)
            $options['prefix'] = $prefix;

        $objects = $this->bucket->objects($options);

        $data = array();

        foreach ($objects as $object)
            $data[] = $object->info();

        return $data;
    }

    function info($name) {
        $object = $this->bucket->object($name);

        return $object->info();
    }

    function exists($name) {
        $object = $this->bucket->object($name);

        return $object->exists();
    }

    function move($name, $new_name) {
        $object = $this->bucket->object($name);
        $object->rename($new_name);

        return $this->exists($new_name);
    }

    function delete($name) {
        $object = $this->bucket->object($name);
        $object->delete();

        return !$object->exists();
    }

    function download($name, $file = false) {
        $object = $this->bucket->object($name);

        if ($file) {
            $object->downloadToFile($file);

            return file_exists($file);
        }

        return $object->downloadAsString();
    }
}