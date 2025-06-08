# JSON to Form Bundle

**Author:** Christophe Abillama <christophe.abillama@gmail.com>

Symfony bundle to transform JSON structures into dynamic forms with Live Components.

## Installation

```bash
composer require ambelz/json-to-form
```

Add the bundle in `config/bundles.php`:

```php
return [
    // ...
    Ambelz\JsonToFormBundle\JsonFormBundle::class => ['all' => true],
];
```

## Quick Start

For quick usage, you only need **3 elements**:

### 1. 📋 A JSON structure that follows the format

> **Documentation:** For detailed structure documentation and examples, see [STRUCTURE.md](STRUCTURE.md)

```json
{
    "slug": "my-simple-form",
    "sections": [
        {
            "slug": "basic-info",
            "title": "Basic Information",
            "categories": [
                {
                    "slug": "contact",
                    "title": "Contact",
                    "questions": [
                        {
                            "key": "name",
                            "type": "text",
                            "label": "Your Name",
                            "required": true
                        }
                    ]
                }
            ]
            // Submit button will be default if not specified
            // See STRUCTURE.md for submit button configuration
        }
    ]
}
```

### 2. 🎛️ A Symfony controller that uses the service

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Ambelz\JsonToFormBundle\Service\JsonToFormTransformer;

class MyController extends AbstractController
{
    public function __construct(
        private JsonToFormTransformer $jsonToFormTransformer
    ) {}
    
    #[Route('/my-form', name: 'my_form')]
    public function index(Request $request): Response
    {
        // Your JSON structure (from file, database, etc.)
        $structure = [
            'slug' => 'my-form',
            'sections' => [
                [
                    'slug' => 'information',
                    'title' => 'Personal Information',
                    'categories' => [
                        [
                            'slug' => 'identity',
                            'title' => 'Identity',
                            'questions' => [
                                [
                                    'key' => 'name',
                                    'type' => 'text',
                                    'label' => 'Name',
                                    'required' => true
                                ],
                                [
                                    'key' => 'email',
                                    'type' => 'email',
                                    'label' => 'Email',
                                    'required' => true
                                ]
                            ]
                        ]
                    ],
                    'submit' => [
                        'label' => 'Save Information',
                        'class' => 'btn btn-success w-100 mt-3'
                    ]
                ]
            ]
        ];

        // Transform JSON to Symfony Form
        $form = $this->jsonToFormTransformer->transform($structure);
        $form->handleRequest($request);
        
        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Get form data as key-value array
            $data = $form->getData();
            
            // $data will contain: ['name' => 'John Doe', 'email' => 'john@example.com']
            // Process the data (save to database, send email, etc.)
            
            // Add success message
            $this->addFlash('success', 'Form submitted successfully!');
            
            // Redirect to avoid resubmission
            return $this->redirectToRoute('my_form');
        }

        return $this->render('my_form.html.twig', [
            'form' => $form,
        ]);
    }
}
```

### 3. 🎨 Simple rendering with `{{ form(form) }}`

```twig
{# templates/my_form.html.twig #}
{% extends 'base.html.twig' %}

{% block title %}My JSON Form{% endblock %}

{% block body %}
    <div class="container">
        <h1>My JSON Form</h1>
        
        {{ form(form) }}
    </div>
{% endblock %}
```

## That's it! 🎉

With these 3 elements, you have a functional dynamic form generated from a JSON structure.

> **💡 Note:** You can configure submit buttons directly in your JSON structure at the section level with custom labels and CSS classes. If no submit is configured, the bundle automatically adds a default "Submit" button at the end of the form. Manage translations in your own application for maximum flexibility!

## Form Data Structure

When the form is submitted and valid, `$form->getData()` returns an associative array where:
- **Keys** are the `key` values from your JSON questions
- **Values** are the user-submitted values

**Example:**
```php
// JSON structure has questions with keys: 'name', 'email', 'age'
$data = $form->getData();

// $data will contain:
[
    'name' => 'John Doe',
    'email' => 'john@example.com', 
    'age' => 30
]
```

**Data processing:**
```php
if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();
    
    // Access individual values
    $userName = $data['name'];
    $userEmail = $data['email'];
    
    // Save to database, send emails, etc.
    $user = new User();
    $user->setName($userName);
    $user->setEmail($userEmail);
    $entityManager->persist($user);
    $entityManager->flush();
}
```

## Form Structure Documentation

For detailed documentation on form structure, field types, options, constraints, and examples, please refer to the [STRUCTURE.md](STRUCTURE.md) file.

### Key Concepts

- The bundle supports all standard Symfony form field types
- Field options in JSON directly correspond to Symfony form options
- Validation constraints can be defined using Symfony's constraint system
- Display dependencies allow for dynamic form fields based on previous answers

## Field Options

The options defined in the JSON directly correspond to the options of Symfony form field types. You can use any option supported by the corresponding field type.

**Example with advanced options:**

```json
{
    "key": "email",
    "type": "email",
    "label": "Email address",
    "required": true,
    "help": "We'll use this address to contact you",
    "placeholder": "example@domain.com",
    "attr": {
        "class": "custom-input",
        "data-validate": "true"
    },
    "constraints": {
        "Email": {
            "message": "This email address is not valid"
        },
        "Length": {
            "min": 5,
            "max": 180,
            "minMessage": "Email must contain at least {{ limit }} characters",
            "maxMessage": "Email cannot exceed {{ limit }} characters"
        }
    }
}
```

**Important:**
- The `key` and `type` keys are specific to the bundle and are not Symfony options
- The `key` defines the field name in the form
- The `type` defines the field type to use
- All other keys are passed directly as options to the Symfony field type

## Submit Button Configuration

You can configure submit buttons at the section level in your JSON. This allows for custom labels, CSS classes, and other HTML attributes. If no submit button is configured in any section, a default "Submit" button is added at the end of the form.

> **Documentation:** For detailed configuration options and default behavior, see the [Section Structure](STRUCTURE.md#📦-section-structure) documentation in `STRUCTURE.md`.

## Conditional Display Dependencies

The bundle allows you to show or hide form fields based on answers to previous questions using the `displayDependencies` property. You can define complex conditions using logical and comparison operators.

> **Documentation:** For a detailed explanation, examples, and a full list of supported operators, see the [Conditional Display Dependencies (displayDependencies)](STRUCTURE.md#conditional-display-dependencies-displaydependencies) documentation in `STRUCTURE.md`.

## Available Services

- **`JsonToFormTransformer`** - Main service to transform JSON to form
- **`FormGeneratorService`** - Advanced form construction
- **`QuestionConditionEvaluator`** - Visibility conditions management
- **`FormStateManager`** - Session state management

