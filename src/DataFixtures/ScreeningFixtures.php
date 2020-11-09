<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use App\Entity\Movie;
use App\Entity\Room;
use App\Entity\Screening;
use App\Entity\Showing;
use App\Repository\CinemaRepository;
use App\Repository\MovieRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScreeningFixtures extends Fixture implements DependentFixtureInterface
{
    private ?ObjectManager $m;

    public function load(ObjectManager $manager)
    {
        $this->m = $manager;

        $rc = $this->m->getRepository(Cinema::class);
        $rm = $this->m->getRepository(Movie::class);

        $cinemas = [];
        $cinemas['gdansk'] = $rc->findOneBy(["name" => "CineMax 5D Gdansk"]);
        $cinemas['warsaw'] = $rc->findOneBy(["name" => "CineMax 5D Warsaw"]);

        $movies = [];
        $movies['walle'] = $rm->findOneBy(["title" => "Wall-E"]);
        $movies['django'] = $rm->findOneBy(["title" => "Django"]);
        $movies['wolf'] = $rm->findOneBy(["title" => "The Wolf of Wall Street"]);
        $movies['future'] = $rm->findOneBy(["title" => "Back to the Future"]);
        $movies['gump'] = $rm->findOneBy(["title" => "Forrest Gump"]);
        $movies['monsters'] = $rm->findOneBy(["title" => "Monsters, Inc"]);
        $movies['godfather'] = $rm->findOneBy(["title" => "The Godfather"]);

        $dates = [
            'past' => [
                'start' => (new \DateTime())->sub(new \DateInterval('P6M')),
                'end' => (new \DateTime())->sub(new \DateInterval('P4M')),
            ],
            'present' => [
                'start' => (new \DateTime())->sub(new \DateInterval('P1M')),
                'end' => (new \DateTime())->add(new \DateInterval('P3M')),
            ],
            'future' => [
                'start' => (new \DateTime())->add(new \DateInterval('P2M')),
                'end' => (new \DateTime())->add(new \DateInterval('P4M')),
            ]
        ];

        $screenings = [
            'past' => [
                'gdansk' => [
                    'walle' => $this->addScreening($cinemas['gdansk'], $movies['walle'], $dates['past']['start'], $dates['past']['end']),
                    'wolf' => $this->addScreening($cinemas['gdansk'], $movies['wolf'], $dates['past']['start'], $dates['past']['end']),
                ],
                'warsaw' => [
                    'django' => $this->addScreening($cinemas['warsaw'], $movies['django'], $dates['past']['start'], $dates['past']['end']),
                ],
            ],
            'present' => [
                'gdansk' => [
                    'walle' => $this->addScreening($cinemas['gdansk'], $movies['walle'], $dates['present']['start'], $dates['present']['end']),
                    'wolf' => $this->addScreening($cinemas['gdansk'], $movies['wolf'], $dates['present']['start'], $dates['present']['end']),
                    'django' => $this->addScreening($cinemas['gdansk'], $movies['django'], $dates['present']['start'], $dates['present']['end']),
                ],
                'warsaw' => [
                    'wolf' => $this->addScreening($cinemas['warsaw'], $movies['wolf'], $dates['present']['start'], $dates['present']['end']),
                    'gump' => $this->addScreening($cinemas['warsaw'], $movies['gump'], $dates['present']['start'], $dates['present']['end']),
                    'django' => $this->addScreening($cinemas['warsaw'], $movies['django'], $dates['present']['start'], $dates['present']['end']),
                ],
            ],
            'future' => [
                'gdansk' => [
                    'godfather' => $this->addScreening($cinemas['gdansk'], $movies['godfather'], $dates['future']['start'], $dates['future']['end']),
                ],
                'warsaw' => [
                    'wolf' => $this->addScreening($cinemas['warsaw'], $movies['wolf'], $dates['future']['start'], $dates['future']['end']),
                ],
            ],
        ];

        $showings = [
            'gdansk' => [
                'room_1' => [
                    '10.00-12.00' => $this->addShowing(
                        $screenings['present']['gdansk']['walle'], $cinemas['gdansk']->getRooms()->get(0), 1250,
                        $this->getTime(10,00), $this->getTime(12,00)
                    ),
                    '12.00-14.00' => $this->addShowing(
                        $screenings['present']['gdansk']['walle'], $cinemas['gdansk']->getRooms()->get(0), 1400,
                        $this->getTime(12,00), $this->getTime(14,00)
                    ),
                    '14.00-16.00' => $this->addShowing(
                        $screenings['present']['gdansk']['django'], $cinemas['gdansk']->getRooms()->get(0), 1450,
                        $this->getTime(14,00), $this->getTime(16,00)
                    ),
                    '16.00-18.00' => $this->addShowing(
                        $screenings['present']['gdansk']['django'], $cinemas['gdansk']->getRooms()->get(0), 1600,
                        $this->getTime(16,00), $this->getTime(18,00)
                    ),
                ],
                'room_2' => [
                    '10.00-13.00' => $this->addShowing(
                        $screenings['present']['gdansk']['wolf'], $cinemas['gdansk']->getRooms()->get(1), 1250,
                        $this->getTime(10,00), $this->getTime(13,00)
                    ),
                    '13.00-15.00' => $this->addShowing(
                        $screenings['present']['gdansk']['walle'], $cinemas['gdansk']->getRooms()->get(1), 1400,
                        $this->getTime(13,00), $this->getTime(15,00)
                    ),
                    '15.00-18.00' => $this->addShowing(
                        $screenings['present']['gdansk']['wolf'], $cinemas['gdansk']->getRooms()->get(1), 1850,
                        $this->getTime(15,00), $this->getTime(18,00)
                    ),
                ],
            ],
            'warsaw' => [
                'room_1' => [
                    '10.00-12.00' => $this->addShowing(
                        $screenings['present']['warsaw']['django'], $cinemas['warsaw']->getRooms()->get(0), 1850,
                        $this->getTime(10,00), $this->getTime(12,00)
                    ),
                    '12.00-14.00' => $this->addShowing(
                        $screenings['present']['warsaw']['gump'], $cinemas['warsaw']->getRooms()->get(0), 1900,
                        $this->getTime(12,00), $this->getTime(14,00)
                    ),
                    '14.00-16.00' => $this->addShowing(
                        $screenings['present']['warsaw']['django'], $cinemas['warsaw']->getRooms()->get(0), 2050,
                        $this->getTime(14,00), $this->getTime(16,00)
                    ),
                    '16.00-18.00' => $this->addShowing(
                        $screenings['present']['warsaw']['gump'], $cinemas['warsaw']->getRooms()->get(0), 2250,
                        $this->getTime(16,00), $this->getTime(18,00)
                    ),
                ],
                'room_2' => [
                    '10.00-13.00' => $this->addShowing(
                        $screenings['present']['warsaw']['wolf'], $cinemas['warsaw']->getRooms()->get(1), 2150,
                        $this->getTime(10,00), $this->getTime(13,00)
                    ),
                    '13.00-15.00' => $this->addShowing(
                        $screenings['present']['warsaw']['gump'], $cinemas['warsaw']->getRooms()->get(1), 2200,
                        $this->getTime(13,00), $this->getTime(15,00)
                    ),
                    '15.00-18.00' => $this->addShowing(
                        $screenings['present']['warsaw']['wolf'], $cinemas['warsaw']->getRooms()->get(1), 2400,
                        $this->getTime(15,00), $this->getTime(18,00)
                    ),
                ],
            ],
        ];

        $this->m->flush();
    }

    private function addScreening(Cinema $cinema, Movie $movie, \DateTimeInterface $start, \DateTimeInterface $end){
        $screening = new Screening();

        $screening->setCinema($cinema);
        $screening->setMovie($movie);
        $screening->setDateStart($start);
        $screening->setDateEnd($end);

        $this->m->persist($screening);
        return $screening;
    }

    private function addShowing(Screening $screening, Room $room, int $price, \DateTimeInterface $start, \DateTimeInterface $end){
        $showing = new Showing();

        $showing->setScreening($screening);
//        $rooms = $screening->getCinema()->getRooms();
//        $showing->setRoom($rooms->get( mt_rand(0, $rooms->count()-1) ));
        $showing->setRoom($room);
        $showing->setPrice($price);
        $showing->setTimeStart($start);
        $showing->setTimeEnd($end);

        $this->m->persist($showing);
        return $screening;
    }

    private function getTime(int $hours = 0, int $minutes = 0){
        return (new \DateTime())->setTime($hours,$minutes);
    }

    public function getDependencies()
    {
        return [
            CinemaFixtures::class,
            MovieFixtures::class,
        ];
    }
}
