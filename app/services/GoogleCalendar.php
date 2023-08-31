<?php

class GoogleCalendar 
{
  public function get()
  {
    $jsonKey = [
      'type' => 'service_account',
    ];
    
    $client = new Google\Client();
    $client->setApplicationName("eventApi");
    $client->setDeveloperKey("../credentials.json");
    $client->setAuthConfig($jsonKey);
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Calendar::CALENDAR);
    $client->setSubject('menezes.itielly@gmail.com');
    
    $service = new Google\Service\Calendar($client);

    $results = $service->events->get('primary', "eventId");
    var_dump($results);
  }
}