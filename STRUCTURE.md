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
        "United States": "us",
        "Canada": "ca",
        "United Kingdom": "uk",
        "France": "fr",
        "Germany": "de"
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
        "Male": "m",
        "Female": "f",
        "Non-binary": "nb",
        "Other": "other",
        "Prefer not to say": "not_specified"
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
        "Sports": "sport",
        "Music": "music",
        "Reading": "reading",
        "Movies": "movies",
        "Travel": "travel",
        "Cooking": "cooking",
        "Technology": "technology",
        "Art": "art"
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

## 🎨 Display Options (displayOptions)

The `displayOptions` property at the root level of the form allows you to customize the display and behavior of the form.

### Display Mode

#### singlePage
All sections are displayed one below the other on a single page:

```json
{
    "displayOptions": {
        "mode": "singlePage"
    }
}
```

#### stepper
Sections are displayed as wizard-style tabs to navigate from one section to another:

```json
{
    "displayOptions": {
        "mode": "stepper"
    }
}
```

### Element Styling

You can customize the appearance of sections, categories, and questions using the `attr.class` and `label_attr.class` properties:

#### Sections
```json
{
    "displayOptions": {
        "sections": {
            "attr": {
                "class": "p-4 border rounded shadow-sm"
            },
            "label_attr": {
                "class": "h3 text-primary mb-3"
            }
        }
    }
}
```

#### Categories
```json
{
    "displayOptions": {
        "categories": {
            "attr": {
                "class": "px-3 py-2 bg-light rounded"
            },
            "label_attr": {
                "class": "h4 text-secondary"
            }
        }
    }
}
```

#### Questions
```json
{
    "displayOptions": {
        "questions": {
            "attr": {
                "class": "mb-3 form-group"
            },
            "label_attr": {
                "class": "form-label fw-bold"
            }
        }
    }
}
```

### Complete displayOptions Example

