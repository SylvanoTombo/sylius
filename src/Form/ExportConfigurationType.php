<?php

namespace App\Form;

use App\Entity\ExportConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportConfigurationType extends AbstractType
{
    const ENTITIES = [
        'product' => 'Product',
        'order' => 'Order'
    ];
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ExportConfigurationType constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach (self::ENTITIES as $key => $value)
        {
            $entity = 'App\\Entity\\'. $value . '\\' . $value;
            $builder->add($key, ChoiceType::class, [
                'mapped' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => array_flip($this->getFieldsFromEntity($entity))
            ]);
        }
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
           $configuration = $event->getData();
           $fields = json_decode($configuration->getEnableFields(), true);

           if (!$configuration->getId()) return;

           foreach ($fields as $field => $value)
           {
               $entity = 'App\\Entity\\'. self::ENTITIES[$field] . '\\' . self::ENTITIES[$field];
               $event->getForm()->add($field, ChoiceType::class, [
                    'mapped' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => array_flip($this->getFieldsFromEntity($entity)),
                    'data' => $value
                ]);
           }


        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)
        {
            $entity = $event->getData();
            $form = $event->getForm();
            $keys = array_keys(self::ENTITIES);
            $configuration = [];

            foreach ($keys as $key) {
                $configuration[$key] = $form->get($key)->getData();
            }

            $entity->setEntity('Test');
            $entity->setEnableFields(json_encode($configuration));

            $event->setData($entity);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExportConfiguration::class,
        ]);
    }

    private function getFieldsFromEntity(string $entity)
    {
        $entityMetadata = $this->entityManager->getClassMetadata($entity);

        return $entityMetadata->fieldNames;
    }
}
