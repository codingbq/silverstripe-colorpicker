<?php

/**
 * Color field
 */
class ColorField extends TextField
{
    public function __construct($name, $title = null, $value = '', $form = null)
    {
        parent::__construct($name, $title, $value, 6, $form);
    }

    public function Field($properties = array())
    {
        $base = str_replace(BASE_PATH . '/', '', dirname(dirname(__DIR__)));

        Requirements::javascript($base . '/javascript/colorpicker.min.js');
        Requirements::javascript($base . '/javascript/colorfield.min.js');

        Requirements::css($base . '/css/colorpicker.min.css');

        return $this->createTag('input', array_merge(
                $this->getAttributes(),
                array(
                    'style' => 'background:' .
                        ($this->Value() ? '#' . $this->Value() : '#ffffff') .
                        '; color: ' .
                        ($this->getTextColor()) . ';'
                )
            )
        );
    }

    public function Type()
    {
        return 'text';
    }

    public function validate($validator)
    {
        if (!empty ($this->Value()) && !preg_match('/^[A-f0-9]{6}$/', $this->Value())) {
            $validator->validationError(
                $this->name,
                _t('ColorField.VALIDCOLORFORMAT', 'Please enter a valid color in hexadecimal format.'),
                'validation',
                false
            );
            return false;
        }
        return true;
    }

    protected function getTextColor()
    {
        if ($this->Value()) {
            $c = intval($this->Value(), 16);
            $r = $c >> 16;
            $g = ($c >> 8) & 0xff;
            $b = $c & 0xff;
            $mid = ($r + $g + $b) / 3;
            return ($mid > 127) ? '#000000' : '#ffffff';
        } else {
            return '#000000';
        }
    }
}

/**
 * Disabled version of {@link ColorField}.
 */
class ColorField_Disabled extends ColorField
{

    protected $disabled = true;

    public function Field($properties = array())
    {
        if ($this->Value()) {
            $val = '#' . $this->Value();
        } else {
            $val = '#ffffff';
        }

        $col = $this->getTextColor();

        return '<span class="readonly" id="' . $this->id() . '" style="color:' . $col .
        '; background:' . $val . ';">' . $val . '</span>' .
        '<input type="hidden" value="' . $this->Value() . '" name="' . $this->getName() . '" />';
    }

    public function Type()
    {
        return 'readonly text';
    }

    public function jsValidation()
    {
        return null;
    }

    public function php()
    {
        return true;
    }

    public function validate($validator)
    {
        return true;
    }
}

?>