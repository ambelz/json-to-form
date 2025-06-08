<?php

namespace TimeSplitters\JsonFormBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Simplified form state management.
 * 
 * @author Christophe Abillama <christophe.abillama@gmail.com>
 */
final class FormStateManager
{
    public function __construct(private RequestStack $stack) {}

    /** Gets the active form structure from the session */
    public function structure(): array
    {
        return $this->stack->getSession()->get('active_form_structure', []);
    }

    /** Gets the current form data (draft) */
    public function data(): array
    {
        return $this->stack->getSession()->get('checkout_draft', []);
    }

    /** Merges and saves data in the session */
    public function save(array $data): void
    {
        $this->stack->getSession()->set('checkout_draft', array_merge($this->data(), $data));
    }
    
    /** Sets the form structure */
    public function setStructure(array $structure): void
    {
        $this->stack->getSession()->set('active_form_structure', $structure);
    }
    
    /** Resets the form data */
    public function reset(): void
    {
        $this->stack->getSession()->remove('checkout_draft');
        $this->stack->getSession()->remove('active_form_structure');
    }
}
