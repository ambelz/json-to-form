# JSON Structure Format

This document describes the complete JSON structure format supported by the JSON Form bundle.

## 📋 Root Structure

```json
{
    "slug": "form-identifier",
    "title": "Form title (optional)",
    "description": "Form description (optional)",
    "sections": [
        // Array of sections
    ]
}
```

## 📦 Section Structure

```json
{
    "slug": "section-identifier",
    "title": "Section title",
    "description": "Section description (optional)",
    "categories": [
        // Array of categories
    ],
    "submit": {
        // Optional submit button configuration
        "label": "Button text",
        "class": "CSS classes",
        "attr": {
            // Additional HTML attributes
        }
    }
}
```

> **Note on Submit Button:** If the `submit` object is omitted from a section, and no other section in the form has a submit button, a default submit button with the label "Submit" and class `btn btn-primary` will be automatically added at the end of the entire form. If at least one section defines a submit button, no default global button is added.

## 🏷️ Category Structure

```json
{
    "slug": "category-identifier", 
    "title": "Category title",
    "description": "Category description (optional)",
    "questions": [
        // Array of questions
    ]
}
```

## ❓ Question Structure

Each question has this basic structure:

```json
{
    "key": "field_name",
    "type": "field_type",
    "label": "Field label",
    "required": true,
    "help": "Help text (optional)",
    "attr": {
        // Additional HTML attributes (voir plus bas)
    },
    "displayDependencies": [
        // Conditional display dependencies (voir plus bas)
    ],
    "constraints": [
        // Validation constraints (voir plus bas)
    ]
}
```

## 🎯 Supported Field Types

### Text Fields

#### Text
```json
{
    "key": "name",
    "type": "text",
    "label": "Full name",
    "required": true,
}
```

#### Email
```json
{
    "key": "email",
    "type": "email",
    "label": "Email address",
    "required": true,
}
```

#### Password
```json
{
    "key": "password",
    "type": "password",
    "label": "Password",
    "required": true,
    "help": "At least 8 characters"
}
```

#### Textarea
```json
{
    "key": "description",
    "type": "textarea",
    "label": "Description",
    "attr": {
        "rows": 4
    }
}
```

#### URL
```json
{
    "key": "website",
    "type": "url",
    "label": "Website"
}
```

#### Phone
```json
{
    "key": "phone",
    "type": "tel",
    "label": "Phone number"
}
```

### Numeric Fields

#### Integer
```json
{
    "key": "age",
    "type": "integer",
    "label": "Age",
    "required": true,
    "attr": {
        "min": 0,
        "max": 120
    }
}
```

#### Number (decimal)
```json
{
    "key": "price",
    "type": "number",
    "label": "Desired price",
    "attr": {
        "step": "0.01",
        "min": "0"
    }
}
```

#### Range
```json
{
    "key": "satisfaction",
    "type": "range",
    "label": "Satisfaction level",
    "attr": {
        "min": 1,
        "max": 10,
        "step": 1
    }
}
```

#### Money
```json
{
    "key": "budget",
    "type": "money",
    "label": "Available budget",
    "currency": "USD"
}
```

### Date and Time Fields

#### Date
```json
{
    "key": "birth_date",
    "type": "date",
    "label": "Birth date",
    "widget": "single_text",
    "required": true
}
```

#### Time
```json
{
    "key": "appointment_time",
    "type": "time",
    "label": "Preferred appointment time",
    "widget": "single_text"
}
```

#### DateTime
```json
{
    "key": "appointment",
    "type": "datetime",
    "label": "Appointment date and time",
    "widget": "single_text"
}
```

### Choice Fields

#### Select (dropdown)
```json
{
    "key": "country",
    "type": "choice",
    "label": "Country of residence",
    "choices": {
        "us": "United States",
        "ca": "Canada",
        "uk": "United Kingdom",
        "fr": "France",
        "de": "Germany"
    },
    "attr": {
        "placeholder": "Choose your country"
    },
    "required": true
}
```

#### Radio buttons
```json
{
    "key": "gender",
    "type": "choice",
    "label": "Gender",
    "choices": {
        "m": "Male",
        "f": "Female",
        "nb": "Non-binary",
        "other": "Other",
        "not_specified": "Prefer not to say"
    },
    "required": true
}
```

