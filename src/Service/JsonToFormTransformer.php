<?php

namespace Ambelz\JsonToFormBundle\Service;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Ambelz\JsonToFormBundle\Service\FormGeneratorService;

/**
 * Main service to transform a JSON structure into a Symfony form
 *
 * Simple usage:
 * $form = $this->jsonToForm->transform($structure, $data, $builder);
 *
 * @author Christophe Abillama <christophe.abillama@gmail.com>
 */
class JsonToFormTransformer
{
    public function __construct(
        private FormGeneratorService $formGenerator,
    ) {
    }

    /**
     * Transforms a JSON structure into a Symfony form
     *
     * @param array $structure JSON structure of the form
     * @param array $data Initial data
     * @param FormBuilderInterface $builder Existing builder
     * @return FormInterface The generated form
     */
    public function transform(array $structure, array $data, FormBuilderInterface $builder): FormInterface
    {
        $builder->setData($data);

        return $this->formGenerator->buildForm($builder, $structure, $data);
    }
}
