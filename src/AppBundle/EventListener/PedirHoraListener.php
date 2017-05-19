<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 17/05/2017
 * Time: 18:17
 */

namespace AppBundle\EventListener;

use AppBundle\Event\HorarioEvent;
use AppBundle\Entity\PedirHora;
use Doctrine\ORM\EntityManager;

class PedirHoraListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadEvents(HorarioEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');


        // load events using your custom logic here,
        // for instance, retrieving events from a repository

        $PedirHoras = $this->entityManager->getRepository('AppBundle:PedirHora')
            ->createQueryBuilder('company_events')
            ->where('company_events.event_datetime BETWEEN :startDate and :endDate')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
            ->getQuery()->getResult();

        // $companyEvents and $companyEvent in this example
        // represent entities from your database, NOT instances of EventEntity
        // within this bundle.
        //
        // Create EventEntity instances and populate it's properties with data
        // from your own entities/database values.

        foreach($PedirHoras as $PedirHora) {

            // create an event with a start/end time, or an all day event
            if ($PedirHora->getAllDay() === false) {
                $hora = new PedirHora($PedirHora->getTitle(), $PedirHora->getStartDatetime(), $PedirHora->getEndDatetime());
            } else {
                $hora = new PedirHora($PedirHora->getTitle(), $PedirHora->getStartDatetime(), null, true);
            }

            //optional calendar event settings
            $hora->setAllDay(true); // default is false, set to true if this is an all day event
            $hora->setBgColor('#FF0000'); //set the background color of the event's label
            $hora->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $hora->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            $hora->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($hora);
        }
    }

}