#### Checkboxes (multiple choice)
```json
{
    "key": "interests",
    "type": "choice",
    "label": "Interests",
    "choices": {
        "sport": "Sports",
        "music": "Music",
        "reading": "Reading",
        "movies": "Movies",
        "travel": "Travel",
        "cooking": "Cooking",
        "technology": "Technology",
        "art": "Art"
    },
    "help": "Select all your interests"
}
```

#### Country
```json
{
    "key": "nationality",
    "type": "country",
    "label": "Nationality"
}
```

### Boolean Fields

#### Checkbox
```json
{
    "key": "accept_terms",
    "type": "checkbox",
    "label": "I accept the terms and conditions",
    "required": true,
    "help": "You must accept the terms to continue"
}
```

### File Fields

#### File Upload
```json
{
    "key": "document",
    "type": "file",
    "label": "Upload document",
    "help": "Accepted formats: PDF, DOC, DOCX (max 5MB)",
    "attr": {
        "accept": ".pdf,.doc,.docx"
    }
}
```

### Hidden Fields

#### Hidden
```json
{
    "key": "csrf_token",
    "type": "hidden",
    "value": "generated_token_value"
}
```

## 🔘 Submit Button Configuration

### Simple Button
```json
{
    "submit": {
        "label": "Submit"
    }
}
```

### Advanced Button
```json
{
    "submit": {
        "label": "Save Information",
        "class": "btn btn-success btn-lg w-100",
        "attr": {
            "data-confirm": "Are you sure?",
            "data-loading-text": "Saving..."
        }
    }
}
```

## 🔗 Question Dependencies

### Simple Dependency
```json
{
    "key": "professional_details",
    "type": "textarea",
    "label": "Professional details",
    "dependencies": [
        {
            "field": "employment_status",
            "value": "employed"
        }
    ]
}
```

### Multiple AND Dependencies
```json
{
    "key": "company_name",
    "type": "text",
    "label": "Company name",
    "dependencies": [
        {
            "field": "employment_status",
            "value": "employed"
        },
        {
            "field": "work_type",
            "value": "full_time"
        }
    ]
}
```

## Conditional Display Dependencies (displayDependencies)

The bundle supports conditional display of fields based on previous answers. Use the `displayDependencies` property within a question's definition to control its visibility. Fields will only be displayed if the conditions defined in `displayDependencies` are met.

**Important:** Display dependencies are evaluated based on data from previously answered questions within the *same form submission*.

```json
{
    "key": "address",
    "type": "text",
    "label": "Address",
    "required": true,
    "displayDependencies": {
        "operator": "AND",
        "conditions": [
            {
                "field": "country",
                "equals": "US"
            },
            {
                "field": "residence_type",
                "in": ["house", "apartment"]
            }
        ]
    }
}
```

### Structure of `displayDependencies`

-   **`operator`** (string, optional, defaults to `AND`): The logical operator to combine multiple conditions.
    -   `AND`: All conditions must be true.
    -   `OR`: At least one condition must be true.
    -   `NOT`: Inverts the result of the conditions (can be used with a single condition or a group).
-   **`conditions`** (array of objects): A list of conditions to evaluate. Each condition object has:
    -   `field` (string): The `key` of the other question to check.
    -   One of the comparison operators (see below) as a key, and the value to compare against.

### Comparison Operators

The value associated with these keys is the value to compare the `field`'s data against.

-   `equals`: The field's value is equal to the specified value.
-   `notEquals`: The field's value is not equal to the specified value.
-   `in`: The field's value is one of the values in the specified array.
-   `notIn`: The field's value is not one of the values in the specified array.
-   `greaterThan`: The field's value is greater than the specified value (for numbers/dates).
-   `lessThan`: The field's value is less than the specified value (for numbers/dates).
-   `greaterThanOrEqual`: The field's value is greater than or equal to the specified value (for numbers/dates).
-   `lessThanOrEqual`: The field's value is less than or equal to the specified value (for numbers/dates).
-   `isNull`: The field's value is null or not provided.
-   `isNotNull`: The field's value is not null and has been provided.
-   `startsWith`: The field's value (string) starts with the specified substring.
-   `endsWith`: The field's value (string) ends with the specified substring.
-   `contains`: The field's value (string or array) contains the specified substring or element.
-   `notContains`: The field's value (string or array) does not contain the specified substring or element.
-   `regex`: The field's value (string) matches the specified regular expression (provide pattern without slashes, e.g., `^[A-Za-z]+$`).

**Example with `OR` and `NOT`:**

