<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Zend\Form;

use Traversable;
use Zend\Form\Element\Collection;
use Zend\Stdlib\PriorityQueue;
use Zend\Stdlib\Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * @category   Zend
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Fieldset extends Element implements FieldsetInterface
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $byName    = array();

    /**
     * @var array
     */
    protected $elements  = array();

    /**
     * @var array
     */
    protected $fieldsets = array();

    /**
     * @var array
     */
    protected $messages  = array();

    /**
     * @var PriorityQueue
     */
    protected $iterator;

    /**
     * Hydrator to use with bound object
     *
     * @var Hydrator\HydratorInterface
     */
    protected $hydrator;

    /**
     * The object bound to this fieldset, if any
     *
     * @var null|object
     */
    protected $object;

    /**
     * Should this fieldset be used as a base fieldset in the parent form ?
     *
     * @var bool
     */
    protected $useAsBaseFieldset = false;

    /**
     * Constructor
     *
     * @param null|string|int $name Optional name for the element
     */
    public function __construct($name = null)
    {
        $this->iterator = new PriorityQueue();
        parent::__construct($name);
    }

    /**
     * Set options for a fieldset. Accepted options are:
     * - use_as_base_fieldset: is this fieldset use as the base fieldset?
     *
     * @param  array|\Traversable $options
     * @return Element|ElementInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['use_as_base_fieldset'])) {
            $this->setUseAsBaseFieldset($options['use_as_base_fieldset']);
        }

        return $this;
    }

    /**
     * Compose a form factory to use when calling add() with a non-element/fieldset
     *
     * @param  Factory $factory
     * @return Form
     */
    public function setFormFactory(Factory $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * Retrieve composed form factory
     *
     * Lazy-loads one if none present.
     *
     * @return Factory
     */
    public function getFormFactory()
    {
        if (null === $this->factory) {
            $this->setFormFactory(new Factory());
        }

        return $this->factory;
    }

    /**
     * Add an element or fieldset
     *
     * $flags could contain metadata such as the alias under which to register
     * the element or fieldset, order in which to prioritize it, etc.
     *
     * @todo   Should we detect if the element/fieldset name conflicts?
     * @param  array|Traversable|ElementInterface $elementOrFieldset
     * @param  array                              $flags
     * @return Fieldset|FieldsetInterface
     * @throws Exception\InvalidArgumentException
     */
    public function add($elementOrFieldset, array $flags = array())
    {
        if (is_array($elementOrFieldset)
            || ($elementOrFieldset instanceof Traversable && !$elementOrFieldset instanceof ElementInterface)
        ) {
            $factory = $this->getFormFactory();
            $elementOrFieldset = $factory->create($elementOrFieldset);
        }

        if (!$elementOrFieldset instanceof ElementInterface) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that $elementOrFieldset be an object implementing %s; received "%s"',
                __METHOD__,
                __NAMESPACE__ . '\ElementInterface',
                (is_object($elementOrFieldset) ? get_class($elementOrFieldset) : gettype($elementOrFieldset))
            ));
        }

        $name = $elementOrFieldset->getName();
        if ((null === $name || '' === $name)
            && (!array_key_exists('name', $flags) || $flags['name'] === '')
        ) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s: element or fieldset provided is not named, and no name provided in flags',
                __METHOD__
            ));
        }

        if (array_key_exists('name', $flags) && $flags['name'] !== '') {
            $name = $flags['name'];

            // Rename the element or fieldset to the specified alias
            $elementOrFieldset->setName($name);
        }
        $order = 0;
        if (array_key_exists('priority', $flags)) {
            $order = $flags['priority'];
        }

        $this->iterator->insert($elementOrFieldset, $order);
        $this->byName[$name] = $elementOrFieldset;

        if ($elementOrFieldset instanceof FieldsetInterface) {
            if ($elementOrFieldset instanceof Collection) {
                $elementOrFieldset->prepareCollection();
            }

            $this->fieldsets[$name] = $elementOrFieldset;
            return $this;
        }

        $this->elements[$name] = $elementOrFieldset;
        return $this;
    }

    /**
     * Does the fieldset have an element/fieldset by the given name?
     *
     * @param  string $elementOrFieldset
     * @return bool
     */
    public function has($elementOrFieldset)
    {
        return array_key_exists($elementOrFieldset, $this->byName);
    }

    /**
     * Retrieve a named element or fieldset
     *
     * @todo   Should this raise an exception if no entry is found?
     * @param  string $elementOrFieldset
     * @return ElementInterface
     */
    public function get($elementOrFieldset)
    {
        if (!$this->has($elementOrFieldset)) {
            return null;
        }
        return $this->byName[$elementOrFieldset];
    }

    /**
     * Remove a named element or fieldset
     *
     * @param  string $elementOrFieldset
     * @return void
     */
    public function remove($elementOrFieldset)
    {
        if (!$this->has($elementOrFieldset)) {
            return;
        }

        $entry = $this->byName[$elementOrFieldset];
        unset($this->byName[$elementOrFieldset]);

        $this->iterator->remove($entry);

        if ($entry instanceof FieldsetInterface) {
            unset($this->fieldsets[$elementOrFieldset]);
            return;
        }

        unset($this->elements[$elementOrFieldset]);
        return;
    }

    /**
     * Retrieve all attached elements
     *
     * Storage is an implementation detail of the concrete class.
     *
     * @return array|Traversable
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Retrieve all attached fieldsets
     *
     * Storage is an implementation detail of the concrete class.
     *
     * @return array|Traversable
     */
    public function getFieldsets()
    {
        return $this->fieldsets;
    }

    /**
     * Set a hash of element names/messages to use when validation fails
     *
     * @param  array|Traversable $messages
     * @return Element|ElementInterface|FieldsetInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setMessages($messages)
    {
        if (!is_array($messages) && !$messages instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable object of messages; received "%s"',
                __METHOD__,
                (is_object($messages) ? get_class($messages) : gettype($messages))
            ));
        }

        foreach ($messages as $key => $messageSet) {
            if (!$this->has($key)) {
                continue;
            }
            $element = $this->get($key);
            $element->setMessages($messageSet);
        }

        return $this;
    }

    /**
     * Get validation error messages, if any
     *
     * Returns a hash of element names/messages for all elements failing
     * validation, or, if $elementName is provided, messages for that element
     * only.
     *
     * @param  null|string $elementName
     * @return array|Traversable
     * @throws Exception\InvalidArgumentException
     */
    public function getMessages($elementName = null)
    {
        if (null === $elementName) {
            $messages = array();
            foreach ($this->byName as $name => $element) {
                $messageSet = $element->getMessages();
                if (!is_array($messageSet) && !$messageSet instanceof Traversable) {
                    continue;
                }
                $messages[$name] = $messageSet;
            }
            return $messages;
        }

        if (!$this->has($elementName)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid element name "%s" provided to %s',
                $elementName,
                __METHOD__
            ));
        }

        $element = $this->get($elementName);
        return $element->getMessages();
    }

    /**
     * Ensures state is ready for use. Here, we append the name of the fieldsets to every elements in order to avoid
     * name clashes if the same fieldset is used multiple times
     *
     * @param  Form $form
     * @return mixed|void
     */
    public function prepareElement(Form $form)
    {
        $name = $this->getName();

        foreach($this->byName as $elementOrFieldset) {
            $elementOrFieldset->setName($name . '[' . $elementOrFieldset->getName() . ']');

            // Recursively prepare elements
            if ($elementOrFieldset instanceof ElementPrepareAwareInterface) {
                $elementOrFieldset->prepareElement($form);
            }
        }
    }

    /**
     * Recursively populate values of attached elements and fieldsets
     *
     * @param  array|Traversable $data
     * @return void
     * @throws Exception\InvalidArgumentException
     */
    public function populateValues($data)
    {
        if (!is_array($data) && !$data instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable set of data; received "%s"',
                __METHOD__,
                (is_object($data) ? get_class($data) : gettype($data))
            ));
        }

        foreach ($data as $name => $value) {
            if (!$this->has($name)) {
                continue;
            }

            $element = $this->get($name);

            if ($element instanceof FieldsetInterface && is_array($value)) {
                $element->populateValues($value);
                continue;
            }

            $element->setAttribute('value', $value);
        }
    }

    /**
     * Countable: return count of attached elements/fieldsets
     *
     * @return int
     */
    public function count()
    {
        return $this->iterator->count();
    }

    /**
     * IteratorAggregate: return internal iterator
     *
     * @return PriorityQueue
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * Set the object used by the hydrator
     *
     * @param  object $object
     * @return Fieldset|FieldsetInterface
     * @throws Exception\InvalidArgumentException
     */
    public function setObject($object)
    {
        if (!is_object($object)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an object argument; received "%s"',
                __METHOD__,
                $object
            ));
        }

        $this->object = $object;
        return $this;
    }

    /**
     * Get the object used by the hydrator
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set the hydrator to use when binding an object to the element
     *
     * @param  HydratorInterface $hydrator
     * @return FieldsetInterface
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * Get the hydrator used when binding an object to the fieldset
     *
     * Will lazy-load Hydrator\ArraySerializable if none is present.
     *
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator instanceof HydratorInterface) {
            $this->setHydrator(new Hydrator\ArraySerializable());
        }
        return $this->hydrator;
    }

    /**
     * Bind values to the bound object
     *
     * @param array $values
     * @return mixed|void
     */
    public function bindValues(array $values = array())
    {
        $hydrator = $this->getHydrator();
        $hydratableData = array();

        foreach ($values as $name => $value) {
            $element = $this->byName[$name];

            if ($element instanceof Collection) {
                $collection = array();
                foreach ($value as $subName => $subValue) {
                    $collection[] = $element->get($subName)->bindValues($subValue);
                }

                $value = $collection;
            }

            if ($element instanceof FieldsetInterface && is_object($element->object)) {
                $value = $element->bindValues($value);
            }

            $hydratableData[$name] = $value;
        }

        $this->object = $hydrator->hydrate($hydratableData, $this->object);
        return $this->object;
    }

    /**
     * Set if this fieldset is used as a base fieldset
     *
     * @param  bool $useAsBaseFieldset
     * @return Fieldset
     */
    public function setUseAsBaseFieldset($useAsBaseFieldset)
    {
        $this->useAsBaseFieldset = (bool)$useAsBaseFieldset;
        return $this;
    }

    /**
     * Is this fieldset use as a base fieldset for a form ?
     *
     * @return bool
     */
    public function useAsBaseFieldset()
    {
        return $this->useAsBaseFieldset;
    }

    /**
     * Make a deep clone of a fieldset
     *
     * @return void
     */
    public function __clone()
    {
        $this->iterator = new PriorityQueue();

        foreach ($this->byName as $key => $value) {
            $value = clone $value;
            $this->byName[$key] = $value;
            $this->iterator->insert($value);

            if ($value instanceof FieldsetInterface) {
                $this->fieldsets[$key] = $value;
            } elseif ($value instanceof ElementInterface) {
                $this->elements[$key] = $value;
            }
        }

        // Also make a deep copy of the object in case it's used within a collection
        if (is_object($this->object)) {
            $this->object = clone $this->object;
        }
    }
}