<?php

namespace bs\Core\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IDPUserAutoSaveFields
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="bs\Core\UsersBundle\Entity\IDPUserAutoSaveFieldsRepository")
 */
class IDPUserAutoSaveFields
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
     * @ORM\Column( name="user_id", type="integer" )
     **/
    private $user_id;

    /**
     * @var boolean
     * @ORM\Column( name="asf_service", type="boolean" )
     **/
    private $asf_service;

    /**
     * @var boolean
     * @ORM\Column( name="asf_legalentity", type="boolean" )
     **/
    private $asf_legalentity;

    /**
     * @var boolean
     * @ORM\Column( name="asf_budgetcode", type="boolean" )
     **/
    private $asf_budgetcode;

    /**
     * @var boolean
     * @ORM\Column( name="asf_documentnature", type="boolean" )
     **/
    private $asf_documentnature;

    /**
     * @var boolean
     * @ORM\Column( name="asf_documenttype", type="boolean" )
     **/
    private $asf_documenttype;

    /**
     * @var boolean
     * @ORM\Column( name="asf_description1", type="boolean" )
     **/
    private $asf_description1;

    /**
     * @var boolean
     * @ORM\Column( name="asf_description2", type="boolean" )
     **/
    private $asf_description2;

    /**
     * @var boolean
     * @ORM\Column( name="asf_closureyear", type="boolean" )
     **/
    private $asf_closureyear;

    /**
     * @var boolean
     * @ORM\Column( name="asf_destructionyear", type="boolean" )
     **/
    private $asf_destructionyear;

    /**
     * @var boolean
     * @ORM\Column( name="asf_filenumber", type="boolean" )
     **/
    private $asf_filenumber;

    /**
     * @var boolean
     * @ORM\Column( name="asf_boxnumber", type="boolean" )
     **/
    private $asf_boxnumber;

    /**
     * @var boolean
     * @ORM\Column( name="asf_containernumber", type="boolean" )
     **/
    private $asf_containernumber;

    /**
     * @var boolean
     * @ORM\Column( name="asf_provider", type="boolean" )
     **/
    private $asf_provider;

    /**
     * @var boolean
     * @ORM\Column( name="asf_limitsdate", type="boolean" )
     **/
    private $asf_limitsdate;

    /**
     * @var boolean
     * @ORM\Column( name="asf_limitsnum", type="boolean" )
     **/
    private $asf_limitsnum;

    /**
     * @var boolean
     * @ORM\Column( name="asf_limitsalpha", type="boolean" )
     **/
    private $asf_limitsalpha;

    /**
     * @var boolean
     * @ORM\Column( name="asf_limitsalphanum", type="boolean" )
     **/
    private $asf_limitsalphanum;

    /**
     * Get id
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @var boolean
     * @ORM\Column( name="asf_name", type="boolean" )
     **/
    private $asf_name;

    /**
     * Set user_id
     * @param integer $user_id
     * @return IDPUserAutoSaveFields
     */
    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Get user_id
     * @return integer
     */
    public function getUserId(){
        return $this->user_id;
    }

    /**
     * Set asf_service
     * @param boolean $asf_service
     * @return IDPUserAutoSaveFields
     */
    public function setAsfService($asf_service){
        $this->asf_service = $asf_service;
        return $this;
    }

    /**
     * Get asf_service
     * @return integer
     */
    public function getAsfService(){
        return $this->asf_service;
    }

    /**
     * Set asf_legalentity
     * @param boolean $asf_legalentity
     * @return IDPUserAutoSaveFields
     */
    public function setAsfLegalentity($asf_legalentity){
        $this->asf_legalentity = $asf_legalentity;
        return $this;
    }

    /**
     * Get asf_legalentity
     * @return integer
     */
    public function getAsfLegalEntity(){
        return $this->asf_legalentity;
    }

    /**
     * Set asf_budgetcode
     * @param boolean $asf_budgetcode
     * @return IDPUserAutoSaveFields
     */
    public function setAsfBudgetcode($asf_budgetcode){
        $this->asf_budgetcode = $asf_budgetcode;
        return $this;
    }

    /**
     * Get asf_budgetcode
     * @return integer
     */
    public function getAsfBudgetcode(){
        return $this->asf_budgetcode;
    }

    /**
     * Set asf_documentnature
     * @param boolean $asf_documentnature
     * @return IDPUserAutoSaveFields
     */
    public function setAsfDocumentNature($asf_documentnature){
        $this->asf_documentnature = $asf_documentnature;
        return $this;
    }

    /**
     * Get asf_documentnature
     * @return integer
     */
    public function getAsfDocumentNature(){
        return $this->asf_documentnature;
    }

    /**
     * Set asf_documenttype
     * @param boolean $asf_documenttype
     * @return IDPUserAutoSaveFields
     */
    public function setAsfDocumenttype($asf_documenttype){
        $this->asf_documenttype = $asf_documenttype;
        return $this;
    }

    /**
     * Get asf_documenttype
     * @return integer
     */
    public function getAsfDocumenttype(){
        return $this->asf_documenttype;
    }

    /**
     * Set asf_description1
     * @param boolean $asf_description1
     * @return IDPUserAutoSaveFields
     */
    public function setAsfDescription1($asf_description1){
        $this->asf_description1 = $asf_description1;
        return $this;
    }

    /**
     * Get asf_description1
     * @return integer
     */
    public function getAsfDescription1(){
        return $this->asf_description1;
    }

    /**
     * Set asf_description2
     * @param boolean $asf_description2
     * @return IDPUserAutoSaveFields
     */
    public function setAsfDescription2($asf_description2){
        $this->asf_description2 = $asf_description2;
        return $this;
    }

    /**
     * Get asf_description2
     * @return integer
     */
    public function getAsfDescription2(){
        return $this->asf_description2;
    }

    /**
     * Set asf_closureyear
     * @param boolean $asf_closureyear
     * @return IDPUserAutoSaveFields
     */
    public function setAsfClosureyear($asf_closureyear){
        $this->asf_closureyear = $asf_closureyear;
        return $this;
    }

    /**
     * Get asf_closureyear
     * @return integer
     */
    public function getAsfClosureyear(){
        return $this->asf_closureyear;
    }

    /**
     * Set asf_destructionyear
     * @param boolean $asf_destructionyear
     * @return IDPUserAutoSaveFields
     */
    public function setAsfDestructionyear($asf_destructionyear){
        $this->asf_destructionyear = $asf_destructionyear;
        return $this;
    }

    /**
     * Get asf_destructionyear
     * @return integer
     */
    public function getAsfDestructionyear(){
        return $this->asf_destructionyear;
    }

    /**
     * Set asf_filenumber
     * @param boolean $asf_filenumber
     * @return IDPUserAutoSaveFields
     */
    public function setAsfFilenumber($asf_filenumber){
        $this->asf_filenumber = $asf_filenumber;
        return $this;
    }

    /**
     * Get asf_filenumber
     * @return integer
     */
    public function getAsfFilenumber(){
        return $this->asf_filenumber;
    }

    /**
     * Set asf_boxnumber
     * @param boolean $asf_boxnumber
     * @return IDPUserAutoSaveFields
     */
    public function setAsfBoxnumber($asf_boxnumber){
        $this->asf_boxnumber = $asf_boxnumber;
        return $this;
    }

    /**
     * Get asf_boxnumber
     * @return integer
     */
    public function getAsfBoxnumber(){
        return $this->asf_boxnumber;
    }

    /**
     * Set asf_containernumber
     * @param boolean $asf_containernumber
     * @return IDPUserAutoSaveFields
     */
    public function setAsfContainernumber($asf_containernumber){
        $this->asf_containernumber = $asf_containernumber;
        return $this;
    }

    /**
     * Get asf_containernumber
     * @return integer
     */
    public function getAsfContainernumber(){
        return $this->asf_containernumber;
    }

    /**
     * Set asf_provider
     * @param boolean $asf_provider
     * @return IDPUserAutoSaveFields
     */
    public function setAsfProvider($asf_provider){
        $this->asf_provider = $asf_provider;
        return $this;
    }

    /**
     * Get asf_provider
     * @return integer
     */
    public function getAsfProvider(){
        return $this->asf_provider;
    }

    /**
     * Set asf_limitsdate
     * @param boolean $asf_limitsdate
     * @return IDPUserAutoSaveFields
     */
    public function setAsfLimitsdate($asf_limitsdate){
        $this->asf_limitsdate = $asf_limitsdate;
        return $this;
    }

    /**
     * Get asf_limitsdate
     * @return integer
     */
    public function getAsfLimitsdate(){
        return $this->asf_limitsdate;
    }

    /**
     * Set asf_limitsnum
     * @param boolean $asf_limitsnum
     * @return IDPUserAutoSaveFields
     */
    public function setAsfLimitsnum($asf_limitsnum){
        $this->asf_limitsnum = $asf_limitsnum;
        return $this;
    }

    /**
     * Get asf_limitsnum
     * @return integer
     */
    public function getAsfLimitsnum(){
        return $this->asf_limitsnum;
    }

    /**
     * Set asf_limitsalpha
     * @param boolean $asf_limitsalpha
     * @return IDPUserAutoSaveFields
     */
    public function setAsfLimitsalpha($asf_limitsalpha){
        $this->asf_limitsalpha = $asf_limitsalpha;
        return $this;
    }

    /**
     * Get asf_limitsalpha
     * @return integer
     */
    public function getAsfLimitsalpha(){
        return $this->asf_limitsalpha;
    }

    /**
     * Set asf_limitsalphanum
     * @param boolean $asf_limitsalphanum
     * @return IDPUserAutoSaveFields
     */
    public function setAsfLimitsalphanum($asf_limitsalphanum){
        $this->asf_limitsalphanum = $asf_limitsalphanum;
        return $this;
    }

    /**
     * Get asf_limitsalphanum
     * @return integer
     */
    public function getAsfLimitsalphanum(){
        return $this->asf_limitsalphanum;
    }

    /**
     * Set asf_name
     * @param boolean $asf_name
     * @return IDPUserAutoSaveFields
     */
    public function setAsfName($asf_name){
        $this->asf_name = $asf_name;
        return $this;
    }

    /**
     * Get asf_name
     * @return integer
     */
    public function getAsfName(){
        return $this->asf_name;
    }
}
