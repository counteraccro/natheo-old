<?php

namespace App\Form\Media;

use App\Entity\Media\Folder;
use App\Form\AppType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FolderType extends AppType
{
    private $tabRef = [];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('parent', EntityType::class, [
                'label' => $this->translator->trans('admin_media#Liste des dossiers disponible'),
                'help' => $this->translator->trans('admin_media#Selectionner le dossier parent'),
                'query_builder' => function (EntityRepository $er) {

                    $sql = "WITH recursive cte (id, name, parent) AS (
                                SELECT id,
                                 name,
                                 parent
                                FROM cms_folder
                                UNION ALL
                                SELECT p.id,
                                 p.name,
                                 p.parent
                                FROM cms_folder p
                                INNER JOIN cte ON p.parent = cte.id
                            )
                            SELECT id, name, parent
                            FROM cte
                            GROUP BY name";

                    $rsm = new ResultSetMapping();
                    $connection = $er->createQueryBuilder('f')->getEntityManager()->getConnection();
                    $stmt = $connection->prepare($sql);
                    $this->tabRef = $results = $stmt->executeQuery()->fetchAllAssociative();

                    $ids = array_map(function ($row) {
                        return $row['id'];
                    }, $results);

                    return $er->createQueryBuilder('f')
                        ->where('f.id IN (:ids)')
                        ->setParameter('ids', $ids);
                },
                'label_html' => true,
                'class' => Folder::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => 'Root',
                'choice_label' => function (Folder $folder) {

                  $nb = count($this->generatePath($folder, []));
                  $before = str_repeat('-', $nb);
                  return $before . ' ' . $folder->getName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Folder::class,
        ]);
    }

    private function generatePath(Folder $folder, array $tab): array
    {
        $tab[] = ['name' => $folder->getName(), 'id' => $folder->getId()];
        if ($folder->getParent() != null) {
            return $this->generatePath($folder->getParent(), $tab);
        } else {
            return $tab;
        }
    }
}
