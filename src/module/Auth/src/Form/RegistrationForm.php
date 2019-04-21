<?php

namespace Auth\Form;

use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\NoObjectExists;
use Zend\Form\Form;
use Zend\Validator\Hostname;

class RegistrationForm extends Form
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * RegistrationForm constructor.
     *
     * @param EntityManager $entityManager
     * @param null $name
     * @param array $options
     */
    public function __construct(EntityManager $entityManager, $name = null, array $options = [])
    {
        $this->entityManager = $entityManager;
        parent::__construct($name, $options);

        $this->addFilter();
    }

    private function addFilter(): void
    {
        $inputFilter = $this->getInputFilter();

        if ($inputFilter === null) {
            return;
        }

        $inputFilter->add([
            'name'       => 'email',
            'required'   => true,
            'filters'    => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => NoObjectExists::class,
                    'options' => [
                        'fields'            => 'email',
                        'object_repository' => $this->entityManager->getRepository(User::class),
                        'messages'          => [
                            'objectFound' => 'A user with this email already exists.',
                        ],
                    ],
                ],
                [
                    'name'    => 'EmailAddress',
                    'options' => [
                        'allow'      => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 255,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'       => 'name',
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
        $inputFilter->add([
            'name'       => 'password',
            'required'   => true,
            'filters'    => [
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 6,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'       => 'confirm_password',
            'required'   => true,
            'filters'    => [
            ],
            'validators' => [
                [
                    'name'    => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ],
        ]);

    }
}
