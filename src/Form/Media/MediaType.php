<?php

namespace App\Form\Media;

use App\Entity\Media\Folder;
use App\Entity\Media\Media;
use App\Form\AppType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('folder', EntityType::class, [
                'label' => $this->translator->trans('admin_media#Dossier parent'),
                'help' => $this->translator->trans('admin_media#Selectionner le dossier parent ou sera rangé le media'),
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
                            GROUP BY name
                            ORDER BY parent";

                    $connection = $er->createQueryBuilder('f')->getEntityManager()->getConnection();
                    $stmt = $connection->prepare($sql);
                    $results = $stmt->executeQuery()->fetchAllAssociative();

                    $ids = array_map(function ($row) {
                        return $row['id'];
                    }, $results);

                    return $er->createQueryBuilder('f')
                        ->where('f.id IN (:ids)')
                        ->setParameter('ids', $ids)
                        ->orderBy('f.parent', 'ASC');
                },
                'label_html' => true,
                'class' => Folder::class,
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'choice_label' => function (Folder $folder) {

                    $path = array_reverse($this->generatePath($folder, []));

                    $before = 'Root / ';

                    unset($path[array_key_last($path)]);

                    foreach ($path as $element) {
                        $before .= $element['name'] . ' / ';
                    }

                    //$before = str_repeat('-', $nb);
                    return $before . $folder->getName();
                },
            ])
            ->add('disabled')
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_media#Valider')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }

    /**
     * Génère le path d'un dossier
     * @param Folder $folder
     * @param array $tab
     * @return array
     */
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
