<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-27
 */

namespace App\Helpers;


class Validate
{
    /** @var array */
    private $_source = [];

    /** @var integer */
    private $_recordID = null;

    /** @var boolean */
    private $_passed = false;

    /** @var array */
    private $_errors = [];


    /**
     * @access public
     * @param array $source
     * @param integer $recordID [optional]
     */
    public function __construct(array $source, $recordID = null)
    {
        $this->_recordID = $recordID;
        $this->_source = $source;
    }

    /**
     * Add Error:
     * @access private
     * @param string $input
     * @param string $error
     */
    private function _addError($input, $error)
    {
        $this->_errors[$input][] = str_replace(['-', '_'], ' ', ucfirst(strtolower($error)));
    }

    /**
     * Check:
     * @access public
     * @param array $inputs
     * @return Validate
     */
    public function check(array $inputs)
    {
        $this->_errors = [];
        $this->_passed = false;
        foreach ($inputs as $input => $rules) {
            if (isset($this->_source[$input])) {
                $value = trim($this->_source[$input]);
                $this->_validate($input, $value, $rules);
            } else {
                $this->_addError($input, TextMessage::get('VALIDATE_MISSING_INPUT', ['%ITEM%' => $input]));
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    /**
     * Errors:
     * @access public
     * @return array
     */
    public function errors()
    {
        return ($this->_errors);
    }

    /**
     * Passed:
     * @access public
     * @return boolean
     */
    public function passed()
    {
        return ($this->_passed);
    }

    /**
     * Validate:
     * @access private
     * @param string $input
     * @param string $value
     * @param array $rules
     * @return void
     */
    private function _validate($input, $value, array $rules)
    {
        foreach ($rules as $rule => $ruleValue) {
            if (($rule === 'required' and $ruleValue === true) and empty($value)) {
                $this->_addError($input, TextMessage::get('VALIDATE_REQUIRED_RULE', ['%ITEM%' => $input]));
            } elseif (!empty($value)) {
                $methodName = lcfirst(ucwords(strtolower(str_replace(['-', '_'], '', $rule)))) . 'Rule';
                if (method_exists($this, $methodName)) {
                    $this->{$methodName}($input, $value, $ruleValue);
                } else {
                    $this->_addError($input, TextMessage::get('VALIDATE_MISSING_METHOD', ['%ITEM%' => $input]));
                }
            }
        }
    }

    /**
     * Filter Rule:
     * @access protected
     * @param string $input
     * @param string $value
     * @param string $ruleValue
     * @return void
     */
    protected function filterRule($input, $value, $ruleValue)
    {
        switch ($ruleValue) {
            // Email
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $data = [
                        '%ITEM%' => $input,
                        '%RULE_VALUE%' => $ruleValue
                    ];
                    $this->_addError($input, TextMessage::get('VALIDATE_FILTER_RULE', $data));
                }
                break;
        }
    }

    /**
     * Matches Rule:
     * @access protected
     * @param string $input
     * @param string $value
     * @param string $ruleValue
     * @return void
     */
    protected function matchesRule($input, $value, $ruleValue)
    {
        if ($value != $this->_source[$ruleValue]) {
            $data = [
                '%ITEM%' => $input,
                '%RULE_VALUE%' => $ruleValue
            ];
            $this->_addError($input, TextMessage::get('VALIDATE_MATCHES_RULE', $data));
        }
    }

    /**
     * Max Characters Rule:
     * @access protected
     * @param string $input
     * @param string $value
     * @param string $ruleValue
     * @return void
     */
    protected function maxCharactersRule($input, $value, $ruleValue)
    {
        if (strlen($value) > $ruleValue) {
            $data = [
                '%ITEM%' => $input,
                '%RULE_VALUE%' => $ruleValue
            ];
            $this->_addError($input, TextMessage::get('VALIDATE_MAX_CHARACTERS_RULE', $data));
        }
    }

    /**
     * Min Characters Rule:
     * @access protected
     * @param string $input
     * @param string $value
     * @param string $ruleValue
     * @return void
     */
    protected function minCharactersRule($input, $value, $ruleValue)
    {
        if (strlen($value) < $ruleValue) {
            $data = [
                '%ITEM%' => $input,
                '%RULE_VALUE%' => $ruleValue
            ];
            $this->_addError($input, TextMessage::get('VALIDATE_MIN_CHARACTERS_RULE', $data));
        }
    }

    /**
     * Required Rule:
     * @access protected
     * @param string $input
     * @param string $value
     * @param string $ruleValue
     * @return void
     */
    protected function requiredRule($input, $value, $ruleValue)
    {
        if ($ruleValue === true and empty($value)) {
            $this->_addError($input, TextMessage::get('VALIDATE_REQUIRED_RULE', ['%ITEM%' => $input]));
        }
    }

    /**
     * Unique Rule:
     * @access protected
     * @param string $input
     * @param string $value
     * @param string $ruleValue
     * @return void
     */
    protected function uniqueRule($input, $value, $ruleValue)
    {
        $check = $this->_Db->select($ruleValue, [$input, "=", $value]);
        if ($check->count()) {
            if ($this->_recordID and $check->first()->id === $this->_recordID) {
                return;
            }
            $this->_addError($input, TextMessage::get('VALIDATE_UNIQUE_RULE', ['%ITEM%' => $input]));
        }
    }
}