<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\DescripcionImagen;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DescripcionImagenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('titulo')
            ->add('mensaje')
            ->add('categoria')
            ->add('imageFile', VichImageType::class, [
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ])
            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])
            ->add('Guardar',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DescripcionImagen',
        ));
    }

    public function getName()
    {
        return 'app_bundle_descripcion_imagen';
    }
}
