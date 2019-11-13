var $teacherCollectionHolder;

var $addNewItem = $('<a href="#" class="btn btn-info">Dodaj</a>');

$(document).ready(function () {
    $teacherCollectionHolder = $('#teacher_list');

    $teacherCollectionHolder.append($addNewItem);

    $teacherCollectionHolder.data('index', $teacherCollectionHolder.find('.teacher-row').length);

    $teacherCollectionHolder.find('.teacher-row').each(function() {
        addRemoveButton($(this));
    });

    $addNewItem.click(function (e) {
        e.preventDefault();
        addNewForm();
    })
})

function addNewForm() {
    var prototype = $teacherCollectionHolder.data('prototype');
    var index = $teacherCollectionHolder.data('index');
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    var $panel = $('<div class="teacher-row"></div>').append(newForm);

    $teacherCollectionHolder.data('index', index+1);

    addRemoveButton($panel);

    $addNewItem.before($panel);
}

function addRemoveButton($panel) {
    var $removeButton = $('<a href="#" class="btn btn-danger">Usuń</a>');

    $removeButton.click(function (e) {
        e.preventDefault();
        $(e.target).parents('.teacher-row').remove();
    });

    $panel.append($removeButton);
}