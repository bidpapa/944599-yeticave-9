<?php

class Validator
{

    private $required_fields;

    private $integer_fields;

    private $email_fields;

    private $date_fields;

    private $img_field;

    private $values;

    private $file;

    private $error = [];

    private $img_tmp_name;

    private $img_path;

    public $check_category = false;

    function __construct(
      $required_fields,
      $integer_fields,
      $email_fields,
      $date_fields,
      $img_field,
      $values,
      $file
    ) {
        $this->required_fields = $required_fields;
        $this->integer_fields = $integer_fields;
        $this->email_fields = $email_fields;
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

    private function isEmailFieldValid()
    {
        foreach ($this->email_fields as $key => $value) {
            if (!filter_var($this->values[$key], FILTER_VALIDATE_EMAIL)
              && !empty($this->values[$key])
            ) {
                $this->error[$key] = $value;
            }
        }
    }

    private function isDateCorrect()
    {
        foreach ($this->date_fields as $key => $value) {
            if (!$this->isDateValid($this->values[$key])
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

    private function categoryCheck()
    {
        $allowed_categories = [1, 2, 3, 4, 5, 6];
        if (!in_array((int)$this->values['category'], $allowed_categories)) {
                $this->error['category'] = 'Вы не можете добавить товар в эту категорию';
            }
    }

    private function isDateValid(string $date): bool
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
            move_uploaded_file($this->img_tmp_name, 'uploads/' . $this->img_path);
            return $img_name = 'uploads/' . $this->img_path;
        }
    }

    public function getErrors()
    {
        if ($this->img_field) {
            $this->checkImage($this->file, $this->img_field);
        }
        if ($this->required_fields) {
             $this->isFieldEmpty();
        }
        if ($this->integer_fields) {
            $this->isInteger();
        }
        if ($this->email_fields) {
            $this->isEmailFieldValid();
        }
        if ($this->date_fields) {
            $this->isDateCorrect();
        }
        if ($this->check_category) {
            $this->categoryCheck();
        }
        return $this->error;
    }

    public function getValues()
    {
        $array = [];
        foreach ($this->values as $key => $value) {
            if ($key != 'password') {
                $array[$key] = htmlspecialchars($value);
            } else {
                $array[$key] = password_hash($value, PASSWORD_DEFAULT);
            }
        }
        return $array;
    }

}