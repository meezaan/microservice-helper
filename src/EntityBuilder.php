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
    public function buildFromPost(array $post)
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
    public function isValid(array $post): bool
    {
        return $this->validate($post);
    }

    /**
     * [validate description]
     * @param  array  $post [description]
     * @return [type]       [description]
     */
    private function validate(array $post): bool
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

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function save(array $post): int
    {
        $this->buildFromPost($post);

        $this->entityManager->persist($this->entity);

        $this->entityManager->flush();

        return $this->entity->getId();
        
    }

    public function get(int $id): ?array
    {
        $result = null;
        foreach ($this->entityManager->getClassMetadata(get_class($this->entity))->getColumnNames() as $key) {
            if (!is_int($key)) {
                $method = ucfirst($key);
                $result[$key] = $this->entity->{'get'.$method}($key);
            }
        }
        return $result;
    }
}
