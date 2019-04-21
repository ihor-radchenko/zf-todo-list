<?php

namespace V1\Serializer;

use V1\Entity\ToDo;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;

class ToDoSerializer extends JsonModel
{
    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        /** @var ToDo $resource */
        $resource = $this->getVariable('resource');
        $result = [
            'data' => null,
        ];
        if ($resource) {
            $result = [
                'data' => [
                    'id'           => $resource->getId(),
                    'title'        => $resource->getTitle(),
                    'user_id'      => $resource->getUserId(),
                    'is_completed' => $resource->getIsCompleted(),
                    'created_at'   => $resource->getCreatedAt(),
                    'updated_at'   => $resource->getUpdatedAt(),
                ],
            ];
        }

        return Json::encode($result, false, ['prettyPrint' => true]);
    }
}
