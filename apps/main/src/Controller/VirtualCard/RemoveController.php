<?php

namespace VirtualCard\Controller\VirtualCard;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Throwable;
use VirtualCard\Exception\VirtualCard\ExpiredVirtualCardException;
use VirtualCard\Exception\VirtualCard\VirtualCardNotFoundException;
use VirtualCard\Form\RemoveVirtualCardType;
use VirtualCard\Service\VirtualCard\Remove\VirtualCardRemoveWrapper;
use VirtualCard\Traits\LoggerTrait;

class RemoveController extends AbstractFOSRestController
{
    use LoggerTrait;
    
    /**
     * Delete virtual card with given id
     *
     * @Annotations\Delete("/remove")
     *
     * @SWG\Tag(name="Virtual Card API")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="reference", type="string", description="Given reference of virtual card"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Operation succeeded. Only successful remove operation will return 200 status code otherwise is below",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="status", type="integer")
     *          }
     *      )
     * )
     *
     * @SWG\Response(
     *     response="400",
     *     description="The data that you send is not valid",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="message", type="string")
     *          }
     *     )
     * )
     *
     * @SWG\Response(
     *     response="404",
     *     description="There is no matching virtual card with given reference",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="message", type="string")
     *          }
     *     )
     * )
     *
     * @SWG\Response(
     *     response="406",
     *     description="The virtual card that you try to remove is expired",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="message", type="string")
     *          }
     *     )
     * )
     *
     * @SWG\Response(
     *     response="503",
     *     description="A problem(s) occurred",
     *     @SWG\Schema(
     *          type="object",
     *          properties={
     *              @SWG\Property(property="message", type="string")
     *          }
     *     )
     * )
     *
     * @param Request $request
     * @param VirtualCardRemoveWrapper $virtualCardRemoveWrapper
     * @param FormFactoryInterface $formFactory
     * @return Response
     */
    public function indexAction(Request $request, VirtualCardRemoveWrapper $virtualCardRemoveWrapper, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory->create(RemoveVirtualCardType::class);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() === true && $form->isValid() === true) {
            try {
                $virtualCard = $virtualCardRemoveWrapper->check($form->get('reference')->getData());
                $result = $virtualCardRemoveWrapper->remove($virtualCard);
            
                $view = $this->view($result, 200);
            
                return $this->handleView($view);
            } catch (VirtualCardNotFoundException $e) {
                $view = $this->view([
                    'message' => $e->getMessage()
                ], 404);
    
                return $this->handleView($view);
            } catch (ExpiredVirtualCardException $e) {
                $view = $this->view([
                    'message' => $e->getMessage()
                ], 406);
    
                return $this->handleView($view);
            } catch (Throwable $e) {
                $this->logger->alert($e);
            
                throw new ServiceUnavailableHttpException(null, 'Service is currently unavailable please try again later.');
            }
        }
    
        throw new BadRequestHttpException('Your data that you sent is not valid.');
    }
}
