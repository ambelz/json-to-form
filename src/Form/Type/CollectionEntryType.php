<?php

namespace Ambelz\JsonToFormBundle\Form\Type;

use Ambelz\JsonToFormBundle\Service\FormGeneratorService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for collection entries
 *
 * This form type is used as entry_type for collections with multiple fields.
 * It receives the fields configuration via options and dynamically builds
 * the sub-fields using the FormGeneratorService.
 *
 * Each sub-field gets the same treatment as a normal question:
 * - Validation constraints
 * - Required/optional handling
 * - Error messages
 * - All field options
 */
class CollectionEntryType extends AbstractType
{
    public function __construct(
        private FormGeneratorService $formGeneratorService
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fields = $options['fields'] ?? [];
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($fields, $builder) {
            $rowData = $event->getData();
            $form = $event->getForm();
            foreach ($fields as $field) {
                $fieldKey = $field['key'] ?? null;
                $value = $fieldKey && is_array($rowData) && array_key_exists($fieldKey, $rowData) ? $rowData[$fieldKey] : null;

                [$fieldName, $type, $options] = $this->formGeneratorService->getFieldOptions($field, $value);
                $form->add($fieldName, $type, $options);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'fields' => [],
        ]);

        $resolver->setAllowedTypes('fields', 'array');
    }

    public function getBlockPrefix(): string
    {
        return 'json_collection_entry';
    }
}
