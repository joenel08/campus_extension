<?php
class CrestController
{
    private $publicationModel;

    public function __construct($pdo)
    {
        require_once __DIR__ . '/../../models/Publication.php';
        $this->publicationModel = new Publication($pdo);
    }

    public function getGroupedByYear()
    {
        return $this->publicationModel->getPublishedGroupedByYear();
    }

    public function getPublished()
    {
        return $this->publicationModel->getPublished();
    }

    public function getById($id)
    {
        return $this->publicationModel->find($id);
    }
}