<?php

namespace V1\Form;

use Zend\Form\Form;

class ToDoCreate extends Form
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);

        $inputFilter = $this->getInputFilter();

        if ($inputFilter) {
            $inputFilter->add([
                'name'       => 'title',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 2,
                            'max' => 255,
                        ],
                    ],
                ],
            ]);
        }
    }
}
