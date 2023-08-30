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
      
    }

    public function put()
    {
      
    }

    public function delete()
    {
      
    }
  }