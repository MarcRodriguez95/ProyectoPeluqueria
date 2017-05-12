<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\DescripcionImagen;
class DescripcionImagenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('mensaje')
            ->add('categoria')
            ->add('Guardar',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DescripcionImagen::class,
        ));
    }

    public function getName()
    {
        return 'app_bundle_descripcion_imagen';
    }
}
