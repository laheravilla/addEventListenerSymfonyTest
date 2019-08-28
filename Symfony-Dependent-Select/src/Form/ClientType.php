<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Continent;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('continent', EntityType::class, [
                'class' => Continent::class,
                'placeholder' => 'Select your continent',
                'mapped' => false,
            ]);

        $builder->get('continent')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                $form->getParent()->add('country', EntityType::class, [
                    'class' => Country::class,
                    'placeholder' => 'Select your country',
                    'choices' => $form->getData()->getCountries()
                ]);

            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $country = $data->getCountry();

                if ($country) {
                    $form->get('continent')->setData($country->getContinent());

                    $form->add('country', EntityType::class, [
                        'class' => Country::class,
                        'placeholder' => 'Select your country',
                        'choices' => $country->getContinent()->getCountries()
                    ]);
                } else {
                    $form->add('country', EntityType::class, [
                        'class' => Country::class,
                        'placeholder' => 'Select your country',
                        'choices' => []
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
