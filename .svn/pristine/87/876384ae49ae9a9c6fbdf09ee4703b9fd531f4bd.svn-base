<?php



namespace Lanix;
use PrestaShop\PrestaShop\Adapter\Entity\ModuleAdminController;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShop\PrestaShop\Adapter\Entity\FormField;

class LxCustomerHelper extends ModuleAdminController
{


    public static function getGridDefinitionModifier (array $params){
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];

        $definition
            ->getColumns()
            ->addAfter(
                'lastname',
                (new DataColumn('rut'))
                    ->setName('RUT')
                    ->setOptions([
                        'field' => 'rut'])
            );

        $definition->getFilters()->add(
            (new Filter('rut', TextType::class))
                ->setTypeOptions(['required' => false])
                ->setAssociatedColumn('rut')
        );

        return $definition;
    }

    public static function getGridQueryModifier (array $params) {

        /** @var QueryBuilder $searchQueryBuilder */
        $searchQueryBuilder = $params['search_query_builder'];
        $searchQueryBuilder->addSelect('z.vch_rut as rut');

        $searchQueryBuilder->leftJoin(
            'c',
            '`' . pSQL(_DB_PREFIX_) . 'lx_customers`',
            'z',
            'c.id_customer = z.id_pshop'
        );

        return $searchQueryBuilder;
    }

    public static function getFormModifier (array $params) {
        /** @var FormBuilderInterface $formBuilder */

        $formBuilder = $params['form_builder'];
        $formBuilder->add('rut', TextType::class, [
            'label' => 'RUT',
            'required' => true,
        ]);

        return $formBuilder;

    }

    public static function getFormField () {
        return [(new FormField)
            ->setRequired(true)
            ->setName('rut')
            ->setType('text')
            ->setMaxLength(12)
            ->addConstraint('isDniLite')
            ->setLabel('RUT')
        ];
    }

}
