import ModalForm from 'core_form/modalform';
import {get_string as getString} from 'core/str';
import {exception as displayException, deleteCancelPromise} from 'core/notification';
import * as externalServices from 'repository_imagehub/webservices';

export const init = async() => {

    // Add listener for adding a new source.
    let addsource = document.getElementById('addsource');
    addsource.addEventListener('click', async(e) => {
        showModal(e, 0);
    });

    // Add listener to edit sources.
    let editsources = document.getElementsByClassName('edit');
    editsources.forEach(element => {
        element.addEventListener('click', async(e) => {
            showModal(e, element.dataset.id);
        });
    });

    // Add listener to delete sources.
    let deletesources = document.getElementsByClassName('delete');
    deletesources.forEach(element => {
        element.addEventListener('click', async(e) => {
            deleteModal(e, element.dataset.id, element.dataset.title);
        });
    });
};

/**
 * Show dynamic form to add/edit a source.
 * @param {*} e
 * @param {*} id
 */
function showModal(e, id) {
    e.preventDefault();
    let title;
    if (id === 0) {
        title = getString('addsource', 'repository_imagehub');
    } else {
        title = getString('editsource', 'repository_imagehub');
    }

    const modalForm = new ModalForm({
        formClass: "repository_imagehub\\form\\managesources_form",
        args: {
            id: id,
        },
        modalConfig: {title: title},
    });
    // Reload page after submit.
    modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, () => location.reload());

    modalForm.show();
}

/**
 * Show dynamic form to delete a source.
 * @param {*} e
 * @param {*} id
 * @param {*} title
 */
function deleteModal(e, id, title) {
    e.preventDefault();

    deleteCancelPromise(
        getString('delete', 'repository_imagehub', title),
        getString('deletewarning', 'repository_imagehub'),
    ).then(async() => {
        if (id !== 0) {
            try {
                const deleted = await externalServices.deleteSource(id);
                if (deleted) {
                    const row = document.querySelector('[sourceid="' + id + '"]');
                    if (row) {
                        row.remove();
                    }
                }
            } catch (error) {
                displayException(error);
            }
        }
        return;
    }).catch(() => {
        return;
    });
}
