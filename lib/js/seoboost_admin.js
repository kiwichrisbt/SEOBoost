/* seoboost_admin.js - v1.0 - 02Dec23 */

$(function() {



    // update input character count & progress bar for recommended length
    $('.seoboost-char-count input').keyup(seoboost_update_char_count);
    function seoboost_update_char_count() {
        let $wrapper = $(this).closest('.seoboost-char-count'),
            $message_count = $wrapper.find('.seoboost-char-count-message span'),
            $bar = $wrapper.find('.seoboost-char-count-bar'),
            length = $wrapper.data('length'),
            chars = $(this).val().length,
            percent = Math.round(chars/length*100);
        $message_count.html(chars);
        $bar.css('width', percent+'%');
        if (chars>length) {
            $wrapper.addClass('over-length');
        } else {
            $wrapper.removeClass('over-length');
        }
    }

    // setup Content Manager add-ins
    function seoboost_cm_addins(field_name, size, length) {
        var $field = $('.oe_ContentnbspManager #page_content input[name="'+field_name+'"]');
        if ($field.length) {    // if field exists
            if (size>0) $field.attr('size', size);
            if (length>0) {
                let chars = $field.val().length,
                    overLength = chars>length ? ' over-length' : '';
                $field.wrap('<div class="seoboost-char-count'+overLength+'" data-length="'+length+'"></div>');
                $field.after('<div class="seoboost-char-count-message"><span class="count">'+chars+'</span>/'+length+'</div><div class="seoboost-char-count-bar" style="width:'+chars+'%"></div>');
                $field.keyup(seoboost_update_char_count);   // set keyup event
            }
        }
    }
    if (typeof seoboost_cm_addin_data !== 'undefined') {
        // iterate through seoboost_cm_addin_data object of add-ins - see SEOBoost.module.php > AdminAddHeadText
        let data = seoboost_cm_addin_data;
        for (const field in data) {
            seoboost_cm_addins(data[field].cm_field_name, data[field].size, data[field].length);
        }
    }

    // cm-add-in-options admin page - make editable checkbox enable/disable that fields inputs
    $('#cm-add-in-options .editable').change(function() {
        let $wrapper = $(this).closest('tr');
        if ( $(this).is(':checked') ) {
            $wrapper.removeClass('disabled').find('.can-be-readonly').prop('readonly', false);
        } else {
            $wrapper.addClass('disabled').find('.can-be-readonly').prop('readonly', true);
        }
    });








    //  ██████╗ ███████╗██████╗ ███████╗ █████╗ ████████╗███████╗██████╗ 
    //  ██╔══██╗██╔════╝██╔══██╗██╔════╝██╔══██╗╚══██╔══╝██╔════╝██╔══██╗
    //  ██████╔╝█████╗  ██████╔╝█████╗  ███████║   ██║   █████╗  ██████╔╝
    //  ██╔══██╗██╔══╝  ██╔═══╝ ██╔══╝  ██╔══██║   ██║   ██╔══╝  ██╔══██╗
    //  ██║  ██║███████╗██║     ███████╗██║  ██║   ██║   ███████╗██║  ██║
    //  ╚═╝  ╚═╝╚══════╝╚═╝     ╚══════╝╚═╝  ╚═╝   ╚═╝   ╚══════╝╚═╝  ╚═╝
    //                                                                   
    //  seoboost_repeater functions  seoboost_repeater sortable pagetable ui-sortable
    $('.seoboost_repeater.sortable').sortable({
        items: '.sortable-item',
        placeholder: 'repeater-placeholder',
        handle: '.handle',
        // forcePlaceholderSize: true,
        opacity: 0.8
    });
    // repeater add
    $(document).on('click', '.seoboost-repeater-add', function(e) {
        e.preventDefault();
        var $repeater = $($(this).data('repeater')),
            $newRepeaterWrapper = $repeater.find('.repeater-wrapper-template').clone(),
            highestRow = $repeater.data('highest-row')+1,
            blockName = $repeater.data('block-name'),
            fieldName = '';     // required for sub_fields
        $newRepeaterWrapper.removeClass('repeater-wrapper-template')
            .addClass('repeater-wrapper')
            .css('display', '');
        ECB2InitialiseSubFields( $newRepeaterWrapper, blockName, highestRow );

        $repeater.data('highest-row', highestRow); // adds 1
        if ( $newRepeaterWrapper.find('textarea.wysiwyg').length ) {
            ECB2RepeaterEditorAdd ($repeater, $newRepeaterWrapper);
        } else { // everything other than a WYSIWYG
            $repeater.append($newRepeaterWrapper);
        }
        updateECB2RepeaterMaxBlocks($repeater);
    });
    // repeater remove
    $(document).on('click', '.seoboost-repeater-remove', function(e) {
        e.preventDefault();
        var $repeater = $(this).closest('.seoboost_repeater'),
            $repeaterWrapper = $(this).closest('.repeater-wrapper'),
            repeaterWrapperCount = $repeater.find('.repeater-wrapper').length;
        if ( $repeaterWrapper.find('textarea.wysiwyg').length ) {   // has Editor
            // ECB2RepeaterEditorRemove ($repeater, $repeaterWrapper, repeaterWrapperCount);
        } else if (repeaterWrapperCount > 1) {
            $repeaterWrapper.remove();
        } else {
            $repeaterWrapper.remove();
            $($repeater.data('repeater-add')).trigger('click');
        }
        updateECB2RepeaterMaxBlocks($repeater);
    });
    // Editor functions support MicroTiny or TinyMCE wysiwyg
    function ECB2RepeaterEditorAdd ($repeater, $newRepeaterWrapper) {
        // $repeater.append($newRepeaterWrapper);
        // $newRepeaterWrapper.find('textarea.wysiwyg').each(function() {
        //     tinymce.EditorManager.execCommand('mceAddEditor', true, $(this).attr('id'));
        //     tinymce.activeEditor.show();
        // });
    }
    function ECB2RepeaterEditorRemove ($repeater, $repeaterWrapper, repeaterWrapperCount) {
        // $repeaterWrapper.find('textarea.wysiwyg').each(function() {
        //     if ( repeaterWrapperCount > 1 ) { // delete repeater editor
        //         tinymce.get( $(this).attr('id') ).remove();
        //         $repeaterWrapper.remove();
        //     } else { // only 1 repeater - so just clear editor contents
        //         editor = tinymce.get( $(this).attr('id') );
        //         editor.setContent('');
        //     }
        // });
    }
    function updateECB2RepeaterMaxBlocks($repeater) {
        if ( $repeater.data('max-blocks') ) {
            var maxBlocks = $repeater.data('max-blocks'),
                blockCount = $repeater.children('.repeater-wrapper').length;
            if ( blockCount>=maxBlocks ) {
                $($repeater.data('repeater-add')).prop('disabled', true);
            } else {
                $($repeater.data('repeater-add')).prop('disabled', false);
            }
        }
    }


    // Initialise sub fields - when first added to page
    function ECB2InitialiseSubFields( $wrapper, blockName, row ) {
        $wrapper.find('.repeater-field').each( function() {
            let fieldName = $(this).data('field-name');
            let multiple = $(this).attr('multiple') ? '[]' : '';
            // set id and name attributes for new input/s
            if (fieldName) {    // sub_fields
                $(this).attr('name', blockName+'[r_'+row+']['+fieldName+']'+multiple)
                    .attr('id', blockName+'_r_'+row+'_'+fieldName);    // set unique id
            } else {    // repeater field
                $(this).attr('name', blockName+'[r_'+row+']')
                .attr('id', blockName+'_r_'+row);    // set unique id
            }
        });
    }
    // active sub fields - when first visible on page
    function ECB2ActivateSubFields( $wrapper ) {
        // ...
        // $wrapper.find('.repeater-field').each( function() {
        //     if ( $(this).hasClass('wysiwyg') ) {
        //         tinymce.EditorManager.execCommand('mceAddEditor', true, $(this).attr('id'));
        //         tinymce.activeEditor.show();
        //     }
        // });

    }
    // deactive sub fields - when hidden on page
    function ECB2DeactivateSubFields( $wrapper ) {
        // $wrapper.find('.repeater-field').each( function() {
        //     if ( $(this).hasClass('wysiwyg') ) {
        //         editor = tinymce.get( $(this).attr('id') );
        //         if (editor!=null) {
        //             editor.save();
        //             editor.remove();
        //         }
        //     }
        // });
    }



});