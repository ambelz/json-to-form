# JSON to Form Bundle

**Author:** Christophe Abillama <christophe.abillama@gmail.com>

Symfony bundle to transform JSON structures into forms.

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

File name: `my-form.json`
Place the JSON file in `config/forms/` directory.

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

### 2. 🎛️ A classic controller that loads JSON and renders the main view

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private const JSON_FORMS_PATH = __DIR__.'/../../config/forms/';

    #[Route('/', name: 'homepage')]
    public function showForm(): Response
    {
        $jsonFile = self::JSON_FORMS_PATH.'my-form.json';

        if (!file_exists($jsonFile)) {
            throw new NotFoundHttpException("Form not found");
        }

        $jsonFileContent = file_get_contents($jsonFile);
        $jsonStructure = json_decode($jsonFileContent, true);

        return $this->render('homepage.html.twig', [
            'jsonStructure' => $jsonStructure,
        ]);
    }
}
```

### 3. 🎨 Main template that includes the Live Component

```twig
{# templates/homepage.html.twig #}
{% extends 'base.html.twig' %}

{% block title %}Homepage{% endblock %}

{% block body %}
<div class="container">
    {% for label, messages in app.flashes %}
        <div class="alert alert-{{ label == 'error' ? 'danger' : label }} alert-dismissible fade show" role="alert">
            {% for message in messages %}
                {{ message }}
            {% endfor %}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    {% endfor %}

    <h1>My form</h1>

    <!-- LiveComponent for the form -->
    <div class="mb-5">
        {{ component('JsonFormComponent', {
            jsonStructure: jsonStructure,
        }) }}
    </div>
</div>
{% endblock %}
```

### 4. 🎨 Twig Template for the Live Component

```twig
{# templates/components/JsonFormComponent.html.twig #}
<div {{ attributes }}>
    {{ form(form, {
        attr: {
            'data-action': 'live#action',
            'data-live-action-param': 'save'
        }
    }) }}
</div>
```

### 5. 🎛️ A Live Component to handle the form

```php
<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Ambelz\JsonToFormBundle\Service\JsonToFormTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('JsonFormComponent')]
class JsonFormComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public array $jsonStructure;

    public function __construct(
        private JsonToFormTransformer $jsonToFormTransformer,
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        $data = []; // initial data to pre-fill the form, in format [sectionSlug][categorySlug][questionKey] = value

        $builder = $this->createFormBuilder($data, [
            'label_attr' => [
                'class' => 'h2', // class for main label
            ],
            'attr' => [
                'class' => 'p-3', // class for the main form block
            ],
        ]);

        return $this->jsonToFormTransformer->transform($this->jsonStructure, $data, $builder);
    }

    #[LiveAction]
    public function save()
    {
        $this->submitForm();
        $form = $this->getForm();

        if (!$form->isValid()) {
            $this->addFlash('error', 'The form contains errors. Please correct them.');
            return;
        }

        // Process form data
        $data = $form->getData();
        // Save to database, send email, etc.

        $this->addFlash('success', 'Congratulations! Your form has been submitted successfully.');
    }
}
```

## That's it! 🎉

With these 5 elements, you have a functional dynamic form generated from a JSON structure with live updates.

> **💡 Note:** You can configure submit buttons directly in your JSON structure at the section level with custom labels and CSS classes. If no submit is configured, the bundle automatically adds a default "Submit" button at the end of the form. Manage translations in your own application for maximum flexibility!

## Form Data Structure

When the form is submitted and valid, `$form->getData()` returns an associative array where:

- **Keys** are the `key` values from your JSON questions
- **Values** are the user-submitted values

**Example:**

```php
$data = $form->getData();

// $data will contain:
[
    'sectionSlug' => [
        'categorySlug' => [
            'questionKey' => 'value',
        ],
    ],
]
```

**Data processing:**

```php
if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();

    // Access individual values
    $userName = $data['sectionSlug']['categorySlug']['questionKey'];
    $userEmail = $data['sectionSlug']['categorySlug']['questionKey'];

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
