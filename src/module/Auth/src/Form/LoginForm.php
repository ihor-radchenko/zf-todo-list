<?php

namespace Auth\Form;

use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\ObjectExists;
use Zend\Form\Form;
use Zend\Validator\Hostname;

class LoginForm extends Form
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
                    'name'    => ObjectExists::class,
                    'options' => [
                        'fields'            => 'email',
                        'object_repository' => $this->entityManager->getRepository(User::class),
                        'messages'          => [
                            'noObjectFound' => 'Invalid credentials.',
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
            ],
        ]);
        $inputFilter->add([
            'name'     => 'password',
            'required' => true,
        ]);

    }
}
