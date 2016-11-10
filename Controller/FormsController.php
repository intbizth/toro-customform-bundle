<?php

namespace Toro\Bundle\CustomFormBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Toro\Bundle\CustomFormBundle\Form\Factory\FormsFactoryInterface;
use Toro\Bundle\CustomFormBundle\Manager\FormsManagerInterface;

class FormsController extends FOSRestController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $schemaAlias = $request->attributes->get('schema');

        $this->isGrantedOr403($schemaAlias);

        $forms = $this->getFormsManager()->load($schemaAlias);

        $view = $this
            ->view()
            ->setData($forms)
        ;

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request)
    {
        $schemaAlias = $request->attributes->get('schema');

        $this->isGrantedOr403($schemaAlias);

        $formsManager = $this->getFormsManager();
        $forms = $formsManager->load($schemaAlias);

        $isApiRequest = $this->isApiRequest($request);

        $form = $this
            ->getFormsFormFactory()
            ->create($schemaAlias, $forms, $isApiRequest ? ['csrf_protection' => false] : [])
        ;

        if ($form->handleRequest($request)->isValid()) {
            $messageType = 'success';
            try {
                $formsManager->save($forms);
                $message = $this->getTranslator()->trans('toro.forms.update', [], 'flashes');
            } catch (ValidatorException $exception) {
                $message = $this->getTranslator()->trans($exception->getMessage(), [], 'validators');
                $messageType = 'error';
            }

            if ($isApiRequest) {
                return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
            }

            $request->getSession()->getBag('flashes')->add($messageType, $message);

            if ($request->headers->has('referer')) {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render($request->attributes->get('template', 'ToroCustomFormBundle:Forms:update.html.twig'), [
            'forms' => $forms,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return FormsManagerInterface
     */
    protected function getFormsManager()
    {
        return $this->get('toro.forms_manager');
    }

    /**
     * @return FormsFactoryInterface
     */
    protected function getFormsFormFactory()
    {
        return $this->get('toro.form_factory.forms');
    }

    /**
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * Check that user can change given schema.
     *
     * @param string $schemaAlias
     *
     * @return bool
     */
    protected function isGrantedOr403($schemaAlias)
    {
        return true;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isApiRequest(Request $request)
    {
        return 'html' !== $request->getRequestFormat();
    }
}
