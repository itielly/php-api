<?php
  namespace app\services;

  use app\repositories\EventRepository;

  class EventService
  {
    /**
     * @var EventRepository
     */
    private $repository;

    public function __construct()
    {
      $this->repository = new EventRepository;
    }

    public function get()
    {
      return $this->repository->get();
    }

    public function post()
    {
      return $this->repository->create();
    }

    public function edit()
    {
      return $this->repository->put();
    }

    public function delete()
    {
      return $this->repository->delete();
    }
  }