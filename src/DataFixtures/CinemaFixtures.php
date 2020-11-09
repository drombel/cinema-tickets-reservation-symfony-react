<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use App\Entity\Room;
use App\Entity\Seat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CinemaFixtures extends Fixture
{
    private ?ObjectManager $m;

    public function load(ObjectManager $manager)
    {
        $this->m = $manager;

        /** Cinemas */
        $this->addCinema(
            "CineMax 5D Gdansk", "cinemax-5d-gdansk.jpg",
            "Local cinema for families, kids and couples. Cinema contains few room and huge parking lot for visitors. Hope we see You there!",
            "Aleja Zwycięstwa 14, 80-219 Gdańsk", "54.3721236", "18.6271601",
            [
                $this->addRoom("Main hall", 8, 8 ),
                $this->addRoom("Viewer's hall", 5, 7 ),
            ]
        );
        $this->addCinema(
            "CineMax 5D Warsaw", "cinemax-5d-warsaw.jpg",
            "Newest cinema located nearby gallery Golden Gates. Every 2 weeks there're new movies. Come and see by yourself!",
            "Złota 59, 00-120 Warszawa", "52.2301458", "21.0005145",
            [
                $this->addRoom("Room 1", 4, 6 ),
                $this->addRoom("Room 2", 6, 6 ),
            ]
        );

        $this->m->flush();
    }

    private function addCinema(
        string $name = "", string $image = "", string $desc = "",
        string $address = "", string $lat = "", string $long = "",
        array $rooms = []
    ){
        $cinema = new Cinema();
        $cinema->setName($name);
        $cinema->setImage($image);
        $cinema->setDescription($desc);
        $cinema->setAddress($address);
        $cinema->setLat($lat);
        $cinema->setLong($long);

        foreach ($rooms as $room)
            $cinema->addRoom($room);

        $this->m->persist($cinema);

        return $cinema;
    }

    private function addRoom(string $name = "", int $rows = 1, int $cols = 1){
        $room = new Room();
        $room->setName($name);
        $room->setRows($rows);
        $room->setCols($cols);

        $centerRows = (int)($rows/2);
        $centerCols = $cols % 2 === 0 ? [$cols/2, ($cols/2)+1] : [(int)($cols/2), (int)($cols/2)+1, (int)($cols/2)+2];

        for ($r = 1; $r <= $rows; $r++){
            for ($c = 1; $c <= $cols; $c++){
                if (\in_array($c, $centerCols) && $r <= $centerRows)              $status = Seat::STATUS_HIDDEN;
                elseif (\in_array($c, [1, $cols]) && \in_array($r, [1, $rows]) )  $status = Seat::STATUS_DISABLED;
                else                                                              $status = Seat::STATUS_ACTIVE;
                $room->addSeat(
                    $this->addSeat($r, $c, $status)
                );
            }
        }
        $this->m->persist($room);

        return $room;
    }

    private function addSeat(int $row, int $col, string $status){
        $seat = new Seat();
        $seat->setRow($row);
        $seat->setCol($col);
        $seat->setStatus($status);

        $this->m->persist($seat);
        return $seat;
    }
}
