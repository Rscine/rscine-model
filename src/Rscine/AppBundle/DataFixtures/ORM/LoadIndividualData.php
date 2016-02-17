<?php

namespace Rscine\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rscine\AppBundle\Entity\Individual;
use Rscine\AppBundle\Entity\ContactInformations;
use Rscine\AppBundle\Entity\Phone;
use Rscine\AppBundle\Entity\Email;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadIndividualData implements FixtureInterface, OrderedFixtureInterface {

    private $individuals = array(
        array(
            'name' => 'cormag',
            'district' => 33,
            'company' => 'Rausten',
            'domain' => 'Sword'
        ),
        array(
            'name' => 'neimi',
            'district' => 34,
            'company' => 'Rausten',
            'domain' => 'Sword'
        ),
        array(
            'name' => 'colm',
            'district' => 48,
            'company' => 'Rausten',
            'domain' => 'Spear'
        ),
        array(
            'name' => 'gerrik',
            'district' => 64,
            'company' => 'Frelia',
            'domain' => 'Axe'
        ),
        array(
            'name' => 'eirika',
            'district' => 33,
            'company' => 'Renais',
            'domain' => 'Spear'
        ),
        array(
            'name' => 'ephraim',
            'district' => 34,
            'company' => 'Renais',
            'domain' => 'Bow'
        ),
        array(
            'name' => 'duessel',
            'district' => 48,
            'company' => 'Frelia',
            'domain' => 'Bow'
        ),
        array(
            'name' => 'frantz',
            'district' => 64,
            'company' => 'Frelia',
            'domain' => 'Axe'
        )
    );

    /**
     *
     */
    public function load(ObjectManager $manager)
    {

        foreach ($this->individuals as $individualItem) {
            $individual = new Individual();
            $individual->setUsername(ucfirst($individualItem['name']));
            $individual->setPlainPassword($individualItem['name']);
            $individual->setLogin($individualItem['name']);
            $individual->setEmail($individualItem['name'].'@gmail.com');

            // Domain binding
            $domain = $manager->getRepository('RscineAppBundle:Domain')->findOneByName($individualItem['domain']);

            if ($domain)
                $individual->setDomain($domain);


            // District binding
            $district = $manager->getRepository('RscineAppBundle:District')->findOneByNumber($individualItem['district']);

            if ($district)
                $individual->setDistrict($district);

            // Company binding
            $company = $manager->getRepository('RscineAppBundle:Company')->findOneByName($individualItem['company']);

            if ($company)
                $individual->setCompany($company);

            // Contact informations binding
            $contactInformations = new ContactInformations();

            for ($i=0; $i < 4; $i++) { 

                $phoneNumber = $this->generateRandomPhoneNumber();

                $phone = new Phone();

                $phone->setNumber($phoneNumber);
                $phone->setType('mobile');

                $contactInformations->addPhone($phone);

            }

            $email = new Email();

            $email->setEmail($individualItem['name'].'@'.strtolower($individual->getCompany()->getName()).'.com');
            $email->setType('office');

            $contactInformations->addEmail($email);
            $individual->setContactInformations($contactInformations);

            $manager->persist($individual);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }

    /**
     * Génère un numéro de téléphone à 10 chiffres
     * @param  [type] $digits [description]
     * @return [type]         [description]
     */
    private function generateRandomPhoneNumber($digits = 9)
    {
        return '0'.str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
    }

}
