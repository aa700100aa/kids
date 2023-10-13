jQuery( document ).ready(function() {
    //Observer to adjust the media attachment modal window 
    var attachmentPreviewObserver = new MutationObserver(function(mutations){
        // look through all mutations that just occured
        for (var i=0; i < mutations.length; i++){

            // look through all added nodes of this mutation
            for (var j=0; j < mutations[i].addedNodes.length; j++){

                //get element
                var element = $(mutations[i].addedNodes[j]);

                //check if this is the attachment details section or if it contains the section
                //need this conditional as we need to trigger on initial modal open (creation) + next and previous navigation through media items
                var onAttachmentPage = false;
                if( (element.hasClass('attachment-details')) || element.find('.attachment-details').length != 0){
                    onAttachmentPage = true;
                }

                if(onAttachmentPage == true){   
                    //find the URL value and update the details image
                    var urlLabel = element.find('label[data-setting="url"]');
                    if(urlLabel.length != 0){
                        var value = urlLabel.find('input').val();
                        element.find('.details-image').attr('src', value);
                    }
                }
            } 
        }   
    });

    attachmentPreviewObserver.observe(document.body, {
      childList: true,
      subtree: true
    });
});