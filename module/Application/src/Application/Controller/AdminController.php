<?php

namespace Application\Controller {
    
    use Application\API\Canonicals\General\Constants;
    use JMS\Serializer\SerializationContext;
    
    class AdminController extends BaseController  {
        
        public function qurbaniAction() {
            $this->getServiceLocator()->get('Navigation')->findOneById(Constants::HOME_PAGE_NAVIGATION_ID)->setActive(true);
            
            $qurbaniRepo = $this->getServiceLocator()->get('QurbaniRepo');

            $context = new SerializationContext();
            $context->setSerializeNull(true);
            
            return array('model' => $this->serializer->serialize(array(
                'qurbani'         => $qurbaniRepo->search(0, PHP_INT_MAX, true),
                'qurbanidetails'  => $qurbaniRepo->getQurbaniDetails(),
                'purchasedSheep'  => $qurbaniRepo->getPurchasedSheep(),
                'purchasedCows'   => $qurbaniRepo->getPurchasedCows(),
                'purchasedCamels' => $qurbaniRepo->getPurchasedCamels()
            ), 'json', $context));
        }
    }
}

