<?php

namespace Auth\Serializer;

use Auth\Entity\User;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;

class UserSerializer extends JsonModel
{
    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        /** @var User $resource */
        $resource = $this->getVariable('resource');
        $result = [];
        if ($resource) {
            $result = [
                'data' => [
                    'id'         => $resource->getId(),
                    'type'       => 'user',
                    'attributes' => [
                        'name'       => $resource->getName(),
                        'email'      => $resource->getEmail(),
                        'created_at' => $resource->getCreatedAt(),
                        'updated_at' => $resource->getUpdatedAt(),
                    ],
                ],
            ];
        }

        return Json::encode($result, false, ['prettyPrint' => true]);
    }
}
