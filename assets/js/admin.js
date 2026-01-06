jQuery(document).ready(function($) {
    // Toggle visibility of Parent Slug field based on Menu Type selection
    var menuTypeSelect = $("[name='menu_type']");
    var parentSlugField = $("[id='parent_slug']").closest('.form-field'); // Assuming fields are wrapped in .form-field

    function toggleParentSlugField() {
        if (menuTypeSelect.val() === 'submenu') {
            parentSlugField.show();
        } else {
            parentSlugField.hide();
        }
    }

    // Initial check on page load
    toggleParentSlugField();

    // Update on change
    menuTypeSelect.on('change', toggleParentSlugField);
});