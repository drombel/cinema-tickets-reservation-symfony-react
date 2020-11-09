symfony server:start
// https
symfony server:ca:install 
// compile Webpack assets using Symfony Encore ... but do that in the background to not block the terminal
symfony run -d yarn encore dev --watch
symfony server:log
symfony server:stop


//yarn add --dev @symfony/webpack-encore
//yarn add webpack-notifier --dev
yarn encore dev --watch

// used commands
php bin/console d:s:u --force
php bin/console make:entity
php bin/console make:entity --regenerate

How to add admin crud?
  * php bin/console make:admin:crud

How to test?

  * Write test cases in the tests/ folder
  * Run php bin/phpunit



  You would need the following: @Route("/{reactRouting}", name="app_home", requirements={"reactRouting"="^(?!api).+"}, defaults={"reactRouting": null})

Note: api in the requirements regex, that would exclude your /api/ endpoints




Mock It Up!
For this tutorial we need some data so we can play around with our CRUD. Enter Faker! With Faker you can add a variety of random information to your database (phone number, emails, first names, last names etc …). For using fixtures in Symfony run this command and it will generate DataFixtures folder:
composer require --dev orm-fixtures
Inside you will see AppFixtures.php file that extends Fixtures. We will simply add Faker and a small For loop for adding data to our database like this:
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName);
            $customer->setLastName($faker->lastName);
            $customer->setEmail($faker->email);
            $customer->setPhoneNumber($faker->phoneNumber);
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
This will then populate our database with 50 records of random customers:
bin/console doctrine:fixtures:load

