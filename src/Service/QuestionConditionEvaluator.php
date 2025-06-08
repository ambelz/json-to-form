<?php

namespace TimeSplitters\JsonFormBundle\Service;

/**
 * Service specialized in evaluating question visibility conditions
 * 
 * This service provides a comprehensive system for evaluating complex conditional logic
 * to determine whether form fields should be displayed. It supports various comparison
 * operators and logical operators (AND, OR, NOT) to create sophisticated display rules.
 * 
 * @author Christophe Abillama <christophe.abillama@gmail.com>
 */
final class QuestionConditionEvaluator
{
    /**
     * Constructor initializes the evaluator with its required dependencies
     * 
     */
    public function __construct()
    {
    }
    
    /**
     * Evaluates if a question should be displayed based on its dependencies
     * 
     * This is the main entry point for condition evaluation. It processes the dependency
     * configuration and delegates to the appropriate evaluation method based on the
     * logical operator (AND, OR, NOT).
     * 
     * Example dependency structure:
     * [
     *   'operator' => 'AND',
     *   'conditions' => [
     *     ['field' => 'age', 'greaterThan' => 18],
     *     ['field' => 'country', 'equals' => 'France']
     *   ]
     * ]
     * 
     * @param array $dependencies Question dependency configuration with operator and conditions
     * @param array $formData Whole form data to evaluate conditions against
     * @return bool True if the question should be displayed, false otherwise
     * @throws \InvalidArgumentException If an unsupported logical operator is provided
     */
    public function shouldDisplay(array $dependencies, array $formData): bool
    {
        // If no dependencies, the question is always displayed
        if (empty($dependencies)) {
            return true;
        }
        
        // Structure with logical operators
        $operator = $dependencies['operator'] ?? 'AND';
        $conditions = $dependencies['conditions'] ?? [];
        
        if (empty($conditions)) {
            return true;
        }
        return match ($operator) {
            'AND' => $this->evaluateAndConditions($conditions, $formData),
            'OR'  => $this->evaluateOrConditions($conditions, $formData),
            'NOT' => !$this->evaluateAndConditions($conditions, $formData),
            default => throw new \InvalidArgumentException("Unsupported logical operator: $operator")
        };
    }
    
