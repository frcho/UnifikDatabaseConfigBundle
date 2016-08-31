<?php

namespace Unifik\DatabaseConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Table(name="container_config")
 * @ORM\Entity(repositoryClass="Unifik\DatabaseConfigBundle\Entity\ConfigRepository")
 */
class Config {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * @ORM\OneToMany(targetEntity="Unifik\DatabaseConfigBundle\Entity\Config", mappedBy="parent", cascade={"remove"})
     */
    private $children;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Unifik\DatabaseConfigBundle\Entity\Config", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Unifik\DatabaseConfigBundle\Entity\Extension", inversedBy="configs")
     * @ORM\JoinColumn(name="extension_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $extension;

    /**
     * Constructor
     */
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Config
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Config
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Add children
     *
     * @param \Unifik\DatabaseConfigBundle\Entity\Config $children
     * @return Config
     */
    public function addChildren(\Unifik\DatabaseConfigBundle\Entity\Config $children) {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Unifik\DatabaseConfigBundle\Entity\Config $children
     */
    public function removeChildren(\Unifik\DatabaseConfigBundle\Entity\Config $children) {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Unifik\DatabaseConfigBundle\Entity\Config $parent
     * @return Config
     */
    public function setParent(\Unifik\DatabaseConfigBundle\Entity\Config $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Unifik\DatabaseConfigBundle\Entity\Config
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set extension
     *
     * @param \Unifik\DatabaseConfigBundle\Entity\Extension $extension
     * @return Config
     */
    public function setExtension(\Unifik\DatabaseConfigBundle\Entity\Extension $extension = null) {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return \Unifik\DatabaseConfigBundle\Entity\Extension
     */
    public function getExtension() {
        return $this->extension;
    }

    public function getConfigTree() {
        if (count($this->children) > 0) {
            $configArray = array();
            foreach ($this->children as $child) {
                $configArray[$child->getName()] = $child->getConfigTree();
            }

            return $configArray;
        }

        if (is_numeric($this->value)) {
            $this->value = intval($this->value);
        }

        return $this->value;
    }

}
