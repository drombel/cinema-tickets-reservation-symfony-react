<?php

namespace App\DataFixtures;

use App\Entity\Seat;
use App\Entity\Showing;
use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    private ?ObjectManager $m;

    public function load(ObjectManager $manager)
    {
        $this->m = $manager;

        $rs = $this->m->getRepository(Showing::class);
        $showings = $rs->findAll();

        /** @var Showing $showing */
        foreach ($showings as $showing){
            $seats = $showing->getRoom()->getSeats()->filter(fn (Seat $seat) => $seat->getStatus() === Seat::STATUS_ACTIVE)->toArray();
            $start = $showing->getScreening()->getDateStart();
            $end = $showing->getScreening()->getDateEnd();

            /**
             * loop to iterate on dates from the beginning to the end 1 day at the time,
             * clone breaks reference chain which caused same date in every ticket
             */
            for ($current = $start; $current <= $end; $current = clone $current->add(new \DateInterval('P1D'))){
                $seatsKeys = array_rand($seats, (int)(count($seats) * 0.7));

                foreach ($seatsKeys as $seatKey){
                    $this->addTicket($showing, $seats[$seatKey], $current);
                }
            }
        }

        $manager->flush();
    }

    private function addTicket(Showing $showing, Seat $seat, \DateTimeInterface $date){
        $ticket = new Ticket();
        $ticket->setShowing($showing);
        $ticket->setSeat($seat);
        $ticket->setDate($date);
        $ticket->setName('Name #'.mt_rand(100, 999));
        $ticket->setSurname('Surname #'.mt_rand(100, 999));

        $this->m->persist($ticket);
        return $ticket;
    }


    public function getDependencies()
    {
        return [
            ScreeningFixtures::class,
        ];
    }
}
