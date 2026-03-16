/**
 * Repeater Field JavaScript
 * Handles add/remove functionality for repeater fields
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initRepeaterFields();
    });

    function initRepeaterFields() {
        $('.directorist-repeater').each(function() {
            var $repeater = $(this);
            var $container = $repeater.find('.directorist-repeater-field-body');
            var $template = $container.find('.repeater-fieldset').first();
            var fieldKey = $repeater.find('input[name]').attr('name');
            var fieldLabel = $repeater.find('.fieldset-title').data('label');
            var fieldOptions = window.repeaterFieldOptions || {};

            console.log(fieldLabel);
            
            // Store the template for cloning
            //$template.addClass('repeater-template').hide();
            
            // Initialize existing items
            $container.find('.repeater-fieldset:not(.repeater-template)').each(function(index) {
                initRepeaterItem($(this), index, fieldKey, fieldOptions);
            });
            
            // Add click handlers for add/remove buttons
            $repeater.on('click', '.action-plus', function(e) {
                e.preventDefault();
                addRepeaterItem($container, $template, fieldKey, fieldOptions, fieldLabel);
                updateRepeaterHiddenInput($repeater);
            });
            
            $repeater.on('click', '.action-minus', function(e) {
                e.preventDefault();
                removeRepeaterItem($(this));
                updateRepeaterHiddenInput($repeater);
            });
            
            // Listen for changes on any field within the repeater
            $repeater.on('change input', 'input, select, textarea', function() {
                updateRepeaterHiddenInput($repeater);
            });
            
            // Initial update of hidden input
            updateRepeaterHiddenInput($repeater);
        });
    }

    function addRepeaterItem($container, $template, fieldKey, fieldOptions, fieldLabel) {
        var $newItem = $template.clone();
        var newIndex = $container.find('.repeater-fieldset:not(.repeater-template)').length;
        
        $newItem.removeClass('repeater-template').show();
        $newItem.attr('data-id', newIndex + 1);
        
        // Update field names and IDs
        $newItem.find('input, select, textarea').each(function() {
            var $field = $(this);
            var name = $field.attr('name');
            var id = $field.attr('id');
            
            if (name) {
                $field.attr('name', fieldKey + '[' + newIndex + '][' + name + ']');
            }
            if (id) {
                $field.attr('id', id + '_' + newIndex);
            }
        });

        $newItem.find('.fieldset-title').text(fieldLabel + ' #' + (newIndex + 1));
        
        // Initialize the new item
        initRepeaterItem($newItem, newIndex, fieldKey, fieldOptions);
        
        // Add to container
        $container.append($newItem);
        
        // Trigger change event
        $newItem.trigger('repeater:item:added');
        
        // Update hidden input after adding item
        var $repeater = $container.closest('.directorist-repeater');
        updateRepeaterHiddenInput($repeater);
    }

    function removeRepeaterItem($button) {
        var $item = $button.closest('.repeater-fieldset');
        var $container = $item.closest('.directorist-repeater-field-body');
        var $repeater = $container.closest('.directorist-repeater');
        
        // Don't remove if it's the last item
        if ($container.find('.repeater-fieldset:not(.repeater-template)').length <= 1) {
            return;
        }
        
        $item.fadeOut(300, function() {
            $(this).remove();
            reindexRepeaterItems($container);
            updateRepeaterHiddenInput($repeater);
        });
    }

    function reindexRepeaterItems($container) {
        $container.find('.repeater-fieldset:not(.repeater-template)').each(function(index) {
            var $item = $(this);
            $item.attr('data-id', index + 1);
            
            // Update field names and IDs
            $item.find('input, select, textarea').each(function() {
                var $field = $(this);
                var name = $field.attr('name');
                var id = $field.attr('id');
                
                if (name && name.includes('[')) {
                    var baseName = name.split('[')[0];
                    var fieldName = name.split('[')[2] ? name.split('[')[2].replace(']', '') : '';
                    $field.attr('name', baseName + '[' + index + '][' + fieldName + ']');
                }
                if (id && id.includes('_')) {
                    var baseId = id.split('_')[0];
                    $field.attr('id', baseId + '_' + index);
                }
            });
        });
    }

    function initRepeaterItem($item, index, fieldKey, fieldOptions) {
        // Initialize any special field types
        $item.find('.directorist-form-element').each(function() {
            var $field = $(this);
            var fieldType = $field.data('field-type') || $field.attr('type');
            
            // Handle different field types
            switch(fieldType) {
                case 'date':
                    if ($.fn.datepicker) {
                        $field.datepicker({
                            dateFormat: 'yy-mm-dd',
                            changeMonth: true,
                            changeYear: true,
                            onSelect: function() {
                                var $repeater = $field.closest('.directorist-repeater');
                                updateRepeaterHiddenInput($repeater);
                            }
                        });
                    }
                    break;
                case 'time':
                    if ($.fn.timepicker) {
                        $field.timepicker({
                            timeFormat: 'HH:mm',
                            interval: 15,
                            minTime: '00:00',
                            maxTime: '23:59',
                            defaultTime: false,
                            startTime: '00:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true,
                            change: function() {
                                var $repeater = $field.closest('.directorist-repeater');
                                updateRepeaterHiddenInput($repeater);
                            }
                        });
                    }
                    break;
                case 'color':
                    if ($.fn.colorpicker) {
                        $field.colorpicker({
                            change: function() {
                                var $repeater = $field.closest('.directorist-repeater');
                                updateRepeaterHiddenInput($repeater);
                            }
                        });
                    }
                    break;
            }
        });
        
        // Handle select fields with options
        $item.find('select').each(function() {
            var $select = $(this);
            var options = $select.data('options');
            
            if (options && Array.isArray(options)) {
                $select.empty();
                $select.append('<option value="">' + $select.attr('placeholder') + '</option>');
                
                options.forEach(function(option) {
                    $select.append('<option value="' + option.option_value + '">' + option.option_label + '</option>');
                });
            }
        });
    }

    function updateRepeaterHiddenInput($repeater) {
        var $hiddenInput = $repeater.find('.directorist-repeater-hidden-input');
        if ($hiddenInput.length === 0) {
            return;
        }
        
        var $container = $repeater.find('.directorist-repeater-field-body');
        var fieldData = [];
        
        // Collect data from all repeater fieldsets
        $container.find('.repeater-fieldset:not(.repeater-template)').each(function() {
            var $fieldset = $(this);
            var itemData = {};
            var processedFields = {};
            
            // Get all input fields in this fieldset
            $fieldset.find('input, select, textarea').each(function() {
                var $field = $(this);
                var name = $field.attr('name');
                
                // Skip the hidden input itself
                if ($field.hasClass('directorist-repeater-hidden-input')) {
                    return;
                }
                
                // Skip if already processed (for radio/checkbox groups)
                if (processedFields[name]) {
                    return;
                }
                
                // Parse the field name to extract the field key
                // Format: fieldKey[index][field_key] or fieldKey[index][field_key][]
                if (name && name.includes('[')) {
                    var matches = name.match(/\[(\d+)\]\[([^\]]+)\](\[\])?/);
                    if (matches && matches.length >= 3) {
                        var fieldKey = matches[2];
                        var isArrayField = matches[3] === '[]';
                        var fieldType = $field.attr('type');
                        
                        // Mark as processed
                        processedFields[name] = true;
                        
                        // Handle different field types
                        if (fieldType === 'checkbox' && isArrayField) {
                            // Checkbox group - collect all checked values
                            var checkedValues = [];
                            $fieldset.find('input[type="checkbox"][name="' + name + '"]:checked').each(function() {
                                checkedValues.push($(this).val());
                            });
                            itemData[fieldKey] = checkedValues;
                        } else if (fieldType === 'radio') {
                            // Radio group - get the checked value
                            var $checkedRadio = $fieldset.find('input[type="radio"][name="' + name + '"]:checked');
                            itemData[fieldKey] = $checkedRadio.length > 0 ? $checkedRadio.val() : '';
                        } else if ($field.is('select[multiple]')) {
                            // Multi-select
                            itemData[fieldKey] = $field.val() || [];
                        } else {
                            // Regular input, textarea, select, etc.
                            var value = $field.val();
                            itemData[fieldKey] = value || '';
                        }
                    }
                }
            });
            
            // Only add item if it has data
            if (Object.keys(itemData).length > 0) {
                fieldData.push(itemData);
            }
        });
        
        // Update the hidden input value with JSON encoded data
        $hiddenInput.val(JSON.stringify(fieldData));
    }

    // Public API
    // window.DirectoristRepeater = {
    //     init: initRepeaterFields,
    //     addItem: function(fieldKey) {
    //         var $repeater = $('[data-field-key="' + fieldKey + '"]');
    //         if ($repeater.length) {
    //             var $container = $repeater.find('.directorist-repeater-field-body');
    //             var $template = $container.find('.repeater-template');
    //             addRepeaterItem($container, $template, fieldKey, {});
    //         }
    //     },
    //     removeItem: function(fieldKey, index) {
    //         var $repeater = $('[data-field-key="' + fieldKey + '"]');
    //         if ($repeater.length) {
    //             var $item = $repeater.find('.repeater-fieldset[data-id="' + index + '"]');
    //             if ($item.length) {
    //                 removeRepeaterItem($item.find('.action-minus'));
    //             }
    //         }
    //     }
    // };

})(jQuery);
