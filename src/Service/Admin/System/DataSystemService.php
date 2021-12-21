<?php
/**
 * Gère les données système de l'application
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */

namespace App\Service\Admin\System;

use App\Entity\Admin\DataSystem;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DataSystemService extends AppService
{
    const ADDITION = '+';
    const SOUSTRACTION = '-';
    const REMPLACE = 'r';

    /**
     * Option qui permet de savoir le nombre de traductions à régénérées
     * SI > 0 alors affichage d'un message pour indiquer que les traductions doivent être mise à jour
     */
    const DATA_SYSTEM_TRANSLATION_GENERATE = "TRANSLATION_GENERATE";

    /**
     * Permet de mettre à jour une donnée system via sa clé
     * Si la donnée n'existe pas elle est créée
     * @param string $key
     * @param string $value
     * @param string $action // Permet de définir si on ajoute / soustrait ou remplace la valeur
     */
    public function update(string $key, string $value, string $action = self::ADDITION)
    {

        $dataSystem = $this->getDataSystem($key);
        $tmpval = intval($dataSystem->getValue());

        switch ($action) {
            case self::ADDITION :

                $dataSystem->setValue(($tmpval + 1));
                break;
            case self::SOUSTRACTION :
                $dataSystem->setValue(($tmpval - 1));
                break;
            case self::REMPLACE :
                $dataSystem->setValue($value);
        }


        $this->doctrine->getManager()->persist($dataSystem);
        $this->doctrine->getManager()->flush();
    }

    /**
     * permet de récupérer une dataSystem, si elle n'existe pas, elle est créée
     * @param string $key
     * @param string $value
     * @return DataSystem
     */
    public function getDataSystem(string $key, string $value = ""): DataSystem
    {
        $dataSystemRepo = $this->doctrine->getRepository(DataSystem::class);
        $dataSystem = $dataSystemRepo->findOneBy(['name' => $key]);

        if ($dataSystem == null) {
            $dataSystem = $this->createDataSystem($key, $value);
        }
        return $dataSystem;
    }

    /**
     * Permet de créer une dataSystem
     * @param string $key
     * @param string $value
     * @return DataSystem
     */
    private function createDataSystem(string $key, string $value): DataSystem
    {
        $dataSystem = new DataSystem();
        $dataSystem->setName($key);
        $dataSystem->setValue($value);
        $this->doctrine->getManager()->persist($dataSystem);
        $this->doctrine->getManager()->flush();
        return $dataSystem;
    }

    /**
     * Permet de vider le cache applicatif
     * @return void
     * @throws \Exception
     */
    public function clearCacheInterne()
    {
        // On vide le cache applicatif
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',
            '--no-warmup' => true,
        ]);
        $output = new NullOutput();
        $application->run($input, $output);
    }

}