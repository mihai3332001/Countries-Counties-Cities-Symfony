<?php

namespace App\Form\EventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Country;
use App\Entity\County;
use GeoNames\Client as GeoNamesClient;

class DynamicFieldsSubscriber implements EventSubscriberInterface 
{
	public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA	=> 'preSetData',
            FormEvents::PRE_SUBMIT 		=> 'preSubmitData',
        ];
    }

     /**
     * Handling form fields before form renders.
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
    	$data = $event->getData();
        $form = $event->getForm();
        //dd($data);
        $country = null;
        if($data != null) {
            //$country = $data->getCountry();
            dd($data);
        }
        $this->addCountyData($form, $country); 
    }

     /**
     * Handling form fields before form renders.
     * @param FormEvent $event
     */
    public function preSubmitData(FormEvent $event)
    {
    	$location['country'] = $event->getData();
                //dd($location);
        if(array_key_exists('country', $location['country'])) { 
            //dd($country);
            $countryObj = new Country();
            $countryObj->setName($location['country']['country']);
            //dd($country);
            $this->addCountyData($event->getForm(), $countryObj);
        }  else {
            $country = null;
            $countyGeoId = $location['country']['county'];
            //dd($countyGeoId);
            $this->addCityData($event->getForm(), $countyGeoId);
        }           
    }

    /**
     * Method to Add State Field. (first dynamic field.)
     * @param FormInterface $form
     * @param type $county
     */

    private function addCountyData(FormInterface $form, Country $country = null) {
    	$countiesArray = [];
        if($country != null) {
            $geoNamesClient = new GeoNamesClient('djmichael');
            [$countryGeoNames] = $geoNamesClient->countryInfo([
                    'country' => $country->getName(),
            ]);
            $countryName = $countryGeoNames->geonameId;
            $countiesJson = $geoNamesClient->children(['geonameId' => $countryName]);
            foreach($countiesJson as $countyJson) {
                $countiesArray[$countyJson->toponymName] = $countyJson->geonameId;
            }
        } 
 
        $counties = null === $countiesArray ? [] : $countiesArray;        
        $form->add('county', ChoiceType::class, [
            'placeholder' => 'Choose an option', 
            'mapped'  => false,
            'attr' => [
                'class' => 'form-control'
            ],
            'choices' => $counties,
           // 'data'  => 'Tirana',
            'constraints' => [
                new NotBlank([
                    'message' => 'not null',
                ]),
            ],           
        ]);
    }

      /**
     * Method to Add State Field. (first dynamic field.)
     * @param FormInterface $form
     * @param type $city
     */

    private function addCityData(FormInterface $form, $county = null) {
    	$citiesArray = [];
        if($county != null) {      
            $geoNamesClient = new GeoNamesClient('djmichael');
            $citiesJson = $geoNamesClient->children(['geonameId' => $county]);
            foreach($citiesJson as $cityJson) {
                $citiesArray[$cityJson->toponymName] = $cityJson->toponymName;
            }
        }

        $cities = null === $citiesArray ? [] : $citiesArray;

        $form->add('city', ChoiceType::class, [
            'placeholder' => 'Choose an option', 
            'mapped'  => false,
            'attr' => [
                'class' => 'form-control'
            ],
            'choices' => $cities,
            'constraints' => [
                new NotBlank([
                    'message' => 'not null',
                ]),
            ],
        ]);
    }
}