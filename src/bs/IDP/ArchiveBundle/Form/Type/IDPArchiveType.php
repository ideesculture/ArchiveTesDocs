<?php // src/bs/IDP/ArchiveBundle/Form/Type/IDPArchiveType.php
namespace bs\IDP\ArchiveBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class IDPArchiveType extends AbstractType
{
	protected $modify;

	public function buildForm(FormBuilderInterface $builder, array $options )
	{
		// Libellé
		$builder->add(
			'name',
			TextareaType::class,
			array (
				'label' => 'Libellé de l\'archive',
				'required' => false,
				'attr' => array( 'rows' => 18, 'maxlength' => 1759 )
			)
		);
		// Numéro d'ordre
		$builder->add(
			'ordernumber',
			TextType::class,
			array (
				'label' => 'Numéro d\'ordre',
				'required' => false,
                'attr' => array('maxlength' => 9)
			)
		);
		// Service
		$builder->add(
			'service',
			EntityType::class,
			array (
				'class' => 'bsIDPBackofficeBundle:IDPServices',
				'choice_label' => 'longname',
				'placeholder' => 'Sélectionnez un service',
				'empty_data'  => null,
				'label' => 'Service',
				'required' => false
				) );
		// Entité légale
		$builder->add(
			'legalentity',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPLegalEntities',
				'choice_label' => 'longname',
				'label' => 'Entité légale',
				'empty_data' => null,
				'group_by' => 'Service.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			));
		// Code Budget
		$builder->add(
			'budgetcode',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPBudgetCodes',
				'choice_label' => 'longname',
				'label' => 'Code budgétaire',
				'empty_data' => null,
				'group_by' => 'Service.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			)
		);
		// Nature du document
		$builder->add(
			'documentnature',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPDocumentNatures',
				'choice_label' => 'longname',
				'label' => 'Nature de document',
				'empty_data' => null,
				'group_by' => 'LegalEntity.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			)
		);
		// Type du document
		$builder->add(
			'documenttype',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPDocumentTypes',
				'choice_label' => 'longname',
				'label' => 'Type de document',
				'empty_data' => null,
				'group_by' => 'DocumentNature.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			)
		);
		// Description 1
		$builder->add(
			'description1',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPDescriptions1',
				'choice_label' => 'longname',
				'label' => 'Description 1',
				'empty_data' => null,
				'group_by' => 'Service.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			)
		);
		// Description 2
		$builder->add(
			'description2',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPDescriptions2',
				'choice_label' => 'longname',
				'label' => 'Description 2',
				'empty_data' => null,
				'group_by' => 'Service.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			)
		);
		// Année de clôture
		$builder->add(
			'closureyear',
			IntegerType::class,
			array (
				'label' => 'Année de clôture',
				'data' => (int)(date("Y")),
				'required' => false
			)
		);
		// Année de destruction
		$builder->add(
			'destructionyear',
			IntegerType::class,
			array(
				'label' => 'Année de destruction',
				'data' => (int)(date("Y")),
				'required' => false
			)
		);
		// Bornes numériques
		$builder->add(
			'limitnummin',
			IntegerType::class,
			array(
				'label' => 'Borne numérique min',
				'required' => false
			)
		);
		$builder->add(
			'limitnummax',
			IntegerType::class,
			array(
				'label' => 'Borne numérique max',
				'required' => false
			)
		);
		// bornes alphabétiques
		$builder->add(
			'limitalphamin',
			TextType::class,
			array(
				'label' => 'Borne alphabétique min',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		$builder->add(
			'limitalphamax',
			TextType::class,
			array(
				'label' => 'Borne alphabétique max',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		// bornes alphanumériques
		$builder->add(
			'limitalphanummin',
			TextType::class,
			array(
				'label' => 'Borne alphanumérique min',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		$builder->add(
			'limitalphanummax',
			TextType::class,
			array(
				'label' => 'Borne alphanumérique max',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		// bornes dates
		$builder->add(
			'limitdatemin',
			TextType::class,
			array(
				'label' => 'Borne date min',
				'required' => false
			)
		);
		$builder->add(
			'limitdatemax',
			TextType::class,
			array(
				'label' => 'Borne date max',
				'required' => false
			)
		);

		$builder->add(
			'documentnumber',
			TextType::class,
			array(
				'label' => 'Numéro de document / dossier',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		$builder->add(
			'boxnumber',
			TextType::class,
			array(
				'label' => 'Numéro de boîte',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		$builder->add(
			'containernumber',
			TextType::class,
			array(
				'label' => 'Numéro de conteneur',
				'required' => false,
                'attr' => array('maxlength' => 255)
			)
		);
		$builder->add(
			'provider',
			EntityType::class,
			array(
				'class' => 'bsIDPBackofficeBundle:IDPProviders',
				'choice_label' => 'longname',
				'label' => 'Code prestataire',
				'empty_data' => null,
//				'group_by' => 'Service.id',
				'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
					return $er->createQueryBuilder('q')->orderBy('q.longname', 'ASC');
				},
				'required' => false
			)
		);

		if( $this->modify == 0 ){
			$builder->add(
				'NewArchiveBtnSave',
				SubmitType::class,
				array(
					'label' => 'Sauvegarder',
			));
			$builder->add(
				'NewArchiveBtnValidate',
				SubmitType::class,
				array(
					'label' => 'Valider',
			));
			$builder->add(
				'NewArchiveBtnPrint',
				SubmitType::class,
				array(
					'label' => 'Editer et valider',
			));
		} else {
			$builder->add(
				'ModifyArchiveBtn',
				SubmitType::class,
				array(
					'label' => 'Modifier' ));
		}

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
		    'data_class' => 'bs\IDP\ArchiveBundle\Entity\IDPArchive',
		));
	}

	public function getName()
	{
		return 'bsIDPArchiveBundle_IDPArchiveType';
	}

	public function __construct( $modify = 0 ){
		$this->modify = $modify;
	}
}

?>