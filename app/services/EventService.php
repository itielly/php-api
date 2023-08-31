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

    public function post($values)
    {
      return $this->repository->create($values);
    }

    public function edit($values)
    {
      return $this->repository->put($values);
    }

    public function delete($id)
    {
      return $this->repository->delete($id);
    }
  }