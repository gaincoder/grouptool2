var answerCount = $('#answer-fields-list').children.length+1;

$(document).ready(function () {
    jQuery('#add-another-answer').click(function (e) {
        e.preventDefault();

        var answerList = jQuery('#answer-fields-list');

        // grab the prototype template
        var newWidget = answerList.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your answers
        // end name attribute looks like name="contact[answers][2]"
        newWidget = newWidget.replace(/__name__/g, answerCount);
        answerCount++;

        // create a new list element and add it to the list
        var newDiv = jQuery('<div class="elem col-sm-12"></div>').html(newWidget);
        newDiv.appendTo(answerList);
        addTagFormDeleteLink(newDiv.find('div.form-group'));
        groupInputs(newDiv.find('div.form-group'));
    });
})


function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<span class="input-group-btn"><button class="btn btn-danger" href="#"><span class="glyphicon glyphicon-remove"></span></button></span>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

function groupInputs(elem) {
    elem.addClass('input-group');
}

$(document).ready(function () {
    // Get the ul that holds the collection of tags
    $collectionHolder = jQuery('#answer-fields-list');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('div.form-group').each(function () {
        addTagFormDeleteLink($(this));
        groupInputs($(this));
    });

    // ... the rest of the block from above
});