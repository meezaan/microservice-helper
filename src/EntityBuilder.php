<?php

namespace Meezaan\MicroServiceHelper;

class EntityBuilder
{
    protected $entity;
    protected $entityManager;
    protected $validationErrors = [];

    public function __construct($entityManager, $entity)
    {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function buildFromPost($post)
    {
        foreach ($post as $key => $val) {
            $method = ucfirst($key);
            $this->entity->{'set'.$method}($val);
        }
    }


    public function isValid(array $post)
    {
        return $this->validate($post);
    }

    private function validate(array $post)
    {
        $this->validationErrors = [];
        foreach ($post as $key => $val) {
            $method = ucfirst($key);
            if (!is_callable([$this->entity, 'set' . $method])) {
                $this->validationErrors[] = [$key => 'Method set' . $method . ' does not exist for posted property ' . $key . '.'];
            }
        }

        if (count($this->validationErrors) > 0) {
            return false;
        }

        return true;
    
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function save($post, $return = true)
    {
        $this->buildFromPost($post);

        $this->entityManager->persist($this->entity);

        $this->entityManager->flush();

        if ($return) {
            return $this->entity->getId();
        }
    }

    public function get($id) {
        $result = false;
        foreach ($this->entityManager->getClassMetadata(get_class($this->entity))->getColumnNames() as $key) {
            if (!is_int($key)) {
                $method = ucfirst($key);
                $result[$key] = $this->entity->{'get'.$method}($key);
            }
        }
        return $result;
    }
}