```json
{
    "displayOptions": {
        "mode": "singlePage",
        "sections": {
            "attr": {
                "class": "p-4 border-2 border-primary rounded-3 mb-4",
                "style": "background-color: #f8f9fa;"
            },
            "label_attr": {
                "class": "h2 text-primary border-bottom pb-2 mb-3"
            }
        },
        "categories": {
            "attr": {
                "class": "px-3 py-2 bg-white rounded shadow-sm mb-3"
            },
            "label_attr": {
                "class": "h4 text-dark mb-2"
            }
        },
        "questions": {
            "attr": {
                "class": "mb-4"
            },
            "label_attr": {
                "class": "form-label fw-bold text-secondary"
            }
        }
    }
}
```

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
    "uniqueKey": "field-types-1.0",
    "title": "Field Types Catalog",
    "description": "Complete demonstration of all available field types with ambelz/json-to-form",
    "slug": "field-types",
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
            "slug": "text-fields",
            "title": "Text Fields",
            "description": "Field types for text input",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "simple-text",
                    "title": "Simple Text",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "text_field",
                            "type": "text",
                            "label": "Text Field",
                            "required": true,
                            "constraints": {
                                "Length": {
                                    "min": 2,
                                    "max": 50,
                                    "minMessage": "At least {{ limit }} characters required",
                                    "maxMessage": "Maximum {{ limit }} characters allowed"
                                }
                            },
                            "attr": {
                                "placeholder": "Enter text",
                                "autocomplete": "name"
                            }
                        },
                        {
                            "key": "email_field",
                            "type": "email",
                            "label": "Email Address",
                            "required": true,
                            "constraints": {
                                "Email": {
                                    "message": "Please enter a valid email address"
                                }
                            },
                            "attr": {
                                "placeholder": "example@domain.com",
                                "autocomplete": "email"
                            }
                        },
                        {
                            "key": "password_field",
                            "type": "password",
                            "label": "Password",
                            "required": true,
                            "constraints": {
                                "Length": {
                                    "min": 8,
                                    "minMessage": "Password must contain at least {{ limit }} characters"
                                },
                                "Regex": {
                                    "pattern": "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)/",
                                    "message": "Password must contain at least one lowercase, one uppercase and one digit"
                                }
                            },
                            "attr": {
                                "placeholder": "Secure password",
                                "autocomplete": "new-password"
                            }
                        },
                        {
                            "key": "url_field",
                            "type": "url",
                            "label": "Website",
                            "required": false,
                            "constraints": {
                                "Url": {
                                    "message": "Please enter a valid URL"
                                }
                            },
                            "attr": {
                                "placeholder": "https://example.com"
                            }
                        },
                        {
                            "key": "tel_field",
                            "type": "tel",
                            "label": "Phone Number",
                            "required": false,
                            "constraints": {
                                "Regex": {
                                    "pattern": "/^[0-9+\\-\\s\\(\\)]+$/",
                                    "message": "Invalid phone format"
                                }
                            },
                            "attr": {
                                "placeholder": "+1 234 567 8900",
                                "autocomplete": "tel"
                            }
                        },
                        {
                            "key": "search_field",
                            "type": "search",
                            "label": "Search...",
                            "required": false,
                            "help": "This field is configured as a 'floating label'.",
                            "help_attr": {
                                "class": "text-secondary fst-italic"
                            },
                            "attr": {
                                "class": "pt-4",
                                "placeholder": "Your message..."
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
                    "slug": "long-text",
                    "title": "Long Text",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "textarea_field",
                            "type": "textarea",
                            "label": "Your message...",
                            "help": "This field is configured as a 'floating label'.",
                            "help_attr": {
                                "class": "text-secondary fst-italic"
                            },
                            "required": false,
                            "constraints": {
                                "Length": {
                                    "max": 500,
                                    "maxMessage": "Maximum {{ limit }} characters"
                                }
                            },
                            "attr": {
                                "style": "min-height: 10rem;",
                                "class": "pt-4",
                                "placeholder": "Your message..."
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
            "slug": "numeric-fields",
            "title": "Numeric Fields",
            "description": "Field types for numeric values",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "numbers",
                    "title": "Numbers",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "integer_field",
                            "type": "integer",
                            "label": "Integer Number",
                            "required": true,
                            "constraints": {
                                "Range": {
                                    "min": 1,
                                    "max": 100,
                                    "notInRangeMessage": "Value must be between {{ min }} and {{ max }}"
                                }
                            },
                            "data": 10
                        },
                        {
                            "key": "number_field",
                            "type": "number",
                            "label": "Decimal Number",
                            "required": false,
                            "constraints": {
                                "Range": {
                                    "min": 0.1,
                                    "max": 999.99,
                                    "notInRangeMessage": "Value must be between {{ min }} and {{ max }}"
                                }
                            },
                            "attr": {
                                "step": "0.01"
                            }
                        },
                        {
                            "key": "range_field",
                            "type": "range",
                            "label": "Slider",
                            "required": false,
                            "constraints": {
                                "Range": {
                                    "min": 0,
                                    "max": 100,
                                    "notInRangeMessage": "Value must be between {{ min }} and {{ max }}"
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
                    "slug": "monetary",
                    "title": "Monetary",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "money_field",
                            "type": "money",
                            "label": "Amount",
                            "required": false,
                            "currency": "EUR",
                            "constraints": {
                                "Range": {
                                    "min": 0,
                                    "max": 10000,
                                    "notInRangeMessage": "Amount must be between {{ min }}€ and {{ max }}€"
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
            "slug": "date-time-fields",
            "title": "Date and Time Fields",
            "description": "Field types for dates and times",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "dates-times",
                    "title": "Dates and Times",
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
                                    "message": "Date must be in the future"
                                }
                            }
                        },
                        {
                            "key": "time_field",
                            "type": "time",
                            "label": "Time",
                            "required": false,
                            "attr": {
                                "step": "300"
                            }
                        },
                        {
                            "key": "datetime_field",
                            "type": "datetime",
                            "label": "Date and Time",
                            "required": false
                        }
                    ]
                }
            ]
        },
        {
            "slug": "choice-fields",
            "title": "Choice Fields",
            "description": "Field types for making selections",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "simple-choice",
                    "title": "Simple Choice",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "select_field",
                            "type": "choice",
                            "label": "Dropdown List",
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
                                "placeholder": "Choose an option"
                            }
                        },
                        {
                            "key": "radio_field",
                            "type": "choice",
                            "label": "Radio Buttons",
                            "required": true,
                            "expanded": true,
                            "multiple": false,
                            "choices": {
                                "Choice A": "choice_a",
                                "Choice B": "choice_b",
                                "Choice C": "choice_c"
                            },
                            "data": "choice_a"
                        }
                    ]
                },
                {
                    "slug": "multiple-choice",
                    "title": "Multiple Choice",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "multiselect_field",
                            "type": "choice",
                            "label": "Multiple Selection",
                            "required": false,
                            "expanded": false,
                            "multiple": true,
                            "choices": {
                                "Element 1": "element1",
                                "Element 2": "element2",
                                "Element 3": "element3",
                                "Element 4": "element4",
                                "Element 5": "element5"
                            },
                            "attr": {
                                "size": "4"
                            }
                        },
                        {
                            "key": "checkbox_multiple_field",
                            "type": "choice",
                            "label": "Multiple Checkboxes",
                            "required": false,
                            "expanded": true,
                            "multiple": true,
                            "choices": {
                                "Interest 1": "interest1",
                                "Interest 2": "interest2",
                                "Interest 3": "interest3",
                                "Interest 4": "interest4"
                            }
                        }
                    ]
                },
                {
                    "slug": "special-choices",
                    "title": "Special Choices",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "country_field",
                            "type": "country",
                            "label": "Country",
                            "required": false,
                            "data": "FR",
                            "attr": {
                                "class": "form-select"
                            }
                        },
                        {
                            "key": "language_field",
                            "type": "language",
                            "label": "Language",
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
                            "label": "Timezone",
                            "help": "Select your timezone to display times correctly",
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
                            "label": "Currency",
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
            "slug": "boolean-fields",
            "title": "Boolean Fields",
            "description": "Field types for true/false values",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "booleans",
                    "title": "Checkboxes",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "checkbox_field",
                            "type": "checkbox",
                            "label": "Simple Checkbox",
                            "required": false,
                            "data": false
                        },
                        {
                            "key": "checkbox_required_field",
                            "type": "checkbox",
                            "label": "I accept the terms of use",
                            "required": true,
                            "constraints": {
                                "EqualTo": {
                                    "value": true,
                                    "message": "You must accept the conditions"
                                }
                            }
                        }
                    ]
                }
            ]
        },
        {
            "slug": "file-fields",
            "title": "File Fields",
            "description": "Field types for file upload",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "files",
                    "title": "File Upload",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "file_field",
                            "type": "file",
                            "label": "Single File",
                            "required": false,
                            "constraints": {
                                "File": {
                                    "maxSize": "2M",
                                    "mimeTypes": ["image/jpeg", "image/png", "application/pdf"],
                                    "mimeTypesMessage": "Please select a JPEG, PNG or PDF file"
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
                                    "maxSizeMessage": "Image must not exceed {{ limit }}",
                                    "maxWidthMessage": "Image must not exceed {{ max_width }}px in width",
                                    "maxHeightMessage": "Image must not exceed {{ max_height }}px in height"
                                }
                            },
                            "attr": {
                                "accept": "image/*"
                            }
                        },
                        {
                            "key": "multiple_files_field",
                            "type": "file",
                            "label": "Multiple Files",
                            "required": false,
                            "multiple": true,
                            "constraints": {
                                "Count": {
                                    "max": 5,
                                    "maxMessage": "You cannot upload more than {{ limit }} files"
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
            "slug": "hidden-fields",
            "title": "Hidden and Special Fields",
            "description": "Special and hidden field types",
            "submit": {
                "label": "Continue",
                "class": "btn btn-primary w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "special",
                    "title": "Special Fields",
                    "label_attr": {
                        "class": "h6"
                    },
                    "questions": [
                        {
                            "key": "hidden_field",
                            "type": "hidden",
                            "data": "hidden_value"
                        },
                        {
                            "key": "color_field",
                            "type": "color",
                            "label": "Color",
                            "required": false,
                            "data": "#3498db"
                        }
                    ]
                }
            ]
        },
        {
            "slug": "collections",
            "title": "Collections and Nested Forms",
            "description": "Dynamic forms with collections",
            "submit": {
                "label": "Finalize",
                "class": "btn btn-success w-100 mt-4 rounded-pill"
            },
            "label_attr": {
                "class": "h5"
            },
            "categories": [
                {
                    "slug": "complex-collection",
                    "title": "Complex Collection",
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
                                "label": "Add Contact",
                                "attr": {
                                    "class": "btn btn-success btn-sm mb-2"
                                }
                            },
                            "button_delete_options": {
                                "label": "Delete",
                                "attr": {
                                    "class": "btn btn-outline-danger btn-sm"
                                }
                            },
                            "fields": [
                                {
                                    "key": "nom",
                                    "type": "text",
                                    "label": "Name",
                                    "required": true,
                                    "constraints": {
                                        "Length": {
                                            "min": 2,
                                            "max": 50,
                                            "minMessage": "Name must contain at least {{ limit }} characters",
                                            "maxMessage": "Name cannot exceed {{ limit }} characters"
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
                                            "message": "Invalid email address"
                                        }
                                    },
                                    "attr": {
                                        "class": "form-control"
                                    }
                                },
                                {
                                    "key": "telephone",
                                    "type": "tel",
                                    "label": "Phone",
                                    "required": false,
                                    "constraints": {
                                        "Regex": {
                                            "pattern": "/^[0-9+\\-\\s\\(\\)]+$/",
                                            "message": "Invalid phone format"
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
        "label": "Submit Complete Form",
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
