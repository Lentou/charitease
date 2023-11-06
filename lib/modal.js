function openModalForm(id) {
    const modal = document.getElementById(id);
    modal.classList.add("is-active");
}

function closeModalForm(id) {
    const modal = document.getElementById(id);
    modal.classList.remove("is-active");
}

function showOtherForm(currentid, newid) {
    const current_modal = document.getElementById(currentid);
    current_modal.classList.remove("is-active");

    const new_modal = document.getElementById(newid);
    new_modal.classList.add("is-active");
}