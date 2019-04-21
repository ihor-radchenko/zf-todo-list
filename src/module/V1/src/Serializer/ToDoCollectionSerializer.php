<?php

namespace V1\Serializer;

use Zend\Json\Json;
use Zend\View\Model\JsonModel;

class ToDoCollectionSerializer extends JsonModel
{
    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        $resources = $this->getVariable('resources');
        $result = ['data' => []];

        if ($resources) {
            foreach ($resources as $resource) {
                $result['data'][] = [
                    'id'           => $resource->getId(),
                    'title'        => $resource->getTitle(),
                    'user_id'      => $resource->getUserId(),
                    'is_completed' => $resource->getIsCompleted(),
                    'created_at'   => $resource->getCreatedAt(),
                    'updated_at'   => $resource->getUpdatedAt(),
                ];
            }
        }

        return Json::encode($result, false, ['prettyPrint' => true]);
    }
}
