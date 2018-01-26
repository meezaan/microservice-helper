<?php

namespace Meezaan\MicroServiceHelper;

class EntityBuilder
{
    /**
     * [protected description]
     * @var [type]
     */
    protected $entity;

    /**
     * [protected description]
     * @var [type]
     */
    protected $entityManager;

    /**
     * [protected description]
     * @var [type]
     */
    protected $validationErrors = [];

    /**
     * [__construct description]
     * @param [type] $entityManager [description]
     * @param [type] $entity        [description]
     */
    public function __construct($entityManager, $entity)
    {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }

    /**
     * [setEntity description]
     * @param [type] $entity [description]
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * [buildFromPost description]
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public function buildFromPost($post)
    {
        foreach ($post as $key => $val) {
            $method = ucfirst($key);
            $this->entity->{'set'.$method}($val);
        }
    }

    /**
     * [isValid description]
     * @param  array   $post [description]
     * @return boolean       [description]
     */
    public function isValid(array $post)
    {
        return $this->validate($post);
    }

    /**
     * [validate description]
     * @param  array  $post [description]
     * @return [type]       [description]
     */
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
