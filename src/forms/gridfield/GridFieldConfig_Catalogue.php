<?php

namespace SilverCommerce\CatalogueAdmin\Forms\GridField;

use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Colymba\BulkManager\BulkManager as GridFieldBulkManager;
use Colymba\BulkManager\BulkAction\UnlinkHandler;
use SilverCommerce\CatalogueAdmin\BulkManager\DisableHandler;
use SilverCommerce\CatalogueAdmin\BulkManager\EnableHandler;
use SilverCommerce\CatalogueAdmin\Helpers\Helper;
use SilverCommerce\CatalogueAdmin\Model\CatalogueCategory;
use Symbiote\GridFieldExtensions\GridFieldConfigurablePaginator;
use SilverStripe\Forms\GridField\GridFieldDetailForm;

/**
 * Allows editing of records contained within the GridField, instead of only allowing the ability to view records in
 * the GridField.
 *
 * @package forms
 * @subpackage fields-gridfield
 */
class GridFieldConfig_Catalogue extends GridFieldConfig
{
    /**
     *
     * @param array $classname Name of class who's subclasses will be added to form
     * @param int $itemsPerPage - How many items per page should show up
     * @param boolean | string $sorting Allow sorting of rows, either false or the name of the sort column
     */
    public function __construct($classname, $itemsPerPage = null, $sort_col = false)
    {
        parent::__construct();

        // Setup initial gridfield
        $this->addComponent(new GridFieldButtonRow('before'));
        $this->addComponent(new GridFieldToolbarHeader());
        $this->addComponent($sort = new GridFieldSortableHeader());
        $this->addComponent($filter = new GridFieldFilterHeader());
        $this->addComponent(new GridFieldDataColumns());
        $this->addComponent(new GridFieldEditButton());
        $this->addComponent(new GridFieldDeleteAction());
        $this->addComponent(new GridFieldPageCount('toolbar-header-right'));
        $this->addComponent($pagination = new GridFieldConfigurablePaginator($itemsPerPage));
        $this->addComponent(new GridFieldExportButton("buttons-before-right"));

        // Setup Bulk manager
        $manager = new GridFieldBulkManager();
        $manager->removeBulkAction(UnlinkHandler::class);
        $manager->addBulkAction(DisableHandler::class);
        $manager->addBulkAction(EnableHandler::class);
        $this->addComponent($manager);

        // Setup add new button
        $subclasses = Helper::getCreatableClasses($classname);
        $add_button = new AddNewMultiClass("buttons-before-left");
        $add_button->setClasses($subclasses);

        // If we are managing a category, use the relevent field,
        // else use product
        $detail_form = new GridFieldDetailForm();
        $detail_form
            ->setItemRequestClass(EnableDisableDetailForm_ItemRequest::class);
        
        $this->addComponent($detail_form);
        $this->addComponent($add_button);

        if ($sort_col) {
            $this->addComponent(new GridFieldOrderableRows($sort_col));
        }

        $sort->setThrowExceptionOnBadDataType(false);
        $filter->setThrowExceptionOnBadDataType(false);
        $pagination->setThrowExceptionOnBadDataType(false);
    }
}
