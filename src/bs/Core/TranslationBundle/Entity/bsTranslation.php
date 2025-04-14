<?php

namespace bs\Core\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bsTranslation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\TranslationBundle\Entity\bsTranslationRepository")
 */
class bsTranslation
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="page", type="integer")
	 */
	private $page;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="sentence", type="integer")
	 */
	private $sentence;

	/**
	 * @var language
	 *
	 * @ORM\Column(name="language", type="integer")
	 */
	private $language;

	/**
	 * @var translation
	 *
	 * @ORM\Column(name="translation", type="string" )
	 */
	private $translation;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set page
	 *
	 * @param integer $page
	 * @return bsTranslation
	 */
	public function setPage($page)
	{
		$this->page = $page;

		return $this;
	}

	/**
	 * Get page
	 *
	 * @return integer
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * Set sentence
	 *
	 * @param integer $sentence
	 * @return bsTranslation
	 */
	public function setSentence($sentence)
	{
		$this->sentence = $sentence;

		return $this;
	}

	/**
	 * Get sentence
	 *
	 * @return integer
	 */
	public function getSentence()
	{
		return $this->sentence;
	}

	/**
	 * Set language
	 *
	 * @param integer $language
	 * @return bsTranslation
	 */
	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * Get language
	 *
	 * @return integer
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * Set translation
	 *
	 * @param string $translation
	 * @return bsTranslation
	 */
	public function setTranslation($translation)
	{
		$this->translation = $translation;

		return $this;
	}

	/**
	 * Get translation
	 *
	 * @return string
	 */
	public function getTranslation()
	{
		return $this->translation;
	}

}
