<?php

/**
 * CSS rules manipulator
 *
 * @author     Florian Klein <florian.klein@free.fr>
 */
class CssManipulator
{
    /**
     * __construct
     *
     * @param CSSDocument $css
     */
    public function __construct(CSSDocument $css, \Closure $logger = null)
    {
        $this->css = $css;
        $this->logger = $logger;
    }

    private function log()
    {
        if(null !== $logger = $this->logger) {
            $logger(print_r(func_get_args(), true));
        }
    }

    /**
     * createRule
     *
     * @param mixed $propery
     * @param mixed $value
     * @param mixed $important
     * @return void
     */
    public function createRule($propery, $value = null, $important = false)
    {
        $rule = new CSSRule($property);
        $rule->setValue($value);

        return $rule;
    }

    /**
     * createDeclarationBlock
     *
     * @param mixed $selector
     * @return void
     */
    public function createDeclarationBlock($selector)
    {
        $declaration = new CSSDeclarationBlock();
        $declaration->setSelectors($selector);

        return $declaration;
    }

    /**
     * addDeclarationBlock
     *
     * @param CSSDeclarationBlock $declaration
     * @return void
     */
    public function addDeclarationBlock(CSSDeclarationBlock $declaration)
    {
        $this->log('adding ', $declaration);
        $this->css->append($declaration);
    }

    /**
     * removeDeclarationBlocks removes declaration blocks that match **exactly** the selector
     *
     * @param CSSSelector $selector
     * @return void
     */
    public function removeDeclarationBlocks(CSSSelector $selector)
    {
        $this->log('remove ', $selector);
        foreach($this->css->getAllDeclarationBlocks() as $declaration) {
            $this->log('trying ', $selector, $declaration->getSelectors());
            if($this->hasSelector($declaration, $selector)) {
                $this->css->remove($declaration);
                $this->log('removing ', $declaration);
            }
        }
    }

    /**
     * addRules add rule to all blocks that match selector, or create one if no match
     *
     * @param CSSSelector $selector
     * @param CSSRule $rule
     * @return void
     */
    public function addRule(CSSSelector $selector, CSSRule $rule)
    {
        $this->log('add ', $rule, ' to ', $selector);
        $found = false;
        foreach($this->css->getAllDeclarationBlocks() as $declaration) {
            if($this->hasSelector($declaration, $selector)) {
                if ($rule->getValue()) {
                    $this->log('adding ', $rule, ' to ', $declaration);
                    $declaration->addRule($rule);
                }
                $found = true;
            }
        }

        if (!$found) {
            $declaration = $this->createDeclarationBlock((string)$selector);
            if ($rule->getValue()) {
                $this->log('adding ', $rule, ' to ', $declaration);
                $declaration->addRule($rule);
            }
            $this->addDeclarationBlock($declaration);
        }
    }

    /**
     * removeRules
     *
     * @param CSSSelector $selector
     * @param CSSRule $rule
     * @return void
     */
    public function removeRule(CSSSelector $selector, CSSRule $rule)
    {
        $this->log('remove ', $rule);
        foreach($this->css->getAllDeclarationBlocks() as $declaration) {
            if($this->hasSelector($declaration, $selector)) {
                $declaration->removeRule($rule->getRule());
                $this->log('removing ', $rule);
            }
        }
    }

    private function hasSelector(CSSDeclarationBlock $declaration, CSSSelector $selector)
    {
        foreach($declaration->getSelectors() as $compare) {
            if((string)$selector === (string)$compare) {
                $this->log('found that ', (string)$selector, ' equals ', (string)$compare);
                return true;
            }
        }

        return false;
    }

    /**
     * save
     *
     * @param mixed $path
     * @return boolean
     */
    public function save($path)
    {
        $this->log('saving to ', $path);
        return @file_put_contents($path, (string)$this->css);
    }
}