```json
{
    "key": "special_offer",
    "type": "text",
    "label": "Special Offer Details",
    "displayDependencies": {
        "operator": "OR",
        "conditions": [
            {
                "field": "is_subscribed",
                "equals": true
            },
            {
                "operator": "NOT", // Nested operator
                "conditions": [
                     {
                         "field": "account_age_days",
                         "lessThan": 30
                     }
                ]
            }
        ]
    }
}
```
This field "special_offer" will be shown if the user `is_subscribed` OR if their `account_age_days` is NOT less than 30 (i.e., 30 or more).

## ✅ Validation Constraints

### Required Field
```json
{
    "key": "email",
    "type": "email",
    "label": "Email",
    "required": true
}
```

### Validation Constraints

The bundle supports all Symfony validation constraints from https://symfony.com/doc/current/reference/constraints.html

```json
{
    "key": "password",
    "type": "password",
    "label": "Password",
    "constraints": {
        "NotBlank": {
            "message": "Password cannot be empty"
        },
        "Regex": {
            "pattern": "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$/",
            "message": "Password must contain at least 8 characters, including uppercase, lowercase and numbers"
        }
    }
}
```

## 📝 Complete Example

Here's a complete example of a user registration form:

```json
{
    "uniqueKey": "types-de-champs-1.0",
    "title": "Catalogue des types de champs",
    "description": "Démonstration complète de tous les types de champs disponibles avec ambelz/json-to-form",
    "slug": "types-de-champs",
    "defaultStorage": "local",
    "displayOptions": {
        "mode": "singlePage",
        "showNavigationMenu": true,
        "navigationPosition": "left",
        "showSectionTitles": true,
        "showProgressBar": true,
        "allowJumpToSection": true,
        "collapsible": true,
        "scrollspy": true,
        "showSummary": true,
        "sections": {
            "attr": {
                "class": "p-2 border-2 border-secondary",
                "style": "border-style: dashed;"
            },
            "label_attr": {
                "class": "h4"
            }
        },
        "categories": {
            "attr": {
                "class": "px-4 py-3"
            },
            "label_attr": {
                "class": "h5"
            }
        },
        "questions": {
            "attr": {
                "class": "px-4 py-3"
            },
            "label_attr": {
                "class": "h6 my-2 font-weight-bold text-secondary"
            }
        }
    },
    "sections": [
        {
            "slug": "champs-texte",
            "title": "Champs de texte",
            "description": "Types de champs pour la saisie de texte",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "texte-simple",
                    "title": "Texte simple",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "text_field",
                            "type": "text",
                            "label": "Champ texte",
                            "required": true,
                            "constraints": {
                                "Length": {
                                    "min": 2,
                                    "max": 50,
                                    "minMessage": "Au moins {{ limit }} caractères requis",
                                    "maxMessage": "Maximum {{ limit }} caractères autorisés"
                                }
                            },
                            "attr": {
                                "placeholder": "Saisissez du texte",
                                "autocomplete": "name"
                            }
                        },
                        {
                            "key": "email_field",
                            "type": "email",
                            "label": "Adresse email",
                            "required": true,
                            "constraints": {
                                "Email": {
                                    "message": "Veuillez saisir une adresse email valide"
                                }
                            },
                            "attr": {
                                "placeholder": "exemple@domaine.com",
                                "autocomplete": "email"
                            }
                        },
                        {
                            "key": "password_field",
                            "type": "password",
                            "label": "Mot de passe",
                            "required": true,
                            "constraints": {
                                "Length": {
                                    "min": 8,
                                    "minMessage": "Le mot de passe doit contenir au moins {{ limit }} caractères"
                                },
                                "Regex": {
                                    "pattern": "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)/",
                                    "message": "Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre"
                                }
                            },
                            "attr": {
                                "placeholder": "Mot de passe sécurisé",
                                "autocomplete": "new-password"
                            }
                        },
                        {
                            "key": "url_field",
                            "type": "url",
                            "label": "Site web",
                            "required": false,
                            "constraints": {
                                "Url": {
                                    "message": "Veuillez saisir une URL valide"
                                }
                            },
                            "attr": {
                                "placeholder": "https://exemple.com"
                            }
                        },
                        {
                            "key": "tel_field",
                            "type": "tel",
                            "label": "Numéro de téléphone",
                            "required": false,
                            "constraints": {
                                "Regex": {
                                    "pattern": "/^[0-9+\\-\\s\\(\\)]+$/",
                                    "message": "Format de téléphone invalide"
                                }
                            },
                            "attr": {
                                "placeholder": "06 12 34 56 78",
                                "autocomplete": "tel"
                            }
                        },
                        {
                            "key": "search_field",
                            "type": "search",
                            "label": "Rechercher...",
                            "required": false,
                            "help": "Ce champ est configuré comme un 'floating label'.",
                            "help_attr": {
                                "class": "text-secondary fst-italic"
                            },
                            "attr": {
                                "class": "pt-4",
                                "placeholder": "Votre message..."
                            },
                            "label_attr": {
                                "class": "h6 font-weight-bold text-secondary"
                            },
                            "row_attr": {
                                "class": "form-floating"
                            }
                        }
                    ]
                },
                {
                    "slug": "texte-long",
                    "title": "Texte long",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "textarea_field",
                            "type": "textarea",
                            "label": "Votre message...",
                            "help": "Ce champ est configuré comme un 'floating label'.",
                            "help_attr": {
                                "class": "text-secondary fst-italic"
                            },
                            "required": false,
                            "constraints": {
                                "Length": {
                                    "max": 500,
                                    "maxMessage": "Maximum {{ limit }} caractères"
                                }
                            },
                            "attr": {
                                "style": "min-height: 10rem;",
                                "class": "pt-4",
                                "placeholder": "Votre message..."
                            },
                            "label_attr": {
                                "class": "h6 font-weight-bold text-secondary"
                            },
                            "row_attr": {
                                "class": "form-floating"
                            }
                        }
                    ]
                }
            ]
        },
        {
            "slug": "champs-numeriques",
            "title": "Champs numériques",
            "description": "Types de champs pour les valeurs numériques",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "nombres",
                    "title": "Nombres",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "integer_field",
                            "type": "integer",
                            "label": "Nombre entier",
                            "required": true,
                            "constraints": {
                                "Range": {
                                    "min": 1,
                                    "max": 100,
                                    "notInRangeMessage": "La valeur doit être comprise entre {{ min }} et {{ max }}"
                                }
                            },
                            "data": 10
                        },
                        {
                            "key": "number_field",
                            "type": "number",
                            "label": "Nombre décimal",
                            "required": false,
                            "constraints": {
                                "Range": {
                                    "min": 0.1,
                                    "max": 999.99,
                                    "notInRangeMessage": "La valeur doit être comprise entre {{ min }} et {{ max }}"
                                }
                            },
                            "attr": {
                                "step": "0.01"
                            }
                        },
                        {
                            "key": "range_field",
                            "type": "range",
                            "label": "Curseur",
                            "required": false,
                            "constraints": {
                                "Range": {
                                    "min": 0,
                                    "max": 100,
                                    "notInRangeMessage": "La valeur doit être comprise entre {{ min }} et {{ max }}"
                                }
                            },
                            "attr": {
                                "min": "0",
                                "max": "100",
                                "step": "5"
                            },
                            "data": 50
                        }
                    ]
                },
                {
                    "slug": "monetaire",
                    "title": "Monétaire",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "money_field",
                            "type": "money",
                            "label": "Montant",
                            "required": false,
                            "currency": "EUR",
                            "constraints": {
                                "Range": {
                                    "min": 0,
                                    "max": 10000,
                                    "notInRangeMessage": "Le montant doit être compris entre {{ min }}€ et {{ max }}€"
                                }
                            },
                            "attr": {
                                "class": "form-control"
                            }
                        }
                    ]
                }
            ]
        },
        {
            "slug": "champs-dates",
            "title": "Champs de dates et heures",
            "description": "Types de champs pour les dates et heures",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "dates-heures",
                    "title": "Dates et heures",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "date_field",
                            "type": "date",
                            "label": "Date",
                            "required": false,
                            "constraints": {
                                "GreaterThan": {
                                    "value": "today",
                                    "message": "La date doit être dans le futur"
                                }
                            }
                        },
                        {
                            "key": "time_field",
                            "type": "time",
                            "label": "Heure",
                            "required": false,
                            "attr": {
                                "step": "300"
                            }
                        },
                        {
                            "key": "datetime_field",
                            "type": "datetime",
                            "label": "Date et heure",
                            "required": false
                        }
                    ]
                }
            ]
        },
        {
            "slug": "champs-choix",
            "title": "Champs de choix",
            "description": "Types de champs pour faire des sélections",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "choix-simple",
                    "title": "Choix simple",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "select_field",
                            "type": "choice",
                            "label": "Liste déroulante",
                            "required": true,
                            "expanded": false,
                            "multiple": false,
                            "choices": {
                                "Option 1": "option1",
                                "Option 2": "option2",
                                "Option 3": "option3",
                                "Option 4": "option4"
                            },
                            "attr": {
                                "placeholder": "Choisissez une option"
                            }
                        },
                        {
                            "key": "radio_field",
                            "type": "choice",
                            "label": "Boutons radio",
                            "required": true,
                            "expanded": true,
                            "multiple": false,
                            "choices": {
                                "Choix A": "choix_a",
                                "Choix B": "choix_b",
                                "Choix C": "choix_c"
                            },
                            "data": "choix_a"
                        }
                    ]
                },
                {
                    "slug": "choix-multiple",
                    "title": "Choix multiple",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "multiselect_field",
                            "type": "choice",
                            "label": "Sélection multiple",
                            "required": false,
                            "expanded": false,
                            "multiple": true,
                            "choices": {
                                "Élément 1": "element1",
                                "Élément 2": "element2",
                                "Élément 3": "element3",
                                "Élément 4": "element4",
                                "Élément 5": "element5"
                            },
                            "attr": {
                                "size": "4"
                            }
                        },
                        {
                            "key": "checkbox_multiple_field",
                            "type": "choice",
                            "label": "Cases à cocher multiples",
                            "required": false,
                            "expanded": true,
                            "multiple": true,
                            "choices": {
                                "Intérêt 1": "interet1",
                                "Intérêt 2": "interet2",
                                "Intérêt 3": "interet3",
                                "Intérêt 4": "interet4"
                            }
                        }
                    ]
                },
                {
                    "slug": "choix-speciaux",
                    "title": "Choix spéciaux",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "country_field",
                            "type": "country",
                            "label": "Pays",
                            "required": false,
                            "data": "FR",
                            "attr": {
                                "class": "form-select"
                            }
                        },
                        {
                            "key": "language_field",
                            "type": "language",
                            "label": "Langue",
                            "required": false,
                            "data": "fr",
                            "attr": {
                                "class": "form-select"
                            }
                        },
                        {
                            "key": "locale_field",
                            "type": "locale",
                            "label": "Locale",
                            "required": false,
                            "data": "fr_FR",
                            "attr": {
                                "class": "form-select"
                            }
                        },
                        {
                            "key": "timezone_field",
                            "type": "timezone",
                            "label": "Fuseau horaire",
                            "help": "Sélectionnez votre fuseau horaire pour afficher les heures correctement",
                            "required": false,
                            "data": "Europe/Paris",
                            "preferred_choices": [
                                "Europe/Paris",
                                "Europe/London",
                                "Europe/Berlin",
                                "America/New_York",
                                "America/Los_Angeles",
                                "Asia/Tokyo",
                                "Australia/Sydney"
                            ],
                            "attr": {
                                "class": "form-select"
                            }
                        },
                        {
                            "key": "currency_field",
                            "type": "currency",
                            "label": "Devise",
                            "required": false,
                            "data": "EUR",
                            "attr": {
                                "class": "form-select"
                            }
                        }
                    ]
                }
            ]
        },
        {
            "slug": "champs-booleen",
            "title": "Champs booléens",
            "description": "Types de champs pour les valeurs vraies/fausses",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "booleens",
                    "title": "Cases à cocher",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "checkbox_field",
                            "type": "checkbox",
                            "label": "Case à cocher simple",
                            "required": false,
                            "data": false
                        },
                        {
                            "key": "checkbox_required_field",
                            "type": "checkbox",
                            "label": "J'accepte les conditions d'utilisation",
                            "required": true,
                            "constraints": {
                                "EqualTo": {
                                    "value": true,
                                    "message": "Vous devez accepter les conditions"
                                }
                            }
                        }
                    ]
                }
            ]
        },
        {
            "slug": "champs-fichiers",
            "title": "Champs de fichiers",
            "description": "Types de champs pour l'upload de fichiers",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "fichiers",
                    "title": "Upload de fichiers",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "file_field",
                            "type": "file",
                            "label": "Fichier unique",
                            "required": false,
                            "constraints": {
                                "File": {
                                    "maxSize": "2M",
                                    "mimeTypes": ["image/jpeg", "image/png", "application/pdf"],
                                    "mimeTypesMessage": "Veuillez sélectionner un fichier JPEG, PNG ou PDF"
                                }
                            },
                            "attr": {
                                "accept": ".jpg,.jpeg,.png,.pdf"
                            }
                        },
                        {
                            "key": "image_field",
                            "type": "file",
                            "label": "Image",
                            "required": false,
                            "constraints": {
                                "Image": {
                                    "maxSize": "5M",
                                    "maxWidth": 2000,
                                    "maxHeight": 2000,
                                    "maxSizeMessage": "L'image ne doit pas dépasser {{ limit }}",
                                    "maxWidthMessage": "L'image ne doit pas dépasser {{ max_width }}px de largeur",
                                    "maxHeightMessage": "L'image ne doit pas dépasser {{ max_height }}px de hauteur"
                                }
                            },
                            "attr": {
                                "accept": "image/*"
                            }
                        },
                        {
                            "key": "multiple_files_field",
                            "type": "file",
                            "label": "Fichiers multiples",
                            "required": false,
                            "multiple": true,
                            "constraints": {
                                "Count": {
                                    "max": 5,
                                    "maxMessage": "Vous ne pouvez pas télécharger plus de {{ limit }} fichiers"
                                }
                            },
                            "attr": {
                                "accept": "image/jpeg,image/png",
                                "multiple": true
                            }
                        }
                    ]
                }
            ]
        },
        {
            "slug": "champs-caches",
            "title": "Champs cachés et spéciaux",
            "description": "Types de champs spéciaux et cachés",
            "submit": {
                "label": "Continuer",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "speciaux",
                    "title": "Champs spéciaux",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "hidden_field",
                            "type": "hidden",
                            "data": "valeur_cachee"
                        },
                        {
                            "key": "color_field",
                            "type": "color",
                            "label": "Couleur",
                            "required": false,
                            "data": "#3498db"
                        }
                    ]
                }
            ]
        },
        {
            "slug": "collections",
            "title": "Collections et formulaires imbriqués",
            "description": "Formulaires dynamiques avec collections",
            "submit": {
                "label": "Finaliser",
                "class": "btn btn-success w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "collection-complexe",
                    "title": "Collection complexe",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "contacts_collection",
                            "type": "collection",
                            "label": "Contacts",
                            "required": false,
                            "allow_add": true,
                            "allow_delete": true,
                            "prototype": true,
                            "button_add_options": {
                                "label": "Ajouter un contact",
                                "attr": {
                                    "class": "btn btn-success btn-sm mb-2"
                                }
                            },
                            "button_delete_options": {
                                "label": "Supprimer",
                                "attr": {
                                    "class": "btn btn-outline-danger btn-sm"
                                }
                            },
                            "fields": [
                                {
                                    "key": "nom",
                                    "type": "text",
                                    "label": "Nom",
                                    "required": true,
                                    "constraints": {
                                        "Length": {
                                            "min": 2,
                                            "max": 50,
                                            "minMessage": "Le nom doit contenir au moins {{ limit }} caractères",
                                            "maxMessage": "Le nom ne peut pas dépasser {{ limit }} caractères"
                                        }
                                    },
                                    "attr": {
                                        "class": "form-control"
                                    }
                                },
                                {
                                    "key": "email",
                                    "type": "email",
                                    "label": "Email",
                                    "required": true,
                                    "constraints": {
                                        "Email": {
                                            "message": "Adresse email invalide"
                                        }
                                    },
                                    "attr": {
                                        "class": "form-control"
                                    }
                                },
                                {
                                    "key": "telephone",
                                    "type": "tel",
                                    "label": "Téléphone",
                                    "required": false,
                                    "constraints": {
                                        "Regex": {
                                            "pattern": "/^[0-9+\\-\\s\\(\\)]+$/",
                                            "message": "Format de téléphone invalide"
                                        }
                                    },
                                    "attr": {
                                        "class": "form-control"
                                    }
                                }
                            ],
                            "attr": {
                                "class": "collection-contacts border rounded p-3 mb-3"
                            }
                        }
                    ]
                }
            ]
        }
    ],
    "submit": {
        "label": "Envoyer le formulaire complet",
        "class": "btn btn-success btn-lg w-100"
    }
}
```

## 📚 Notes

- **Default submit button**: If no section has a submit button configured, a default "Submit" button with CSS class `btn btn-primary` is automatically added at the end of the form.
- **Dependencies**: Questions can be shown/hidden based on the values of other fields using the `dependencies` array.
- **Validation**: Use Symfony's validation constraints in the `constraints` array for advanced validation.
- **Internationalization**: The bundle doesn't handle translations internally. Manage translations in your application for maximum flexibility.
- **Field types**: All Symfony form field types are supported. If a type is not recognized, it defaults to `text`.
