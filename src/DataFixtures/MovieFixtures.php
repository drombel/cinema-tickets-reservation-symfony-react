<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\MovieCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    private ?ObjectManager $m;

    public function load(ObjectManager $manager)
    {
        $this->m = $manager;

        /** Movie Categories **/
        $comedy = $this->addCategory("Comedy", "comedy.jpg");
        $drama = $this->addCategory("Drama", "drama.jpg");
        $action = $this->addCategory("Action", "action.jpg");
        $animated = $this->addCategory("Animated", "animated.jpg");
        $scifi = $this->addCategory("Sci-fi", "sci-fi.jpg");

        /** Movies **/
        $this->addMovie(
            "Wall-E", 98, new \DateTime("2008-07-18"), "walle.jpg",
            "In a distant, but not so unrealistic, future where mankind has abandoned earth because it has become covered with trash from products sold by the powerful multi-national Buy N Large corporation, WALL-E, a garbage collecting robot has been left to clean up the mess. Mesmerized with trinkets of Earth's history and show tunes, WALL-E is alone on Earth except for a sprightly pet cockroach. One day, EVE, a sleek (and dangerous) reconnaissance robot, is sent to Earth to find proof that life is once again sustainable. WALL-E falls in love with EVE. WALL-E rescues EVE from a dust storm and shows her a living plant he found amongst the rubble. Consistent with her 'directive', EVE takes the plant and automatically enters a deactivated state except for a blinking green beacon. WALL-E, doesn't understand what has happened to his new friend, but, true to his love, he protects her from wind, rain, and lightning, even as she is unresponsive. One day a massive ship comes to reclaim EVE, but WALL-E, out of love or loneliness, hitches a ride on the outside of the ship to rescue EVE. The ship arrives back at a large space cruise ship, which is carrying all of the humans who evacuated Earth 700 years earlier. The people of Earth ride around this space resort on hovering chairs which give them a constant feed of TV and video chatting. They drink all of their meals through a straw out of laziness and/or bone loss, and are all so fat that they can barely move. When the auto-pilot computer, acting on hastily-given instructions sent many centuries before, tries to prevent the people of Earth from returning by stealing the plant, WALL-E, EVE, the portly captain, and a band of broken robots stage a mutiny.",
            [$comedy, $animated, $scifi]
        );
        $this->addMovie(
            "Django", 165, new \DateTime("2013-01-18"), "django.jpg",
            "In 1858, in Texas, the former German dentist Dr. King Schultz meets the slave Django in a lonely road while being transported by the slavers Speck Brothers. He asks if Django knows the Brittle Brothers and with the affirmative, he buys Django for him. Then Dr. Schultz tells that he is a bounty hunter chasing John, Ellis and Roger Brittle and proposes a deal to Django: if he helps him, he would give his freedom, a horse and US$ 75.00 for him. Django accepts the deal and Dr. Schultz trains him to be his deputy. They kill the brothers in Daughtray and Django tells that he would use the money to buy the freedom of his wife Broomhilda, who is a slave that speaks German. Dr. Schultz proposes another deal to Django: if he teams-up with him during the winter, he would give one-third of the rewards and help him to rescue Broomhilda. Django accepts his new deal and they become friends. After the winter, Dr. Schultz goes to Gatlinburgh and learns that Broomhilda was sold to the ruthless Calvin Candie von Shaft, who lives in the Candyland Farm, in Mississippi. Dr. Schultz plots a scheme with Django to lure Calvin and rescue Broomhilda from him. But his cruel minion Stephen is not easily fooled.",
            [$action, $comedy, $drama]
        );
        $this->addMovie(
            "The Wolf of Wall Street", 180, new \DateTime("2014-01-03"), "the-wolf-of-wall-street.jpg",
            "In the early 1990s, Jordan Belfort teamed with his partner Donny Azoff and started brokerage firm Stratford-Oakmont. Their company quickly grows from a staff of 20 to a staff of more than 250 and their status in the trading community and Wall Street grows exponentially. So much that companies file their initial public offerings through them. As their status grows, so do the amount of substances they abuse, and so do their lies. They draw attention like no other, throwing lavish parties for their staff when they hit the jackpot on high trades. That ultimately leads to Belfort featured on the cover of Forbes Magazine, being called 'The Wolf Of Wall St.'. With the FBI onto Belfort's trading schemes, he devises new ways to cover his tracks and watch his fortune grow. Belfort ultimately comes up with a scheme to stash their cash in a European bank. But with the FBI watching him like a hawk, how long will Belfort and Azoff be able to maintain their elaborate wealth and luxurious lifestyles?",
            [$action, $comedy, $drama]
        );
        $this->addMovie(
            "Back to the Future", 116, new \DateTime("1985-06-03"), "back-to-the-future.jpg",
            "Marty McFly's life is a dump. His father, George, is constantly bullied by his supervisor Biff Tannen and his mother, Lorraine, is an overweight alcoholic. One day, Marty gets a call from his scientist friend Dr. 'Doc' Emmet Brown telling Marty to meet him at Twin Pines Mall at 1:15 AM where Doc unveils a time machine that runs off of plutonium built into a DeLorean and demonstrates it to Marty. Marty accidentally activates the time machine, sending him back to 1955 where he accidentally gets in the way of his teenage parents meeting. Marty must find a way to convince Doc that he is from the future, reunite his parents, and ultimately get back to the future.",
            [$comedy, $action, $scifi]
        );
        $this->addMovie(
            "Forrest Gump", 148, new \DateTime("1994-11-04"), "forrest-gump.jpg",
            "The movie Forrest Gump follows the life events of a man who shares the name as the title of the film. Gump faces many tribulations throughout his life, but he never lets any of them interfere with his happiness. From wearing braces on his legs, to having a below average IQ and even being shot, Gump continues to believe that good things will happen and goes after his dreams. While several less than ideal things occur during Gump's life, he manages to turn each setback into something good for him, such as when he finally gets his braces off he discovers that he is capable of running faster than most other people. This skill allows Gump to not only escape his bullies while he is a child in Greenbow, but also to gain a football scholarship, save many soldiers' lives and become famous for his ability. While Gump eventually achieves the majority of the things he hoped to throughout the movie, it proved a much more difficult task to win the heart of his life-long friend Jenny Curran. The movie is centered on Forrest Gump and the incidences that occur during his life, but during each period in his lifetime he thinks back of Jenny and how important she is to him. Although the two characters grew up together and shared a very close friendship, as the movie progresses they grow apart. This upsets Gump who cares immensely for the girl who had a rough start in life, and it seems the two always end up back in each other's lives, often in extraordinary ways like meeting in the Reflection Pond in D.C. Even though Gump is the main character of the film, it similarly tells the story of Curran and the hardships she faces.",
            [$drama, $comedy]
        );
        $this->addMovie(
            "Monsters, Inc.", 92, new \DateTime("2002-01-01"), "monsters-inc.jpg",
            "A city of monsters with no humans called Monstropolis centers around the city's power company, Monsters, Inc. The lovable, confident, tough, furry blue behemoth-like giant monster named James P. Sullivan (better known as Sulley) and his wisecracking best friend, short, green cyclops monster Mike Wazowski, discover what happens when the real world interacts with theirs in the form of a 2-year-old baby girl dubbed 'Boo,' who accidentally sneaks into the monster world with Sulley one night. And now it's up to Sulley and Mike to send Boo back in her door before anybody finds out, especially two evil villains such as Sulley's main rival as a scarer, chameleon-like Randall (a monster that Boo is very afraid of), who possesses the ability to change the color of his skin, and Mike and Sulley's boss Mr. Waternoose, the chairman and chief executive officer of Monsters, Inc.",
            [$comedy, $animated, $scifi]
        );
        $this->addMovie(
            "The Godfather", 175, new \DateTime("1972-12-31"), "god-father.jpg",
            "The story begins as 'Don' Vito Corleone, the head of a New York Mafia 'family', oversees his daughter's wedding with his wife Carmela. His beloved son Michael has just come home from the war, but does not intend to become part of his father's business. Through Michael's life the nature of the family business becomes clear. The business of the family is just like the head of the family, kind and benevolent to those who give respect, but given to ruthless violence whenever anything stands against the good of the family. Don Vito lives his life in the way of the old country, but times are changing and some don't want to follow the old ways and look out for community and 'family'. An up and coming rival of the Corleone family wants to start selling drugs in New York, and needs the Don's influence to further his plan. The clash of the Don's fading old world values and the new ways will demand a terrible price, especially from Michael, all for the sake of the family.",
            [$drama, $action]
        );

        $this->m->flush();
    }

    private function addMovie(string $title = "", int $length = 0, ?\DateTimeInterface $date = null, string $poster = "", string $desc = "", array $cats = []){
        // TODO: add screen images
        $movie = new Movie();
        $movie->setTitle($title);
        $movie->setLength($length);
        $movie->setPremiereDate($date);
        $movie->setPoster($poster);
        $movie->setDescription($desc);

        foreach ($cats as $cat)
            $movie->addCategory($cat);

        $this->m->persist($movie);

        return $movie;
    }

    private function addCategory(string $name = "", string $image = ""){
        $cat = new MovieCategory();
        $cat->setName($name);
        $cat->setImage($image);

        $this->m->persist($cat);

        return $cat;
    }

}