    /**
     * Evaluates a series of conditions with the AND operator
     * 
     * All conditions must evaluate to true for the result to be true.
     * If any condition is false, the method returns false immediately (short-circuit evaluation).
     * 
     * @param array $conditions Array of condition configurations to evaluate
     * @param array $formData Form data to evaluate conditions against
     * @return bool True if all conditions are true, false otherwise
     */
    private function evaluateAndConditions(array $conditions, array $formData): bool
    {
        foreach ($conditions as $condition) {
            if (!$this->evaluateSingleCondition($condition, $formData)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Evaluates a series of conditions with the OR operator
     * 
     * At least one condition must evaluate to true for the result to be true.
     * If any condition is true, the method returns true immediately (short-circuit evaluation).
     * 
     * @param array $conditions Array of condition configurations to evaluate
     * @param array $formData Form data to evaluate conditions against
     * @return bool True if at least one condition is true, false otherwise
     */
    private function evaluateOrConditions(array $conditions, array $formData): bool
    {
        foreach ($conditions as $condition) {
            if ($this->evaluateSingleCondition($condition, $formData)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Evaluates a single condition
     * 
     * This method handles the evaluation of a single condition configuration.
     * It supports various comparison operators like equals, notEquals, in, contains,
     * greaterThan, etc. It also supports nested conditions with logical operators.
     * 
     * @param array $condition Condition configuration with field and comparison operator
     * @param array $formData Form data to evaluate the condition against
     * @return bool Result of the condition evaluation
     */
    private function evaluateSingleCondition(array $condition, array $formData): bool
    {
        $field = $condition['field'] ?? null;
        if ($field === null) {
            return false;
        }
        
        // Recursive search for the value in the array
        $value = $formData[$field] ?? null;
        
        // Support for nested conditions with operators
        if (isset($condition['operator']) && isset($condition['conditions'])) {
            return $this->shouldDisplay($condition, $formData);
        }
        
        // Supported condition types
        return match (true) {
            isset($condition['isNotNull']) => $this->isNotNull($value),
            isset($condition['isNull']) => $this->isNull($value),
            isset($condition['hasValue']) => $this->equals($value, $condition['hasValue']),
            isset($condition['equals'])  => $this->equals($value, $condition['equals']),
            isset($condition['notEquals']) => !$this->equals($value, $condition['notEquals']),
            isset($condition['in']) => $this->in($value, $condition['in']),
            isset($condition['notIn']) => !$this->in($value, $condition['notIn']),
            isset($condition['contains']) => $this->contains($value, $condition['contains']),
            isset($condition['notContains']) => !$this->contains($value, $condition['contains']),
            isset($condition['greaterThan']) => $this->greaterThan($value, $condition['greaterThan']),
            isset($condition['lessThan']) => $this->lessThan($value, $condition['lessThan']),
            isset($condition['greaterThanOrEqual']) => $this->greaterThanOrEqual($value, $condition['greaterThanOrEqual']),
            isset($condition['lessThanOrEqual']) => $this->lessThanOrEqual($value, $condition['lessThanOrEqual']),
            default => false
        };
    }
    
    /**
     * Checks if a value is not null or empty
     * 
     * This method evaluates if a value exists and is not empty.
     * It handles different data types appropriately:
     * - For null or empty string: returns false
     * - For empty arrays: returns false
     * - For all other values: returns true
     * 
     * @param mixed $value The value to check
     * @return bool True if the value is not null or empty, false otherwise
     */
    private function isNotNull(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        
        if (is_array($value) && count($value) === 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Checks if a value is null or empty
     * 
     * This is the inverse of isNotNull() method.
     * 
     * @param mixed $value The value to check
     * @return bool True if the value is null or empty, false otherwise
     */
    private function isNull(mixed $value): bool
    {
        return !$this->isNotNull($value);
    }
    
    /**
     * Checks if a value equals another
     * 
     * Uses strict comparison (===) to ensure type safety.
     * 
     * @param mixed $value The value to check
     * @param mixed $target The target value to compare against
     * @return bool True if the values are equal, false otherwise
     */
    private function equals(mixed $value, mixed $target): bool
    {
        return $value === $target;
    }
    
    /**
     * Checks if a value is in an array
     * 
     * Uses strict comparison (in_array with third parameter true)
     * to ensure type safety when checking array membership.
     * 
     * @param mixed $value The value to check
     * @param array $values Array of values to check against
     * @return bool True if the value is in the array, false otherwise
     */
    private function in(mixed $value, array $values): bool
    {
        return in_array($value, $values, true);
    }
    
    /**
     * Checks if an array contains a specific value or if a string contains a substring
     * 
     * This method handles two different types of containment checks:
     * 1. For arrays: checks if the array contains the target value (using in_array)
     * 2. For strings: checks if the string contains the target substring (using str_contains)
     * 
     * @param mixed $value The array or string to check in
     * @param mixed $target The value or substring to search for
     * @return bool True if the value contains the target, false otherwise
     */
    private function contains(mixed $value, mixed $target): bool
    {
        if (is_array($value)) {
            return in_array($target, $value, true);
        }
        
        if (is_string($value) && is_string($target)) {
            return str_contains($value, $target);
        }
        
        return false;
    }
    
    /**
     * Checks if a value is greater than another
     * 
     * Only works with numeric values. Returns false if either value is not numeric.
     * 
     * @param mixed $value The value to check
     * @param mixed $target The target value to compare against
     * @return bool True if value > target, false otherwise or if non-numeric
     */
    private function greaterThan(mixed $value, mixed $target): bool
    {
        if (!is_numeric($value) || !is_numeric($target)) {
            return false;
        }
        
        return $value > $target;
    }
    
    /**
     * Checks if a value is less than another
     * 
     * Only works with numeric values. Returns false if either value is not numeric.
     * 
     * @param mixed $value The value to check
     * @param mixed $target The target value to compare against
     * @return bool True if value < target, false otherwise or if non-numeric
     */
    private function lessThan(mixed $value, mixed $target): bool
    {
        if (!is_numeric($value) || !is_numeric($target)) {
            return false;
        }
        
        return $value < $target;
    }
    
    /**
     * Checks if a value is greater than or equal to another
     * 
     * Only works with numeric values. Returns false if either value is not numeric.
     * 
     * @param mixed $value The value to check
     * @param mixed $target The target value to compare against
     * @return bool True if value >= target, false otherwise or if non-numeric
     */
    private function greaterThanOrEqual(mixed $value, mixed $target): bool
    {
        if (!is_numeric($value) || !is_numeric($target)) {
            return false;
        }
        
        return $value >= $target;
    }
    
    /**
     * Checks if a value is less than or equal to another
     * 
     * Only works with numeric values. Returns false if either value is not numeric.
     * 
     * @param mixed $value The value to check
     * @param mixed $target The target value to compare against
     * @return bool True if value <= target, false otherwise or if non-numeric
     */
    private function lessThanOrEqual(mixed $value, mixed $target): bool
    {
        if (!is_numeric($value) || !is_numeric($target)) {
            return false;
        }
        
        return $value <= $target;
    }
}
