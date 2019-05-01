<?php

class Validator
{

    private $required_fields;

    private $integer_fields;

    private $date_fields;

    private $img_field;

    private $values;

    private $file;

    private $error = [];

    private $img_tmp_name;

    private $img_path;

    function __construct(
      $required_fields,
      $integer_fields,
      $date_fields,
      $img_field,
      $values,
      $file
    ) {
        $this->required_fields = $required_fields;
        $this->integer_fields = $integer_fields;
        $this->date_fields = $date_fields;
        $this->img_field = $img_field;
        $this->values = $values;
        $this->file = $file;
    }

    private function isFieldEmpty()
    {
        foreach ($this->required_fields as $key => $value) {
            if (empty($this->values[$key])) {
                $this->error[$key] = $value;
            }
        }
    }

    private function isInteger()
    {
        foreach ($this->integer_fields as $key => $value) {
            if ((!is_numeric($this->values[$key]) || $this->values[$key] < 1)
              && !empty($this->values[$key])
            ) {
                $this->error[$key] = $value;
            }
        }
    }

    private function isDateCorrect()
    {
        foreach ($this->date_fields as $key => $value) {
            if (!$this->is_date_valid($this->values[$key])
              && !empty($this->values[$key])
            ) {
                $this->error[$key] = $value;
            } elseif (strtotime($this->values[$key]) < strtotime('tomorrow')
              && !empty($this->values[$key])
            ) {
                $this->error[$key]
                  = 'Дата окончания должна быть минимум на день больше сегодняшнего';
            }
        }
    }

    private function is_date_valid(string $date): bool
    {
        $format_to_check = 'Y-m-d';
        $dateTimeObj = date_create_from_format($format_to_check, $date);
        return $dateTimeObj !== false
          && array_sum(date_get_last_errors()) === 0;
    }

    private function checkImage($file, $img_field)
    {
        $key = key($img_field);
        if ($file[$key]['error'] === 0 && !empty($file[$key]['name'])) {
            $this->img_tmp_name = $file[$key]['tmp_name'];
            $this->img_path = $file[$key]['name'];
            $mime_type = mime_content_type($file[$key]['tmp_name']);
            if (!in_array($mime_type,
              ['image/jpg', 'image/jpeg', 'image/png'])
            ) {
                $this->error[$key]
                  = 'Загрузите картинку в формате JPG, JPEG или PNG';
            }
        } else {
            $this->error[$key] = 'Загрузите картинку';
        }
    }

    public function loadImage()
    {
        if (!$this->error) {
            move_uploaded_file($this->img_tmp_name, 'img/' . $this->img_path);
            return $img_name = 'img/' . $this->img_path;
        }
    }

    public function getErrors()
    {
        $this->checkImage($this->file, $this->img_field);
        $this->isFieldEmpty();
        $this->isInteger();
        $this->isDateCorrect();
        return $this->error;
    }

}