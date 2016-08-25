<?php

namespace Core;


class Upload
{
    const UPLOAD_ERROR_CODE = [
        UPLOAD_ERR_INI_SIZE => 'Размер файла %s превысил максимально допустимый размер, который задан директивой upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'Размер файла %s превысил значение MAX_FILE_SIZE, указанное в HTML-форме',
        UPLOAD_ERR_PARTIAL => 'Файл %s был получен только частично',
        UPLOAD_ERR_NO_FILE => 'Файл %s не был загружен',
        UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка',
        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл %s на диск',
        UPLOAD_ERR_EXTENSION => 'PHP-расширение остановило загрузку файла %s',
    ];
    /**
     * @var array
     */
    private $files;
    /**
     * @var array
     */
    private $errors = [];

    /**
     * Upload constructor.
     */
    public function __construct()
    {
        $this->files = array_combine(
            array_map(
                'strip_tags', array_keys($_FILES)
            ),
            array_values($_FILES)
        );
        $this->upload();
    }

    private function upload()
    {
        foreach (array_keys($this->files) as $name) {
            if (is_array($this->files[$name]['name'])) {
                $this->errors[] = 'Поле ' . $name . ' является массивом' . $this->arrayError($this->files[$name]['name']);
                unset($this->files[$name]);
                continue;
            }
            if (
                is_uploaded_file($this->files[$name]['tmp_name']) &&
                $this->files[$name]['error'] == UPLOAD_ERR_OK
            ) {
                if (true) {
                    if (move_uploaded_file($this->files[$name]['tmp_name'], './assets/avatar/' . $this->files[$name]['name'])) {
                        $this->errors[] = 'Файл ' . $this->files[$name]['name'] . ' был успешно загружен';
                    } else {
                        $this->errors[] = 'Файл ' . ($this->files[$name]['name'] ?: 'в поле' . $name) . ' не удалось переместить в новое место';
                    }
                }
            } else {
                $this->errors[] = sprintf(self::UPLOAD_ERROR_CODE[$this->files[$name]['error']], ($this->files[$name]['name'] ?: 'в поле ' . $name));
            }
        }
    }

    /**
     * @param array $array
     *
     * @return string
     */
    private function arrayError($array)
    {
        $array = array_values(array_filter($array));
        if (!empty($array)) {
            return '. ' . (count($array) > 1 ? 'Файлы ' . implode(', ', $array) . ' не были загружены' : 'Файл ' . $array[0] . ' не был загружен');
        }
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return array_map(
            function ($error) {
                return $error . '.';
            },
            $this->errors
        );
    }
}