<?php
/**
 * Controller global
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class AppController extends AbstractController
{

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

}