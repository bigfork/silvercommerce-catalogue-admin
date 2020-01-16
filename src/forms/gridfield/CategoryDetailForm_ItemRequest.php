<?php

namespace SilverCommerce\CatalogueAdmin\Forms\GridField;

class CategoryDetailForm_ItemRequest extends EnableDisableDetailForm_ItemRequest
{
    private static $allowed_actions = [
        'edit',
        'view',
        'ItemEditForm'
    ];

    /**
     *
     * @param GridFIeld $gridField
     * @param GridField_URLHandler $component
     * @param DataObject $record
     * @param Controller $popupController
     * @param string $popupFormName
     */
    public function __construct($gridField, $component, $record, $popupController, $popupFormName)
    {
        parent::__construct(
            $gridField,
            $component,
            $record,
            $popupController,
            $popupFormName
        );
    }

    /**
     * Overload default edit form
     *
     * @return Form
     */
    public function ItemEditForm()
    {
        $form = parent::ItemEditForm();
        
        return $form;
    }
}
