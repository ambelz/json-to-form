<?php

namespace Ambelz\JsonToFormBundle\Service;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;

/**
 * Symfony form generation service from JSON structures
 * 
 * This service allows dynamic creation of Symfony forms based on JSON configuration.
 * It supports nested sections, categories, and questions with various field types,
 * constraints, and conditional display logic.
 * 
 * @author Christophe Abillama <christophe.abillama@gmail.com>
 */
class FormGeneratorService
{
    /**
     * Constructor initializes the form generator with its required dependencies
     * 
     * @param QuestionConditionEvaluator $conditionEvaluator Used to evaluate conditional display logic
     * @param PropertyAccessorInterface $propertyAccessor Used to access properties in complex objects
     */
    public function __construct(
        private QuestionConditionEvaluator $conditionEvaluator, 
        private PropertyAccessorInterface $propertyAccessor
    ) {
    }
    
    /**
     * Dynamically builds a Symfony form from a JSON structure.
     * 
     * This method processes the entire form structure, organizing it into sections and categories.
     * It handles the hierarchical structure of the form and delegates the creation of individual
     * form fields to the addQuestion method.
     * 
     * The expected JSON structure should contain:
     * - slug: A unique identifier for the form
     * - sections: An array of form sections, each containing categories and questions
     * 
     * @param FormBuilderInterface $builder The form builder
     * @param array $structure The JSON structure of the form
     * @param array $formData Form data for dependencies and conditional logic
     * @return FormInterface The built form with all fields and validation rules
     * @throws \InvalidArgumentException If the structure is invalid
     */
    public function buildForm(FormBuilderInterface $builder, array $structure, array $formData = []): FormInterface
    {
        $dynBuilder = new DynamicFormBuilder($builder);

        if (!isset($structure['sections']) || !isset($structure['slug'])) {
            throw new \InvalidArgumentException("The JSON structure of the form must contain at least one section and a 'slug' field.");
        }

        $hasSubmitButton = false;

        foreach ($structure['sections'] as $section) {
            if (!isset($section['slug']) || !is_string($section['slug']) || empty(trim($section['slug']))) {
                throw new \InvalidArgumentException("Each section must have a 'slug' (non-empty string).");
            }
            $sectionKey      = $section['slug'];
            $sectionLabel    = $section['title'] ?? ucfirst($sectionKey);

            $sectionAttr = $structure['displayOptions']['sections']['attr'] ?? [];
            $sectionLabelAttr = $structure['displayOptions']['sections']['label_attr'] ?? [];
            
            // Section builder (inherit_data=true to avoid creating an additional data level)
            $sectionBuilder = $dynBuilder->create($sectionKey, FormType::class, [
                'label'        => $sectionLabel,
                'inherit_data' => true,
                'attr' => $sectionAttr,
                'label_attr' => $sectionLabelAttr,
            ]);

            foreach ($section['categories'] as $index => $category) {
                if (!isset($category['slug']) || !is_string($category['slug']) || empty(trim($category['slug']))) {
                    throw new \InvalidArgumentException(sprintf("Each category in section '%s' must have a 'slug' (non-empty string).", $sectionKey));
                }
                $categoryKey   = $category['slug'];
                $categoryLabel = $category['title'] ?? ucfirst($categoryKey);

                $categoryAttr = $structure['displayOptions']['categories']['attr'] ?? [];
                $categoryLabelAttr = $structure['displayOptions']['categories']['label_attr'] ?? [];
                
                // Category builder (inherit_data=true to avoid creating an additional data level)
                $categoryBuilder = $sectionBuilder->create($categoryKey, FormType::class, [
                    'label'        => $categoryLabel,
                    'inherit_data' => true,
                    'attr' => $categoryAttr,
                    'label_attr' => $categoryLabelAttr,
                ]);

                if (isset($category['questions']) && is_array($category['questions'])) {
                    foreach ($category['questions'] as $question) {
                        $formData[$question['key']] = $formData[$question['key']] ?? $question['data'] ?? null;
                        $this->addQuestion($categoryBuilder, $question, $formData);
                    }
                }
                
                $sectionBuilder->add($categoryBuilder);
            }

            // Automatically add a submit button at the end of the section if configured
            if (isset($section['submit']) && is_array($section['submit'])) {
                $submitLabel = $section['submit']['label'] ?? 'Send';
                
                $submitAttrs = ['class' => 'btn btn-primary'];
                if (isset($section['submit']['class'])) {
                    $submitAttrs['class'] = $section['submit']['class'];
                }
                if (isset($section['submit']['attr']) && is_array($section['submit']['attr'])) {
                    $submitAttrs = array_merge($submitAttrs, $section['submit']['attr']);
                }
                
                $sectionBuilder->add('submit', SubmitType::class, [
                    'label' => $submitLabel,
                    'attr' => $submitAttrs
                ]);
                
                $hasSubmitButton = true;
            }

            $dynBuilder->add($sectionBuilder);
        }

        // Add a default submit button at the end if no section has configured one
        if (!$hasSubmitButton) {
            $dynBuilder->add('submit', SubmitType::class, [
                'label' => 'Send',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
        }

        return $dynBuilder->getForm();
    }

    /**
     * Adds a question to the form while managing its dependencies
     * 
     * This method processes a single form field ("question") configuration and adds it to the form.
     * It handles:
     * - Field type mapping from simple strings to Symfony form types
     * - Field options (label, required, attr, etc.)
     * - Default values
     * - Choices for select/radio/checkbox fields
     * - Validation constraints
     * - Conditional display logic based on other field values
     * 
     * @param FormBuilderInterface $builder The form builder to add the field to
     * @param array $question The question configuration with key, type, and options
     * @param array $formData Form data for evaluating dependencies and conditions
     * @throws \InvalidArgumentException If the question configuration is invalid
     */
    public function addQuestion(FormBuilderInterface $builder, array $question, array $formData = []): void
    {
        if (!isset($question['key']) || !isset($question['type'])) {
            throw new \InvalidArgumentException("Each question must have at least a 'key' and a 'type'.");
        }

        $fieldName = $question['key'];
        unset($question['key']);
        $type      = $this->mapType($question['type']);
        unset($question['type']);
        
        $options = $question;
        // Ensure that basic options are defined
        $options['label']    = $question['label'] ?? ucfirst($fieldName);
        $options['required'] = $question['required'] ?? false;

        $value = $formData[$fieldName] ?? $question['data'] ?? null;

        // Adapt the value according to the field type
        switch ($type) {
            case IntegerType::class:
                $options['data'] = is_numeric($value) ? (int) $value : $value;
                break;
            case NumberType::class:
                $options['data'] = is_numeric($value) ? (float) $value : $value;
                break;
            case FileType::class:
                if ($value instanceof \Symfony\Component\HttpFoundation\File\File) {
                    $options['data'] = $value;
                    }
                    break;
                
            case DateTimeType::class:
            case DateType::class:
            case TimeType::class:
                $options['data'] = $value instanceof \DateTime ? $value : new \DateTime($value);
                break;
            default:
                $options['data'] = $value;
        }

        if (isset($question['constraints'])) {
            $constraints = $question['constraints'];
            $options['constraints'] = $this->buildConstraints($constraints);
        }

        $dependencies = $question['displayDependencies'] ?? [];
        unset($options['displayDependencies']);
        if (!empty($dependencies) && isset($dependencies['operator'], $dependencies['conditions'])) {
            if($this->conditionEvaluator->shouldDisplay($dependencies, $formData)){
                $builder->add($fieldName, $type, $options);
            }
        } else {
            $builder->add($fieldName, $type, $options);
        }
    }

    /**
     * Builds validation constraints from configuration
     * 
     * This method dynamically creates Symfony validation constraints from a configuration array.
     * It supports all standard Symfony constraints and allows passing options to them.
     * 
     * Example configuration:
     * ["NotBlank" => ["message" => "This field cannot be empty"], "Length" => ["min" => 5]]
     * 
     * @param array $constraintsConfig Array of constraint configurations
     * @return array Array of instantiated Symfony constraint objects
     * @throws \InvalidArgumentException If a constraint class doesn't exist or is invalid
     */
    private function buildConstraints(array $constraintsConfig): array
    {
        $constraints = [];

        foreach ($constraintsConfig as $constraintName => $options) {
            $fqcn = 'Symfony\\Component\\Validator\\Constraints\\' . $constraintName;

            if (!class_exists($fqcn)) {
                throw new \InvalidArgumentException("Constraint class $fqcn does not exist.");
            }

            if (!is_subclass_of($fqcn, Constraint::class)) {
                throw new \InvalidArgumentException("$fqcn is not a valid Symfony Constraint.");
            }
            
            // For constraints like Length, NotBlank, etc., we directly pass the options
            // without using OptionsResolver which can be too strict
            if (is_array($options)) {
                // We directly pass the associative array to the constructor
                $constraints[] = new $fqcn($options);
            } else {
                // For constraints without options
                $constraints[] = new $fqcn();
            }
        }

        return $constraints;
    }

    /**
     * Maps simple type strings to Symfony form type classes
     * 
     * This method converts user-friendly type names (like 'email', 'date') to their
     * corresponding Symfony form type class names. This allows the JSON configuration
     * to use simple strings instead of fully qualified class names.
     * 
     * @param string $type Simple type name to map
     * @return string Fully qualified class name of the Symfony form type
     */
    private function mapType(string $type): string
    {
        return match ($type) {
            'text'      => TextType::class,
            'email'     => EmailType::class,
            'number',
            'integer'   => IntegerType::class,
            'date'      => DateType::class,
            'datetime'  => DateTimeType::class,
            'time'      => TimeType::class,
            'url'       => UrlType::class,
            'tel'       => TelType::class,
            'search'    => SearchType::class,
            'password'  => PasswordType::class,
            'range'     => RangeType::class,
            'percent'   => PercentType::class,
            'money'     => MoneyType::class,
            'country'   => CountryType::class,
            'language'  => LanguageType::class,
            'locale'    => LocaleType::class,
            'currency'  => CurrencyType::class,
            'checkbox'  => CheckboxType::class,
            'radio'     => RadioType::class,
            'choice'    => ChoiceType::class,
            'file'      => FileType::class,
            'collection'=> CollectionType::class,
            default     => TextType::class,
        };
    }

    /**
     * Determines if an array is associative (has string keys) or sequential (has numeric keys)
     * 
     * This helper method is used to properly handle choices in select/radio/checkbox fields.
     * Associative arrays are treated as key-value pairs, while sequential arrays are treated
     * as having the same key and value.
     * 
     * @param array $array The array to check
     * @return bool True if the array is associative, false if it's sequential
     */
    private function isAssocArray(array $array): bool
    {
        return array_keys($array) !== range(0, \count($array) - 1);
    }
}
