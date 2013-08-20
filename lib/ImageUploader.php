<?php

import_lib('Form');

class ImageUploader {
    private $id;
    private $form;
    private $directory;
    private $configurations;
    private $fields;
    private $limit;
    private $data;

    function __construct($id = 'images', $directory = 'objects', $configurations = array()) {
        $this->id = $id;
        $this->form = new Form($id);
        $this->form->setClear(true);
        $this->directory = $directory;

        $configurations = array('thumbs' => array('cut', 60, 60));
        $this->configurations = $configurations;

        $this->fields = 1;
        $this->limit = -1;
        $this->data = array();
    }

    function setFields($fields) {
        $this->fields = $fields;
    }

    function setLimit($limit) {
        $this->limit = $limit;
    }

    function setData($data) {
        $this->data = $data;
    }

    function getCount() {
        return $this->limit < 0 ? $this->fields : min($this->limit - count($this->data), $this->fields);
    }

    function processImage($source, $name) {
        foreach ($this->configurations as $sub => $settings) {
            $destination = resource($this->directory . '/' . $sub . '/' . $name);
            if (is_array($settings)) {
                list($type, $max_width, $max_height) = $settings;
            } else {
                $type = $settings;
                $max_width = 800;
                $max_height = 600;
            }
            if ($type == 'none') {
                rename($source, $destination);
            } else {
                $image = new Imagick($source);
                $size = $image->getImageGeometry();
                $width = $size['width'];
                $height = $size['height'];
                switch ($type) {
                case 'cut':
                    $image->cropThumbnailImage($max_width, $max_height);
                    break;
                case 'cut-width':
                    $image->cropThumbnailImage($max_width * $height / $max_height, $height);
                    $image->thumbnailImage($max_width, $max_height, true);
                    break;
                case 'cut-height':
                    $image->cropThumbnailImage($width, $max_height * $width / $max_width);
                    $image->thumbnailImage($max_width, $max_height, true);
                    break;
                case 'scale':
                    $image->thumbnailImage($max_width, $max_height, true);
                    break;
                }
                $image->writeImage($destination);
                unlink($source);
            }
        }
    }

    function process($namer) {
        $result = array();
        if (!$this->form->received())
            return $result;
        $count = $this->getCount();
        for ($i = 1; $i <= $count; $i++) {
            $file = $this->form->getFile('image_' . $i);
            $description = $this->form->get('description_' . $i);
            if ($file['error'] == 0 && is_uploaded_file($file['tmp_name'])) {
                $data = $namer();
                $data['name'] .= '.' . extension($file['name']);
                $this->processImage($file['tmp_name'], $data['name']);
                $data['description'] = $description;
                $result[] = $data;
            }
        }
        return $result;
    }

    function getForm() {
        foreach ($this->data as $image)
            $this->form->addRaw('<img src="' . resource_url($this->directory . '/thumbs/' . $image['file']) . '" /><p>' . $image['description'] . '</p>');
        $count = $this->getCount();
        for ($i = 1; $i <= $count; $i++) {
            $this->form->add('Image ' . $i, 'file', 'image_' . $i, false);
            $this->form->add('Description ' . $i, 'text', 'description_' . $i, false);
        }
        $this->form->add('Submit', 'submit');
        return $this->form;
    }
}

?>
